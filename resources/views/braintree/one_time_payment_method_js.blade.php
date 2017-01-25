<script>

    var handler = StripeCheckout.configure({
        key: '{{env('STRIPE_PUBLIC_KEY')}}',
        image: '/images/logo-dark.png',
        locale: 'auto',
        token: function(token) {
            getSolutionForm = $('#getSolutionForm');

            (getSolutionForm.find('input[name=token]')).val(token.id);

            @if(!Auth::user())
                $('#registerUserModal').modal('show');
            @elseif(!Auth::user()->subscribed())
                $('#saveCreditCardModal').modal('show');
            @else
                $('#getSolutionForm').submit();
            @endif

        }
    });

    // Close Checkout on page navigation
    $(window).on('popstate', function() {
        handler.close();
    });

</script>

<script>
    $('.payButton').on('click', function(e) {
        // Open Checkout with further options
        handler.open({
            name: 'Engineering Mastered',
            panelLabel: 'Pay',
            amount: 200,
            @if(!Auth::guest())
            email: '{{Auth::user()->email}}'
            @endif
        });
        e.preventDefault();
    });
</script>


