@extends('emails.inline_template')

@section('content')
    @include('emails.inline_paragraph_top')
        {{$message}}
    @include('emails.inline_paragraph_bottom')
@endsection