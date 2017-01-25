<html>
<head>

</head>
<body>
@foreach($questions as $question)
    <p>
        {{ $question->title }}
    </p>

    {!! $question->simple_body !!}

    <p>
        Solutions to other WebAssign, SmartPhysics and MasteringPhysics problems can be found on http://engineeringmastered.com
    </p>

    <p>
        Instant solution to this problem can be found here: https://engrmastered.com/question/{{$question->id}}
    </p>
    <hr><br>
@endforeach
</body>
</html>