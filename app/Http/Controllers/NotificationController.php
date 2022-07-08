<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    //
    public function markAsRead($notificationId){
        $userUnreadNotification = Auth::user()->unreadNotifications->where('id',$notificationId)->first();

        if($userUnreadNotification){
            $userUnreadNotification->markAsRead();
        }

        return redirect()->back();
    }
    public function AllMarkAsRead(){

        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back();

    }
    public function deleteAllNotification(){
        Auth::user()->notifications()->delete();
        return redirect()->back()->with('status',"Deleted All Notificatioin");
    }

}
