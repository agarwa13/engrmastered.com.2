@extends('emails.inline_template')

@section('content')

    @include('emails.inline_paragraph_top')
            <a href="{{url('user/'.$user->id)}}">{{$user->name}}</a> tried to redeem his income.
            At the time of the request, his income was {{$amount}}. Can you look into what happened and manually process his pay out?
    @include('emails.inline_paragraph_bottom')

    @include('emails.inline_paragraph_top')
            Email: {{$user->email}}
    @include('emails.inline_paragraph_bottom')

    @include('emails.inline_paragraph_top')
            User ID: {{$user->id}}
    @include('emails.inline_paragraph_bottom')
@endsection



