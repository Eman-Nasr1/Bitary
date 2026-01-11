<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactUsRequest;
use App\Models\ContactUs;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    /**
     * Store a new contact us message.
     */
    public function store(StoreContactUsRequest $request)
    {
        $data = $request->validated();

        // Get user if authenticated
        $user = auth()->user();

        // Create contact message
        $contactUs = ContactUs::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'subject' => $data['subject'] ?? null,
            'message' => $data['message'],
            'status' => 'new',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Your message has been sent successfully. We will get back to you soon.',
            'data' => [
                'id' => $contactUs->id,
                'name' => $contactUs->name,
                'email' => $contactUs->email,
                'subject' => $contactUs->subject,
                'created_at' => $contactUs->created_at->format('Y-m-d H:i:s'),
            ],
        ], 201);
    }

    /**
     * Get user's contact messages
     */
    public function myMessages(Request $request)
    {
        $user = auth('sanctum')->user();
        
        if (!$user) {
            return errorJsonResponse('Unauthenticated', 401);
        }

        $query = ContactUs::with('repliedByAdmin')
            ->where('email', $user->email);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $messages = $query->orderBy('created_at', 'DESC')->paginate(15);

        $formattedMessages = $messages->map(function($message) {
            return [
                'id' => $message->id,
                'name' => $message->name,
                'email' => $message->email,
                'phone' => $message->phone,
                'subject' => $message->subject,
                'message' => $message->message,
                'status' => $message->status,
                'admin_reply' => $message->admin_reply,
                'replied_at' => $message->replied_at ? $message->replied_at->format('Y-m-d H:i:s') : null,
                'replied_by' => $message->repliedByAdmin ? [
                    'id' => $message->repliedByAdmin->id,
                    'name' => $message->repliedByAdmin->name,
                    'email' => $message->repliedByAdmin->email,
                ] : null,
                'created_at' => $message->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return successJsonResponse($formattedMessages, 'Messages retrieved successfully', $messages->total());
    }

    /**
     * Get a specific contact message by ID
     */
    public function show($id)
    {
        $user = auth('sanctum')->user();
        
        if (!$user) {
            return errorJsonResponse('Unauthenticated', 401);
        }

        $message = ContactUs::with('repliedByAdmin')
            ->where('id', $id)
            ->where('email', $user->email)
            ->first();

        if (!$message) {
            return errorJsonResponse('Message not found or you do not have permission to view it', 404);
        }

        $formattedMessage = [
            'id' => $message->id,
            'name' => $message->name,
            'email' => $message->email,
            'phone' => $message->phone,
            'subject' => $message->subject,
            'message' => $message->message,
            'status' => $message->status,
            'admin_reply' => $message->admin_reply,
            'replied_at' => $message->replied_at ? $message->replied_at->format('Y-m-d H:i:s') : null,
            'replied_by' => $message->repliedByAdmin ? [
                'id' => $message->repliedByAdmin->id,
                'name' => $message->repliedByAdmin->name,
                'email' => $message->repliedByAdmin->email,
            ] : null,
            'read_at' => $message->read_at ? $message->read_at->format('Y-m-d H:i:s') : null,
            'created_at' => $message->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $message->updated_at->format('Y-m-d H:i:s'),
        ];

        return successJsonResponse($formattedMessage, 'Message retrieved successfully');
    }
}
