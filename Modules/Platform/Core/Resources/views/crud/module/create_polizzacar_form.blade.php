<script src="https://unpkg.com/materialize-stepper@3.1.0/dist/js/mstepper.min.js"></script>

<style>
    li{
        list-style: none;
    }
</style>

    {!! form_start($form) !!}
    

<ul class="stepper linear">

    @foreach($show_fields as $panelName => $panel)

        <li class="step {{ $loop->first ? 'active' : '' }}">
            <div class="step-title waves-effect">
                {{ Html::section($language_file,$panelName,$sectionButtons) }}
            </div>

            <div class="step-content">                
                @foreach($panel as $fieldName => $options)

                    @if($form->{$fieldName}!= null && !isset($options['hide_in_form']) && !isset($options['hide_in_create']))
                        <div class="{{ isset($options['col-class']) ? $options['col-class'] : 'col-lg-6 col-md-6 col-sm-6 col-xs-6' }}">

                            {!! form_row($form->{$fieldName}) !!}
                        </div>
                    @endif

                @endforeach

                <div class="step-actions" style="clear: both;">
                    @if ( ! $loop->first)
                        <button class="waves-effect waves-dark btn btn-warning previous-step">BACK</button>
                    @endif
                    @if ( ! $loop->last)
                        <button class="waves-effect waves-dark btn btn-success next-step" >CONTINUE</button>
                    @endif
                </div>
            </div>
        </li>
    @endforeach

    {!! form_end($form, $renderRest = true) !!}

</ul>

</div>
<script type="text/javascript">
    $(document).ready(function(){
        function saveStepData(destroyFeedback, form, activeStepContent) {
            // The true parameter will proceed to the next step besides destroying the preloader
            //destroyFeedback(false);
        }

        function validationFunction(stepperForm, activeStepContent) {
            // You can use the 'stepperForm' to valide the whole form around the stepper:
            someValidationPlugin(stepperForm);
            // Or you can do something with just the activeStepContent
            someValidationPlugin(activeStepContent);
            // Return true or false to proceed or show an error
            return true;
        }

        function defaultValidationFunction(stepperForm, activeStepContent) {
            var ret = true;
            var inputs = activeStepContent.querySelectorAll('input, textarea, select');
            for (let i = 0; i < inputs.length; i++) {
                if (!inputs[i].checkValidity()) {
                    ret = false;
                    break;
                }
            }
            
             if (ret) {
                var currentSteps = stepperInstace.getSteps();
                var step_id = currentSteps.active.index + 1;
                
                // do what you like with the input
                $input = $('<input type="text" class="temp_step" name="step_id"/>').attr("value", step_id);

                // append to the form
                $('#module_form').append($input);

                // save step data to db.
                $.ajax({
                    type:'POST',
                    url: '/polizzacar/polizzacar/tmp',

                    //data:{name:name, password:password, email:email},
                    data: $('#module_form').serialize(), 

                    success:function(data){
                        //alert(data.success);
                        console.log(data.success);
                        //$('.temp_step').remove();
                    }
                });
            }


            return true;
        }
        var stepper = document.querySelector('.stepper');
        var stepperInstace = new MStepper(stepper, {
            // Default active step.
            firstActive: 0,
            // Allow navigation by clicking on the next and previous steps on linear steppers.
            linearStepsNavigation: true,
            // Auto focus on first input of each step.
            autoFocusInput: false,
            // Set if a loading screen will appear while feedbacks functions are running.
            showFeedbackPreloader: true,
            // Auto generation of a form around the stepper.
            autoFormCreation: true,
            // Function to be called everytime a nextstep occurs. It receives 2 arguments, in this sequece: stepperForm, activeStepContent.
            validationFunction: defaultValidationFunction, // more about this default functions below
            // Enable or disable navigation by clicking on step-titles
            stepTitleNavigation: true,
            // Preloader used when step is waiting for feedback function. If not defined, Materializecss spinner-blue-only will be used.
            feedbackPreloader: '<div class="spinner-layer spinner-blue-only">...</div>'
        });
    });
</script>


@if( $modal_form )
    @foreach($jsFiles as $jsFile)
        <script src="{!! Module::asset($moduleName.':js/'.$jsFile) !!}"></script>
    @endforeach
@endif


@if($form_request != null && $modal_form)
    {!! JsValidator::formRequest($form_request, '#'.$formId) !!}
@endif
