@extends('emails.inline_template')

@section('content')

    @include('emails.inline_paragraph_top')
        To set a new password, click on the button below.
    @include('emails.inline_paragraph_bottom')

    @include('emails.inline_paragraph_top')
        @include('emails.inline_button_as_link',['link' => url('password/reset/'.$token), 'text' => 'Reset Password'])
    @include('emails.inline_paragraph_bottom')

    @include('emails.inline_paragraph_top')
        If the button above doesn't work, copy and paste the following into your browser:
    @include('emails.inline_paragraph_bottom')


    @include('emails.inline_paragraph_top')
        <a href="{{ url('password/reset/'.$token) }}">{{ url('password/reset/'.$token) }}</a>
    @include('emails.inline_paragraph_bottom')

    @include('emails.inline_paragraph_top')
        If you didn't request this password reset, let us know right away at <a href="mailto:nikhil@engineeringmastered.com">nikhil@engineeringmastered.com</a>
    @include('emails.inline_paragraph_bottom')

@endsection