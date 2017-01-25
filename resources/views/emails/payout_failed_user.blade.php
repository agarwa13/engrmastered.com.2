@extends('emails.inline_template')

@section('content')

    @include('emails.inline_paragraph_top')
    Hi {{$user->name}},
    @include('emails.inline_paragraph_bottom')

    @include('emails.inline_paragraph_top')
        You recently tried to redeem your earnings of {{$amount}}$ on Engineering Mastered. Unfortunately, PayPal has declined the transaction.
        The most common reason is an incorrect email address but occasionally there may be some other issues as well.
    @include('emails.inline_paragraph_bottom')

    @include('emails.inline_paragraph_top')
        Regardless, we have logged the issue and alerted an administrator.
        The administrator will attempt to manually process the transaction and will contact you if he needs any additional information from you.
    @include('emails.inline_paragraph_bottom')

    @include('emails.inline_paragraph_top')
        Please visit your <a href="{{url('user/'.$user->id)}}">profile on Engineering Mastered</a> to ensure we have an accurate email address.
    @include('emails.inline_paragraph_bottom')

@endsection



