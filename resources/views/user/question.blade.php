@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <h4>My Questions</h4>

                @foreach($questions as $question)
                    @include('html_generator.question_summary_with_actions',['question' => $question, 'actions' => $html_generator->getActionsForQuestions($question)])
                @endforeach
                {!! $questions->render() !!}

            </div>
        </div>
    </div>
@endsection


@section('scripts')

    @include('html_generator.ajax_actions')

@endsection