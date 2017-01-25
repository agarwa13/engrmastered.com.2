@extends('app')

@section('title',$title)
@section('description',$description)

@section('content')

    <div class="container">

        <header>
            <input id="search-input"
                   type="text" autocomplete="off"
                   spellcheck="false"
                   autocorrect="off"
                   placeholder="Search by Question or Course"
                   v-model="query"
                   v-on="keyup: search() | key 'enter'"
                   autofocus>
            <div id="search-input-icon"></div>
        </header>

        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12">
                <p class="result_stats">@{{ nbHits }} results</p>
            </div>
        </div>

        <div id="results">
            <article v-repeat="question: questions">
                <div data-resource-type="question" data-resource-id="@{{question.objectID}}">
                    <div class="row">

                        <div class="col-md-12">
                            <h4 class="title" style="margin-bottom: 2px; display: inline-block">
                                <a href=" https://engrmastered.com/question/@{{question.objectID}}">
                                        <span class="title_listjs">
                                            @{{question.title}}
                                        </span>
                                </a>

                                    <span class="courses" style="padding-left: 10px">
                                            <span class="label label-default tag" style="display: inline-block;">@{{question.courses}}<span data-role="remove"></span></span>
                                    </span>
                            </h4>

                            <p class="description">
                                @{{ question.body }}
                            </p>
                        </div>
                    </div>
                </div>
                <hr>
            </article>
        </div>

        <nav>
            <ul class="pager">
                <li><a id="load_more_button" href="#load_more_button" v-if="this.page < this.nbPages-1" v-on="click: search(1,true)">Load More</a></li>
            </ul>
        </nav>

    </div>

@endsection

@section('scripts')

    <script src="//cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/0.12.1/vue.js"></script>

    <script>
        new Vue({
            el: 'body',

            data: {
                query: '{{$query}}',
                course: "{{$course_number}}",
                creator_id: "{{$creator_id}}",
                solver_id: "{{$solver_id}}",
                is_approved: "{{$is_approved}}",
                has_solutions: "{{$has_solutions}}",
                has_approved_solution: "{{$has_approved_solution}}",
                questions: [],
                nbHits: 0,
                nbPages: 0,
                page: 0
            },

            ready: function() {
                this.client = algoliasearch('I1QY54YABM', 'b5981cb0afb264b8432f9d17d255a0d5');
                this.index = this.client.initIndex('questions');
                this.search();
                $("#search-input").focus();
            },


            methods: {
                search: function(page_modifier, load_more) {

                    /*
                     Set the Facet Filters
                     1. Course
                     */
                    var facet_filters = [];
                    if(this.course !== ""){
                        facet_filters.push('course_ids:'+this.course);
                    }

                    /*
                     Set the Numeric and Boolean Filters
                     1. Creator
                     2. Solver
                     3. Has Solutions
                     4. Has Approved Solution (Default = True)
                     */
                    var numeric_filters = [];
                    if(this.creator_id !== ""){
                        numeric_filters.push('creator_id = ' + this.creator_id);
                    }

                    if(this.solver_id !== ""){
                        numeric_filters.push('solver_id = ' + this.solver_id);
                    }


                    if(this.is_approved !== ""){
                        if(this.is_approved === "true" ){
                            numeric_filters.push('reviewer_id>0');
                        }

                        if(this.is_approved === "false"){
                            numeric_filters.push('reviewer_id=0');
                        }
                    }

                    if(this.has_solutions !== ""){
                        if(this.has_solutions === "true"){
                            numeric_filters.push('has_solutions = 1');
                        }
                        if(this.has_solutions === "false"){
                            numeric_filters.push('has_solutions = 0')
                        }
                    }

                    if(this.has_approved_solution !== ""){

                        if(this.has_approved_solution === "true"){
                            numeric_filters.push('has_approved_solution = 1');
                        }

                        if(this.has_approved_solution === "false"){
                            numeric_filters.push('has_approved_solution = 0');
                        }
                    }



                    /*
                     Set the Page Filter
                     */
                    page_filter = (this.page + page_modifier) || 0;

                    /*
                     Load More or New Search. Default is new search.
                     */
                    load_more = load_more || false;

                    /*
                     Call Search Engine and Return Results
                     */
                    this.index.search(
                            this.query,
                            {
                                numericFilters: numeric_filters.join(),
                                facets: '*',
                                facetFilters: facet_filters,
                                page: page_filter
                            },function(error, results) {
                                if(error){
                                    console.log(error);
                                    return;
                                }
                                console.log(results);

                                if(load_more){
                                    this.questions = this.questions.concat(results.hits);
                                }else{
                                    this.questions = results.hits;
                                }

                                this.nbHits = results.nbHits;
                                this.nbPages = results.nbPages;
                                this.page = results.page;
                            }.bind(this)
                    );
                }
            }
        });
    </script>


@endsection