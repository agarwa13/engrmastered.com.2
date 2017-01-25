{{--<div class="panel panel-default" data-resource-type="question" data-resource-id="{{$question->id}}">--}}
<div data-resource-type="question" data-resource-id="{{$question->id}}">

    {{--<div class="panel-body">--}}

    <div class="row">

        <div class="col-md-1">

            @if(!$question->has_approved_solution)
            <p class="text-center" style="margin-bottom: 2px;">{{$question->followedByCount()}}</p>
            <p class="text-center" style="margin-bottom: 14px;">Re-asks</p>
            @else
            <p class="text-center" style="margin-bottom: 2px;">{{count($question->usageRecords)}}</p>
            <p class="text-center" style="margin-bottom: 0;">Uses</p>
            @endif
        </div>

        <div class="col-md-11">

            <h4 class="title" style="margin-bottom: 2px; display: inline-block">
                <a href="{{url('question/'.$question->id)}}">
                    <span class="title_listjs">
                        {{$question->title}}
                    </span>
                </a>

                <span class="courses" style="padding-left: 10px">
                    @foreach($question->courses as $course)
                        <span class="label label-default tag" style="display: inline-block;">{{$course->acronym."@".$course->university->acronym}}<span data-role="remove"></span></span>
                    @endforeach
                </span>
            </h4>

            <p class="description">
                {{str_replace('$','', strip_tags($question->simple_body))}}
            </p>

            {{--@if(count($actions) > 0)--}}
                {{--<div class="actions" data-resource-type="actions" data-resource-id="{{$question->id}}">--}}
                    {{--@foreach($actions as $action)--}}
                        {{--{!! $html_generator->displayAction($action, $question) !!}--}}
                    {{--@endforeach--}}
                {{--</div>--}}
            {{--@endif--}}


        </div>
    </div>

</div>
<hr>