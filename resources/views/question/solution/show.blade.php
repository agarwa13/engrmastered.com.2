@extends('app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <!-- Display the Title of the Question if it has one -->
            @if($question->title)
                <h4>{{$question->title}}</h4>
            @endif

            <!-- Display the Images of the Question -->
            @if($images)
                <div class="row">
                    @foreach($images as $image)
                        <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                            <a data-lightbox="image" href="{{asset($image)}}">
                                <img class="img-responsive" src="{{asset($image)}}">
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Display the Body of the Question -->
            <div>
                {!! $question->body !!}
            </div>

            <!-- Display the Solution -->
            <div class="solution-wrapper" @if(!isset($answer)) style="display: none" @endif>
                <hr>
                <h4>Solution</h4>
                {!! $answer !!}

                <hr>

                @if(isset($usage_record) && Auth::user())

                <!-- Display the Form to Comment about the Solution -->
                <form action="{{url('review')}}" method="post" style="padding-bottom: 20px;">

                    {!! csrf_field() !!}
                    <input type="hidden" name="question_id" value="{{$question->id}}">
                    <input type="hidden" name="question_usage_record_id" value="{{$usage_record->id}}">

                    <div class="form-group">
                        <label for="positive_review">Review Type:</label>
                        <select class="form-control" name="positive_review" id="positive_review">
                            <option value="1">Positive Review</option>
                            <option value="0">Negative Review</option>
                        </select>
                        <p class="help-block">Questions with many negative reviews are flagged for review by an administrator.</p>
                    </div>

                    <div class="form-group">
                        <label for="refund_requested">Are you requesting a refund?:</label>
                        <select class="form-control" name="refund_requested" id="refund_requested">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                        <p class="help-block">We will get back to you when we have reviewed your request. We may have some additional questions about the issue you faced.</p>
                    </div>

                    <div class="form-group">
                        <label for="refund_requested">Comment:</label>
                        <textarea class="form-control" rows="3" name="comment" id="comment"></textarea>
                        <p class="help-block">Please provide additional details.</p>
                    </div>

                    <button type="submit" class="btn btn-default">Submit Review</button>

                </form>

                @else
                    @if(Auth::user())
                    <a href="{{url('user/'.Auth::user()->id.'/used_questions')}}"> If you are not satisfied
                        with the solution, click here to report it and/or request a refund</a>
                    @endif
                @endif

            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// This makes sure that the repeat fields update
$(":input").change(function (event) {
    // Get the input field that changed
    changedInput = $(event.target);

    // Find all associated repeat fields
    selectorString = "." + "repeated-" + changedInput.attr('name');
    repeatFields = $(selectorString);

    repeatFields.each(function (index) {
        $(this).val(changedInput.val());
    });
});
</script>

<!-- If the answer is being displayed then disable all the fields -->
<script>
    $(document).ready(function() {
        $(".question-input").attr("disabled", "disabled");
        $('.question-input').change();
    });
</script>

<!-- Get Usage Record to update values -->
    @if(isset($usage_record))
        <script>
            $(document).ready(function(){

                var variables = new Array();

                @foreach( array_keys(json_decode($usage_record->variables_used,true)) as $key)
                    variables.push('{{json_decode($usage_record->variables_used,true)[$key]}}');
                @endforeach

                $(".question-input").each(function(i,obj){
                    $(this).val( variables[i] );
                });

            });
        </script>
    @endif




@endsection