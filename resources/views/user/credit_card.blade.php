<div class="panel panel-default" data-resource-type="payment_method" data-resource-id="{{$payment_method->id}}">
    <div class="panel-heading">
        <a role="button" data-toggle="collapse" href="#{{$payment_method->id}}">
            <h4 class="panel-title" style="display: inline-block">{{$payment_method->brand}} ending in {{$payment_method->last4}}</h4>
        </a>
        @if($payment_method->id == $default_payment_method_card_id)
            <span class="label label-info tag">Default</span>
        @endif
    </div>

    <div id="{{$payment_method->id}}" class="panel-collapse collapse">
        <div class="panel-body">
            <div class="col-md-6">
                <p>Name on Card: {{$payment_method->name}}</p>
                <p>Expires {{$payment_method->exp_month}} / {{$payment_method->exp_year}}</p>
            </div>

            <div class="col-md-6">
                @if($payment_method->id == $default_payment_method_card_id)
                    <p>Change the Default Method of Payment to Delete this card</p>
                @else
                    <button class="btn btn-default" type="button" onclick="make_payment_method_default('{{$payment_method->id}}')">Make Default</button>
                    <button class="btn btn-default" type="button" onclick="delete_payment_method('{{$payment_method->id}}')">Delete</button>
              @endif
            </div>

        </div>

    </div>

</div>