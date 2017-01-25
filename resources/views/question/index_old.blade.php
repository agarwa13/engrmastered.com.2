@extends('app')

@section('title',$title)


@section('content')

<div class="container">


    @if(isset($heading))
        <div class="row" style="margin-bottom: 10px">
            <div class="col-md-12">
                <h3 style="color: #5e5e5e;">{{$heading}}</h3>
            </div>

            {{--<div class="col-md-6">--}}
                {{--<div class="pull-right">--}}
                    {{--<h3><span class="label label-default">New</span></h3>--}}
                {{--</div>--}}
            {{--</div>--}}

        </div>
    @endif


    <div class="row">


        <div class="col-md-12 list">
            @foreach($questions as $question)
                @include('html_generator.question_summary_with_actions',[
                'question' => $question,
                'actions' => $html_generator->getActionsForQuestions($question)
                ])
            @endforeach

            {!! $questions->render() !!}

        </div>
    </div>
</div>
@endsection
@section('scripts')

@include('html_generator.ajax_actions')

    {{--<script>--}}
        {{--function tog(v){return v?'addClass':'removeClass';}--}}
        {{--$(document).on('input', '.clearable', function(){--}}
            {{--$(this)[tog(this.value)]('x');--}}
        {{--}).on('mousemove', '.x', function( e ){--}}
            {{--$(this)[tog(this.offsetWidth-18 < e.clientX-this.getBoundingClientRect().left)]('onX');--}}
        {{--}).on('touchstart click', '.onX', function( ev ){--}}
            {{--ev.preventDefault();--}}
            {{--$(this).removeClass('x onX').val('').change();--}}
        {{--});--}}
    {{--</script>--}}



@endsection