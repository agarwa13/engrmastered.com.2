@extends('emails.inline_template')

@section('content')

@include('emails.inline_paragraph_top')
Dear Admin,
@include('emails.inline_paragraph_bottom')

@include('emails.inline_paragraph_top')
A dispute of type {{$type}} has been updated on Stripe. Please login to your stripe account to see what is going on.
@include('emails.inline_paragraph_bottom')


@endsection