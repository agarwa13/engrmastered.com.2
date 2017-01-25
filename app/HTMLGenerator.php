<?php
/**
 * Created by PhpStorm.
 * User: Nikhil
 * Date: 9/15/2015
 * Time: 7:15 PM
 */

namespace App;

use Auth;

/*
 * Returns HTML that can be used across various views
 */

class HTMLGenerator
{

    private $user;

    public function __construct(){
        $this->user = Auth::user();
    }

    public function displayAction($action, $question, $solution = null){

        switch($action){
            case 'edit_question':
                return '<a href="'.url('question/'.$question->id.'/edit').'">Edit Question</a>';

            case 'delete_question':
                return '<a href="" onclick="delete_question(event, '.$question->id.')">Delete Question</a>';

            case 'approve_question':
                return '<a href="" onclick="approve_question(event, this, '.$question->id.')">Approve Question</a>';

            case 'revert_question_approval':
                return '<a href="" onclick="revert_question_approval(event, this, '.$question->id.')">Revert Approval</a>';

            case 'revert_approved_solution':
                return '<a href="" onclick="revert_approved_solution(event, this, '.$question->id.')">Revert Approved Solution</a>';

            case 'follow_question':
                return '<span class="follow-wrapper"><a href="#" style="padding-left: 10px" class="secondary-link-color" onclick="follow_question(event, this, '.$question->id.')"><i class="fa fa-refresh"></i> Re-Ask</a> <span class="badge">'.$question->followedByCount().'</span></span>';

            case 'stop_following_question':
                return '<span href="" class="secondary-link-color" style="padding-left: 10px"><i class="fa fa-refresh"></i> Re-Asked <span class="badge">'.$question->followedByCount().'</span></span>';

            case 'view_solutions_for_approval':
                return '<a href="'.url('admin/question/'.$question->id.'/solutions').'">View Solutions</a>';

            case 'add_solution':
                return '<a class="primary-link-color add_solution" href="'.url('question/'.$question->id.'/solution/create').'">Write Solution</a>';

            case 'edit_solution':
                return '<a href="'.url("question/".$question->id."/solution/".$solution->id."/edit").'">Edit Solution</a>';

            case 'delete_solution':
                return '<a href="" onclick="delete_solution(event, ' . $question->id .', '.$solution->id.')">Delete Solution</a>';

            case 'edit_my_solution':
                return '<a class="primary-link-color" href="'.url('question/'.$question->id.'/solution/create').'">Edit Solution</a>';

            case 'approve_solution':
                return '<a href="" onclick="approve_solution(event, this, '.$solution->id.')">Approve Solution</a>';

            case 'revert_solution_approval':
                return '<a href="" onclick="revert_solution_approval(event, this, '.$solution->id.')">Revert Solution Approval</a>';

            case 'edit_approved_solution':
                $solution = $question->getApprovedSolution();
                return '<a href="'.url('question/'.$question->id.'/solution/'.$solution->id.'/edit').'">Edit Approved Solution</a>';

            case 'review_later':
                return '<a href="" onclick="review_later(event, this, '.$question->id.')">Review Later</a>';

            case 'review_completed':
                return '<a href="" onclick="review_completed(event, this, '.$question->id.')">Review Completed</a>';

            default:
                return '';
        }

    }


    public function displayActionAsButton($action, $question, $solution = null){
        switch($action){


            case 'edit_question':
                return '<a class="btn btn-warning" href="'.url('question/'.$question->id.'/edit').'">
                 <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit Question</a>';

            case 'delete_question':
                return '<a class="btn btn-danger" href="" onclick="delete_question(event, '.$question->id.')">
                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete Question</a>';

            case 'approve_question':
                return '<a class="btn btn-primary" href="" onclick="approve_question(event, this, '.$question->id.')">
                <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> Approve Question</a>';

            case 'revert_question_approval':
                return '<a class="btn btn-warning" href="" onclick="revert_question_approval(event, this, '.$question->id.')">
                <span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span> Revert Approval</a>';

            case 'revert_approved_solution':
                return '<a class="btn btn-warning" href="" onclick="revert_approved_solution(event, this, '.$question->id.')">
                <span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span> Revert Approved Solution</a>';

            case 'follow_question':
                return '<a class="btn btn-default" href="" onclick="follow_question(event, this, '.$question->id.')"> <i class="fa fa-refresh"></i> Follow </a>';

            case 'stop_following_question':
                return '<a class="btn btn-primary" href="" onclick="stop_following_question(event, this, '.$question->id.')">
                <span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span> Stop Following Question</a>';

            case 'view_solutions_for_approval':
                return '<a class="btn btn-default" href="'.url('admin/question/'.$question->id.'/solutions').'">View Solutions</a>';

            case 'add_solution':
                return '<a class="btn btn-default" href="'.url('question/'.$question->id.'/solution/create').'">
                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> &nbsp; Write Solution</a>';

            case 'edit_solution':
                return '<a class="btn btn-warning" href="'.url("question/".$question->id."/solution/".$solution->id."/edit").'">
                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit Solution </a>';

            case 'delete_solution':
                return '<a class="btn btn-danger" href="" onclick="delete_solution(event, ' . $question->id .', '.$solution->id.')">
                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete Solution</a>';

            case 'edit_my_solution':
                return '<a class="btn btn-warning" href="'.url('question/'.$question->id.'/solution/create').'">
                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit My Solution</a>';

            case 'approve_solution':
                return '<a class="btn btn-primary" href="" onclick="approve_solution(event, this, '.$solution->id.')">
                <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> Approve Solution</a>';

            case 'revert_solution_approval':
                return '<a class="btn btn-warning" href="" onclick="revert_solution_approval(event, this, '.$solution->id.')">
                <span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span> Revert Solution Approval</a>';

            case 'edit_approved_solution':
                $solution = $question->getApprovedSolution();
                return '<a class="btn btn-warning" href="'.url('question/'.$question->id.'/solution/'.$solution->id.'/edit').'">
                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit Approved Solution</a>';

            case 'review_later':
                return '<a class="btn btn-primary" href="" onclick="review_later(event, this, '.$question->id.')">
                <span class="glyphicon glyphicon-tags" aria-hidden="true"></span> Review Later</a>';

            case 'review_completed':
                return '<a class="btn btn-primary" href="" onclick="review_completed(event, this, '.$question->id.')">
                <span class="glyphicon glyphicon-tags" aria-hidden="true"></span> Review Completed</a>';

            default:
                return '';
        }
    }


    /*
     * Based on the State of the Solution (Approved or Not Approved)
     * and the authenticated user
     * a list of available actions is returned
     */
    public function getActionsForSolutions($solution){

        // Array to store the actions that will be displated
        $actions = [];

        if(Auth::guest()){

            /*
             * Add Actions that Guests are allowed.
             */

        }else{

            // If the user is an Admin
            if($this->user->isAdmin()){
                if(!$solution->isApproved()){
                    array_push($actions,'approve_solution');
                    array_push($actions, 'edit_solution');
                    array_push($actions, 'delete_solution');
                }else{
                    array_push($actions,'revert_solution_approval');
                    array_push($actions, 'edit_solution');
                    array_push($actions, 'delete_solution');
                }
            }

            // If the user is an Admin
            // or
            // the user created the question and it has not been approved yet
            // then allow the user to edit it or delete it
            if($this->user->id == $solution->creator_id && !$solution->isApproved()){
                array_push($actions, 'edit_my_solution');
                array_push($actions, 'delete_solution');
            }

            if(in_array('edit_my_solution', $actions) && in_array('edit_solution', $actions)){
                if(($key = array_search('edit_solution', $actions)) !== false) {
                    unset($actions[$key]);
                }
            }

        }

        return array_unique($actions);

    }


    /*
     * Based on the State of the Question (Approved, Unapproved, Has Solutions, Has Approved Solution)
     * and the authenticated user
     * a list of available actions is returned
     */
    public function getActionsForQuestions($question){

        // Array to store the actions that will be displayed
        $actions = [];

        if(Auth::guest()){

            /*
             * Add Actions that Guests are allowed.
             */
            if(!$question->hasApprovedSolution()) {
                array_push($actions, 'add_solution');
                array_push($actions, 'follow_question');
            }

        }else{
            /*
            * Admin's should always be able to edit or delete the Question
            * at any time even if they are approved
            */
            if($this->user->isAdmin()){
                array_push($actions,'edit_question');
                array_push($actions,'delete_question');

                if($question->isMarkedForReview()){
                    array_push($actions,'review_completed');
                }else{
                    array_push($actions,'review_later');
                }

            }

            /*
             * Students should be able to add solutions if no approved solution is available
             */
            if(!$question->hasApprovedSolution()){
                /*
                 * Either Display Add Solution or Edit Solution
                 */
                if($this->user->hasSolutionForQuestion($question)){
                    array_push($actions,'edit_my_solution');
                }else{
                    array_push($actions,'add_solution');
                }
            }

            /*
             * Allow users to follow the question or un-follow the question
             */
            if($this->user->following->contains($question->id)){
                array_push($actions, 'stop_following_question');
            }else{
                array_push($actions, 'follow_question');
            }

            /*
             * If Question is Approved,
             *      Admin should be able to revert the approved Question
             */
            if($question->isApproved()){
                if($this->user->isAdmin()){
                    array_push($actions,'revert_question_approval');
                }
                /*
                 * If Solution is Approved,
                 *      Admin should be able to revert the approved Solution
                 */
                if($question->hasApprovedSolution()){
                    if($this->user->isAdmin()){
                        array_push($actions, 'edit_approved_solution');
                        array_push($actions, 'revert_approved_solution');
                    }
                }else{
                    /*
                     * If Solution is Not Approved,
                     *      Admin should be able to View Submitted Solutions for Approval
                     */
                    if($this->user->isAdmin() && $question->hasUnapprovedSolutions()){
                        array_push($actions,'view_solutions_for_approval');
                    }
                }
            }else{
                /*
                 * If Question is Not Approved,
                 *      Admin should be able to approve the Question (covered earlier)
                 *      Creator/Admin of the Question should be able to Edit the Question
                 */
                if($this->user->id == $question->creator_id){
                    array_push($actions,'edit_question');
                    array_push($actions,'delete_question');
                }

                if($this->user->isAdmin()){
                    array_push($actions,'approve_question');
                }
            }
        }

        return array_unique($actions);

    }

}