@extends('emails.inline_template')

@section('content')

    @include('emails.inline_paragraph_top')
    Dear {{$user->name}},
    @include('emails.inline_paragraph_bottom')

    @include('emails.inline_paragraph_top')
    Please confirm your email address by clicking on the button below. You will receive 2 free credits once your email address is confirmed.
    @include('emails.inline_paragraph_bottom')

    @include('emails.inline_paragraph_top')
        @include('emails.inline_button_as_link',['link' => url('email/confirmation/'.$user->confirmation_code), 'text' => 'Confirm Account'])
    @include('emails.inline_paragraph_bottom')

@endsection