<?php

namespace App\Http\Controllers\Api;

use App\Classes\PropertyClass;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Property;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class ChatsController extends Controller
{
    private $unique_ids = [];
    public function message(Request $request)
    {
        $this->validate($request, [
            'message' => 'required|min:1|max:500'
        ]);
        $property = Property::find($request->propertyID);
        $message = new Message;
        $message->sender_id = $request->user()->id;
        $message->receiver_id = $request->receiverID;
        $message->property_id = $property->id;
        $message->chat = $request->message;
        $message->save();
        return ['status' => 1, 'message' => 'Message successfully sent'];
        //return $request->all();
    }
    public function get_messages(Request $request)
    {
        $user = $request->user();
        $chats = Message::where('sender_id', $user->id)->orWhere('receiver_id', $user->id)->orderBy('created_at', 'desc')->get();
        $propChats = Message::where('sender_id', $user->id)->orWhere('receiver_id', $user->id)->orderBy('created_at', 'ASC')->groupBy('property_id')->get();

        $unique_prop_chats = $chats->reject(function ($chat) {
            $chatter_id = $chat->receiver_id == Auth::id() ? $chat->sender_id : $chat->receiver_id;
            $unique_id = Auth::id() . $chatter_id . $chat->property_id;
            if (in_array($unique_id, $this->unique_ids)) {
                return true;
            } else {
                array_push($this->unique_ids, $unique_id);
                return false;
            }
        });
        Log::info($this->unique_ids);
        foreach ($unique_prop_chats as $prop_chat) {
            $chatter_id = $prop_chat->receiver_id == $user->id ? $prop_chat->sender_id : $prop_chat->receiver_id;
            // Log::info($prop_chats);
            $chatter = User::findOrFail($chatter_id);
            //make the user dp link absolute url
            if ($chatter->dp_link) {
                $chatter->dp_link = URL($chatter->dp_link);
            }
            $prop_chat->chatter = $chatter;
            $prop_chat->property = Property::find($prop_chat->property_id);
            //get chat property image
            //initialize getResource class for accessing needed resources
            if ($prop_chat->property) {
                $property_class = new PropertyClass;
                $unique_prop_chats->property_image = $property_class->get_property_images($prop_chat->property);
            }
        }
        $newChats = [];
        foreach ($unique_prop_chats as $chat) {
            $newChats[] = $chat;
        }
        return ['msg' => 'getting user\'s message', 'propChats' => $propChats, 'chats' => $chats, 'uniquePropChats' => $newChats];
    }
}
