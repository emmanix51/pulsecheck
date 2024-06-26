<?php

namespace App\Http\Controllers;

use App\Models\RespondentGroup;
use Illuminate\Http\Request;

class RespondentGroupController extends Controller
{
    // List all respondent groups
    public function index()
    {
        return RespondentGroup::all();
    }

    // Create a new respondent group
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'type' => 'required|in:student,faculty,staff,stakeholder',
            'category' => 'nullable|string',
        ]);

        $respondentGroup = RespondentGroup::create($validatedData);

        return response()->json($respondentGroup, 201);
    }

    // Delete a respondent group
    public function destroy(RespondentGroup $respondentGroup)
    {
        $respondentGroup->delete();

        return response()->json(null, 204);
    }
}
