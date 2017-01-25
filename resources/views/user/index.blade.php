@extends('app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">

            @if(isset($heading))
            <h4>{{$heading}}</h4>
            @endif

            <div id="users">

                <form class="form-inline" onsubmit="return false;">
                    <div class="form-group">
                        <input type="text" class="form-control search" id="exampleInputName2" placeholder="Search">
                    </div>

                    <div class="pull-right">
                        <div class="form-group">
                            <label>Sort By</label>
                        </div>

                        <button class="btn btn-default sort" data-sort="name">
                            Name
                        </button>

                        <button class="btn btn-default sort" data-sort="email">
                            Email
                        </button>

                        <button class="btn btn-default sort" data-sort="tokens_remaining">
                            Tokens Remaining
                        </button>

                        <button class="btn btn-default sort" data-sort="income">
                            Income
                        </button>

                        <button class="btn btn-default sort" data-sort="usage">
                            Usage
                        </button>


                    </div>
                </form>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Tokens</th>
                            <th>Income</th>
                            <th>Usage</th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @foreach($users as $user)
                        <tr data-resource-type="user" data-resource-id="{{$user->id}}" data-url="{{url('user/'.$user->id)}}">
                            <td><a href="#" class="name" data-name="name" data-type="text" data-pk="{{$user->id}}" data-url="{{url('user/'.$user->id)}}">{{$user->name}}</a></td>
                            <td><a href="#" class="email" data-name="email" data-type="text" data-pk="{{$user->id}}" data-url="{{url('user/'.$user->id)}}">{{$user->email}}</a></td>
                            <td><a href="#" class="tokens_remaining" data-name="tokens_remaining" data-type="number" data-pk="{{$user->id}}" data-url="{{url('user/'.$user->id)}}">{{$user->tokens_remaining}}</a></td>
                            <td><a href="#" class="income" data-name="income" data-type="number" data-pk="{{$user->id}}" data-url="{{url('user/'.$user->id)}}">{{$user->income}}</a></td>
                            <td><a href="{{url('user/'.$user->id.'/used_questions')}}" class="usage">{{count($user->usedQuestions)}}</a></td>
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
            $('.email').editable();
            $('.tokens_remaining').editable();
            $('.income').editable();

            var options = {
                valueNames: [ 'name', 'email', 'tokens_remaining', 'income','usage' ],
                page: 10,
                plugins: [ListPagination({})]
            };
            var userList = new List('users', options);

        });
    </script>
@endsection