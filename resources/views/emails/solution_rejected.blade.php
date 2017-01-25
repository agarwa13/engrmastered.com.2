@extends('emails.inline_template')

@section('content')
    @include('emails.inline_paragraph_top')
    Dear {{$user->name}},
    @include('emails.inline_paragraph_bottom')

    @include('emails.inline_paragraph_top')
    You recently submitted a solution for the question <a href="{{url('question/'.$question->id)}}">{{$question->title}}</a> posted on <a href="{{url('/')}}">Engineering Mastered</a>. An administrated has reviewed the solutions submitted for this question. Unfortunately, your solution was not selected.
    @include('emails.inline_paragraph_bottom')

    @include('emails.inline_paragraph_top')
    If the selected solution has negative reviews against it, then we will re-consider the solution you have posted.
    @include('emails.inline_paragraph_bottom')

    @include('emails.inline_paragraph_top')
    Please note that your solution may have been accurate. However, if a different user also submitted an accurate solution that meets our guidelines sooner than you did, then we select their solution.
    @include('emails.inline_paragraph_bottom')

@endsection