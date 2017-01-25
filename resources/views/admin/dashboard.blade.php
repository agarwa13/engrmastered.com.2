@extends('app')

<?php
        use App\Question;
        use App\Solution;
        use App\User;
?>

@section('content')

    <div class="container">
        <div class="row">


            <!-- External Links -->
            <div class="col-md-4">

                <div class="list-group">
                    <a href="#" class="list-group-item active">
                        External Links
                    </a>

                    <a target="_blank" href="https://stripe.com/" class="list-group-item">
                        Stripe
                    </a>

                    <a target="_blank" href="https://analytics.google.com/analytics/web/#home/a69523187w106578822p110954480/" class="list-group-item">
                        Google Analytics
                    </a>

                    <a target="_blank" href="https://adwords.google.com" class="list-group-item">
                        Google Adwords
                    </a>

                    <a target="_blank" href="https://mail.zoho.com/zm/#mail/inbox" class="list-group-item">
                        Zoho Mail
                    </a>

                    <a target="_blank" href="https://accounting.waveapps.com/dashboard/business/3258057/#/" class="list-group-item">
                        Wave Accounts
                    </a>
                </div>

            </div>


            <!-- Questions -->
            <div class="col-md-4">


                <div class="list-group">
                    <a href="#" class="list-group-item active">
                        Questions
                    </a>

                    <a href="{{url('question')}}" class="list-group-item">
                        <span class="badge">@{{ total_questions }}</span>
                        Total Questions
                    </a>

                    <a href="{{url('admin/questions_pending_approval')}}" class="list-group-item">
                        <span class="badge">@{{ questions_pending_approval }}</span>
                        Questions Pending Approval
                    </a>

                    <a href="{{url('admin/questions_without_solutions')}}" class="list-group-item">
                        <span class="badge">@{{ questions_without_any_solutions }}</span>
                        Questions without any Solutions
                    </a>

                    <a href="{{url('admin/questions_with_unapproved_solutions')}}" class="list-group-item">
                        <span class="badge">@{{ questions_with_unapproved_solutions }}</span>
                        Questions with Unapproved Solutions
                    </a>

                    <a href="{{url('/')}}" class="list-group-item">
                        <span class="badge">@{{ questions_with_approved_solutions }}</span>
                        Questions with Approved Solutions
                    </a>

                    <a href="{{url('admin/questions_for_later_review')}}" class="list-group-item">
                        <span class="badge">{{count(Question::getQuestionsForLaterReview()->get())}}</span>
                        Questions marked for Later Review
                    </a>

                </div>
            </div>


            <!-- Users and Roles -->
            <div class="col-md-4">

                <div class="list-group">
                    <a href="#" class="list-group-item active">
                        Users
                    </a>

                    <a href="{{url('user')}}" class="list-group-item">
                        <span class="badge">{{count(User::all())}}</span>
                        Total Users
                    </a>

                    <a href="{{url('user/students')}}" class="list-group-item">
                        <span class="badge">{{count(User::getStudents()->get())}}</span>
                        Total Students
                    </a>

                    <a href="{{url('user/editors')}}" class="list-group-item">
                        <span class="badge">{{count(User::getEditors()->get())}}</span>
                        Total Editors
                    </a>

                    <a href="{{url('user/managers')}}" class="list-group-item">
                        <span class="badge">{{count(User::getManagers()->get())}}</span>
                        Total Managers
                    </a>

                    <a href="{{url('user/admins')}}" class="list-group-item">
                        <span class="badge">{{count(User::getAdmins()->get())}}</span>
                        Total Admins
                    </a>
                </div>
            </div>

        </div>
        <div class="row">

            <!-- Internal Links -->
            <div class="col-md-4">
                <div class="list-group">
                    <a href="#" class="list-group-item active">
                        Internal Links
                    </a>
                    <a href="{{url('usage_record')}}" class="list-group-item">
                        All Usage Records
                    </a>

                    <a href="{{url('university')}}" class="list-group-item">
                        All Universities
                    </a>

                    <a href="{{url('course')}}" class="list-group-item">
                        All Courses
                    </a>
                </div>
            </div>

            <!-- Usage Summary -->
            <div class="col-md-4">
                <div class="list-group">
                    <a href="#" class="list-group-item active">
                        Reviews
                    </a>
                    <a href="{{url('review')}}" class="list-group-item">
                        All Reviews
                    </a>
                </div>
            </div>


        </div>
    </div>

@endsection

@section('scripts')
    <script src="//cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/0.12.1/vue.js"></script>

    <script>
        new Vue({
            el: 'body',

            data: {
                total_questions: 0,
                questions_pending_approval: 0,
                questions_without_any_solutions: 0,
                questions_with_unapproved_solutions: 0,
                questions_with_approved_solutions: 0,
                nbHits: 0
            },

            ready: function() {
                this.client = algoliasearch('I1QY54YABM', 'b5981cb0afb264b8432f9d17d255a0d5');
                this.index = this.client.initIndex('questions');
                this.calculate();
            },


            methods: {

                calculate: function(){

                    // Calculate Total Questions
                    this.search();

                    // Calculate Questions Pending Approval
                    this.search("false");

                    // Calculate questions without any solutions
                    this.search("true","false");

                    // Calculate questions with unapproved solutions
                    this.search("true","true","false");

                    // Calculate questions with approved solutions
                    this.search("true","true","true");

                },

                search: function(is_approved, has_solutions, has_approved_solution) {

                    /*
                     Set the Numeric and Boolean Filters
                     1. Is Approved
                     2. Has Solutions
                     3. Has Approved Solution
                     */
                    var numeric_filters = [];

                    if(is_approved !== ""){
                        if(is_approved === "true"){
                            numeric_filters.push('reviewer_id>0')
                        }

                        if(is_approved === "false"){
                            numeric_filters.push("reviewer_id=0")
                        }

                    }

                    if(has_solutions !== ""){
                        if(has_solutions === "true"){
                            numeric_filters.push('has_solutions=1');
                        }
                        if(has_solutions === "false"){
                            numeric_filters.push('has_solutions=0')
                        }
                    }

                    if(has_approved_solution !== ""){

                        if(has_approved_solution === "true"){
                            numeric_filters.push('has_approved_solution=1');
                        }

                        if(has_approved_solution === "false"){
                            numeric_filters.push('has_approved_solution=0');
                        }
                    }

                    /*
                     Call Search Engine and Return Results
                     */
                    this.index.search(
                            "",
                            {
                                numericFilters: numeric_filters.join(),
                                facets: '*'
                            },function(error, results) {
                                if(error){
                                    console.log(error);
                                    return;
                                }

                                console.log(results);


                                if(is_approved !== "false" && is_approved != "true"){
                                    this.total_questions = results.nbHits;
                                    console.log('total questions: ' + this.total_questions);
                                }

                                if(is_approved === "false"){
                                    this.questions_pending_approval = results.nbHits;
                                    console.log('questions pending approval: ' + this.questions_pending_approval);
                                }

                                if(is_approved === "true"){

                                    if(has_solutions === "false"){
                                        this.questions_without_any_solutions = results.nbHits;
                                        console.log('questions without any solutions: ' + this.questions_without_any_solutions);
                                    }

                                    if(has_solutions === "true"){

                                        if(has_approved_solution === "false"){
                                            this.questions_with_unapproved_solutions = results.nbHits;
                                            console.log('questions_with_unapproved_solutions: ' + this.questions_with_unapproved_solutions);
                                        }

                                        if(has_approved_solution === "true"){
                                            this.questions_with_approved_solutions = results.nbHits;
                                            console.log('questions_with_approved_solutions: ' + this.questions_with_approved_solutions);
                                        }

                                    }

                                }


                            }.bind(this)
                    );
                }
            }
        });
    </script>

@endsection