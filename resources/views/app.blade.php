<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
          content="@yield('description','Engineering Mastered has the answers to all UIUC and UW Homework Questions!')">

    <meta name="author" content="EngineeringMastered.com">
    <title>@yield('title') Engineering Mastered</title>
    <link rel="shortcut icon" href="{{ asset('images/logo-dark.png') }}">

    <!-- Preload CSS -->
    {{--<link href="{{ Cdn::asset('/css/preload.css') }}" rel="stylesheet">--}}
    <link href="{{ asset('/css/preload.css') }}" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

    <!-- Bootstrap Table and Bootstrap Select -->
    <link rel="stylesheet" href="{{Cdn::asset('/vendor/bootstrap-select/bootstrap-select.min.css')}}">
    <link rel="stylesheet" href="{{Cdn::asset('/vendor/bootstrap-table/bootstrap-table.min.css')}}">

    <!-- Bootstrap Editable -->
    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css"
          rel="stylesheet"/>

    <!-- Bootstrap Social CSS -->
    <link rel="stylesheet" href="{{Cdn::asset('/vendor/bootstrap-social/bootstrap-social.css')}}">

    <!-- Including Drop Zone CSS -->
    <link rel="stylesheet" href="{{Cdn::asset('vendor/dropzone/basic.css')}}">
    <link rel="stylesheet" href="{{Cdn::asset('vendor/dropzone/dropzone.css')}}">

    <!-- Lightbox to display Images -->
    <link rel="stylesheet" href="{{Cdn::asset('vendor/lightbox2/css/lightbox.css')}}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{Cdn::asset('vendor/font-awesome/css/font-awesome.css')}}">

    <!-- Custom CSS -->
    {{--<link href="{{ Cdn::asset('/css/custom.css') }}" rel="stylesheet">--}}
    <link href="{{ asset('/css/custom.css') }}" rel="stylesheet">

    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic'
          rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Raleway:400,100,200,300,500,600,700,800,900' rel='stylesheet'
          type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Lato:400,100italic,100,300italic,300,700,400italic,700italic,900,900italic'
          rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700italic,700,900,900italic'
          rel='stylesheet' type='text/css'>

    @yield('css','')

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


    <!-- Sumo me Script -->
    <script src="//load.sumome.com/" data-sumo-site-id="178de06d924dc35b8c742ad28fd67a3be0a60e7514189ed6e321b208586b56ad" async="async"></script>

</head>
<body>
<div class="se-pre-con"></div>
<div class="ajax-loader"></div>

@include('common.navbar')

@include('common.alerts')

@yield('content')

<div class="navbar navbar-default footer @if(Request::is('/')) navbar-fixed-bottom @endif">
    <div class="container">
        <p class="text-muted">
            Copyright by © Engineering Mastered 2015 | &nbsp;
            <a href="{{url('/faq')}}">FAQs</a> &nbsp; | &nbsp;
            <a href="{{url('/privacy_policy')}}">Privacy Policy</a> &nbsp; | &nbsp;
            <a href="{{url('/honor_code')}}">Honor Code</a> &nbsp; | &nbsp;
            <a href="{{url('/terms_and_conditions')}}">Terms and Conditions</a> &nbsp; | &nbsp;
            <a data-toggle="modal" data-target="#contactUs" href="#contactUs">Contact Us</a> &nbsp; |
        </p>
    </div>
</div>

<!-- Scripts jQuery, Modernizr and Bootstrap -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>

<!-- Scripts checkout.js -->
<script src="https://checkout.stripe.com/checkout.js"></script>

<!-- Setup AJAX to ensure CSRF token is sent with all AJAX requests -->
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Show a Loading Icon when the ajax event is occuring
    $(document).ajaxSend(function(){
        $(".ajax-loader").fadeIn("slow");
    });

    $(document).ajaxComplete(function(){
        $(".ajax-loader").fadeOut("slow");
    });
</script>

<!-- Bootstrap Table and Bootstrap Select Scripts -->
<!-- Note: Bootstrap table is not compatible with bootstrap editable without the extension -->
<script src="{{Cdn::asset('/vendor/bootstrap-select/bootstrap-select.min.js')}}"></script>
<script src="{{Cdn::asset('/vendor/bootstrap-table/bootstrap-table.min.js')}}"></script>
<script src="{{Cdn::asset('/vendor/bootstrap-table/extensions/editable/bootstrap-table-editable.js')}}"></script>



<!-- Bootstrap Editable used to push changes -->
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>

<!-- Notify.js used for notifications -->
@include('common.notifications')

<!-- Bootbox.js Used for confirmation of actions -->
<script src="{{Cdn::asset('/vendor/bootbox/bootbox.min.js')}}"></script>

<!-- Lightbox.js Used for Images -->
<script src="{{Cdn::asset('vendor/lightbox2/js/lightbox.min.js')}}"></script>

<!-- MathJAX CDN Script -->
<script type="text/x-mathjax-config">
    MathJax.Hub.Config({
      tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']],
      processEscapes: true
      }
    });

</script>
<script type="text/javascript" src="//cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>

<!-- List.js and Fuzzy Search Plugin -->
<script src="{{Cdn::asset('vendor/listjs/list.js')}}"></script>
<script src="{{Cdn::asset('vendor/listjs/list.pagination.min.js')}}"></script>

<!-- Pre Loader Icon Script -->
@include('common.pre-loader-icon')

<!-- Google Analytics Code -->
@if( !Auth::guest() && Auth::user()->isAdmin() )
    <!-- Do NOT show Google Analytics Code. -->
@else
    @include('common.google_analytics')
@endif

@yield('scripts')

</body>
</html>