<?php

namespace App\Http\Controllers;

use App\Models\ChatGroup;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ContactController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|exists:users,email',
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Obtener el usuario que será añadido como contacto
        $contactUser = User::where('email', $request->email)->first();

        // Verificar que no se esté agregando a sí mismo
        if ($contactUser->id == $request->user()->id) {
            return response()->json(['message' => 'You cannot add yourself as a contact'], 400);
        }

        // Verificar si el contacto ya existe
        $existingContact = Contact::where('user_id', $request->user()->id)
                                  ->where('contact_id', $contactUser->id)
                                  ->first();

        if ($existingContact) {
            return response()->json(['message' => 'This contact already exists'], 400);
        }

        // Crear el contacto
        $contact = Contact::create([
            'user_id' => $request->user()->id,
            'contact_id' => $contactUser->id,
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Contact created successfully', 'contact' => $contact], 201);
    }

    public function getAllContacts(Request $request)
    {
        $contacts = $request->user()->contacts()->with('contact')->get();

        return response()->json(['contacts' => $contacts]);
    }


    public function getContactsWithoutChat(Request $request)
    {
        // Obtener el ID del usuario autenticado
        $userId = $request->user()->id;

        // Obtener todos los IDs de usuarios con los que el usuario autenticado tiene un chat 1x1
        $chatPartners = ChatGroup::where('is_group', false) // Solo chats 1x1
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('users.id', $userId);
            })
            ->with('users') // Cargar todos los usuarios en esos chats
            ->get()
            ->pluck('users.*.id') // Recoger todos los IDs de los usuarios
            ->flatten() // Aplanar el array
            ->unique() // Eliminar duplicados
            ->all();

        // Obtener los contactos que no están en chats 1x1 con el usuario autenticado
        $contacts = $request->user()->contacts()
            ->with('contact')
            ->get()
            ->filter(function ($contact) use ($chatPartners) {
                return !in_array($contact->contact_id, $chatPartners);
            });

        // Devolver los contactos
        return response()->json(['contacts' => array_values($contacts->toArray())]);
    }


    public function deleteContact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Obtener el usuario que se desea eliminar como contacto
        $contactUser = User::where('email', $request->email)->first();

        // Si el usuario no se encuentra, devolver error
        if (!$contactUser) {
            return response()->json(['message' => 'Contact not found'], 404);
        }

        // Verificar si el contacto existe para el usuario autenticado
        $contact = Contact::where('user_id', $request->user()->id)
                        ->where('contact_id', $contactUser->id)
                        ->first();

        if (!$contact) {
            return response()->json(['message' => 'Contact not found'], 404);
        }

        // Obtener o crear el chat grupal entre ambos usuarios
        $chatGroup = ChatGroup::where('is_group', false)
            ->whereHas('users', function ($query) use ($request, $contactUser) {
                $query->where('user_id', $request->user()->id);
            })
            ->whereHas('users', function ($query) use ($contactUser) {
                $query->where('user_id', $contactUser->id);
            })
            ->first();

        // Si existe el chat grupal, eliminarlo
        if ($chatGroup) {
            $chatGroup->delete();
        }

        // Eliminar el contacto
        $contact->delete();

        return response()->json(['message' => 'Contact deleted successfully']);
    }
}
