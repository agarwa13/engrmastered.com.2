@extends('emails.inline_template')
@section('content')
    @include('emails.inline_paragraph_top')
    Dear Admin,
    @include('emails.inline_paragraph_bottom')

    @include('emails.inline_paragraph_top')
    <a href="{{url('user/'.$user->id)}}">{{$user->name}}</a> has requested a payout of {{$amount}}.
    @include('emails.inline_paragraph_bottom')

    @include('emails.inline_paragraph_top')
    Since this amount is quite large, we have not processed it automatically. Please review the user's account and
    if everything looks okay, process their request.
    @include('emails.inline_paragraph_bottom')
@endsection