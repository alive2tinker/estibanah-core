<?php

namespace App\Http\Controllers;

use App\Mail\FormInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendInvitations(Request $request){
        $emails = explode(",",$request->input('emails'));
        $cleanEmails = [];
        foreach($emails as $email){
            $cEmail = str_replace(' ','',$email);
            array_push($cleanEmails, $cEmail);
        }
        Mail::to($cleanEmails)->queue(new FormInvitation($request->input('link'), $request->input('title')));
        return response()->json('invitations have been sent!', 200);

    }
}
