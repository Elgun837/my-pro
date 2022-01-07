<?php

namespace App\Http\Controllers\Admin;
use App\Models\Message;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MessengerController extends Controller
{
    public function index(){
        $messages = Message::orderBy('seen_date')->get();
    
        return view('dashboard.messages.index',compact('messages'));
        }
    
        public function message($id){
    
        $message = Message::findOrFail($id);
        if($message->seen_date === null){
            $message->seen_date = now();
            $message->save();
        }
        return view('dashboard.messages.message',compact('message'));
        }
        public function destroy($id){
            $message = Message::findOrFail($id);
            $message->delete();
            return redirect()->back();
        }
        public function send (Request $request) {
    
            $title = $request->title;
            $email = $request->email;
            $body  = $request->body;
    
            Mail::send('dashboard.emails.send', [ 'body'=> $body ], function($message) use($email,$title){
                $message->to($email)->subject($title);
            });
    
            return redirect()->back()->with('success', 'Succesfully send');
        }
}
