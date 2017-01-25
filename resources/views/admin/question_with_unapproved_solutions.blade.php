@extends('app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="courses">
                    @foreach($question->courses as $course)
                        {{--<span class="tag label label-info">{{$course->acronym."@".$course->university->acronym}}<span data-role="remove"></span></span>--}}
                        <a href="{{url('course/'.$course->id)}}">{{$course->acronym."@".$course->university->acronym}}, </a>
                    @endforeach
                </div>

                @if(!$request->isMethod('post'))
                <form action="{{url('admin/question/'.$question->id.'/solutions')}}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                @endif

                <!-- Display Question -->
                <h4>{{$question->title}}</h4>

                <div class="panel-body">
                    {!! $question->body !!}
                </div>

                <?php
                if($question->images){
                    $images = json_decode($question->images);
                }else{
                    $images = "";
                }
                ?>

                @if($images)
                    <div class="row">
                        @foreach($images as $image)
                            <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                                <a data-lightbox="image" href="{{asset($image)}}">
                                    <img class="img-responsive" src="{{asset($image)}}">
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if(!$request->isMethod('post'))
                <!-- Calculate Solution button -->
                <input type="submit" value="Calculate Solutions" class="btn btn-primary btn-block" role="button">
                </form>
                @endif

                <div class="btn-group btn-group-justified">
                    @foreach($html_generator->getActionsForQuestions($question) as $action)
                        {!! $html_generator->displayActionAsButton($action, $question) !!}
                    @endforeach
                </div>

                <hr>

                @foreach($question->solutions as $solution)
                    @if($solution->ready_for_review)
                    <div data-resource-type="solution" data-resource-id="{{$solution->id}}">
                        <ul class="nav nav-tabs">

                            @if(Request::isMethod('get'))
                            <li class="active"><a data-toggle="tab" href="#code-{{$solution->id}}">Code</a></li>
                            <li><a data-toggle="tab" href="#results-{{$solution->id}}">Results</a></li>
                            @else
                            <li><a data-toggle="tab" href="#code-{{$solution->id}}">Code</a></li>
                            <li class="active"><a data-toggle="tab" href="#results-{{$solution->id}}">Results</a></li>
                            @endif

                            <li><a data-toggle="tab" href="#profile-{{$solution->id}}">Profile</a></li>
                        </ul>

                        <div class="tab-content" style="height: 150px; overflow: auto;">

                            @if(Request::isMethod('get'))
                            <div id="code-{{$solution->id}}" class="tab-pane fade in active">
                            @else
                            <div id="code-{{$solution->id}}" class="tab-pane fade">
                            @endif
                                <pre>{{$solution->getFileContents()}}</pre>
                            </div>

                            @if(Request::isMethod('get'))
                            <div id="results-{{$solution->id}}" class="tab-pane fade">
                            @else
                            <div id="results-{{$solution->id}}" class="tab-pane fade in active">
                            @endif
                                @if($request->isMethod('post'))
                                    {!! $solution->getAnswer($request) !!}
                                @else
                                    Click Calculate Solutions above to get Result
                                @endif
                            </div>

                            <div id="profile-{{$solution->id}}" class="tab-pane fade">
                                <ul class="list-group">
                                    <li class="list-group-item">{{$solution->creator->name}}</li>
                                    <li class="list-group-item">Questions Approved: {{$solution->creator->getProfile()['questionsApproved']}}</li>
                                    <li class="list-group-item">Solutions Approved: {{$solution->creator->getProfile()['solutionsApproved']}}</li>
                                </ul>
                            </div>
                        </div>

                        {{--<div class="btn-group" role="group" aria-label="...">--}}
                        <div class="btn-group btn-group-justified">
                            @foreach($html_generator->getActionsForSolutions($solution) as $action)
                                {!! $html_generator->displayActionAsButton($action, $question, $solution) !!}
                            @endforeach
                        </div>
                    </div>
                    <hr>

                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        // This makes sure that the repeat fields update
        $(":input").change(function (event) {
            // Get the input field that changed
            changedInput = $(event.target);

            // Find all associated repeat fields
            selectorString = "." + "repeated-" + changedInput.attr('name');
            repeatFields = $(selectorString);

            repeatFields.each(function (index) {
                $(this).val(changedInput.val());
            });
        });
    </script>

    @if($request->isMethod('post'))

    <!-- If the answer is being displayed then disable all the fields -->
    <script>
        $(document).ready(function() {
            $(".question-input").attr("disabled", "disabled");
            $('.question-input').change();
        });
    </script>

    @else

    <script>
        // Returns a random integer between min (included) and max (included)
        // Using Math.round() will give you a non-uniform distribution!
        function getRandomIntInclusive(min, max) {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        }

        //Fill all inputs with value 3
        $(document).ready(function(){
            $('.question-input').each(function(index,value){
                $(this).val(getRandomIntInclusive(1,10));
            });

            $('.question-input').change();
        });
    </script>

    @endif

@include('html_generator.ajax_actions')




@endsection





