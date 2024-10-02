<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        return Template::where('user_id', $user->id)->paginate(5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'template' => 'required|json',

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $template = new Template();
        $template->name = $request->name;
        $template->user_id = $request->user_id;
        $template->template = $request->template;

        $template->save();

        // Optionally, you can store additional data like respondent groups, questions, and information fields if needed
        // For simplicity, they are included in the data field

        return response()->json($template, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,string $id)
    {
        $user = $request->user();
        $template = Template::where('id',$id)->firstOrFail();
        if ($user->id !== $template->user_id) {
            return abort(403, 'You do not own this survey.');
        }

        return $template;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,string $id)
    {
        //
        $user = $request->user();
        $template = Template::where('id',$id)->firstOrFail();
        if ($user->id !== $template->user_id) {
            return abort(403, 'dili imohang template');
        }
        $template->delete();
    }
}
