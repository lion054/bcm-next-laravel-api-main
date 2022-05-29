<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Contact;
use App\Models\User;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'in:Home,Office',
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
        $type = $request->query('type');
        $limit = $request->query('limit');
        $page = $request->query('page');

        $query = Contact::query();
        if (!empty($search)) {
            $query->where('phone', 'like', "%$search%");
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
        $contact = Contact::find($id);

        if ($contact === null) {
            return response()->json([
                'success' => FALSE,
                'message' => 'Record not found',
            ], 404);
        }

        return $contact;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|digits:10',
            'type' => 'required|in:Home,Office',
            'user_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return [
                'success' => FALSE,
                'messages' => $validator->messages(),
            ];
        }

        $data = $request->input();
        $user = User::find($data['user_id']);

        if ($user === null) {
            return response()->json([
                'success' => FALSE,
                'messages' => [
                    'user_id' => 'Record not found',
                ],
            ], 404);
        }

        $contact = Contact::create($data);
        return $contact;
    }

    public function update(Request $request, $id)
    {
        $contact = Contact::find($id);

        if ($contact === null) {
            return response()->json([
                'success' => FALSE,
                'message' => 'Record not found',
            ], 404);
        }

        $validator = Validator::make($request->input(), [
            'phone' => 'digits:10',
            'type' => 'in:Home,Office',
            'user_id' => 'integer',
        ]);

        if ($validator->fails()) {
            return [
                'success' => FALSE,
                'messages' => $validator->messages(),
            ];
        }

        $data = $request->input();
        if (isset($data['user_id'])) {
            $user = User::find($data['user_id']);

            if ($user === null) {
                return response()->json([
                    'success' => FALSE,
                    'messages' => [
                        'user_id' => 'Record not found',
                    ],
                ], 404);
            }
        }

        $contact->update($data);
        return $contact;
    }

    public function delete($id)
    {
        $contact = Contact::find($id);

        if ($contact === null) {
            return response()->json([
                'success' => FALSE,
                'message' => 'Record not found',
            ], 404);
        }

        $contact->delete();
        return response()->json([], 204);
    }
}
