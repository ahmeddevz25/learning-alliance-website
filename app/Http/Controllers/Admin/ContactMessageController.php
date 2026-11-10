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
        $this->middleware('permission:contactmessages delete')->only('destroy', 'bulkDelete', 'deleteSpam');
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

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return back()->with('error', 'Please select at least one message to delete.');
        }

        $count = ContactMessage::whereIn('id', $ids)->delete();
        return redirect()->route('contactmessages')->with('success', "{$count} messages deleted successfully.");
    }

    public function deleteSpam(Request $request)
    {
        $data = $request->validate([
            'keyword' => 'required|string|min:2',
        ]);

        $kw = trim($data['keyword']);
        $deleted = ContactMessage::where('name', 'LIKE', "%{$kw}%")
            ->orWhere('email', 'LIKE', "%{$kw}%")
            ->delete();

        return redirect()->route('contactmessages')
            ->with('success', "Successfully deleted {$deleted} spam messages matching '{$kw}'.");
    }
}
