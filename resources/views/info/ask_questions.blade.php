@extends('app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <img src="{{asset('vendor/icons/ask_question.png')}}" class="img-responsive" height="300px">
            </div>
        </div>
        <div class="row">


            <div class="col-md-12" style="font-size: 16px; line-height: 24px;">

                <h2>Ask Questions, Earn Credits</h2>

                <p>
                    Unlike Chegg who charges you points to ask questions, we reward you for asking questions!
                </p>

                <p>
                    The first time a user pays for access to a question you posted, you will receive two credits in your account!
                </p>

                <p>
                    We will also notify you via email when the question is solved, so that you can get the solution as soon as it is available.
                </p>

            </div>
        </div>
    </div>

@endsection