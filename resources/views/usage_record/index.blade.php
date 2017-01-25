@extends('app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <table
                        data-toggle="table"
                        data-pagination="true"
                        data-search="true">
                    <thead>
                    <tr>
                        <th data-field="id" class="col-md-1">ID</th>
                        <th data-field="question_id" class="col-md-1">QID</th>
                        <th data-field="user_id" class="col-md-2">Name</th>
                        <th data-field="tokens_paid" class="col-md-1">Tokens Paid</th>
                        <th data-field="stripe_charge_amount" class="col-md-1">Dollars Paid</th>
                        <th data-field="tokens_refunded" class="col-md-1">Tokens Refunded</th>
                        <th data-field="charge_refunded" class="col-md-1">Dollars Refunded</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($usage_records as $usage_record)
                        <tr data-resource-type="usage_record" data-resource-id="{{$usage_record->id}}" data-url="{{url('usage_record/'.$usage_record->id)}}">
                            <td data-field="id"><a href="{{url('usage_record/'.$usage_record->id)}}">{{$usage_record->id}}</a></td>
                            <td data-field="question_id"><a href="{{url('question/'.$usage_record->question_id)}}">{{$usage_record->question_id}}</a></td>
                            <td data-field="user_id"><a href="{{url('user/'.$usage_record->user_id)}}">{{$usage_record->user->name}}</a></td>
                            <td data-field="tokens_paid">{{$usage_record->tokens_paid}}</td>
                            <td data-field="stripe_charge_amount">{{$usage_record->stripe_charge_amount}}</td>
                            <td data-field="tokens_refunded">@if($usage_record->tokens_refunded) true @else false @endif</td>
                            <td data-field="charge_refunded">@if($usage_record->charge_refunded) true @else false @endif</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>

@endsection