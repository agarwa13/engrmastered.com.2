@extends('app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <h4>My Solutions</h4>
                @foreach($solutions as $solution)
                    <?php $question = $solution->question; ?>
                        @if($question != null)
                            @include('html_generator.question_summary_with_actions',['question' => $question, 'actions' => $html_generator->getActionsForQuestions($question)])
                        @endif
                @endforeach

                {!! $solutions->render() !!}

            </div>
        </div>
    </div>

@endsection