@extends('app')

@section('title','Courses and Universities |')
@section('description','Questions and Answers organized by course and university. Solve all the homework problems from your course within 1 hour and score 100% on your homework grade')


@section('content')

<div class="container" id="listjs-list">
    <div class="row" style="margin-bottom: 20px">
        <div class="col-md-12">
            <div class="input-group input-group-lg">
                <input type="text" class="form-control search" placeholder="Search by Course or University">
                    <span class="input-group-btn">
                        <button class="btn btn-warning" type="submit">Search</button>
                    </span>
            </div><!-- /input-group -->
        </div>
    </div>
    <div class="flexcontainer list">
    @foreach ($courses as $course)
        <div class="col-md-4" data-resource-type="course" data-resource-id="{{$course->id}}">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="acronym">
                        <a href="{{url('/course/' . $course->id)}}">
                            <h4 class="acronym_listjs">
                                {{$course->acronym}}
                                @if($course->university_id > 0)
                                @ {{$course->university->acronym}}
                                @endif
                            </h4>
                        </a>
                    </div>

                    <div class="name">
                        {{$course->name}} at
                        @if($course->university_id > 0)
                        @ {{$course->university->name}}
                        @endif
                    </div>

                    @if(Auth::user() && Auth::user()->isAdmin())
                        <p>
                            <a href="#" onclick="delete_course(event, '{{$course->id}}')">Delete</a>
                            @if(!$course->reviewer_id > 0)
                             |
                            <a href="#" onclick="approve_course(event, '{{$course->id}}')">Approve</a>
                            @endif
                        </p>
                    @endif

                </div>
            </div>
        </div>
    @endforeach
    </div>
</div>


    @if(Auth::user() && Auth::user()->isAdmin())

        <div class="container">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Add Course</h3>
                </div>
                <div class="panel-body">

                    <form class="form-horizontal" action="{{url('course')}}" method="post">

                        {!! csrf_field() !!}

                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="acronym" class="col-sm-2 control-label">Acronym</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="acronym" name="acronym" placeholder="Acronym">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="instructor" class="col-sm-2 control-label">Instructor</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="instructor" name="instructor" placeholder="Instructor">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="university_id" class="col-sm-2 control-label">University</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="university_id">
                                    @foreach($universities as $university)
                                        <option value="{{$university->id}}">{{$university->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-default">Add Course</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

        </div>

    @endif


@endsection

@section('scripts')
<script>
/*
Set the Options for List.js List
 */
var options = {
    valueNames: [ 'acronym_listjs', 'name'],
    plugins: [
    ]
};

/*
Initialize the List
 */
 var listObj = new List('listjs-list',options);
 </script>

@if(Auth::user() && Auth::user()->isAdmin())

<script>
    function approve_course(event, course_id){

        event.preventDefault();

        /*
         Confirm that user wants to approve this question
         */
        bootbox.confirm({
            message: "Are you sure you want to approve this university?",
            callback: function(r){
                if(r){

                    $.ajax({
                        type: "PUT",
                        url: "{{url('course')}}" + "/" + course_id,
                        data: {reviewer_id: '{{Auth::user()->id}}'}
                    });
                }
            },
            backdrop: false
        });
    }


    function delete_course(event, course_id){
        /*
         Confirm that user wants to approve this question
         */
        bootbox.confirm({
            message: "Are you sure you want to delete this university?",
            callback: function(r){
                if(r){

                    $.ajax({
                        type: "DELETE",
                        url: "{{url('course')}}" + "/" + course_id
                    });

                    /*
                     Remove the Solution from the View
                     */
                    $('[data-resource-type="course"][data-resource-id="' + course_id + '"]').remove();

                }
            },
            backdrop: false
        });

        /*
         Return False to Stop Propogation
         */
        event.preventDefault();
    }
</script>

@endif

@endsection