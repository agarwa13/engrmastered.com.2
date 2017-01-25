@extends('app')

@section('title',substr(preg_replace('/\s+/', ' ',str_replace('$','', strip_tags($question->simple_body))),0,30) . "... |")
@section('description',substr(preg_replace('/\s+/', ' ',str_replace('$','', strip_tags($question->simple_body))),0,100))

@section('content')



<div class="container">
    <div class="row">
        <div class="col-md-12">


            @if(!$question->hasApprovedSolution())
            <div class="alert alert-warning alert-dismissable" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>This question is not yet solved.</strong> What can you do?
                <ol>
                    <li>Re-ask the question to indicate your interest</li>
                    <li>Or solve it and many will be thankful</li>
                </ol>
            </div>
            @endif

@if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors as $error)
                    <li>{!! $error !!}</li>
                @endforeach
            </ul>
		</div>
	@endif

            <form action="{{url('question/'.$question->id.'/solution')}}" method="GET" id="getSolutionForm">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="token">

                <!-- This Hidden field will be populated by jQuery if we are simultaneously saving the card of a registered user -->
                <input type="hidden" name="save_card_for_future">

                <h4>
                    <span class="courses">
                        @foreach($question->courses as $course)
                            <span class="label label-default tag" style="display: inline-block;">{{$course->acronym."@".$course->university->acronym}}<span data-role="remove"></span></span>
                        @endforeach
                    </span>
                </h4>

                <h3 class="title">
                    <span class="title_listjs">
                        {{$question->title}}
                    </span>
                </h3>
                <h5 style="color: #F5298E; font-size: 16px;">Solution Price: 2.00 $</h5>

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


            <br>

            <!-- Allow User to Get Solution if it has an Approved Solution -->
            @if($question->hasApprovedSolution())
                @if(!isset($answer))
                    @if(!Auth::guest() && Auth::user()->subscribed())
                        <!-- If the User is Logged In and Subscribed show the Get Solution button that submits the Form -->
                        <button id="getSolution" value="Get Solution" class="btn btn-default">Get Solution</button>
                    @else
                        <!-- Otherwise show the button that will launch the payment form -->
                        <input type="submit" class="btn btn-default payButton" value="Get Solution">
                    @endif
                @endif
            @endif

            <!-- Display Actions -->
            @if(count($actions) > 0)
                <div class="btn-group">
                    @foreach($actions as $action)
                        {!! $html_generator->displayAction($action, $question) !!}
                    @endforeach
                </div>
            @endif

            </form>

            <!-- Display the Solution -->
            <div class="solution-wrapper" @if(!isset($answer)) style="display: none" @endif>
                <hr>
                <h4>Solution</h4>
                @if(isset($answer))
                    {!! $answer !!}
                @endif
            </div>




        </div>

        {{--TODO:: Need to identify the next and previous question in a better fashion--}}
        {{--<div class="col-md-12">--}}
            {{--<div class="btn-group" role="group" style="margin-top: 20px; padding-bottom: 20px;">--}}
                {{--<a type="button" class="btn btn-default" href="{{url('/question/'.($question->id-1))}}">Prev</a>--}}
                {{--<a type="button" class="btn btn-default" href="{{url('/question/'.($question->id+1))}}">Next</a>--}}
            {{--</div>--}}
        {{--</div>--}}
    </div>
</div>


<!-- Save Credit Card for Future Use Modal -->
<div class="modal fade" id="saveCreditCardModal" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Save Card for Future Use?</h4>
            </div>

            <div class="modal-body">
                <p>Access to each solution costs 2.00$. In a moment, you will be asked for your card details so we can charge you for access. If you like, we can save your card details so you do not have to enter it again.
                    </p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="continueWithoutSavingCard">No, Thanks</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" id="saveCardAndContinue">Save Card</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Confirm Purchase Modal -->
<div class="modal fade" id="confirmGetSolutionModal" data-backdrop="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Confirm Purchase</h4>
      </div>
      <div class="modal-body">
          <p>This solution costs 2$. Are you sure you want to continue?</p>
          @if(Auth::user())
              <p>Any credits you have will be used before your card is charged.</p>
          @endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="confirmGetSolutionButton">Continue</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


{{--<!-- Register User Modal -->--}}
{{--<div class="modal fade" id="registerUserModal" data-backdrop="false">--}}
    {{--<div class="modal-dialog">--}}
        {{--<div class="modal-content">--}}
            {{--<div class="modal-header">--}}
                {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
                {{--<h3 class="modal-title">Register and Save Card for Future Use</h3>--}}
            {{--</div>--}}
            {{--<div class="modal-body">--}}
                {{--<form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/register') }}" id="registerUserForm">--}}
                    {{--<input type="hidden" name="_token" value="{{ csrf_token() }}">--}}
                    {{--<input type="hidden" name="stripeToken" value="" class="stripeTokenHidden">--}}

                    {{--<div class="form-group">--}}
                        {{--<label class="col-md-4 control-label">Name</label>--}}
                        {{--<div class="col-md-6">--}}
                            {{--<input type="text" class="form-control" name="name" value="{{ old('name') }}">--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{--<div class="form-group">--}}
                        {{--<label class="col-md-4 control-label">E-Mail Address</label>--}}
                        {{--<div class="col-md-6">--}}
                            {{--<input type="email" class="form-control" name="email" value="{{ old('email') }}">--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{--<div class="form-group">--}}
                        {{--<label class="col-md-4 control-label">Password</label>--}}
                        {{--<div class="col-md-6">--}}
                            {{--<input type="password" class="form-control" name="password">--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{--<div class="form-group">--}}
                        {{--<label class="col-md-4 control-label">Confirm Password</label>--}}
                        {{--<div class="col-md-6">--}}
                            {{--<input type="password" class="form-control" name="password_confirmation">--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</form>--}}
            {{--</div>--}}
            {{--<div class="modal-footer">--}}
                {{--<button type="button" class="btn btn-default" data-dismiss="modal" id="continueWithoutRegistration">No, Thanks</button>--}}
                {{--<a data-dismiss="modal" id="continueWithoutRegistration" style="padding-right: 15px; text-decoration: underline">No, Thanks</a>--}}
                {{--<button type="button" class="btn btn-primary" data-dismiss="modal" id="registerAndContinue">Register</button>--}}
            {{--</div>--}}
        {{--</div><!-- /.modal-content -->--}}
    {{--</div><!-- /.modal-dialog -->--}}
{{--</div><!-- /.modal -->--}}

@endsection

@section('scripts')

@include('html_generator.ajax_actions')

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

    $(document).ready(function(){
        $('.question-input').change();
    });
</script>

@if(isset($answer))
    <!-- If the answer is being displayed then disable all the fields -->
    <script>
        $(document).ready(function() {
            $(".question-input").attr("disabled", "disabled");
            $('.question-input').change();
        });
    </script>
@endif


<script>
    // When the User Submits the Form to get the solution of the displayed question
    $('#getSolutionForm').submit(function(event){
        if(event.originalEvent === undefined){
            // Submission Called By Code, so we let it submit
        }else{
            event.preventDefault();

            @if(Auth::user())
                @if(Auth::user()->subscribed() || Auth::user()->tokens_remaining >= 2)
                    $('#confirmGetSolutionModal').modal({show: true});
                @else
                    $('#saveCreditCardModal').modal({show: true});
                @endif
            @else
                launch_stripe_for_one_time_payment_guest_user();
            @endif
        }
    });

    // When the User Confirms he wants to see the solution
    $('#confirmGetSolutionForm').submit(function(event){
        event.preventDefault();
        $('#getSolutionForm').submit();
    });
    $('#confirmGetSolutionButton').click(function(){
        $('#getSolutionForm').submit();
    });

    // When the User says Yes, he wants to save the payment method
    $('#saveCardAndContinue').click(function(event){
        getSolutionForm = $('#getSolutionForm');
        getSolutionForm.find('input[name=save_card_for_future]').val(1);
        launch_stripe_for_save_payment_method_registered_user();
    });

    // When the User says No to Save, and just charge once
    $('#continueWithoutSavingCard').click(function(event){
        getSolutionForm = $('#getSolutionForm');
        getSolutionForm.find('input[name=save_card_for_future]').val(0);
        launch_stripe_for_one_time_payment_registered_user();
    });
</script>

@include('stripe.launch_stripe_for_one_time_payment_registered_user')
@include('stripe.launch_stripe_to_save_payment_method_and_use_immediately')
@include('stripe.launch_stripe_for_one_time_payment_guest_user')

@endsection