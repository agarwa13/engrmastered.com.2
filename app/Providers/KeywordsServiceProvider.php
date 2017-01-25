<?php

namespace App\Providers;

use App\Services\AlchemyAPI;
use Illuminate\Support\ServiceProvider;
use App\Question;

class KeywordsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $alchemy_api = new AlchemyAPI();

        Question::saved(function ($question) use ($alchemy_api) {
            $keywords = array();
            $response = $alchemy_api->keywords('html',$question->raw_body,array());
            foreach ($response['keywords'] as $keyword) {
                array_push($keywords, $keyword['text']);
            }
            $question->keywords = $keywords ;
            $question->save();
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
