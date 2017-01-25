@extends('emails.inline_template')

@section('content')

    @include('emails.inline_paragraph_top')
    The Question <a href="{{url('question/'.$question->id)}}">{{$question->title}}</a> has been created.
    We will update you once the question has been solved and an administrator has approved the solution.
    @include('emails.inline_paragraph_bottom')

@endsection