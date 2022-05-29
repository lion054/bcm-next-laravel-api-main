<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Contact;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'blocked' => 'in:true,false',
            'limit' => 'integer|min:1',
            'page' => 'integer|min:0',
        ]);

        if ($validator->fails()) {
            return [
                'success' => FALSE,
                'messages' => $validator->messages(),
            ];
        }

        $search = $request->query('search');
        $blocked = $request->query('blocked');
        $limit = $request->query('limit');
        $page = $request->query('page');

        $query = User::query();
        if (!empty($search)) {
            $query->where(function ($subquery) use ($search) {
                $subquery->where('first_name', 'like', "%$search%");
                $subquery->orWhere('last_name', 'like', "%$search%");
            });
        }
        if (!empty($blocked)) {
            $blocked = $request->boolean('blocked');
            $query->where('blocked', $blocked ? '1' : '0');
        }
        if (!empty($limit)) {
            $limit = intval($limit);
            $query->take($limit);
            if (!empty($page)) {
                $page = intval($page);
                $query->skip($page * $limit);
            }
        }

        $result = $query->get()->toArray();
        return $result;
    }

    public function show($id)
    {
        $user = User::find($id);

        if ($user === null) {
            return response()->json([
                'success' => FALSE,
                'message' => 'Record not found',
            ], 404);
        }

        return $user;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:1',
            'last_name' => 'min:1',
            'blocked' => 'required|in:true,false',
        ]);

        if ($validator->fails()) {
            return [
                'success' => FALSE,
                'messages' => $validator->messages(),
            ];
        }

        $data = $request->input();
        $data['blocked'] = empty($data['blocked']) ? '0' : '1';

        $user = User::create($data);
        return $user;
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if ($user === null) {
            return response()->json([
                'success' => FALSE,
                'message' => 'Record not found',
            ], 404);
        }

        $validator = Validator::make($request->input(), [
            'first_name' => 'min:1',
            'last_name' => 'min:1',
            'blocked' => 'boolean',
        ]);

        if ($validator->fails()) {
            return [
                'success' => FALSE,
                'messages' => $validator->messages(),
            ];
        }

        $data = $request->input();
        if (isset($data['blocked'])) {
            $data['blocked'] = empty($data['blocked']) ? '0' : '1';
        }

        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        $user = User::find($id);

        if ($user === null) {
            return response()->json([
                'success' => FALSE,
                'message' => 'Record not found',
            ], 404);
        }

        $user->delete();
        return response()->json([], 204);
    }

    public function showContacts($id)
    {
        $user = User::find($id);

        if ($user === null) {
            return response()->json([
                'success' => FALSE,
                'message' => 'Record not found',
            ], 404);
        }

        $query = Contact::query();
        $query->where('user_id', $id);

        $result = $query->get()->toArray();
        return $result;
    }
}
