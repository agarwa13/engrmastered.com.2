@extends('emails.inline_template')
@section('content')
@include('emails.inline_paragraph_top')
The Question <a href="{{url('question/'.$question->id)}}">{{$question->title}}</a> has been solved!
You can request a solution by clicking here: <a href="{{url('question/'.$question['id'])}}">{{url('question/'.$question['id'])}}</a>
@include('emails.inline_paragraph_bottom')
@endsection