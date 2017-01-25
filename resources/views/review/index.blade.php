@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3>Reviews</h3>
                <table id="table" data-search="true" data-detail-view="true" data-detail-formatter="detailFormatter">
                    <thead>
                    <tr>
                        <th data-field="review_id" data-align="center" data-sortable="true">ID</th>
                        <th data-field="question_id" data-align="center" data-sortable="true">Question ID</th>
                        <th data-field="user" data-align="center" data-sortable="true">User</th>
                        <th data-field="negative_review" data-align="center" data-sortable="true">Positive Review?</th>
                        <th data-field="refund_status" data-align="center" data-sortable="true">Refund Status</th>
                        <th data-field="comment" >Comment</th>
                        <th data-field="usage_record_id" data-align="center">Usage Record ID</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($reviews as $review)
                        <tr data-review-id="{{$review->id}}" data-tokens-paid="{{$review->question_usage_record->tokens_paid or 0}}" data-amount-charged="{{$review->question_usage_record->stripe_charge_amount or 0}}">
                            <td>{{$review->id}}</td>
                            <td><a href="{{url('question/'.$review->question_id)}}">{{$review->question_id}}</a></td>
                            <td><a href="{{url('user/'.$review->user_id)}}">{{$review->user->name}}</a></td>
                            <td>@if($review->positive_review) Yes @else No @endif</td>
                            <td>@if($review->refund_requested)
                                    @if($review->refund_request_reviewed)
                                        @if($review->refund_authorized_by > 0)
                                            Approved by {{$review->refund_authorizer->name}}
                                        @else
                                            Request Reviewed
                                        @endif
                                    @else
                                        Not Reviewed
                                    @endif
                                @else
                                    Not Requested
                                @endif
                            </td>
                            <td>{{$review->comment}}</td>
                            <td><a href="{{url('usage_record/'.$review->question_usage_record_id)}}">{{$review->question_usage_record_id}}</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Initiate the Bootstrap Table
        var reviews_table = $('#table').bootstrapTable();
    </script>

    <script>
        function detailFormatter(index, row){
            var html = [];
            rowElement = $('tr[data-review-id="' + row.review_id + '"]');
            console.log(rowElement.attr('data-review-id'));
            html.push('Tokens Paid: ' + rowElement.attr('data-tokens-paid'));
            html.push('Amount Charged: ' + rowElement.attr('data-amount-charged') + '$');
            html.push(
                        '<a href="#" onclick="delete_review(event, ' + row.review_id + ')">Delete Review</a>'
                        + ' <a style="padding-left: 20px;" href="#" onclick="refund_tokens(event, ' + row.review_id + ')">Refund Tokens</a>'
                        + ' <a style="padding-left: 20px;" href="#" onclick="refund_charge(event, ' + row.review_id + ')">Refund Charge</a>'
                        + ' <a style="padding-left: 20px;" href="#" onclick="refund_all(event, ' + row.review_id + ')">Refund All</a>'
            );
            return html.join('<br>');
        }
    </script>


    <script>
        /*
         Send an Admin Request
         */
        function post_admin_action(action, id){
            $.ajax({
                type: "POST",
                url: "{{url('admin/action')}}",
                data: { action: action, id: id }
            });
        }


        /*
         Delete Review
         */
        function delete_review(event, id){
            event.preventDefault();
            bootbox.confirm({
                message: 'Are you sure you want to delete this review?',
                callback: function(r){
                    if(r){
                        $.ajax({
                            type: "DELETE",
                            url: "{{url('review')}}" + "/" + id
                        });
                    }
                },
                backdrop: false
            });
        }

        /*
        Refund Tokends
         */
        function refund_tokens(event, id){
            event.preventDefault();
            post_admin_action('refund_tokens_based_on_review',id);
        }

        /*
        Refund Charge
         */
        function refund_charge(event,id){
            event.preventDefault();
            post_admin_action('refund_charge_based_on_review',id);
        }

        /*
        Refund Tokens and Charge
         */
        function refund_tokens_and_charge(event,id){
            event.preventDefault();
            post_admin_action('refund_tokens_and_charge_based_on_review',id);
        }


    </script>

@endsection