<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Category;
use App\Ticket;
use Auth;

class TicketsController extends Controller
{
    public function create()
    {
        $categories = Category::all();

        return view('tickets.create', compact('categories'));
    }

    public function store(Request $req)
    {
        $this->validate($req, [
            'title'     => 'required',
            'category'  => 'required',
            'priority'  => 'required',
            'message'   => 'required'
        ]);

        $ticket = new Ticket([
            'title'     =>  $req->input('title'),
            'user_id'   =>  Auth::user()->id,
            'ticket_id' =>  strtoupper(str_random(10)),
            'category_id'   =>  $req->input('category'),
            'priority'  =>  $req->input('priority'),
            'message'   =>  $req->input('message'),
            'status'    =>  "Open"
        ]);

        $ticket->save();

        // Send mail to user inform about this created ticket
        Mail::to(Auth::user()->email)->send(new TicketCreated($ticket));

        return redirect()->back()->with('status', 'A ticket with ID: #$ticket->ticket_id has been opened.');
    }

    public function userTickets()
    {
        $tickets = Ticket::where('user_id', Auth::user()->id)->paginate(10);
        $categories = Category::all();

        return view('tickets.user_tickets', compact('tickets', 'categories'));
    }

    public function show($ticket_id)
    {
        $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();

        $comments = $ticket->comments;

        $category = $ticket->category;

        return view('tickets.show', compact('ticket', 'category', 'comments'));
    }
}
