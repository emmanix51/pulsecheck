<?php

namespace App\Http\Controllers;

use App\Models\College;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(5);

        return response()->json(['users' => $users], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idnum' => 'required|integer|unique:users,idnum',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,surveymaker,respondent',
            'respondent_type' => 'nullable|string|in:student,faculty,staff,stakeholder',
            'category' => 'nullable|string',
            'year_level' => 'nullable|string',
            'college_id' => 'nullable|exists:colleges,id',
            'program_id' => 'nullable|exists:programs,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $data = $request->all();
        if (empty($data['category'])) {
            if (!empty($data['college_id']) && !empty($data['program_id'])) {
                $college = College::find($data['college_id']);
                $program = Program::find($data['program_id']);

                if ($college && $program) {
                    $data['category'] = $college->college_name . ' - ' . $program->program_name;
                }
            } elseif (!empty($data['college_id'])) {
                $college = College::find($data['college_id']);
                if ($college) {
                    $data['category'] = $college->college_name;
                }
            } else {
                $data['category'] = null;
            }
        }

        $user = new User();
        $user->idnum = $data['idnum'];
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->role = $data['role'];
        $user->respondent_type = $data['respondent_type'];
        $user->category = $data['category'];
        $user->year_level = $data['year_level'];
        $user->college_id = $data['college_id'];
        $user->program_id = $data['program_id'];
        $user->save();

        return response()->json(['user' => $user], 201);
    }

    public function show(User $user, Request $request)
    {
        //
        $user = $request->user();
        return $user;
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'idnum' => 'required|integer|unique:users,idnum,' . $user->id . ',id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,surveymaker,respondent',
            'respondent_type' => 'nullable|string|in:student,faculty,staff,stakeholder',
            'category' => 'nullable|string',
            'year_level' => 'nullable|string',
            'college_id' => 'nullable|exists:colleges,id',
            'program_id' => 'nullable|exists:programs,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $data = $request->all();

        // Inline logic for generating the category
        if (empty($data['category'])) {
            if (!empty($data['college_id']) && !empty($data['program_id'])) {
                $college = College::find($data['college_id']);
                $program = Program::find($data['program_id']);

                if ($college && $program) {
                    $data['category'] = $college->college_name . ' - ' . $program->program_name;
                }
            } elseif (!empty($data['college_id'])) {
                $college = College::find($data['college_id']);
                if ($college) {
                    $data['category'] = $college->college_name;
                }
            } else {
                $data['category'] = null;
            }
        }

        // Update user details
        $user->idnum = $data['idnum'];
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->email = $data['email'];
        $user->role = $data['role'];
        $user->respondent_type = $data['respondent_type'];
        $user->category = $data['category'];
        $user->year_level = $data['year_level'];
        $user->college_id = $data['college_id'];
        $user->program_id = $data['program_id'];
        $user->save();

        return response()->json(['user' => $user], 200);
    }


    // public function update(Request $request, $id)
    // {
    //     $user = User::find($id);

    //     if (!$user) {
    //         return response()->json(['error' => 'User not found'], 404);
    //     }

    //     $validator = Validator::make($request->all(), [
    //         'idnum' => 'required|integer|unique:users,idnum,' . $user->id . ',id',
    //         'first_name' => 'required|string|max:255',
    //         'last_name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:users,email,' . $user->id,
    //         'role' => 'required|in:admin,surveymaker,respondent',
    //         'respondent_type' => 'nullable|string|in:student,faculty,staff,stakeholder',
    //         'category' => 'nullable|string',
    //         'year_level' => 'nullable|string',
    //         'college_id' => 'nullable|exists:colleges,id',
    //         'program_id' => 'nullable|exists:programs,id',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 400);
    //     }

    //     $user->idnum = $request->idnum;
    //     $user->first_name = $request->first_name;
    //     $user->last_name = $request->last_name;
    //     $user->email = $request->email;
    //     $user->role = $request->role;
    //     $user->respondent_type = $request->input('respondent_type');
    //     $user->category = $request->input('category');
    //     $user->year_level = $request->input('year_level');
    //     $user->college_id = $request->input('college_id');
    //     $user->program_id = $request->input('program_id');
    //     $user->save();

    //     return response()->json(['user' => $user], 200);
    // }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
