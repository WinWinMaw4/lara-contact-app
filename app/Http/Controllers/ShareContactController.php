<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\SharedContact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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


//             following comment code is cut code
//            Contact::whereIn('id',json_decode($sharedContact->contact_ids))->update(['user_id'=>Auth::id()]);

//            this is copy code
//            $sharedContact->status = $request->action;
//            $sharedContact->update();
            $contacts = Contact::whereIn('id',json_decode($sharedContact->contact_ids))->get();
            foreach ($contacts as $contact){
                $sharedContact = $contact->replicate();
                $newName = 'share_'.time().'_'.$contact->photo;
                $newPathWithName = 'public/photo/'.$newName;
                if (Storage::copy('public/photo/'.$contact->photo , $newPathWithName)) {
//                    dd("success");
                    $sharedContact->photo = $newName;
                    $sharedContact->user_id = Auth::id();

                    $result = $sharedContact->save();
                }
            }


//            dd($result);

        }

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
