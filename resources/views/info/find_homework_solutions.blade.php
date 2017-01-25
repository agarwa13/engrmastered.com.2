@extends('app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <img src="{{asset('vendor/icons/find_question.png')}}" class="img-responsive" height="300px">
            </div>
        </div>
        <div class="row">


            <div class="col-md-12" style="font-size: 16px; line-height: 24px;">

                <h2>Find Homework Solutions</h2>

                <p>
                    Searching for your question is easy. Just paste a few words from your question into a search field and press enter.
                </p>

                <p>
                    Notice that you can filter your search results to just display questions or courses by clicking on the tabs at the top of the search results.
                </p>

                <p>
                    If your question is unsolved, simply click re-ask. This will indicate to the folks solving the questions that they need to prioritize getting the solution to this question since more people are looking for it.
                </p>

            </div>
        </div>
    </div>

@endsection