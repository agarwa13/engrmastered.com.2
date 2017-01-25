@extends('app')

@section('content')

<div class="container" id="listjs-list">
    <div class="row">
        <div class="col-md-12">
            <div>
                <i class="fa fa-search fa-3x" style="display: inline; float: left; color:#ccc; width: 6%;"></i>
                <input class="searchbox search fuzzy-search" type="text" value="" placeholder="Search by Course, Question or University " name="searchbox">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 list">
            @foreach($questions as $question)
                @include('html_generator.question_summary_with_actions',['question' => $question, 'actions' => $html_generator->getActionsForQuestions($question)])
            @endforeach
        </div>
        <div class="col-md-12">
            <nav>
                  <ul class="pagination">
                  </ul>
            </nav>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
/*
Set the Options for List.js List
 */
var options = {
    valueNames: [ 'courses', 'title_listjs', 'tags', 'description' ],
    page: 10,
    plugins: [
      ListPagination({})
    ]
};

/*
Initialize the List
 */
 var listObj = new List('listjs-list',options);
</script>
@endsection