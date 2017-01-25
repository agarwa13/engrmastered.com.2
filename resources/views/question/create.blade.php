@extends('app')

@section('content')

    <div class="container-fluid">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {!! session('status') !!}
                        </div>
                    @endif

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($editMode)
                        <form id="createForm" class="form" method="post" action="{{url('question/'.$question->id)}}">
                    @else
                        <form id="createForm" class="form" method="post" action="{{url('question')}}">
                    @endif

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        @if($editMode)
                            <input type="hidden" name="id" value="{{$question->id}}">
                            <input type="hidden" name="_method" value="PUT">
                        @endif

                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" name="title" placeholder="What's your homework question?" value="@if($editMode){{$question->title}}@else{{old('title')}}@endif">
                        </div>

                        <div class="form-group">
                            <label for="body">Body</label>
                            <textarea class="form-control" name="body">
                                @if($editMode) {{$question->raw_body}} @else {{old('body')}} @endif
                            </textarea>
                            <p class="help-block">Use <code>@{{1}}</code> for the first variable, <code>@{{2}}</code> for the second variable and so on.
                                Use <code>@{{1r}}</code> to display the first variable at a second location in the question. You can also display the default
                                value of each variable like this <code>@{{1,2}}</code> where 1 is used since it is the first variable and 2 is the default value.
                                It is highly recommended that you provide default values when posting your question.
                            </p>
                        </div>


                        <div class="form-group">
                            <label>Courses</label>
                            <select name="courses[]" class="form-control selectpicker" multiple>
                                <option value="0">None</option>

                                @if($editMode)
                                    @foreach($question->courses as $course)
                                        <option value="{{$course->id}}" selected>{{$course->name}} ({{$course->acronym}})</option>
                                    @endforeach
                                @endif

                                @foreach($courses as $course)
                                    <option value="{{$course->id}}">{{$course->name}} ({{$course->acronym}})</option>
                                @endforeach
                            </select>
                        </div>


                        <input type="hidden" name="images" value="@if($editMode){{$question->images}}@else{{old('images')}}@endif">

                    </form>

                    <form style="margin-top: 20px; margin-bottom: 20px;" action="{{url('upload/image')}}" class="dropzone" id="my-awesome-dropzone">
                    </form>



                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <hr>
                    <h4 id="title-wrapper">
                        @if($editMode) {{$question->title}} @else {{old('title')}} @endif
                    </h4>


                    <!---

                    html = '<div class=\"col-lg-3 col-md-4 col-xs-6 thumb\" style=\"position: relative\">';
                    html += '<button onclick=\"deleteImage(this.id)\" type=\"button\" style=\"position: absolute; right: 20px; top: 5px;\" id="image-1">&times; Delete</button>';
                    html += '<div class=\"thumbnail\" href=\"#\">';
                    html += '<img class=\"img-responsive\" src=\"'+ response.src +'\">';
                    html += '</div>';
                    html+= '</div>';
                    -->

                    <div>
                        <div class="row" id="image-wrapper">
                            @if(isset($images))
                                @if($images)
                                    <?php $counter = 1; ?>
                                    @foreach($images as $image)
                                        <div class="col-lg-3 col-md-4 col-xs-6 thumb" style="position: relative">
                                        <button onclick="deleteImage(this.id)" type="button" style="position: absolute; right: 20px; top: 5px;" id="image-{{$counter}}">&times; Delete</button>
                                            <a class="thumbnail" href="#">
                                                <img class="img-responsive" src="{{asset($image)}}" alt="">
                                            </a>
                                        </div>
                                        <?php $counter++; ?>
                                    @endforeach
                                @endif
                            @endif
                        </div>
                    </div>

                    <div id="body-wrapper">
                        @if($editMode) {!! $question->body !!} @else {!! old('body') !!} @endif
                    </div>

                    <!-- Dropzone generates ugly previews. These are dropped into the hidden wrapper so the client never sees them -->
                    <div id="hidden-wrapper" style="display: none;"></div>
                </div>
            </div>

            @if($editMode)
                <button form="createForm" type="submit" class="btn btn-default btn-block btn-success">Update Question</button>
            @else
                <button form="createForm" type="submit" class="btn btn-default btn-block btn-success">Add Question</button>
            @endif

            <!-- Display Actions -->
            @if($editMode)
                @if(count($actions) > 0)
                    <div class="btn-group btn-group-justified" role="group">
                    @foreach($actions as $action)
                        @if($action != 'edit_question')
                            {!! $html_generator->displayActionAsButton($action, $question) !!}
                        @endif
                    @endforeach
                    </div>
                @endif
            @endif


        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h4>Formatting Tips</h4>
                    <ul>
                        <li>
                            <p style="color: red">Use MathJax to format your math content.</p>
                            For example:
                            <ul>
                                <li>
                                    To display $\omega$ <strong>inline</strong>, use:<br><code>$\omega$</code>

                                </li>

                                <li style="margin-top: 10px;">
                                    To display
                                    \begin{align}
                                    \dot{x} & = \sigma(y-x) \\
                                    \dot{y} & = \rho x - y - xz \\
                                    \dot{z} & = -\beta z + xy
                                    \end{align}

                                    <br>use:<br>

                                    <code>
                                    \begin{align}<br>
                                    \dot{x} & = \sigma(y-x) \\<br>
                                    \dot{y} & = \rho x - y - xz \\<br>
                                    \dot{z} & = -\beta z + xy<br>
                                    \end{align}</code>

                                </li>
                                <li style="margin-top: 10px;">
                                A tutorial with more examples can be found here <a href="http://meta.math.stackexchange.com/questions/5020/mathjax-basic-tutorial-and-quick-reference">here</a>.
                                </li>
                            </ul>
                        </li>

                        <li>

                            <p style="color: red">
                            Use Input Fields instead of numbers where appropriate.
                            </p>

                            <ul>
                                <li>
                                    Use <code>@{{1}}</code> for the first variable, <code>@{{2}}</code> for the second variable and so on.
                                </li>
                                <li>
                                    Use <code>@{{1r}}</code> to display the first variable at a second location in the question.
                                </li>
                                <li>
                                    <code>@{{1}}</code> and <code>@{{1r}}</code> will be replaced with input fields (like this one: <input size="3" type="text">) when the questions are displayed as can be seen in the preview.
                                </li>
                            </ul>

                            <!-- TODO: Make "See Example" Toggle the Example below -->
                            <p>
                             See example:<br>
                             </p>
                             <p>
                             Instead of Posting a Specific Question Like: <br>
                             "What is the length of the shadow that a pole of length = <code>4</code> m
                             creates if the sun is at <code>45</code> degrees with respect to the ground plane?"
                             <br>
                            Replace <code>4</code> and <code>45</code> with <code>@{{1}}</code>
                            and <code>@{{2}}</code> respectively.
                            <br>
                            So you would type:
                            <br>
                            "What is the length of the shadow that a pole of length = <code>@{{1}}</code> m
                            creates if the sun is at <code>@{{2}}</code> degrees with respect to the ground plane?"
                            </p>
                        </li>
                    </ul>



                </div>
            </div>
        </div>



    </div>

@endsection


@section('scripts')

    <script src="//cdn.ckeditor.com/4.5.3/full/ckeditor.js"></script>
    <script src="{{asset('js/create_question.js')}}"></script>



    <script>
        // This makes sure that the repeat fields update
        $(".question-input").change(function (event) {
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

                    // Add image to image wrapper
                    html = '<div class=\"col-lg-3 col-md-4 col-xs-6 thumb\" style=\"position: relative\">';
                    html += '<button onclick=\"deleteImage(this.id)\" type=\"button\" style=\"position: absolute; right: 20px; top: 5px;\" id="image-' + imageId + '">&times; Delete</button>';
                    html += '<div class=\"thumbnail\" href=\"#\">';
                    html += '<img class=\"img-responsive\" src=\"'+ response.src +'\">';
                    html += '</div>';
                    html+= '</div>';
                    $('#image-wrapper').append(html);

                    // Add image to images hidden input element
                    images.push(response.image);
                    $('input:hidden[name=images]').val(JSON.stringify(images));

                });
            },
            headers: {
                'X-CSRF-Token': $('input:hidden[name="_token"]').val()
            }
        };

        /*
        Ensure that when user deletes images, it is removed from the screen, the hidden field and the array in javascript
         */
        function deleteImage(btnId){
            var matches = btnId.match(/\d+$/);
            imageId = matches[0];

            // Removed from Array
            images.splice(imageId-1,1);

            // Removed from  hidden input in form
            $('input:hidden[name=images]').val(JSON.stringify(images));

            // Removed from view
            $('#'+btnId).parent().remove();
        }

    </script>


    @include('html_generator.ajax_actions')

@endsection