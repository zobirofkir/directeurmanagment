<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewMessageNotification;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function index()
    {
        // Get all users except the authenticated user, with their latest message
        $contacts = User::where('id', '!=', Auth::id())
            ->select('users.*')
            ->selectRaw('(
                SELECT messages.content
                FROM messages
                WHERE (messages.sender_id = users.id AND messages.receiver_id = ?)
                   OR (messages.sender_id = ? AND messages.receiver_id = users.id)
                ORDER BY messages.created_at DESC
                LIMIT 1
            ) as last_message', [Auth::id(), Auth::id()])
            ->selectRaw('(
                SELECT messages.created_at
                FROM messages
                WHERE (messages.sender_id = users.id AND messages.receiver_id = ?)
                   OR (messages.sender_id = ? AND messages.receiver_id = users.id)
                ORDER BY messages.created_at DESC
                LIMIT 1
            ) as last_message_time', [Auth::id(), Auth::id()])
            ->orderByRaw('CASE WHEN last_message_time IS NULL THEN 1 ELSE 0 END, last_message_time DESC')
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=7F9CF5&background=EBF4FF',
                    'last_message' => $user->last_message,
                    'last_message_time' => $user->last_message_time ? \Carbon\Carbon::parse($user->last_message_time)->diffForHumans() : null
                ];
            });

        // Get messages for the first contact by default
        $initialMessages = [];
        if ($contacts->isNotEmpty()) {
            $firstContact = $contacts->first();
            $initialMessages = $this->getMessagesForContact($firstContact['id']);
        }

        return inertia('Chats/Chat', [
            'contacts' => $contacts,
            'messages' => $initialMessages,
            'currentUser' => [
                'id' => Auth::id(),
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&color=7F9CF5&background=EBF4FF',
            ]
        ]);
    }

    private function getMessagesForContact($contactId)
    {
        return Message::where(function($query) use ($contactId) {
            $query->where('sender_id', Auth::id())
                  ->where('receiver_id', $contactId);
        })->orWhere(function($query) use ($contactId) {
            $query->where('sender_id', $contactId)
                  ->where('receiver_id', Auth::id());
        })
        ->orderBy('created_at', 'asc')
        ->get()
        ->map(function($message) {
            return [
                'id' => $message->id,
                'content' => $message->content,
                'media_url' => $message->media_url ? asset('storage/' . $message->media_url) : null,
                'media_type' => $message->media_type,
                'time' => $message->created_at->format('H:i'),
                'date' => $message->created_at->format('Y-m-d'),
                'isSender' => $message->sender_id === Auth::id(),
                'sender' => [
                    'name' => $message->sender->name,
                    'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($message->sender->name) . '&color=7F9CF5&background=EBF4FF',
                ]
            ];
        });
    }

    public function getMessages($contactId)
    {
        $messages = $this->getMessagesForContact($contactId);
        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        try {
            $validated = $request->validate([
                'receiver_id' => 'required|exists:users,id',
                'content' => 'nullable|string',
                'media' => 'nullable|file|mimes:jpeg,png,gif,mp4,mov,avi|max:10240' // 10MB max
            ]);

            $message = new Message([
                'sender_id' => Auth::id(),
                'receiver_id' => $validated['receiver_id'],
                'content' => $request->input('content', ''), // Provide empty string as default
            ]);

            if ($request->hasFile('media')) {
                $file = $request->file('media');

                // Ensure the file is valid
                if (!$file->isValid()) {
                    throw new \Exception('Invalid file upload');
                }

                // Create directory if it doesn't exist
                $path = storage_path('app/public/chat-media');
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }

                // Store the file
                $fileName = $file->store('chat-media', 'public');
                if (!$fileName) {
                    throw new \Exception('Failed to store file');
                }

                $message->media_url = $fileName;
                $message->media_type = $this->getMediaType($file->getMimeType());
            }

            $message->save();

            // Get the receiver user
            $receiver = User::find($validated['receiver_id']);

            // Send notification
            $receiver->notify(new NewMessageNotification($message, Auth::user()));

            $messageData = [
                'id' => $message->id,
                'content' => $message->content,
                'media_url' => $message->media_url ? asset('storage/' . $message->media_url) : null,
                'media_type' => $message->media_type,
                'time' => $message->created_at->format('H:i'),
                'isSender' => true,
                'sender' => [
                    'id' => Auth::id(),
                    'name' => Auth::user()->name,
                    'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&color=7F9CF5&background=EBF4FF',
                ],
            ];

            broadcast(new MessageSent($message))->toOthers();

            return response()->json($messageData);

        } catch (\Exception $e) {
            Log::error('Message sending failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to send message',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function getMediaType($mimeType)
    {
        if (strpos($mimeType, 'image/') === 0) {
            return 'image';
        } elseif (strpos($mimeType, 'video/') === 0) {
            return 'video';
        }
        return null;
    }
}
