<?php

namespace App\Http\Controllers;

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
}
