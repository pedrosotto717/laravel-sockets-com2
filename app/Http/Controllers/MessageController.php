<?php

namespace App\Http\Controllers;

use App\Events\NewMessageEvent;
use App\Models\ChatGroup;
use App\Models\ChatMessage;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recipient_email' => 'string|email',
            'message' => 'required|string',
            'group_id' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $chatGroup = null;

        if ($request->has('group_id')) {
            // Caso 1: Se proporciona un GroupID
            $chatGroup = ChatGroup::find($request->group_id);
        } else {
            // Caso 2: No se proporciona un GroupID, crear o verificar el chat grupal de uno a uno
            $recipientUser = User::where('email', $request->recipient_email)->first();

            // Obtener o crear el chat grupal entre ambos usuarios
            $chatGroup = ChatGroup::where('is_group', false)
                ->whereHas('users', function ($query) use ($request, $recipientUser) {
                    $query->where('user_id', $request->user()->id);
                })
                ->whereHas('users', function ($query) use ($recipientUser) {
                    $query->where('user_id', $recipientUser->id);
                })
                ->first();

            if (!$chatGroup) {
                $chatGroup = ChatGroup::create(['is_group' => false, 'name' => ""]);
                $chatGroup->users()->attach([$request->user()->id, $recipientUser->id]);
            }
        }

        // Crear el mensaje
        $chatMessage = ChatMessage::create([
            'user_id' => $request->user()->id,
            'chat_group_id' => $chatGroup->id,
            'message' => $request->message,
        ]);

        broadcast(new NewMessageEvent($chatGroup->id));
        
        return response()->json(['message' => 'Message sent successfully', 'chat_message' => $chatMessage], 201);
    }


    public function sendAttachment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:3072|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,gif',
            'group_id' => 'required|exists:chat_groups,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Manejar el archivo adjunto
        $filePath = $request->file('file')->store('chat_files', 'public');

        // Crear el mensaje con el archivo adjunto
        $chatMessage = ChatMessage::create([
            'user_id' => $request->user()->id,
            'chat_group_id' => $request->group_id,
            'file_path' => $filePath,
        ]);

        // Emitir el evento de nuevo mensaje
        broadcast(new NewMessageEvent($request->group_id));

        return response()->json(['message' => 'Attachment sent successfully', 'chat_message' => $chatMessage], 201);
    }

    public function getMessages(Request $request, $chatGroupId)
    {
        $chatGroup = ChatGroup::find($chatGroupId);

        if (!$chatGroup) {
            return response()->json(['message' => 'Chat group not found'], 404);
        }

        // Verificar si el usuario pertenece al chat grupal
        if (!$chatGroup->users->contains($request->user()->id)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $messages = $chatGroup->messages()->get();

        // Añadir la URL completa del archivo adjunto si existe
        $messages->transform(function ($message) {
            if ($message->file_path) {
                $message->file_url = Storage::url($message->file_path);
            }
            return $message;
        });

        return response()->json(['chat_group_id' => $chatGroupId, 'messages' => $messages]);
    }
}