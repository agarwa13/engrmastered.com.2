<div class="panel panel-default" data-resource-type="payment_method" data-resource-id="{{$payment_method->token}}">
    <div class="panel-heading">
        <a role="button" data-toggle="collapse" href="#{{$payment_method->token}}">
            <h4 class="panel-title" style="display: inline-block">PayPal</h4>
        </a>
        @if($payment_method->default)
            <span class="label label-info tag">Default</span>
        @endif
    </div>

    <div id="{{$payment_method->token}}" class="panel-collapse collapse">
        <div class="panel-body">
            <div class="col-md-6">
                Email: {{$payment_method->email}}
            </div>

            <div class="col-md-6">
                @if(!$payment_method->default)
                    <button class="btn btn-default" type="button" onclick="make_payment_method_default('{{$payment_method->token}}')">Make Default</button>
                    <button class="btn btn-default" type="button" onclick="delete_payment_method('{{$payment_method->token}}')">Delete</button>
                @endif
            </div>

        </div>

    </div>

</div>