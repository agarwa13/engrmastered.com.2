<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Home Controller Routes
Route::get('/','HomeController@getHome');
Route::get('home','HomeController@getHome');

Route::get('faq','HomeController@getFAQ');
Route::get('privacy_policy','HomeController@getPrivacyPolicy');
Route::get('honor_code','HomeController@getHonorCode');
Route::get('terms_and_conditions','HomeController@getTermsAndConditions');
Route::get('copyright','HomeController@getCopyright');
Route::get('answer_questions','HomeController@getAnswerQuestions');
Route::get('ask_questions','HomeController@getAskQuestions');
Route::get('find_homework_solutions','HomeController@getFindHomeworkSolutions');
Route::get('write_solutions_guide','HomeController@getWriteSolutionsGuide');

// Search Route
Route::get('search-results','HomeController@getSearchResults');

// Authentication routes (Middleware called by Controller)
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');


// Registration routes (Middleware called by Controller)
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

//Social Login Routes
Route::get('google/login','Auth\AuthController@googleLogin');
Route::get('facebook/login','Auth\AuthController@facebookLogin');

// Route to Upload Images (for both questions and solutions)
// TODO: Delete unused questions, cron job?
Route::post('upload/image','ImageController@postUpload');

// Display Questions
Route::get('question/unsolved','QuestionController@getUnsolved');
Route::get('question/solved','QuestionController@getSolved');

// Display Questions by Course / Status
Route::get('course/{id}/approved_questions','CourseController@showApprovedQuestions');
Route::get('course/{id}/unsolved_questions','CourseController@showUnsolvedQuestions');
Route::get('course/{id}/solved_questions','CourseController@showSolvedQuestions');

// Display Questions by User / Status
Route::get('user/{id}/question','UserController@showQuestions');
Route::get('user/{id}/solution','UserController@showSolutions');
Route::get('user/{id}/used_questions','UserController@getUsedQuestions');

//Return Button/Link Actions for Display
Route::get('button/{button}/{action}/{question_id}','HTMLController@getButton');
Route::get('button/{button}/{action}/{question_id}/{solution_id}','HTMLController@getButton');

// Follow or Stop Following Question
Route::post('question/{id}/follow','UserController@followQuestion');
Route::post('question/{id}/stop_following','UserController@stopFollowingQuestion');

// Site Map
get('sitemap.xml','HomeController@siteMap');

// Process Admin Actions
Route::post('admin/action','AdminController@postAction');

// Display Questions by Status for Admin
Route::get('admin/all_questions','AdminController@getAllQuestions');
Route::get('admin/questions_pending_approval','AdminController@getQuestionsPendingApproval');
Route::get('admin/questions_without_solutions','AdminController@getQuestionsWithoutSolutions');
Route::get('admin/questions_with_unapproved_solutions','AdminController@getQuestionsWithUnapprovedSolutions');
Route::get('admin/question/{id}/solutions','AdminController@getQuestionWithUnapprovedSolutions');
Route::post('admin/question/{id}/solutions','AdminController@postQuestionWithUnapprovedSolutions');
Route::get('admin/questions_for_later_review','AdminController@getQuestionsForLaterReview');
Route::get('admin/refund_requests_pending_review','ReviewController@getRefundRequestsPendingReview');

// Display Users Filtered by Role
Route::get('user/students','UserController@getStudents');
Route::get('user/editors','UserController@getEditors');
Route::get('user/managers','UserController@getManagers');
Route::get('user/admins','UserController@getAdmins');

// Payment Gateway Related
Route::post('add-payment-method','StripeController@addPaymentMethod');
Route::post('default_payment_method/{token}','StripeController@updateDefaultPaymentMethod');
Route::delete('payment_method/{token}','StripeController@deletePaymentMethod');

// Payment Gateway Webhook for Disputes
Route::post('stripe/charge/dispute','StripeController@handleDispute');

// Get Search Results
Route::get('search','HomeController@search');

// PayPal Related
Route::controller('paypal','PayPalController');

// Admin Controller
Route::controller('admin','AdminController');

// Email Templates
Route::post('email/bounce-or-complaint','EmailController@postBounceOrComplaint');
Route::controller('email','EmailController');

// Resource Controllers (Should be last)
Route::resource('user','UserController');
Route::resource('question','QuestionController');
Route::resource('question.solution','QuestionSolutionController');
Route::resource('usage_record','UsageRecordController');
Route::resource('course','CourseController');
Route::resource('university','UniversityController');
Route::resource('review','ReviewController',['except' => ['create','edit']]);


// Redirect from old website
Route::get('PHYS211/{random?}/{random2?}/{random3?}/{random4?}',function(){
    return redirect('/');
});

Route::get('PHYS212/{random?}/{random2?}/{random3?}/{random4?}',function(){
   return redirect('/');
});

Route::get('PHYS213/{random?}/{random2?}/{random3?}/{random4?}',function(){
    return redirect('/');
});

Route::get('tam212/{random?}/{random2?}/{random3?}/{random4?}',function(){
    return redirect('/');
});

Route::get('TAM212/{random?}/{random2?}/{random3?}/{random4?}',function(){
    return redirect('/');
});

Route::get('ECE205/{random?}/{random2?}/{random3?}/{random4?}',function(){
    return redirect('/');
});

Route::get('Login/main.php',function(){
    return redirect('/');
});

Route::get('Login/index.php',function(){
    return redirect('/');
});

Route::get('Contact.php',function(){
    return redirect('/');
});

Route::get('privacypolicy',function(){
    return redirect('/');
});

Route::get('login/register.php',function(){
    return redirect('/');
});


Route::get('honorcode',function(){
    return redirect('/');
});



