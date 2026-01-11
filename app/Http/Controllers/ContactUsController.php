<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactUsController extends Controller
{
    /**
     * Display a listing of contact messages.
     */
    public function index(Request $request)
    {
        $query = ContactUs::with('repliedByAdmin');

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search by name, email, or subject
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('subject', 'LIKE', "%{$search}%")
                  ->orWhere('message', 'LIKE', "%{$search}%");
            });
        }

        $messages = $query->orderBy('created_at', 'DESC')->paginate(15);

        return view('dashboard.contact-us.index', [
            'messages' => $messages,
        ]);
    }

    /**
     * Display the specified contact message.
     */
    public function show(string $id)
    {
        $message = ContactUs::with('repliedByAdmin')->findOrFail($id);

        // Mark as read if status is new
        if ($message->status === 'new') {
            $message->update([
                'status' => 'read',
                'read_at' => now(),
            ]);
        }

        return view('dashboard.contact-us.show', compact('message'));
    }

    /**
     * Mark message as read.
     */
    public function markAsRead(string $id)
    {
        $message = ContactUs::findOrFail($id);
        
        if ($message->status === 'new') {
            $message->update([
                'status' => 'read',
                'read_at' => now(),
            ]);
        }

        return redirect()->back()
            ->with('success', 'Message marked as read!');
    }

    /**
     * Reply to contact message.
     */
    public function reply(Request $request, string $id)
    {
        $request->validate([
            'admin_reply' => 'required|string|max:5000',
        ]);

        $message = ContactUs::findOrFail($id);
        $admin = Auth::guard('admin')->user();

        $message->update([
            'status' => 'replied',
            'admin_reply' => $request->admin_reply,
            'replied_at' => now(),
            'replied_by' => $admin->id,
        ]);

        return redirect()->back()
            ->with('success', 'Reply sent successfully!');
    }

    /**
     * Archive message.
     */
    public function archive(string $id)
    {
        $message = ContactUs::findOrFail($id);
        $message->update(['status' => 'archived']);

        return redirect()->back()
            ->with('success', 'Message archived successfully!');
    }

    /**
     * Remove the specified message from storage.
     */
    public function destroy(string $id)
    {
        $message = ContactUs::findOrFail($id);
        $message->delete();

        return redirect()->route('dashboard.contact-us.index')
            ->with('success', 'Message deleted successfully!');
    }
}
