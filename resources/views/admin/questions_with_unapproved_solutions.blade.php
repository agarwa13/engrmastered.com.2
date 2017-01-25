{{--@extends('app')--}}

{{--@section('content')--}}

    {{--<div class="container">--}}
        {{--<div class="row">--}}
            {{--<div class="col-md-12">--}}



                {{--@foreach($questions as $question)--}}

                    {{--<form action="{{url('admin/question/'.$question->id.'/solutions')}}" method="POST">--}}
                        {{--<input type="hidden" name="_token" value="{{ csrf_token() }}">--}}

                            {{--<!-- Display Question -->--}}
                        {{--<h4>{{$question->title}}</h4>--}}

                        {{--<div class="panel-body">--}}
                            {{--{!! $question->body !!}--}}
                        {{--</div>--}}

                        {{--<?php--}}
                        {{--if($question->images){--}}
                            {{--$images = json_decode($question->images);--}}
                        {{--}else{--}}
                            {{--$images = "";--}}
                        {{--}--}}
                        {{--?>--}}

                        {{--@if($images)--}}
                            {{--<div class="row">--}}
                                {{--@foreach($images as $image)--}}
                                    {{--<div class="col-lg-3 col-md-4 col-xs-6 thumb">--}}
                                        {{--<a class="thumbnail" href="#">--}}
                                            {{--<img class="img-responsive" src="{{asset($image)}}" alt="">--}}
                                        {{--</a>--}}
                                    {{--</div>--}}
                                {{--@endforeach--}}
                            {{--</div>--}}
                        {{--@endif--}}

                        {{--<!-- Calculate Solution button -->--}}
                        {{--<input type="submit" value="Calculate Solutions" class="btn btn-primary btn-block" role="button">--}}

                    {{--</form>--}}
                    {{--<hr>--}}

                    {{--@foreach($question->solutions as $solution)--}}
                        {{--@if($solution->ready_for_review)--}}
                        {{--<!-- Panel with Creator Profile, Code and Solution -->--}}
                        {{--<div class="panel with-nav-tabs panel-default" id="panel-{{$solution->id}}">--}}
                            {{--<div class="panel-heading">--}}
                                {{--<ul class="nav nav-tabs">--}}
                                    {{--<li class="active"><a href="#code-{{$solution->id}}" data-toggle="tab">Code</a></li>--}}
                                    {{--<li><a href="#profile-{{$solution->id}}" data-toggle="tab">Profile</a></li>--}}
                                {{--</ul>--}}
                            {{--</div>--}}
                            {{--<div class="panel-body">--}}
                                {{--<div class="tab-content">--}}
                                    {{--<div class="tab-pane fade in active" id="code-{{$solution->id}}">--}}
                                        {{--<pre>{{$solution->getFileContents()}}</pre>--}}
                                    {{--</div>--}}
                                    {{--<div class="tab-pane fade" id="profile-{{$solution->id}}">--}}
                                        {{--<ul class="list-group">--}}
                                            {{--<li class="list-group-item">{{$solution->creator->name}}</li>--}}
                                            {{--<li class="list-group-item">Questions Approved: {{$solution->creator->getProfile()['questionsApproved']}}</li>--}}
                                            {{--<li class="list-group-item">Solutions Approved: {{$solution->creator->getProfile()['solutionsApproved']}}</li>--}}
                                        {{--</ul>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="panel-footer">--}}
                                {{--<a class="btn btn-primary" onclick="approve({{$solution->id}})" role="button">Approve (Show in Questions) </a>--}}
                                {{--<a class="btn btn-warning" href="{{url('question/'.$question->id.'/solution/'.$solution->id."/edit")}}" role="button">Edit (Make Changes Before Approving) </a>--}}
                                {{--<a class="btn btn-danger" onclick="deleteSolution({{$solution->id}})" role="button">Reject Solution (Delete Solution)</a>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--@endif--}}
                    {{--@endforeach--}}
                {{--@endforeach--}}

                {{--{!! $questions->render() !!}--}}

            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

{{--@endsection--}}


{{--@section('scripts')--}}

{{--<script>--}}

        {{--function approve(id){--}}
            {{--$.ajax({--}}
                {{--type: "POST",--}}
                {{--url: "{{url('admin/action')}}",--}}
                {{--data: {--}}
                    {{--action: 'approve_solution',--}}
                    {{--solution: id--}}
                {{--},--}}
                {{--success: function(data){--}}
                    {{--console.log(data);--}}
                    {{--/*--}}
                    {{--Since we are only displaying one question per page we will reload the page--}}
                     {{--*/--}}
                    {{--location.reload();--}}
                {{--}--}}
            {{--});--}}
        {{--}--}}


        {{--function deleteSolution(id){--}}
            {{--$.ajax({--}}
                {{--type: "POST",--}}
                {{--url: "{{url('admin/action')}}",--}}
                {{--data: {--}}
                    {{--action: 'delete_solution',--}}
                    {{--solution: id--}}
                {{--},--}}
                {{--success: function(data){--}}

                    {{--console.log(data);--}}

                    {{--if(data.success){--}}
                        {{--$('#panel-'+id).remove();--}}
                    {{--}else{--}}
                        {{--$('#panel-'+id).addClass('panel-danger');--}}
                    {{--}--}}
                {{--}--}}
            {{--});--}}
        {{--}--}}


    {{--</script>--}}


    {{--<script>--}}
        {{--// Returns a random integer between min (included) and max (included)--}}
        {{--// Using Math.round() will give you a non-uniform distribution!--}}
        {{--function getRandomIntInclusive(min, max) {--}}
            {{--return Math.floor(Math.random() * (max - min + 1)) + min;--}}
        {{--}--}}

        {{--//Fill all inputs with value 3--}}
        {{--$(document).ready(function(){--}}
            {{--$('.question-input').val(getRandomIntInclusive(1,10));--}}
            {{--$('.question-input').change();--}}
        {{--});--}}
    {{--</script>--}}

{{--<script>--}}
    {{--// This makes sure that the repeat fields update--}}
    {{--$(":input").change(function (event) {--}}
        {{--// Get the input field that changed--}}
        {{--changedInput = $(event.target);--}}

        {{--// Find all associated repeat fields--}}
        {{--selectorString = "." + "repeated-" + changedInput.attr('name');--}}
        {{--repeatFields = $(selectorString);--}}

        {{--repeatFields.each(function (index) {--}}
            {{--$(this).val(changedInput.val());--}}
        {{--});--}}
    {{--});--}}
{{--</script>--}}

{{--@endsection--}}