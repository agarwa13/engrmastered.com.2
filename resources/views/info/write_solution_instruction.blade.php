@extends('app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <h2>Getting Started - Writing Solutions</h2>



                <p>
                    Writing solutions on Engineering Mastered is a little different (and a lot more rewarding) from writing solutions elsewhere. We develop general solutions to homework problems so that student's can directly get the answer to the exact question presented to them. To help you understand how you can write generic solutions, we have developed this guide.
                </p>

                <p>While we do not require those writing solutions to have the following skills, it definitely helps to know something about:</p>
                <ul>
                    <li>A programming language (like PHP)</li>
                    <li>HTML</li>
                    <li>Latex / MathJAX</li>
                </ul>

                <p>
                    So let's get started. Since this is a step-by-step tutorial on writing solutions on Engineering Mastered, we will use the question below as an example and show you how we might solve it:
                </p>

                <div class="panel panel-default">
                    <div class="panel-body">
                        <h4>Block On Incline</h4>

                        <p>
                            A mass m = <input type="text" size="3"> kg is pulled along a horizontal floor with NO friction for a distance d = <input type="text" size="3"> m. Then the mass is pulled up an incline that makes an angle &theta; = <input type="text" size="3"> &deg; with the horizontal and has a coefficient of kinetic friction $\mu$k = <input type="text" size="3">. The entire time the massless rope used to pull the block is pulled parallel to the incline at an angle of &theta;&deg; (thus on the incline it is parallel to the surface) and has a tension T = <input type="text" size="3"> N.
                        </p>

                        <p>
                            1) What is the work done by tension before the block goes up the incline? (On the horizontal surface.)
                        </p>

                        <p>
                            2) What is the speed of the block right before it begins to travel up the incline?
                        </p>
                    </div>
                </div>

                <h3>Step 1</h3>
                <p>Click on the "Write Solution" Button. You should see a screen similar to the one shown below:</p>
                <img class="img-responsive" src="{{asset('images/write_solution_1.png')}}">

                <p>
                    On the left hand side of the page, the question is displayed. This is exactly how the question is displayed to the user. Notice that there are several input fields (boxes with a number in it) in the question. The customer is allowed to change the value in each input field to any real number. So when you write your solution you must assume that the numbers in the boxes can change.
                </p>

                <p>
                    Below the question, there is a blank area that will eventually be used to preview our solution. We will discuss this in more detail in a later section of this guide.
                </p>

                <p>
                    On the right hand side of the page, there is an editor (the black box). We will be writing our solution (in PHP + HTML) inside this editor. The editor has several features that help us write our code quickly. We will discuss these features in a later section of this guide.
                </p>

                <p>
                    Under the editor, you find a drop box for images. We can use this to add images to our solution.
                </p>

                <p>
                    Below the drop box for images, we have a few buttons. We will discuss the use of each of these buttons later in the guide.
                </p>


                <h3>Step 2</h3>

                <h4>Add an Opening and Closing PHP Tag to the editor.</h4>

                <p>
                    After you have added the opening and closing tags to your editor, it should look like this:
                </p>


                <div id="solution-editor-1" class="solution-editor" style="height: 50px; width: 50%">&lt;?php
?&gt;</div>

                <h3>Step 3</h3>
                <h4>Retrieve the values from the input field and save them to a variable with a user friendly name</h4>

                <p>The value of the first input field can be accessed as <code>$v1</code>, the value of the second input field can be accessed as <code>$v2</code> and so on. Our code will be much more readable if we name our variables as <code>$mass</code> or <code>$distance</code> since that is what it represents</p>

                <p>
                    In our example, the first variable is the mass, therefore we define the variable <code>$mass</code> like this: <code>$mass = $v1;</code>
                </p>

                <p>
                    The second variable is the distance, therefore we define the variable <code>$distance</code> like this: <code>$distance = $v2;</code>
                </p>

                <p>
                    Similarly, we define variables with user friendly names for each of the input values.
                </p>

                <p>
                    At the end of this step our editor looks something like this:
                </p>


<div id="solution-editor-2" class="solution-editor" style="height: 180px; width: 50%">&lt;?php
// Define User Friendly Variables for each Input Value
$m = $v1;
$d = $v2/100;           // Convert distance given in cm to m
$theta = $v3*pi()/180;  //Convert angle given in degrees to radians
$muk = $v4;
$t = $v5;
?&gt;
</div>
<br>
                <p>
                    Notice that instead of simply assigning the value in the input field, we might process it so that we can use it later in the code. For example, php trigonometric functions like cos and sin take their arguments in radians. So we convert the angle in degrees that the user provides to radians. Similarly, we convert the distance from centimeters to meters.
                </p>

                <p>
                    Also notice that we did not use <code>$mass</code>. Instead, we used <code>$m</code>. This is OK as long as it is clear which input value the variable is referring to.
                </p>


                <h3>Step 3</h3>
                <h4>Compute the Solution</h4>

                <p>
                    For this question, you can compute the solution of the problem as:
                </p>

                <p class="text-center">
                    $Work Done = Distance \times Tension \times cos(\theta)$
                </p>

                <p class="text-center">
                    $ Velocity = \sqrt {Work Done \times 2 / Mass}$
                </p>

                <p>
                    We can write this as code in our editor. After this, our editor will look like this:
                </p>

<div id="solution-editor-3" class="solution-editor" style="height: 240px; width: 50%;">&lt;?php
// Define User Friendly Variables for each Input Value
$m = $v1;
$d = $v2/100;           // Convert distance given in cm to m
$theta = $v3*pi()/180;  //Convert angle given in degrees to radians
$muk = $v4;
$t = $v5;

// Compute the Solution
$w = $d*$t*cos($theta); //We can compute the cosine using the cos function
$v = sqrt((2*$w)/$m);   //We can compute the square root using the sqrt function
?&gt;
</div>
                <br>

                <p>
                    Note that we used a couple of functions <code>cos</code> and <code>sqrt</code> in computing the solution. These are functions of the PHP language.
                    You can use most functions defined in the PHP language in your code. We use PHP 5.6 to process your code.
                </p>

                <p>
                    Here is a list of helpful functions: <a href="http://php.net/manual/en/ref.math.php">Math Functions</a>
                </p>
                <p>
                    Here is a list of helpful constants: <a href="http://php.net/manual/en/math.constants.php">Math Constants</a>
                    It is unlikely that you will need any math functions beyond these.
                </p>

                <h3>Step 4</h3>
                <h4>Display the Answer</h4>

                <p>
                    Now we simply display the answer using HTML. For this we will need to use the <code>echo</code> function in php.
                    We display the answer after the closing PHP tag. (<code>&lt;?</code>)
                </p>

                <p>
                    This is how the editor might look after we add the HTML and PHP to display the solution:
                </p>

<div id="solution-editor-4" class="solution-editor" style="height: 320px; width: 50%;">&lt;?php
// Define User Friendly Variables for each Input Value
$m = $v1;
$d = $v2/100;           // Convert distance given in cm to m
$theta = $v3*pi()/180;  //Convert angle given in degrees to radians
$muk = $v4;
$t = $v5;

// Compute the Solution
$w = $d*$t*cos($theta); //We can compute the cosine using the cos function
$v = sqrt((2*$w)/$m);   //We can compute the square root using the sqrt function

// Display the Solution
?&gt;
&lt;p&gt;
    Work done = &lt;?php echo roundToSigDigits($w,6); ?> J
&lt;/p&gt;
&lt;p&gt;
    Velocity = &lt;?php echo roundToSigDigits($vel,6); ?> m/s
&lt;/p&gt;
</div>
                <p>
                    Notice that we use the function <code>roundToSigDigits($real_number, $significant_digits)</code> when displaying the result. This is a special function defined by Engineering Mastered. This function allows you to round a <code>$real_number</code> to <code>$significant_digits</code>.
                </p>

                <p>
                    That's it! You have now written a PHP script to compute and display the solution of the question. To check your work, you can use the "Check Solution" button. When you click on the "Check Solution" button, the answer is displayed below the question. If the answer is as expected, then you can submit the solution for review by an administrator on Engineering Mastered.
                </p>

                <p>
                    To submit the solution for review, simply click on the "Submit Solution for Review" button. An administrator will review the solution as soon as possible and let you know if they have any feedback, if the solution is approved, or if your solution is rejected.
                </p>

                <h2>Additional Guidelines</h2>

                <h3>
                    Use LaTeX
                </h3>
                <h4>LaTeX should be used to properly format your solution</h4>
                <table class="table">
                    <thead>
                    <tr>
                        <td>LaTeX</td>
                        <td>Output</td>
                    </tr>
                    </thead>

                    <tbody>
                    <tr>
                        <td class="tex2jax_ignore"><pre>$\infty$</pre></td>
                        <td>$\infty$</td>
                    </tr>

                    <tr>
                        <td class="tex2jax_ignore"><pre>$\hat{i}$</pre></td>
                        <td>$\hat{i}$</td>
                    </tr>

                    <tr>
                        <td class="tex2jax_ignore"><pre>$\hat{j}$</pre></td>
                        <td>$\hat{j}$</td>
                    </tr>

                    <tr>
                        <td class="tex2jax_ignore"><pre>$\hat{k}$</pre></td>
                        <td>$\hat{k}$</td>
                    </tr>

                    <tr>
                        <td class="tex2jax_ignore"><pre>35 $^{\circ}$</pre></td>
                        <td>35 $^{\circ}$ </td>
                    </tr>


                    <tr>
                        <td class="tex2jax_ignore"><pre>Q&lt;sub&gt;1&lt;/sub&gt;</pre></td>
                        <td>Q<sub>1</sub></td>
                    </tr>

                    <tr>
                        <td class="tex2jax_ignore"><pre>S&lt;sup&gt;1&lt;/sup&gt;</pre></td>
                        <td>S<sup>1</sup></td>
                    </tr>

                    <tr>
                        <td class="tex2jax_ignore"><pre>$\alpha$</pre></td>
                        <td>$\alpha$</td>
                    </tr>


                    <tr>
                        <td class="tex2jax_ignore"><pre>$\omega$</pre></td>
                        <td>$\omega$</td>
                    </tr>

                    <tr>
                        <td class="tex2jax_ignore"><pre>$\Omega$</pre></td>
                        <td>$\Omega$</td>
                    </tr>

                    <tr>
                        <td class="tex2jax_ignore"><pre>$\beta$</pre></td>
                        <td>$\beta$</td>
                    </tr>

                    <tr>
                        <td class="tex2jax_ignore"><pre>$\gamma$</pre></td>
                        <td>$\gamma$</td>
                    </tr>

                    <tr>
                        <td class="tex2jax_ignore"><pre>$\rho$</pre></td>
                        <td>$\rho$</td>
                    </tr>

                    <tr>
                        <td class="tex2jax_ignore"><pre>$\sigma$</pre></td>
                        <td>$\sigma$</td>
                    </tr>


                    <tr>
                        <td class="tex2jax_ignore"><pre>$\phi$</pre></td>
                        <td>$\phi$</td>
                    </tr>

                    <tr>
                        <td class="tex2jax_ignore"><pre>$\mu$</pre></td>
                        <td>$\mu$</td>
                    </tr>


                    <tr>
                        <td class="tex2jax_ignore"><pre>$\lambda$</pre></td>
                        <td>$\lambda$</td>
                    </tr>

                    <tr>
                        <td class="tex2jax_ignore"><pre>$\theta$</pre></td>
                        <td>$\theta$</td>
                    </tr>


                    <tr>
                        <td class="tex2jax_ignore"><pre>$\vec{A}_{cb}$</pre></td>
                        <td>$\vec{A}_{cb}$</td>
                    </tr>


                    <tr>
                        <td class="tex2jax_ignore"><pre>$\vec{A}$</pre></td>
                        <td>$\vec{A}$</td>
                    </tr>


                    <tr>
                        <td class="tex2jax_ignore"><pre>$\frac{2}{x+2}$</pre></td>
                        <td>$\frac{2}{x+2}$</td>
                    </tr>

                    </tbody>
                </table>

                <h3>Features of the Editor</h3>
                <h4>Ctrl + S to save</h4>
                <p>While you are typing in the editor, you can hit Ctrl + S. This will upload your code so that you can return to editing your code later.</p>

                <h4>Auto-Save</h4>
                <p>
                    The editor automatically saves your code every 30 seconds. That way, if you accidentally navigate away from the page, your work is still saved for you when you return.
                </p>

                <h4>Top Right Corner for Status of Requests</h4>
                <p>
                    You will notice a revolving icon in the top right corner of the page when a request is on going. You can continue to edit your code as the request is being processed.
                </p>
                <p>
                    After a request is processed, the revolving icon dissapears and the status of the request is displayed. For example, a syntax error maybe displayed.
                </p>

                <h4>Syntax highlighting</h4>
                <p>
                    Your PHP and HTML code is highlighted so that you can quickly notice if you missed a closing quotation mark or a closing html tag.
                </p>

            </div>
        </div>

    </div>

@endsection


@section('scripts')
        <!-- Using Ace Editor -->
    <script src="{{asset('/vendor/ace/src/ace.js')}}"></script>

    <script>
        var editor = ace.edit('solution-editor-1');
        editor.setTheme("ace/theme/monokai");
        editor.getSession().setMode("ace/mode/php");
        editor.setReadOnly(true);

        editor = ace.edit('solution-editor-2');
        editor.setTheme("ace/theme/monokai");
        editor.getSession().setMode("ace/mode/php");
        editor.setReadOnly(true);


        editor = ace.edit('solution-editor-3');
        editor.setTheme("ace/theme/monokai");
        editor.getSession().setMode("ace/mode/php");
        editor.setReadOnly(true);


        editor = ace.edit('solution-editor-4');
        editor.setTheme("ace/theme/monokai");
        editor.getSession().setMode("ace/mode/php");
        editor.setReadOnly(true);


    </script>
@endsection