<script>
    var handler_for_save_payment_method_registered_user = StripeCheckout.configure({
        key: '{{env('STRIPE_PUBLIC_KEY')}}',
        image: '/images/logo-dark.png',
        locale: 'auto',
        token: function(token){
            getSolutionForm = $('#getSolutionForm');
            (getSolutionForm.find('input[name=token]')).val(token.id);
            getSolutionForm.submit();
        }
    });

    // Close Checkout on page navigation
    $(window).on('popstate', function() {
        handler_for_save_payment_method_registered_user.close();
    });

    function launch_stripe_for_save_payment_method_registered_user() {
        handler_for_save_payment_method_registered_user.open({
            name: 'Engineering Mastered',
            panelLabel: 'Save Payment Method',
            @if(!Auth::guest())
            email: '{{Auth::user()->email}}'
            @endif
        });
    }
</script>