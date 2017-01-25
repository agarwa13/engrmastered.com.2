<?php

namespace App\Console;

use App\Solution;
use App\Question;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Storage;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Task to Delete all the files in Storage
        $schedule->call(function(){

            $directory = 'solutions';

            $files = Storage::disk('local')->files($directory);
            foreach($files as $file){
                if($file != 'solutions/common_functions.php'){
                    Storage::disk('local')->delete($file);
                }
            }
        })->daily();



        //Task to clean up the database
        $schedule->call(function(){
            $solutions = Solution::getApprovedSolutions()->get();
            foreach($solutions as $solution){
                $question = Question::findorfail($solution->question_id);
                $question->has_solutions = true;
                $question->has_approved_solution = true;
                $question->approved_solution_id = $solution->id;
                $question->save();
            }
        })->daily();




    }
}
