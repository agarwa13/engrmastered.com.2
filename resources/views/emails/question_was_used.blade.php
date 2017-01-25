@extends('emails.inline_template')

@section('content')

    @include('emails.inline_paragraph_top')
    Dear {{$user->name}},
    @include('emails.inline_paragraph_bottom')

    @include('emails.inline_paragraph_top')
    Thank you for purchasing the solution to <a href="{{url('question/'.$question->id)}}">{{$question->title}}</a>.
    @include('emails.inline_paragraph_bottom')

    @include('emails.inline_paragraph_top')
    We hope you are satisfied with the solution you received.
    However, if you are not satisfied, please do not hesitate to contact us.
    @include('emails.inline_paragraph_bottom')

    @include('emails.inline_paragraph_top')
    For complaints related to the question or solution itself, please visit the history page and initiate a negative review.
    For more general complaints, contact us at <a href="mailto:admin@engrmastered.com">admin@engrmastered.com</a>
    @include('emails.inline_paragraph_bottom')

@endsection