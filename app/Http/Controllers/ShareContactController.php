<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\SharedContact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShareContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SharedContact $sharedContact)
    {

//        return $sharedContact;
        if($sharedContact->status == "cancel"){
            return abort(404);
        }

//        return $sharedContact;
        $from = User::find($sharedContact->from);
        $to = User::find($sharedContact->to);
        $contacts = Contact::whereIn('id',json_decode($sharedContact->contact_ids))->get();

        return view('share/shared-contact',compact('from','to','sharedContact','contacts'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SharedContact $sharedContact)
    {
        //
        if($request->action === "accept"){

//            Contact::whereIn('id',json_decode($sharedContact->contact_ids))->update(['user_id'=>Auth::id()]);
//            $contact = Contact::whereIn('id',json_decode($sharedContact->contact_ids))->get();
            $contacts = Contact::whereIn('id',json_decode($sharedContact->contact_ids))->get();
//            return $contact;
//            $newContact = $contact->replicate();
            foreach ($contacts as $contact){
                $sharedContact = $contact->replicate();
            }

//            $sharedContact = $contacts->replicate();
            $sharedContact->user_id = Auth::id();
            $result = $sharedContact->save();
            dd($result);

        }
        $sharedContact->status = $request->action;
        $sharedContact->update();

        return redirect()->route('contact.index')->with('status','Some Contacts You '.$request->action);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
