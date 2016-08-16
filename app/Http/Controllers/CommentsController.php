<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use App\Ticket;
use App\Comment;
use Auth;

class CommentsController extends Controller
{
    public function postComment(Request $req)
    {
        $this->validate($req, [
            'comment'   => 'required'
        ]);

        $comment = Comment::create([
            'ticket_id' => $req->input('ticket_id'),
            'user_id'   => Auth::user()->id,
            'comment'   => $req->input('comment'),
        ]);

        //send mail showing other comment to ticket besides owner

        return redirect()->back()->with('status', 'Your comment has be submitted.');
    }
}
