VAANCE_Login = {

    init: function () {

        $('#userAdmin').on('click', function () {
            $('#name').val('admin@vaance.com');
            $('#password').val('admin');
        });
        $('#userCompany1').on('click', function () {
            $('#name').val('norman@vaance.com');
            $('#password').val('admin');
        });
        $('#userCompany2').on('click', function () {
            $('#name').val('wesker@vaance.com');
            $('#password').val('admin');
        });

        $('#sign_up').on('submit', function(ev) {
            $('#signupModal').modal('show');
        });


    }

}

VAANCE_Login.init();