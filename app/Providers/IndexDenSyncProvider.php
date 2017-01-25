<?php

namespace App\Providers;

use App\Question;
use Illuminate\Support\ServiceProvider;

class IndexDenSyncProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        $client = new \Indextank_Api(env('INDEXDEN_PRIVATE_URL'));
        $index = $client->get_index('questions');

        Question::saved(function($question) use ($index){

            $document = array(
                'text' => $question->simple_body,
                'title' => $question->title,
                'creator' => $question->creator_id,
                'reviewer' => $question->reviewer_id
            );

            $categories = array(
                'is_approved' => ($question->reviewer_id > 0),
                'has_solutions' => ($question->has_solutions > 0),
                'has_approved_solution' => $question->has_approved_solution
            );

            $document_variables = array(
                '0' => $question->num_followers,
                '1' => count($question->usageRecords)
            );

            $index->add_document($question->id, $document, $document_variables, $categories);

        });

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
