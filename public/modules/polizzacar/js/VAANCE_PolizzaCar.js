var VAANCE_PolizzaCar = {

    init: function () {
        this.polizzaSetup();
        // this.recalculateValues();
        this.copyButtons();
        this.signAndAcceptButton();
        this.signDocusignButton();
        this.initFileInput();
        this.saveButton();
        this.initDateIssueInCreate();
    },

    copyButtons: function(){
        
        $(document).on('click','#contractor-copy-from-procurement',function(e){
            e.preventDefault();

            var procurementId = $('#procurement_id').val();

            if(procurementId > 0 ) {
                $.ajax({
                    type: "POST",
                    url: '/polizzacar/copy-from-procurement',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'procurement_id' : procurementId
                    },
                    dataType: 'json',
                    success: function (result) {

                        $('#company_name').val(result.data.company_name);
                        $('#company_vat').val(result.data.company_vat);
                        $('#company_email').val(result.data.company_email);
                        $('#company_phone').val(result.data.company_phone);
                        $('#company_address').val(result.data.company_address);
                        $('#company_city').val(result.data.company_city);
                        $('#company_cap').val(result.data.company_cap);
                        $('#company_provincia').val(result.data.company_provincia);
                        $('#country_id').val(result.data.country.id);
                        $('#works_type_details').val(result.data.works_type_details);
                        $('#works_descr').val(result.data.works_descr);
                        $('#works_place').val(result.data.works_place);
                        $('#primary_works_place').val(result.data.primary_works_place);
                        $('#works_duration_mm').val(result.data.works_duration_mm);

                        VAANCE_Common.initComponents();
                        $.AdminBSB.input.activate();
                    }
                });
            }else{
                VAANCE_Common.showNotification('bg-red', $.i18n._('fill_in_the_missing_record'));
            }

        });
    },

    recalculateValues: function(){

        var car_p1_limit_amount = parseFloat($('#car_p1_limit_amount').val() != '' ? $('#car_p1_limit_amount').val().replaceAll(',','').replaceAll(' €','') : 0);
        var car_p1_premium_gross = 0;
        var car_p1_premium_net = 0;

        var car_p2_limit_amount = parseFloat($('#car_p2_limit_amount').val() != '' ? $('#car_p2_limit_amount').val().replaceAll(',','').replaceAll(' €','') : 0);
        var car_p2_premium_gross = 0;
        var car_p2_premium_net = 0;

        var car_p3_limit_amount = parseFloat($('#car_p3_limit_amount').val() != '' ? $('#car_p3_limit_amount').val().replaceAll(',','').replaceAll(' €','') : 0);
        var car_p3_premium_gross = 0;
        var car_p3_premium_net = 0;

        var works_duration_mm = $('#works_duration_mm').val();
        var riskId = $('#risk_id').val();

        if(riskId) {
            $.ajax({
                type: "POST",
                url: '/polizzacar/getTariffa',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'risk_id' : riskId
                },
                dataType: 'json',
                success: function (result) {

                    if (works_duration_mm == '24') {
                        $('#coeff_tariffa').val(result.data.mm_24);
                    } else {
                        $('#coeff_tariffa').val(result.data.mm_36);
                    }

                    $('#tax_rate').val(result.data.tax_rate);
                    $('#commission').val(result.data.commission);

                    VAANCE_Common.initComponents();
                    $.AdminBSB.input.activate();
                }
            });
        }else{
            VAANCE_Common.showNotification('bg-red', $.i18n._('fill_in_the_missing_record'));
        }

        var coeff_tariffa = $('#coeff_tariffa').val();
        var tax_rate = $('#tax_rate').val();
        var commission =  $('#commission').val();

            car_p1_premium_gross = (car_p1_limit_amount * coeff_tariffa) / 1000;
            car_p2_premium_gross = (car_p2_limit_amount * coeff_tariffa) / 1000;
            car_p3_premium_gross = (car_p3_limit_amount * coeff_tariffa) / 1000;
        
            car_p1_premium_net = car_p1_premium_gross / ( 1 + (tax_rate/100));
            car_p2_premium_net = car_p2_premium_gross / ( 1 + (tax_rate/100));
            car_p3_premium_net = car_p3_premium_gross / ( 1 + (tax_rate/100));

            $('#car_p1_premium_gross').html($.number(car_p1_premium_gross,2,',','.')).prepend('€ ');
            $('#car_p1_premium_net').html($.number(car_p1_premium_net,2,',','.')).prepend('€ ');
            $('#car_p2_premium_gross').html($.number(car_p2_premium_gross,2,',','.')).prepend('€ ');
            $('#car_p2_premium_net').html($.number(car_p2_premium_net,2,',','.')).prepend('€ ');
            $('#car_p3_premium_gross').html($.number(car_p3_premium_gross,2,',','.')).prepend('€ ');
            $('#car_p3_premium_net').html($.number(car_p3_premium_net,2,',','.')).prepend('€ ');

            tot_gross = car_p1_premium_gross + car_p2_premium_gross + car_p3_premium_gross;
            $('#total_gross').html($.number(tot_gross,2,',','.')).prepend('€ ');

            tot_net = car_p1_premium_net + car_p2_premium_net + car_p3_premium_net;
            $('#total_net').html($.number(tot_net,2,',','.')).prepend('€ ');
    },

    polizzaSetup: function(){
        $(document).on('change','.card .calc,#risk_id,#works_duration_mm' ,function(e){
           VAANCE_PolizzaCar.recalculateValues();
        });
    },

    initFileInput: function(){
        $('#pdf_signed_contract').fileinput({
            allowedFileExtensions: ['pdf'],
            dropZoneEnabled: false,
            uploadAsync: false,
            showUpload: false,
            showRemove: false,
            showCaption: true,
            maxFileCount: 1,
            showBrowse: true,
            showPreview: true,
            language: 'it',
            browseOnZoneClick: true,
            browseLabel: 'Sfoglia …',
            uploadUrl: '/polizzacar/polizzacar/uploadPDFfiles',
            uploadExtraData: function() {
                return {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    field_name: 'pdf_signed_contract',
                    polizzaId: $('#polizzacarId').val()
                };
            }
        });

        $('#pdf_payment_proof').fileinput({
            allowedFileExtensions: ['pdf'],
            dropZoneEnabled: false,
            uploadAsync: false,
            showUpload: false,
            showRemove: false,
            showCaption: true,
            language: 'it',
            maxFileCount: 1,
            showBrowse: true,
            showPreview: true,
            browseOnZoneClick: true,
            browseLabel: 'Sfoglia …',
            uploadUrl: '/polizzacar/polizzacar/uploadPDFfiles',
            uploadExtraData: function() {
                return {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    field_name: 'pdf_payment_proof',
                    polizzaId: $('#polizzacarId').val()
                };
            }
        });

    },

    signAndAcceptButton: function(){
        $(document).on('click','.btnUploadFile',function(e){
            e.preventDefault();
            $('#modalUploadFile').modal({show: true});
        });
    },

    signDocusignButton: function(){
        $(document).on('click','.docuAjax',function(e){
            e.preventDefault();
            $('#signAjaxForm').modal({show: true});

            // $('#signAjaxForm .modal-dialog').removeClass('modal-lg').addClass('modal-xl');
            // $('#signAjaxForm .modal-title').html($.i18n._('choose_product_or_service'));
            if( $('#docuSigniFrame').is(':empty') ) {

                $('#signAjaxForm #docuSigniFrame').load('/polizzacar/polizzacar/docusign/'+ VAANCE.polizza_Id + '/', function (result) {

                    Url = $.parseJSON(result)
                    $('#iframe_loader').hide();

                    $('#docuSigniFrame').attr('src', Url.url);
                    
                    // $('#signAjaxForm').modal({show: false});
                    return Url.url;

                });
            }
            //function result(data){
                
           // }
        });
    },
    
    saveButton: function(){
        $(document).on('click','.btnSave',function(e){
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: '/polizzacar/updatePdfStatus',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'polizzaId' : $('#polizzacarId').val()
                },
                dataType: 'json',
                success: function (result) {
                    var status = result.data.status;
                    if (status == 'error') {
                        alert(result.data.msg);
                    } else if (status == 'ok') {
                        window.location.reload();
                    }                    
                }
            });
        });
    },

    initDateIssueInCreate: function () {
        $( document ).ready(function() {
            // check current is create.
            var url_pathname = window.location.pathname;
            
            if (url_pathname == '/polizzacar/polizzacar/create') { // this is polizzacar create
                // $('.datepicker').datetimepicker({date : new Date(1434544649384)});
                var today = new Date();
                var dd = today.getDate();
                var mm = today.getMonth()+1; 
                var yyyy = today.getFullYear();
                if(dd<10) { dd='0'+dd; } 
                if(mm<10) { mm='0'+mm; } 
                today = dd+'/'+mm+'/'+yyyy;

                $('.datepicker').val(today);
                $("label[for='date_request']").css('top', '-15px');
            }
            
        });
    }

};

VAANCE_PolizzaCar.init();