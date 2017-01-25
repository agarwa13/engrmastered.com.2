<div class="panel panel-default">
    <div class="panel-heading" role="tab" id="heading{{$serial_number}}">
        <h4 class="panel-title">
            <a role="button" data-toggle="collapse" href="#collapse{{$serial_number}}" aria-expanded="true" aria-controls="collapse{{$serial_number}}">
                <span class="question">{!! $question !!}</span>
            </a>
        </h4>
    </div>
    <div id="collapse{{$serial_number}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{$serial_number}}">
        <div class="panel-body answer">
            {!! $answer !!}
        </div>
    </div>
</div>