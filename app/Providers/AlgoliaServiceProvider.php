<?php

namespace App\Providers;

use AlgoliaSearch\Client;
use Illuminate\Support\ServiceProvider;
use App\Question;

class AlgoliaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $search_client = new Client(env('ALGOLIA_APPLICATION_ID'),env('ALGOLIA_ADMIN_KEY'));
        $index = $search_client->initIndex('questions');

        Question::saved(function($question) use ($index){

            $courses = array();
            $course_ids = array();
            foreach($question->courses as $course){
                array_push($courses, $course->name . " " . $course->acronym . " ");
                array_push($course_ids, (int) $course->id);
            }

            // select the identifier of this row
            $row = array(
                "objectID" => $question->id,
                "title" => $question->title,
                "body" => str_replace('$','', strip_tags($question->simple_body)),
                "creator_id" => (int) $question->creator_id,
                "reviewer_id" => (int) $question->reviewer_id,
                "has_solutions" => ($question->has_solutions == "1"),
                "has_approved_solution" => ($question->has_approved_solution == "1"),
                "approved_solution_id" => (int) $question->approved_solution_id,
                "usage_count" => count($question->usageRecords),
                "courses" => $courses,
                "course_ids" => $course_ids
            );

            if($question->has_approved_solution){
                $row["solver_id"] = (int) $question->getApprovedSolution()->creator_id;
            }

            $index->saveObject($row);

        });


        Question::deleting(function($question) use ($index){
            $index->deleteObject($question->id);
        });

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
