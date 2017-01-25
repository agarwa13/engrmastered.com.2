<?php

namespace App\Http\Controllers;

use App\Services\Notification;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\University;

class UniversityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $universities = University::all();
        return view('university.index')->with('universities',$universities);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $university = new University();
        $university->name = $request->input('name');
        $university->acronym = $request->input('acronym');
        $university->creator_id = $request->user()->id;

        if($request->user()->isAdmin()){
            $university->reviewer_id = $request->user()->id;
        }

        $university->save();

        return $this->index();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $university = University::findOrFail($id);
        return view('university.show')->with('university',$university);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Notification $notification ,$id)
    {
        if($request->user()->isAdmin()){

            $university = University::findOrFail($id);

            // Editable Plugin sends the pk input, standard requests do not.
            if($request->has('pk')){
                $university->setAttribute($request->input('name'),$request->input('value'));
            }else{
                if($request->has('name')){
                    $university->name = $request->input('name');
                    $notification->add_notification('University Name Updated');
                }

                if($request->has('acronym')){
                    $university->acronym = $request->input('acronym');
                    $notification->add_notification('University Acronym Updated');
                }

                if($request->has('reviewer_id')){
                    $university->reviewer_id = $request->input('reviewer_id');
                    $notification->add_notification('Reviewer Updated');
                }
            }

            $university->save();

        }else{
            abort(403);
        }

        if($request->ajax()){
            return response()->json();
        }else{
            return $this->index();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Notification $notification, Request $request , $id)
    {
        $university = University::findOrFail($id);
        $university->delete();
        $notification->add_notification('University Deleted');

        if($request->ajax()){
            return response()->json();
        }else{
            return $this->index();
        }


    }
}
