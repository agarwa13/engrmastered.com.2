<nav class="navbar navbar-default navbar-inverse navbar-fixed-top" role="navigation" @if(Request::is('/')) style="margin-bottom: 0;" @endif>
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{url()}}">Engr. Mastered</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="{{ url('question') }}">Questions</a></li>
                {{--<li><a href="{{ url('question/unsolved') }}">Unanswered</a></li>--}}
                <li><a href="{{ url('course') }}">Courses</a></li>

            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li><a href="{{ url('question/create') }}">Ask Question</a></li>
                @if(Auth::guest())
                <li><a href="{{url('auth/login')}}">Sign In / Sign Up</a></li>
                @else
                    @if(Auth::user()->isAdmin())
                    <li><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
                    @endif

                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false" type="button"><i
                                class="glyphicon glyphicon-user"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{url('/user/'.Auth::user()->id.'/')}}">My Account</a></li>
                        <li><a href="{{url('/user/'.Auth::user()->id.'/used_questions')}}">History</a></li>
                        <li><a href="{{url('/user/'.Auth::user()->id.'/question')}}">Questions Asked</a></li>
                        <li><a href="{{url('/user/'.Auth::user()->id.'/solution')}}">Questions Solved</a></li>
                        <li><a href="{{ url('/auth/logout') }}">Logout</a></li>
                    </ul>
                </li>
                @endif


            </ul>

            <form action="{{url('search-results')}}" method="get" class="navbar-form navbar-right" role="search">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Search" name="q" value="">
                </div>
                <button type="submit" class="btn btn-default">Search</button>
            </form>

        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-->
</nav>



{{--<div class="container">--}}
    {{--<div class="row">--}}
        {{--<div class="col-md-12">--}}
            {{--<h3 class="brand" style="font-family: 'Raleway'; font-weight: 700; margin-top: 0px; display: inline-block">--}}
                {{--<a style="text-decoration: none; color: black" href="{{url('/')}}">Engr.<br>Mastered</a></h3>--}}

            {{--<div class="pull-right">--}}

                {{--<form action="{{url('search-results')}}" method="get" class="navbar-left" role="search" _lpchecked="1" style="display: inline-block; padding-right: 4px;">--}}
                    {{--<div class="form-group">--}}
                    {{--<input style="border-radius: 5000px; height: 46px; padding-right: 16px; margin-right: 4px;" type="text" class="form-control" placeholder="Search" name="q" value="">--}}
                    {{--</div>--}}
                    {{--<button type="submit" class="btn btn-default">Search</button>--}}
                {{--</form>--}}



                {{--<a href="{{ url('question/create') }}" class="btn btn-default btn-secondary-action" type="button">Ask--}}
                    {{--Question</a>--}}

                {{--@if(Auth::guest())--}}
                    {{--<a href="{{url('auth/login')}}" class="btn btn-default btn-primary-action" type="button">Sign In /--}}
                        {{--Sign Up</a>--}}
                {{--@else--}}
                    {{--@if(Auth::user()->isAdmin())--}}
                        {{--<a href="{{ url('admin/dashboard') }}" class="btn btn-default btn-secondary-action"--}}
                           {{--type="button">Dashboard</a>--}}
                    {{--@endif--}}
                    {{--<div class="btn-group">--}}
                        {{--<button class="btn btn-default btn-secondary-action dropdown-toggle" data-toggle="dropdown"--}}
                                {{--aria-haspopup="true" aria-expanded="false" type="button"><i--}}
                                    {{--class="glyphicon glyphicon-user"></i></button>--}}
                        {{--<ul class="dropdown-menu">--}}
                            {{--<li><a href="{{url('/user/'.Auth::user()->id.'/')}}">My Account</a></li>--}}
                            {{--<li><a href="{{url('/user/'.Auth::user()->id.'/used_questions')}}">History</a></li>--}}
                            {{--<li><a href="{{url('/user/'.Auth::user()->id.'/question')}}">Questions Asked</a></li>--}}
                            {{--<li><a href="{{url('/user/'.Auth::user()->id.'/solution')}}">Questions Solved</a></li>--}}
                            {{--<li><a href="{{ url('/auth/logout') }}">Logout</a></li>--}}
                        {{--</ul>--}}
                    {{--</div>--}}
                {{--@endif--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}