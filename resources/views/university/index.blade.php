@extends('app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <h3>University</h3>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Add University</h3>
                    </div>
                    <div class="panel-body">

                        <form class="form-horizontal" action="{{url('university')}}" method="post">

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
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-default">Add University</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>

                <hr>

                <div id="universities">

                    <form class="form-inline" onsubmit="return false;">
                        <div class="form-group">
                            <input type="text" class="form-control search" placeholder="Search">
                        </div>

                        <div class="pull-right">
                            <div class="form-group">
                                <label>Sort By</label>
                            </div>

                            <button class="btn btn-default sort" data-sort="name">
                                Name
                            </button>

                            <button class="btn btn-default sort" data-sort="acronym">
                                Acronym
                            </button>
                        </div>
                    </form>

                    <table class="table">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Acronym</th>
                            <th>Creator</th>
                            <th>Reviewer</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody class="list">
                        @foreach($universities as $university)
                            <tr data-resource-type="university" data-resource-id="{{$university->id}}">
                                <td><a href="#" class="name" data-name="name" data-type="text" data-pk="{{$university->id}}" data-url="{{url('university/'.$university->id)}}">{{$university->name}}</a></td>
                                <td><a href="#" class="acronym" data-name="acronym" data-type="text" data-pk="{{$university->id}}" data-url="{{url('university/'.$university->id)}}">{{$university->acronym}}</a></td>
                                <td>{{$university->creator->name}}</td>
                                <td> @if($university->reviewer) {{$university->reviewer->name}} @else Not Approved @endif</td>
                                <td>
                                    <a href="#" onclick="delete_university(event, this, '{{$university->id}}')">Delete</a> |
                                    <a href="#" onclick="approve_university(event, this, '{{$university->id}}')">Approve</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <ul class="pagination"></ul>

                </div>


            </div>
        </div>
    </div>

@endsection



@section('scripts')
    <script>
        $(document).ready(function() {

            $.fn.editable.defaults.mode = 'inline';
            $.fn.editable.defaults.ajaxOptions = {type: "PUT"};

            $('.name').editable();
            $('.acronym').editable();

            var options = {
                valueNames: [ 'name', 'acronym' ],
                page: 10,
                plugins: [ListPagination({})]
            };
            var universitiesList = new List('universities', options);

        });
    </script>


    <script>
        function approve_university(event, actionElement, university_id){

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
                            url: "{{url('university')}}" + "/" + university_id,
                            data: {reviewer_id: '{{Auth::user()->id}}'},
                            success: function(){
//                                actionElement.replace('Approved');
                            }
                        });
                    }
                },
                backdrop: false
            });
        }


        function delete_university(event, actionElement, university_id){
            /*
             Confirm that user wants to approve this question
             */
            bootbox.confirm({
                message: "Are you sure you want to delete this university?",
                callback: function(r){
                    if(r){

                        $.ajax({
                            type: "DELETE",
                            url: "{{url('university')}}" + "/" + university_id
                        });

                        /*
                         Remove the Solution from the View
                         */
                        $('[data-resource-type="university"][data-resource-id="' + university_id + '"]').remove();

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



@endsection