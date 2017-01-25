@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3>Questions</h3>
                <table class="table">
                    <tr>
                        <td>Title</td>
                        <td>Positive Reviews</td>
                        <td>Negative Reviews</td>
                        <td>Refund Requests</td>
                        <td>Total Reviews</td>
                    </tr>
                @foreach($questions as $question)
                    <tr>
                        <td><a href="{{url('question/'.$question->id)}}">{{$question->title}}</a></td>
                        <td class="text-center">{{$question->positiveReviewsCount->aggregate or "0"}}</td>
                        <td class="text-center">{{$question->negativeReviewsCount->aggregate or "0"}}</td>
                        <td class="text-center">{{$question->refundRequestsCount->aggregate or "0"}}</td>
                        <td class="text-center">{{$question->reviewsCount->aggregate or "0"}}</td>
                    </tr>
                @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection