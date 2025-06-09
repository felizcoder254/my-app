<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
  // Fetch last 50 messages for a room
  public function fetchMessages($room)
  {
    $msgs = ChatMessage::where('room',$room)
       ->latest()->take(50)
       ->with('user:id,name')
       ->get()->reverse();
    return response()->json($msgs);
  }

  // Store a new message
  public function sendMessage(Request $req, $room)
  {
    $req->validate(['message'=>'required|string|max:500']);
    $msg = ChatMessage::create([
      'room'     => $room,
      'user_id'  => Auth::id(),
      'message'  => $req->message,
    ]);
    $msg->load('user:id,name');
    return response()->json($msg,201);
  }

  public function clear($room) {
    \App\Models\ChatMessage::where('room', $room)->delete();
    return response()->json(['status' => 'cleared']);
}

}
