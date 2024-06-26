<?php

namespace App\Http\Controllers;

use App\Events\ChatGroupCreated;
use App\Models\ChatGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    public function createGroup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'emails' => 'required|array',
            'emails.*' => 'string|email',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Crear el grupo de chat
        $chatGroup = ChatGroup::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_group' => true,
        ]);

        // Obtener los IDs de los usuarios por correo electrónico
        $userIds = User::whereIn('email', $request->emails)->pluck('id')->toArray();

        // Añadir al usuario creador al grupo
        array_push($userIds, $request->user()->id);

        // Adjuntar los usuarios al grupo de chat
        $chatGroup->users()->attach($userIds);
        $chatGroup->users;

        // Emitir el evento de grupo creado
        broadcast(new ChatGroupCreated($chatGroup, $userIds));

        return response()->json(['message' => 'Group created successfully', 'data' => $chatGroup], 201);
    }

    public function getUserGroups(Request $request)
    {
        // Obtener todos los grupos a los que pertenece el usuario autenticado
        $groups = $request->user()->chatGroups()->with('users')->get();

        return response()->json(['groups' => $groups]);
    }

    public function addMemberToGroup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group_id' => 'required|integer',
            'email' => 'required|string|email',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Obtener el grupo y el usuario a añadir
        $group = ChatGroup::find($request->group_id);
        $user = User::where('email', $request->email)->first();

        if(!$group) {
            return response()->json(['message' => 'Group not found'], 404);
        }

        if(!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Añadir el usuario al grupo
        if (!$group->users()->find($user->id)) {
            $group->users()->attach($user->id);

            // Emitir un evento para notificar que un nuevo miembro ha sido añadido
            broadcast(new ChatGroupCreated($group, [$user->id]));

            return response()->json(['message' => 'Member added successfully', 'group' => $group], 201);
        } else {
            return response()->json(['message' => 'User already in the group'], 400);
        }
    }

    public function leaveGroup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group_id' => 'required|integer|exists:chat_groups,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Obtener el grupo usando el group_id
        $group = ChatGroup::find($request->group_id);

        // Verificar si el grupo es grupal
        if (!$group->is_group) {
            return response()->json(['message' => 'Not a group chat'], 400);
        }

        // Verificar si el usuario autenticado es miembro del grupo
        if (!$group->users()->find($request->user()->id)) {
            return response()->json(['message' => 'User not a member of the group'], 404);
        }

        // Remover al usuario del grupo
        $group->users()->detach($request->user()->id);

        return response()->json(['message' => 'Successfully left the group']);
    }

}
