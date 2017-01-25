@extends('app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12">


                <h3>
                    Frequently Asked Questions
                </h3>


                <div id="faq-list" style="padding-top: 40px;">

                    <div class="left-inner-addon-index-search-box">
                        <span class="glyphicon glyphicon-search"></span>
                        <input type="text" class="searchbox search" placeholder="Search Frequently Asked Questions">
                    </div>


                    <div class="list" style="padding-top: 40px;"> <!-- List Container -->

                        @include('legal.reusable_elements.collapsable_panel',[
                        'question' => 'The solution I received is incorrect. What should I do?',
                        'answer' => '<p>Go to your history page and select the question you used. Add a review and make sure to select Yes under the refund requested dropdown."</p><p>We will review your request, and process your refund if the solution is incorrect.</p>',
                        'serial_number' => 1]);


                        @include('legal.reusable_elements.collapsable_panel',['question' => 'The solution is correct but I am still dissatisfied with the solution. Can I get a refund?','answer' => '<p>We will evaluate your request on a case by case basis. Please submit a review of the question. You can find a record of the questions you have purchased in your history page, make sure to indicate it is a negative review and that you are requesting a refund.</p><p>We will review your request and get back to you as soon as possible.</p>', 'serial_number' => 2]);

                        @include('legal.reusable_elements.collapsable_panel',['question' => 'How do I get started solving questions and earning money on engrmastered.com?','answer' => '<p>Contact us at nikhil@engineeringmastered.com</p>', 'serial_number' => 3]);

                        @include('legal.reusable_elements.collapsable_panel',['question' => 'How do I get the solution to a question', 'answer' => '<p>You can simply navigate to the question and click on get solution to get the solution to a question. If a payment is required, then you will see a pop up asking to enter your credit card details. Once you enter your credit card details, your card will be charged and the solution will be displayed. If your card is saved on file, then we will charge the card and display the solution. There is no need to enter your card details again.</p>','serial_number' => 4
                        ])

                        @include('legal.reusable_elements.collapsable_panel',['question' => 'I asked a question. Why have I not received any credits?', 'answer' => 'Credits for a question are provided only after a paying customer uses the question. So after you post the question, a user must solve the question. Once a user has solved the question, an administrator will review the question and solution and make it available for purchase. Once a user purchases access to the solution, you will be awarded two credits.', 'serial_number' => 5])


                        @include('legal.reusable_elements.collapsable_panel',['question' => 'I solved a question. Why have I not received any money?', 'answer' => 'Money is only earned for a question after a paying customer uses the question. So after you submit a solution to a question, an administrator will review the question and solution and make it available for purchase. When the first user purchases access to the solution, you will be awarded a monetary award that is larger than the monetary award you receive for subsequent uses. Please note that many users may submit a solution to the same question. In that case, the best solution will be selected at the sole discretion of EngrMastered.com and the remaining solutions will be discarded. Only the user that provided the published solution will receive a monetary reward', 'serial_number' => 6])


                        @include('legal.reusable_elements.collapsable_panel',['question' => 'I cannot find my course in the drop down menu while adding a question. What should I do?', 'answer' => 'You can select None for now. We will be adding a feature to add a course in the future', 'serial_number' => 7])


                    </div> <!-- End of List Container -->

                </div>

            </div>
        </div>
    </div>

@endsection


@section('scripts')
    <script>
        var options = {
            valueNames: ['question', 'answer']
        };

        var faqList = new List('faq-list', options);

    </script>
@endsection
