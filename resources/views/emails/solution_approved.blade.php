@extends('emails.inline_template')

@section('content')
    @include('emails.inline_paragraph_top')
    Dear {{$user->name}},
    @include('emails.inline_paragraph_bottom')

    @include('emails.inline_paragraph_top')
    You recently submitted a solution for the question <a href="{{url('question/'.$question->id)}}">{{$question->title}}</a> posted on <a href="{{url('/')}}">Engineering Mastered</a>. An administrated has reviewed your solution and has marked it as the accepted answer!
    @include('emails.inline_paragraph_bottom')

    @include('emails.inline_paragraph_top')
    When a user first purchases access to your solution, you will receive 2 dollars. You will also receive 20 cents each subsequent time a user purchases access to your solution. You can redeem your earnings from <a href="{{url('user/'.$user->id)}}">your account page</a>.
    @include('emails.inline_paragraph_bottom')

@endsection