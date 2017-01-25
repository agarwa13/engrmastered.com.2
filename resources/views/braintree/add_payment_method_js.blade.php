<script>
    var handler = StripeCheckout.configure({
        key: '{{env('STRIPE_PUBLIC_KEY')}}',
        image: '/images/logo-dark.png',
        locale: 'auto',
        token: function(token) {
            console.log(token.id);
            $.ajax({
                type: "POST",
                url: '{{url('add-payment-method')}}',
                data: {
                    token : token.id
                },
                success: function(response){
                    console.log(response);
                    $('[data-resource-type="payment_method"][data-resource-id="' + response.previous_default_token + '"]').replaceWith(response.previous_default_html);
                    $('#payment-methods-wrapper').append(response.html);
                    $('.subscribeButton.removable').remove();
                    $.notify('Credit Card Saved for Future Use', 'success');
                }
            });
        }
    });

    // Close Checkout on page navigation
    $(window).on('popstate', function() {
        handler.close();
    });
</script>

<script>
    $('.subscribeButton').on('click', function(e) {
        // Open Checkout with further options
        handler.open({
            name: 'Engineering Mastered',
            panelLabel: 'Save Card',
            @if(!Auth::guest())
            email: '{{Auth::user()->email}}'
            @endif
        });
        e.preventDefault();
    });
</script>


