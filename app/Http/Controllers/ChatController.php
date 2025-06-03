<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Faculty;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat.index');
    }

public function getUsers()
{
    $users = User::select('id', 'name', 'email')->get();
    $faculties = Faculty::select('id', 'name', 'email')->get();

    $all = $users->map(function ($item) {
        $item->type = 'user';
        return $item;
    })->merge(
        $faculties->map(function ($item) {
            $item->type = 'faculty';
            return $item;
        })
    );

    return response()->json($all);
}



//    public function getMessages($userId)
// {
//     try {
//         // Fetch messages between authenticated user and the given userId
//         $messages = ChatMessage::where('user_id', auth()->id())
//             ->where('receiver_id', $userId)
//             ->orWhere('receiver_id', auth()->id())
//             ->where('user_id', $userId)
//             ->get();

//         return response()->json($messages);
//     } catch (\Exception $e) {
//         return response()->json(['error' => 'Could not fetch messages'], 500);
//     }
// }
public function getMessages($userId)
{
    $messages = ChatMessage::where('receiver_id', $userId)
                           ->orWhere('sender_id', $userId)
                           ->get(); // This returns a collection, which can be converted to an array

    return response()->json($messages);
}
    // Store a chat message
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        // Store the message
        $message = ChatMessage::create([
            'user_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        return response()->json($message, 201);
    }
}