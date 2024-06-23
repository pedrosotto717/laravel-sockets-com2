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
            'email' => 'required|string|email',
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $contactUser = User::where('email', $request->email)->first();

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
