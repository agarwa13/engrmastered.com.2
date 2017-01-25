@extends('app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <img src="{{asset('vendor/icons/find_answer.png')}}" class="img-responsive" height="300px">
            </div>
        </div>
        <div class="row">


            <div class="col-md-12" style="font-size: 16px; line-height: 24px;">

                <h2>Answer Questions, Earn Money</h2>

                <p>
                    Unlike Chegg and other competitors, we reward the smartest visitors of our website with money not worthless points. And we don't just reward you when you solve a question, we continue rewarding you each time another user pays for access to your solution!
                </p>

                <p>
                    Each solution submitted is reviewed by an administrator. If your solution meets our guidelines, we will publish your solution (and send you an email to congratulate you). Once your solution is published you will start earning money!
                </p>

                <p>
                    The first time access to your solution is purchased you will earn 1.50 $. For each subsequent time your solution is purchased, you will earn 20 cents.
                </p>

                <p>
                    You have the option of redeeming your earnings at anytime by visiting your account page. As long as you have at least 20$ in earnings, your earnings will be sent to you via PayPal.
                </p>

                <div class="alert alert-info">
                    <p>
                        At this time, we are only allowing a limited number of folks to solve questions. If you are interested in joining the team solving questions, drop us an email at <a class="alert-link" href="mailto:nikhil@engineeringmastered.com">nikhil@engineeringmastered.com</a> and tell us why you think you are a good candidate. We will get back to you as soon as possible
                    </p>

                    <p>
                        Here are a few things we look for at this early stage:
                    </p>
                    <ul>
                        <li>Familiarity with PHP or any programming language really</li>
                        <li>Familiarity with tools like MATLAB, Mathematica, Wolfram Alpha</li>
                        <li>Good grades in Physics, Math, Chemistry, Dynamics, Statics etc.</li>
                        <li>Strong network in a university (Actively involved in student organization etc.)</li>
                    </ul>

                    <p>
                        You can find a guide to writing solutions to problems on Engineering Mastered here: <a href="{{url('write_solutions_guide')}}">Guide to Writing Solutions</a>
                    </p>

                </div>


            </div>
        </div>
    </div>

@endsection