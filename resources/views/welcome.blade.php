@extends('app')

@section('title','Homework Solutions to WebAssign, SmartPhysics and other college level courses |')
@section('description','Answers to your homework problem. Simply plug in the values from your homework problem into our calculator to get the answers to your particular homework problem.')

@section('content')

    <div class="jumbotron" style="padding-bottom: 80px">
        <div class="container">
            <h2>Homework Solutions</h2>
            <h2>For Engineering Students</h2>
            <h2>By Former Engineering Students</h2>
            <form action="{{url('search-results')}}" style="padding-top: 30px;">
                <div class="input-group input-group-lg">
                    <input autocomplete="off" type="text" name="q" class="form-control" placeholder="Search by Questions, Course, or University" autofocus>
                <span class="input-group-btn">
                    <button class="btn btn-warning" style="background-color: #F5298E; border-color: #F5298E; background-image: none;" type="submit">Search</button>
                </span>
                </div><!-- /input-group -->
            </form>
        </div>
    </div>

    <div class="container" style="padding-top: 50px;">
        <div class="row">
            <div class="col-md-4">
                {{--<div class="panel panel-default">--}}
                    {{--<div class="panel-body">--}}
                <img src="{{asset('vendor/icons/find_question.png')}}" class="img-responsive center-block" style="max-width: 50%">
                <h4 class="text-center" style="font-weight: 700"><a href="{{url('find_homework_solutions')}}">Find Homework Solutions</a></h4>
                <p class="text-center">Plug in your variables and find the answer. <br> Each solution only costs 2.00 $</p>
                    {{--</div>--}}
                {{--</div>--}}
            </div>

            <div class="col-md-4">
                {{--<div class="panel panel-default">--}}
                    {{--<div class="panel-body">--}}
                <img src="{{asset('vendor/icons/ask_question.png')}}" class="img-responsive center-block" style="max-width: 50%">
                <h4 class="text-center" style="font-weight: 700"><a href="{{url('ask_questions')}}">Ask Questions</a></h4>
                <p class="text-center">Earn credits for asking questions you are not able to find. Redeem credits for solutions</p>
                    {{--</div>--}}
                {{--</div>--}}
            </div>

            <div class="col-md-4">
                {{--<div class="panel panel-default">--}}
                    {{--<div class="panel-body">--}}
                        <img src="{{asset('vendor/icons/find_answer.png')}}" class="img-responsive center-block" style="max-width: 50%">
                        <h4 class="text-center" style="font-weight: 700"><a href="http://www.engineeringmastered.com">Video Solutions</a></h4>
                        <p class="text-center">
                            You can now find video solutions for a number of questions. The videos provide an in-depth understanding of the concepts and solves the problem step by step.</a>
                        </p>
                    </div>
                {{--</div>--}}
            {{--</div>--}}
        </div>
    </div>





@endsection