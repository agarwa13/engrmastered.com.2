@extends('app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4>My Account</h4>
                <hr>
                <div class="col-md-6">

                    <!-- User Info -->
                    <p><strong>User Info</strong>
                    <p>
                        <a href="#" class="name" data-name="name" data-type="text" data-pk="{{$user->id}}" data-url="{{url('user/'.$user->id)}}">{{$user->name}}</a>
                    </p>
                    <p>
                        <a href="#" class="email" data-name="email" data-type="text" data-pk="{{$user->id}}" data-url="{{url('user/'.$user->id)}}">{{$user->email}}</a>
                    </p>
                    @if(!$user->confirmed)
                    <div class="alert alert-warning" role="alert">
                         (Email address is unconfirmed) <a href="#" onclick="resend_email_confirmation()" class="alert-link">Click here to re-send confirmation email</a>
                    </div>
                    @endif


                    <!-- Password -->
                    <p><strong>Password</strong></p>
                    {{--<a href="{{url('user/'.$user->id.'/edit/password/')}}">Change password</a>--}}
                    Change Password: <a href="#" class="password" data-name="password" data-type="password" data-pk="{{$user->id}}" data-url="{{url('user/'.$user->id)}}">[hidden]</a>

                </div>


                <div class="col-md-6">
                    <!-- Engineering Mastered Credits -->
                    <p><strong>Available Engineering Mastered Credits</strong></p>
                    <span data-toggle="tooltip" data-placement="right" title="Earn more credits by asking questions">{{$user->tokens_remaining}}</span>
                    <!-- Income Earned from Solving Questions -->
                    <p><strong>Unredeemed Income from Solutions Submitted</strong></p>
                    <span class="unredeemed_income" data-toggle="tooltip" data-placement="right" title="Earn more income by solving unanswered questions">${{$user->income - $user->income_redeemed}}</span>
                    {{--@if($user->income - $user->income_redeemed > 0) <a class="redeem" href="#">Redeem Income</a> @endif--}}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div id="payment-methods-wrapper">
                    <!-- Payment Information -->
                    <h4 style="padding-top: 20px;">Payment Methods</h4>
                    <hr>
                    <p><a href="#" class="subscribeButton">Add Payment Method</a></p>
                    @if($user->has_payment_method())
                        @each('user.payment_method',$user->payment_methods(),'payment_method')
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $.fn.editable.defaults.mode = 'popup';
            $.fn.editable.defaults.ajaxOptions = {type: "PUT"};
            $('.name').editable();
            $('.email').editable();
            $('.password').editable();
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>

    <script>
        function delete_payment_method(token){
            $.ajax({
                url: "{{url('payment_method')}}" + "/" + token,
                type: 'DELETE',
                success: function(response){
                    $('[data-resource-type="payment_method"][data-resource-id="' + token + '"]').hide('slow', function(){ $target.remove(); });
                    $.notify('Payment Method Removed','success');
                }
            });
        }
    </script>

    <script>
        function make_payment_method_default(token){
            $.ajax({
                url: "{{url('default_payment_method')}}" + "/" + token,
                type: 'POST',
                success: function(response){
                    console.log(response);
                    $('[data-resource-type="payment_method"][data-resource-id="' + response.new_default_token + '"]').replaceWith(response.new_default_html);
                    $('[data-resource-type="payment_method"][data-resource-id="' + response.previous_default_token + '"]').replaceWith(response.previous_default_html);
                    $.notify('Default Payment Method Changed','success');
                }
            });
        }
    </script>

    <script>
        function resend_email_confirmation(){
            $.ajax({
                url: "{{url('email/resend-confirmation/'.$user->id)}}",
                type: 'POST',
                success: function(response){
                    console.log(response);
                    $.notify('We have sent you a confirmation email. Please check your inbox and spam folder.','success');
                },
                error: function(jqXHR, textStatus, errorThrown){
                    if (jqXHR.status == 400) {
                        $.notify('We cannot send anymore emails to this email address. Please change your email address before requesting a new confirmation email', 'warning');
                    }
                }
            });
        }
    </script>

    <script>
        // Send a Request to Redeem Earnings
        $('.redeem').on('click',function(event){
            event.preventDefault();
            bootbox.confirm({
                message: 'Your earnings will be sent via PayPal to {{$user->email}}. Click OK to confirm',
                callback: function(r){
                    if(r){

                        $.ajax({
                            url: '{{url('paypal/redeem-earnings')}}',
                            method: 'post',
                            success: function(response,status){
                                $('.unredeemed_income').html('0$');
                                $('.redeem').remove();
                                $.notify('Payment Processed.', 'success');
                            },
                            error: function(jqXHR, textStatus, errorThrown){

                                console.log(jqXHR.status);

                                if(jqXHR.status == 404 ){
                                    $.notify('Since you are redeeming more than 500$, an administrator must manually review the request. We will be in touch shortly.', 'warning');
                                }

                                if(jqXHR.status == 403 ){
                                    $.notify('Please wait until you accumulate at least 20$ in earnings before redeeming your earnings', 'warning');
                                }

                                if(jqXHR.status == 402){
                                    $.notify('Administrators should email their managers. Do not redeem income through the website', 'warning');
                                }

                                if (jqXHR.status == 401){
                                    $.notify('Unable to Process Payment. Please confirm your email address prior to redeeming earnings.', 'warning');
                                }

                                if (jqXHR.status == 400) {
                                    $.notify('Unable to Process Payment. Administrator has been notified and will manually process your request.', 'warning');
                                }


                            }
                        });
                    }
                }
            });
        });
    </script>

@endsection