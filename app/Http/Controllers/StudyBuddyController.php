<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudyBuddyController extends Controller
{
    //
    public function join(Request $req){
  $req->validate(['paper'=>'required|in:paper_1,paper_2']);
  return DB::transaction(function(){
    $user = auth()->user();
    // look for a half-full room
    $room = ChatRoom::where('paper',request('paper'))
                    ->where('is_full',false)
                    ->lockForUpdate()
                    ->first();
    if($room){
      $room->users()->attach($user);
      $room->update(['is_full'=>true]);
    } else {
      $room = ChatRoom::create(['paper'=>request('paper')]);
      $room->users()->attach($user);
    }
    return redirect()->route('chat.show',$room);
  });
}

}
