@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4>Review</h4>
                <p>Review Submitted By: <a href="{{url('user/'.$review->user->id)}}">{{$review->user->name}}</a></p>
                <p>Positive Review: {{$review->positive_review}}</p>
                <p>Refund Requested: {{$review->refund_requested}}</p>
                <p>Refund Authorized By: {{$review->refund_authorized_by or "Not Authorized"}}</p>
                <p>Comment: $review->comment</p>
                <a href="">See more reviews of this question</a>
            </div>
        </div>
    </div>
@endsection