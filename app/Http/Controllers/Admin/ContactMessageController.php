<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Mail\ContactSubmitted;
use App\Models\ContactMessage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ContactMessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:contactmessages')->only('index');
        $this->middleware('permission:contactmessages delete')->only('destroy');
    }

    public function index()
    {
        $messages = ContactMessage::latest()->get();
        return view('admin.contactmessage.show-contactmessage', compact('messages'));
    }

    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();
        return redirect()->route('contactmessages')->with('success', 'Contact message deleted successfully.');
    }
}
