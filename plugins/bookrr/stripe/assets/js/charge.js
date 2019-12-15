bookrr = {
    stripe:{
        pubKey: ""
    }
};

(function(bookrr){
    var stripe;

    bookrr.stripeInit = function(){
        stripe = Stripe(bookrr.stripe.pubKey);

        var elements = stripe.elements({
            fonts: [{
                cssSrc: 'https://fonts.googleapis.com/css?family=Roboto',
            }, ],
            // Stripe's examples are localized to specific languages, but if
            // you wish to have Elements automatically detect your user's locale,
            // use `locale: 'auto'` instead.
            locale: window.__exampleLocale
        });
    
        var card = elements.create('card', {
            iconStyle: 'solid',
            style: {
                base: {
                    iconColor: '#c4f0ff',
                    color: '#fff',
                    fontWeight: 500,
                    fontFamily: 'Roboto, Open Sans, Segoe UI, sans-serif',
                    fontSize: '16px',
                    fontSmoothing: 'antialiased',
    
                    ':-webkit-autofill': {
                        color: '#eeeeee',
                    },
                    '::placeholder': {
                        color: '#eeeeee',
                    },
                },
                invalid: {
                    iconColor: '#eeeeee',
                    color: '#eeeeee',
                },
            },
        });
    
        card.mount('#card-element');
    
        registerElements([card], '#stripe');
    }
    
    // bookrr.charge = function() {
    //     stripe.createToken(card).then(function (result) {
    //         if (result.error) {
    //             // Inform the user if there was an error
    //             var errorElement = document.getElementById('card-errors');
    //             errorElement.textContent = result.error.message;
    //         } else {
    //             stripeTokenHandler(result.token);
    //             $('#payment-form').request('onCreate');
    //         }
    //     });
    // }

    
    function registerElements(elements, wrapper) {
        var formClass    = wrapper;
        var formWrap      = document.querySelector(formClass);
    
        var form         = formWrap.querySelector('form');
        var resetButton  = formWrap.querySelector('a.reset');
        var error        = form.querySelector('.error');
        var errorMessage = error.querySelector('.message');
    
        function enableInputs() {
            Array.prototype.forEach.call(
                form.querySelectorAll(
                    "input[type='text'], input[type='email']"
                ),
                function (input) {
                    input.removeAttribute('disabled');
                }
            );
        }
    
        function disableInputs() {
            Array.prototype.forEach.call(
                form.querySelectorAll(
                    "input[type='text'], input[type='email'], input[type='tel']"
                ),
                function (input) {
                    input.setAttribute('disabled', 'true');
                }
            );
        }
    
        function triggerBrowserValidation() {
            // The only way to trigger HTML5 form validation UI is to fake a user submit
            // event.
            var submit = document.createElement('input');
            submit.type = 'submit';
            submit.style.display = 'none';
            form.appendChild(submit);
            submit.click();
            submit.remove();
        }
    
        // Listen for errors from each Element, and show error messages in the UI.
        var savedErrors = {};
        elements.forEach(function (element, idx) {
            element.on('change', function (event) {
                if (event.error) {
                    error.classList.add('visible');
                    savedErrors[idx] = event.error.message;
                    errorMessage.innerText = event.error.message;
                } else {
                    savedErrors[idx] = null;
    
                    // Loop over the saved errors and find the first one, if any.
                    var nextError = Object.keys(savedErrors)
                        .sort()
                        .reduce(function (maybeFoundError, key) {
                            return maybeFoundError || savedErrors[key];
                        }, null);
    
                    if (nextError) {
                        // Now that they've fixed the current error, show another one.
                        errorMessage.innerText = nextError;
                    } else {
                        // The user fixed the last error; no more errors.
                        error.classList.remove('visible');
                    }
                }
            });
        });
    

        // Listen on the form's 'submit' handler...
        bookrr.submit = function(){
            // Trigger HTML5 validation UI on the form if any of the inputs fail
            // validation.
            var plainInputsValid = true;
            Array.prototype.forEach.call(form.querySelectorAll('input'), function (
                input
            ) {
                if (input.checkValidity && !input.checkValidity()) {
                    plainInputsValid = false;
                    return;
                }
            });
            if (!plainInputsValid) {
                triggerBrowserValidation();
                return;
            }

            // Show a loading screen...
            formWrap.classList.add('submitting');

            // Disable all inputs.
            disableInputs();

            // Gather additional customer data we may have collected in our form.
            var name     = form.querySelector('stripe-name');
            var address1 = form.querySelector('stripe-address');
            var city     = form.querySelector('stripe-city');
            var state    = form.querySelector('stripe-state');
            var zip      = form.querySelector('stripe-zip');
            var additionalData = {
                name: name ? name.value : undefined,
                address_line1: address1 ? address1.value : undefined,
                address_city: city ? city.value : undefined,
                address_state: state ? state.value : undefined,
                address_zip: zip ? zip.value : undefined,
            };

            // Use Stripe.js to create a token. We only need to pass in one Element
            // from the Element group in order to create a token. We can also pass
            // in the additional customer data we collected in our form.
            stripe.createToken(elements[0], additionalData).then(function (result) {
                // Stop loading!
                formWrap.classList.remove('submitting');

                if (result.token) {
                    // If we received a token, show the token ID.
                    stripeTokenHandler(result.token);
                    formWrap.classList.add('submitted');

                    $('#payment-form').request('onCreate',{
                        success: function(res){
                            $('.modal').modal('hide');
                            $.request('onRefresh');
                        }
                    });
                } else {
                    // Otherwise, un-disable inputs.
                    enableInputs();
                }
            });

            return this;
        }
    
        document.querySelector('#stripeSubmit').addEventListener('click', function(){
            bookrr.submit();
            return this;
        });

        resetButton.addEventListener('click', function (e) {
            e.preventDefault();
            // Resetting the form (instead of setting the value to `''` for each input)
            // helps us clear webkit autofill styles.
            form.reset();
    
            // Clear each Element.
            elements.forEach(function (element) {
                element.clear();
            });
    
            // Reset error state as well.
            error.classList.remove('visible');
    
            // Resetting the form does not un-disable inputs, so we need to do it separately:
            enableInputs();
            formWrap.classList.remove('submitted');
        });
    }

    // Send Stripe Token to Server
    function stripeTokenHandler(token) {
        // Insert the token ID into the form so it gets submitted to the server
        var form = document.getElementById('payment-form');
        // Add Stripe Token to hidden input
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        form.appendChild(hiddenInput);
    }

})(bookrr);