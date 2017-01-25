@extends('app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <h4>
                    @if($editMode)
                        Update Solution
                    @else
                        Create Solution
                    @endif

                </h4>
                <hr>

                <!-- Display the Question -->
                <form id="checkSolutionForm">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    @if($editMode)
                        <input type="hidden" name="_method" value="PUT">
                    @endif

                    <input type="hidden" name="request_type">
                    <input type="hidden" name="ready_for_review" value="0">

                    <!-- Display the Title of the Question if it has one -->
                    @if($question->title)
                        <h4>
                            {{$question->title}}
                        </h4>
                    @endif

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

                        @if(Auth::user()->isAdmin())
                            <a href="{{url('question/'.$question->id.'/edit')}}">Edit Question</a>
                        @endif
                </form>

                <h4>Solution</h4>
                <hr>
                <div id="answer-wrapper"></div>


            </div>

            <div class="col-md-6 tex2jax_ignore">

                <form id="checkSolutionForm2">
                    <div class="form-group">
                        <label for="solution">Solution Code</label>
                        <textarea name="solution" style="display: none;"></textarea>
                        <div id="solution-editor">@if($editMode){{$solution->getFileContents()}}@endif</div>
                        <p class="help-block">Access the first variable as \$v1, the second variable as \$v2 and so on.</p>
                    </div>
                </form>

                <form style="margin-top: 20px; margin-bottom: 20px;" action="{{url('upload/image')}}" class="dropzone" id="my-awesome-dropzone">
                </form>

                <input onclick="checkSolution()" type="submit" value="Check Solution" class="btn btn-default">
                <input onclick="submitSolutionForReview()" type="submit" value="Submit Solution For Review" class="btn btn-default">

                <div>
                    <!-- Display Actions -->
                    @if(count($actions) > 0)
                        <div class="btn-group">
                            @foreach($actions as $action)
                                {!! $html_generator->displayActionAsButton($action, $question) !!}
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Display Success Message if Any -->
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Display Error Messages if Any -->
                @if(count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>


            {{--<div class="col-md-6">--}}
                {{--<h4>Solution</h4>--}}
                {{--<hr>--}}
                {{--<div id="answer-wrapper"></div>--}}
            {{--</div>--}}

        </div>
    </div>

    <div id="hidden-wrapper" style="display: none;"></div>

@endsection



@section('scripts')

    <!-- Using Ace Editor -->
    <script src="{{asset('/vendor/ace/src/ace.js')}}"></script>

    <script>
        var editor = ace.edit('solution-editor');
        editor.setTheme("ace/theme/monokai");
        editor.getSession().setMode("ace/mode/php");

        var editor_is_dirty = false;

        editor.commands.addCommand({
            name: 'intentional_save',
            bindKey: {win: 'Ctrl-S',  mac: 'Command-S'},
            exec: function(editor) {
                save_code();
            },
            readOnly: true // false if this command should not apply in readOnly mode
        });

    </script>


    <script>

//        Submit the Form via AJAX and display the answer
        function checkSolution(){
            $('input:hidden[name=request_type]').val('check_solution');
            sendRequest();
        }

        editor.on("change",function(e){
            editor_is_dirty = true;
        });


        function save_code(){
            if(editor_is_dirty == true){
                $('input:hidden[name=request_type]').val('auto_save');
                sendRequest();
                editor_is_dirty = false;
            }
        }

//        Auto Save the Solution
        setInterval(function saveSolution(){
            save_code();
        },30000);

//        Save the Solution and Submit for Review
        function submitSolutionForReview(){
            $('input:hidden[name=request_type]').val('change_ready_for_review_status');
            $('input:hidden[name=ready_for_review]').val(1);
            sendRequest();
        }

//        Do not save the solution for review anymore
        function recallSolutionFromReview(){
            $('input:hidden[name=request_type]').val('change_ready_for_review_status');
            $('input:hidden[name=ready_for_review]').val(0);
            sendRequest();
        }

        function sendRequest(){

            $('textarea[name="solution"]').val(editor.getSession().getValue());

            $.ajax({
                @if($editMode)
                    type: "POST",
                    url: "{{url('question/'.$question->id.'/solution/'.$solution->id)}}",
                @else
                    type: "POST",
                    url: "{{url('question/'.$question->id.'/solution')}}",
                @endif

                data: $('#checkSolutionForm, #checkSolutionForm2').serialize(),
                success: function(response){

                    console.log(response);

                    if(response.request_type == 'auto_save'){
                        $.notify('Auto-saved Solution','success');
                    }

                    if(response.request_type == 'check_solution'){
                        $('#answer-wrapper').html(response.answer);
                        $('#code-wrapper').html(response.code);
                        MathJax.Hub.Queue(["Typeset",MathJax.Hub,'answer-wrapper']);
                    }

                    if(response.request_type == 'change_ready_for_review_status'){

                        if(response.ready_for_review == true){
                            $.notify('Solution has been submitted for review','success');

                            @if(Auth::user()->isAdmin())
                                window.location.replace("{{url('admin/question/'.$question->id.'/solutions')}}");
                            @else
                                window.location.replace("{{url('user/'.Auth::user()->id.'/solution')}}");
                            @endif
                        }else{
                            $.notify('Solution has been recalled from review. An administrator cannot see it until you submit for review','warning');
                        }
                    }
                },
                error: function(xhr, status, error) {

                    // 422 usually occurs when some variables are not set
                    if(xhr.status == 422){
                        message = 'Some variables have not been set. Please set the variables prior to checking your solution.';
                        $.notify(message,'warning');
                        html = '<div class="alert alert-warning" role="alert">' + message + '</div>';
                        $('#answer-wrapper').html(html);
                    }

                    //500 typically occurs when php code has a syntax error
                    // 503 also is usually a error with php code
                    if(xhr.status == 500){
                        message = 'PHP Code may have a syntax error. We will update the website soon to provide hints on what the error might be';
                        $.notify(message,'warning');
                        html = '<div class="alert alert-warning" role="alert">' + message + '</div>';
                        $('#answer-wrapper').html(html);
                    }

                    if(xhr.status == 503){
                        message = 'Your code has an error. Perhaps you are trying to access a variable that is not defined by the user';
                        $.notify(message,'warning');
                        html = '<div class="alert alert-warning" role="alert">' + message + '</div>';
                        $('#answer-wrapper').html(html);
                    }


                }
            });
        }
    </script>


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




    <!-- Including DropZone -->
    <script src="{{asset('vendor/dropzone/dropzone.js')}}"></script>

    <!-- Configure Dropzone -->
    <script>

        images = new Array();

        @if($editMode)
            @if(isset($images))
                @if($images)
                    @foreach($images as $image)
                        images.push("{{$image}}");
                    @endforeach
                @endif
            @endif
        $('input:hidden[name=images]').val(JSON.stringify(images));
        @endif

        Dropzone.options.myAwesomeDropzone = {
            maxFilesize: 2, //MB
            previewsContainer: '#hidden-wrapper',
            init: function() {
                this.on("success", function(file, response) {
                    console.log(response);

                    // Calculate Image Number for ID
                    imageId = images.length + 1;

                    // Create a HTML Tag that will display the image
                    html = '<img class=\"img-responsive\" src=\"'+ "/" + response.image +'\">';

                    // Add the image to the HTML
//                    editor.replaceRange(html,editor.getCursor())
                    editor.insert(html);

                    // Add image to images hidden input element
                    images.push(response.image);
                });
            },
            headers: {
                'X-CSRF-Token': $('input:hidden[name="_token"]').val()
            }
        };

    </script>


    <script>
        // Returns a random integer between min (included) and max (included)
        // Using Math.round() will give you a non-uniform distribution!
        function getRandomIntInclusive(min, max) {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        }

        //Fill all inputs with value 3
        $(document).ready(function(){
            $('.question-input').each(function(index,value){
                $(this).val(getRandomIntInclusive(1,10));
            });

            $('.question-input').change();
        });
    </script>

@endsection