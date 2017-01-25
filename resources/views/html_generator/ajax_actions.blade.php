<script>

    /*
     Actions for Admins
     */
    function approve_question(event, actionElement, id){

        /*
        Confirm that user wants to approve this question
        */
        bootbox.confirm({
            message: "Are you sure you want to approve this question?",
            callback: function(r){
                if(r){
                    /*
                    Send Request to Database (Async)
                    */
                    post_admin_action('approve_question',id);
                    /*
                    Change the Button/Link to Revert Question Approval (Async)
                    */
                    replace_element(actionElement, 'revert_question_approval', id);
                }
            },
            backdrop: false
        });

        /*
        Return False to Stop Propogation
         */
        event.preventDefault();

    }



    function review_later(event, actionElement, id){
        /*
         Send Request to Database (Async)
         */
        post_admin_action('review_later',id);
        /*
         Change the Button/Link to Revert Question Approval (Async)
         */
        replace_element(actionElement, 'review_completed', id);
        /*
         Return False to Stop Propogation
         */
        event.preventDefault();
    }




    function review_completed(event, actionElement, id){
        /*
         Send Request to Database (Async)
         */
        post_admin_action('review_completed',id);
        /*
         Change the Button/Link to Revert Question Approval (Async)
         */
        replace_element(actionElement, 'review_later', id);
        /*
         Return False to Stop Propogation
         */
        event.preventDefault();
    }





    function approve_solution(event, actionElement, id){

        /*
        Confirm that the user wants to approve this solution
        */
        bootbox.confirm({
            message: "Are you sure you want to approve this solution?",
            callback: function(r){
                if(r){
                    /*
                    Send Request to Database (Async)
                    */
                    post_admin_action('approve_solution',id);

                    /*
                    Change the Button/Link to Revert Solution Approval
                    */
                    replace_element(actionElement, 'revert_solution_approval', 0, id);

                }
            },
            backdrop: false
        });

        /*
        Return False to Stop Propogation
         */
        event.preventDefault();
    }

    function revert_question_approval(event, actionElement, id){
        /*
        Confirm that the user wants to demote this question
        */
        bootbox.confirm({
            message: "Are you sure you want to demote this question?",
            callback: function(r){
                if(r){
                    /*
                     Send Request to Database (Async)
                     */
                    post_admin_action('revert_question_approval',id);

                    /*
                     Change the Button/Link to Approve Question
                     */
                    replace_element(actionElement, 'approve_question', id);

                }
            },
            backdrop: false
        });

        /*
        Return False to Stop Propogation
         */
        event.preventDefault();

    }

    /*
    id here is the question id. The question should be a question with an approved solution
    */
    function revert_approved_solution(event, actionElement, id){
        /*
        Confirm that the user wants to unapprove the solution
        */
        bootbox.confirm({
            message: "Are you sure you want to demote the approved solution?",
            callback: function(r){
                if(r){
                    /*
                     Send the Request to the Database (Async)
                     */
                    post_admin_action('revert_approved_solution', id);

                    /*
                     Remove the Button with View Solutions
                     */
                    replace_element(actionElement, 'view_solutions_for_approval', id);

                }
            },
            backdrop: false
        });

        /*
        Return False to Stop Propogation
         */
        event.preventDefault();


    }

    /*
    id here is the solution id.
    */
    function revert_solution_approval(event, actionElement, id){

        /*
        Confirm that the user wants to unapprove the solution
        */
        bootbox.confirm({
            message: "Are you sure you want to demote this solution?",
            callback: function(r){
                if(r){
                    /*
                     Send the Request to the Database (Async)
                     */
                    post_admin_action('revert_solution_approval', id);

                    /*
                     Change the Button/Link to Approve Solution
                     */
                    replace_element(actionElement, 'approve_solution', 0, id);

                }
            },
            backdrop: false
        });

        /*
        Return False to Stop Propogation
         */
        event.preventDefault();

    }



    /*
    Start Following Question
     */
    function follow_question(event, actionElement, question_id){

        event.preventDefault();

        @if(Auth::guest())
            window.location.href = "{{url('auth/login')}}";

        @else
            /*
            Post to Server
             */
            $.ajax({
                type: "POST",
                url: "{{url('question')}}" + "/" + question_id +  "/follow"
            });

            /*
            Replace with Stop Following Question
            */
            replace_element($(actionElement).parent('.follow-wrapper'), 'stop_following_question', question_id);

            /*
            Return False to Stop Propogation
             */
            event.preventDefault();
        @endif

    }

    /*
    Stop Following Question
    */
    function stop_following_question(event, actionElement, question_id){
        /*
        Post to Server
         */
        $.ajax({
            type: "POST",
            url: "{{url('question')}}" + "/" + question_id +  "/stop_following"
        });

        /*
        Replace with Stop Following Question
        */
        replace_element(actionElement, 'follow_question', question_id);

        /*
        Return False to Stop Propogation
         */
        event.preventDefault();
    }

    /*
    Mark Question for Later Review
    */


    /*
    Send an Admin Request
     */
    function post_admin_action(action, id){
        $.ajax({
            type: "POST",
            url: "{{url('admin/action')}}",
            data: { action: action, id: id }
        });

    }

    /*
     Actions for all users
     */

     /*
     Delete Question
     */
    function delete_question(event, id){

        /*
        Confirm User Wants to Delete this Question?
         */
        bootbox.confirm({
            message: 'Are you sure you want to delete this question?',
            callback: function(r){
                if(r){
                    /*
                     Delete the Question from the Database
                     */
                    $.ajax({
                        type: "DELETE",
                        url: "{{url('question')}}" + "/" + id,
                        complete: function(jqXHR, textStatus){

                            /*
                             If the Route is specific to the Question, then redirect on delete
                             */
                            current_route = "{{Request::path()}}";
                            if( current_route.indexOf('question/' + id) != -1 ){
                                window.location.href = "{{url('question')}}";
                            }

                        }
                    });

                    /*
                     Remove the Question from the View
                     */
                    $('[data-resource-type="question"][data-resource-id="' + id + '"]').remove();

                }
            },
            backdrop: false
        });

         /*
         Return False to Stop Propogation
          */
         event.preventDefault();

    }


    /*
     Delete Solution
     */
    function delete_solution(event, question_id, solution_id){
        /*
        Confirm User Wants to Delete this Solution
         */
         bootbox.confirm({
             message: 'Are you sure you want to delete this solution?',
             callback: function(r){
                 if(r){
                     /*
                      Delete this Solution from the Database
                      */
                     $.ajax({
                         type: "DELETE",
                         url: "{{url('question')}}" + "/" + question_id + "/solution/" + solution_id
                     });

                     /*
                      Remove the Solution from the View
                      */
                     $('[data-resource-type="solution"][data-resource-id="' + solution_id + '"]').remove();

                 }
             },
             backdrop: false
         });

          /*
          Return False to Stop Propogation
           */
          event.preventDefault();

    }

     /*
        Replace a Button with another Button
     */
     function replace_element(oldActionElement, newAction, question_id, solution_id){

        // Button or Link
        button = $(oldActionElement).hasClass('btn');

        // Solution Id is an optional argument
        if (typeof solution_id === 'undefined') { solution_id = null; }

        if(solution_id == null){
            url = "{{url('button')}}" + "/" + button + "/" + newAction + "/" + question_id
        }else{
            url = "{{url('button')}}" + "/" + button + "/" + newAction + "/" + question_id + "/" + solution_id
        }

        // Get the New Element from the Server
        $.ajax({
            type: "GET",
            url: url,
            success: function(response){
                /*
                    Replace the old Element with the new Element
                 */
                $(oldActionElement).replaceWith(response.button);
            }
        });

     }

    // Set Focus on the First .question-input
    $(document).ready(function(){
        $('.question-input').first().focus();
    });

</script>

