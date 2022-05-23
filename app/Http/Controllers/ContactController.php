<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Mail\SendMail;
use App\Models\Contact;
use App\Models\User;
use http\Env\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = Contact::where("user_id",Auth::id())->latest('id')->paginate(7);
        return view("contact.index",compact('contacts'));
    }
    public function goToTrash(Contact $contact){
        $contacts = Contact::onlyTrashed()->where("user_id",Auth::id())->latest('deleted_at')->paginate(7);
        return view('contact.contact-trash',compact('contacts'));
    }

    public function search(\Illuminate\Http\Request $request){
        $searchKey = $request->search;
        $contacts = Contact::where("name","LIKE","%$searchKey%")->orWhere("phone","LIKE","%$searchKey%")->paginate(5);
        return view('contact.index',compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contact.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreContactRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreContactRequest $request)
    {
        $request->validate([
           "name"=>'required|min:3',
           "phone"=>'required|min:9|max:11',
            "photo"=>'nullable|file|mimes:jpeg,png|max:5000'
        ]);

        DB::beginTransaction();

        try{

            if($request->hasFile('photo')){
                $newName = "profile_".uniqid().".".$request->file('photo')->extension();
                $request->file('photo')->storeAs("public/photo",$newName);
            }else{
                $newName = null;
            }
            $newContact = new Contact();
            $newContact->name = $request->name;
            $newContact->phone = $request->phone;
            $newContact->photo = $newName;
            $newContact->user_id = Auth::id();
            $newContact->save();

            DB::commit();

        }
        catch(\Exception $e){
            DB::rollBack();
            throw $e;

        }


        return redirect()->route('contact.index')->with('status',"You $newContact->name contact Created");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        Gate::authorize('update',$contact);
        $contact = Contact::findOrFail($contact->id);
        return view('contact.edit',compact('contact'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateContactRequest  $request
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateContactRequest $request, Contact $contact)
    {
        $request->validate([
            "name"=>'required|min:3',
            "phone"=>'required|min:9|max:11',
            "photo"=>'nullable|file|mimes:jpeg,png|max:5000'
        ]);

        DB::beginTransaction();
        try{

            $contact = Contact::findOrFail($contact->id);
            $contact->name = $request->name;
            $contact->phone = $request->phone;
            $contact->user_id = Auth::id();
            if($request->hasFile('photo')){
                //            delete old cover
                Storage::delete("public/photo/".$contact->photo);

                $newName = "profile_".uniqid().".".$request->file('photo')->extension();
                $request->file('photo')->storeAs("public/photo",$newName);
                $contact->photo = $newName;
            }
            $contact->update();

            DB::commit();

        }catch (\Exception $error){
            DB::rollBack();
            throw $error;
        }


        return redirect()->route('contact.index')->with('status',"You $contact->name Updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        Gate::authorize('delete',$contact);
        $contact = Contact::findOrFail($contact->id);
        $contact->delete();
        return redirect()->route('contact.index');
    }
    public function restore($id){
        $contact=Contact::withTrashed()->findOrFail($id);
        $contact->restore();
        return redirect()->back()->with('status',"$contact->name has been restored");
    }
    public function forceDelete($id){
        $contact=Contact::withTrashed()->findOrFail($id);
        Storage::delete("public/photo/".$contact->photo);
        $contact->forceDelete();
        return redirect()->back()->with('status',"$contact->name has been forceDelete");
    }

    public function bulkAction(\Illuminate\Http\Request $request){
//        return $request;
        if($request->functionality == 1){
            $receiver = User::where('email',$request->email)->first();
            $receiverId = $receiver->id;
            $sendUsers = Auth::user()->name;
            $sendContact = Contact::whereIn('id',$request->contact_ids)->get();
            Contact::whereIn('id',$request->contact_ids)->update(['user_id'=>$receiverId]);

//            Send Mail
//            $mailReceivers = [Auth::user()->email,$request->email];
//            foreach($mailReceivers as $mailReceiver){
//                Mail::to($mailReceiver)->send(new SendMail($sendUsers,$sendContact,$receiver));
//            }

        }elseif($request->functionality == 2){
            Contact::destroy($request->contact_ids);
        }else{
            return abort(403);
        }
        return redirect()->back();
    }



    public function bulkActionOnce(\Illuminate\Http\Request $request){
        $receiver = User::whereIn('email',$request->email)->first();
        $receiverId = $receiver->id;
        $sendUsers = Auth::user()->name;
        $sendContact = Contact::where('id',$request->contact_id)->get();
        Contact::where('id',$request->contact_id)->update(['user_id'=>$receiverId]);

        return redirect()->back()->with('status',"You shared some contact to $receiver->name");
    }



    public function bulkForceAction(\Illuminate\Http\Request $request){

        if($request->functionality == 1){
            $contact = Contact::onlyTrashed()->whereIn('id', $request->contact_ids);
                $contact->restore();
        }elseif($request->functionality == 2){

            $contacts = Contact::onlyTrashed()->whereIn('id', $request->contact_ids)->get();
                foreach ($contacts as $contact) {

                    Storage::delete("public/photo/".$contact->photo);

                    $contact->forceDelete();
                }
        }else{
            return abort(403);
        }
        return redirect()->back();
    }
}
