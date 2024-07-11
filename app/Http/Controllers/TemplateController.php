<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSurveyRequest;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreSurveyRequest $request)
    {
        // Validate the incoming request using StoreSurveyRequest rules
        $validatedData = $request->validated();

        // Handle file upload
        if ($request->hasFile('image')) {
            $fileName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $fileName);
            $validatedData['image'] = $fileName;
        }

        $respondentGroupsData = $validatedData['respondent_groups'] ?? [];
        unset($validatedData['respondent_groups']);

        $questionsData = $validatedData['questions'] ?? [];
        unset($validatedData['questions']);

        $informationFieldsData = $validatedData['information_fields'] ?? [];
        unset($validatedData['information_fields']); // Remove from main data

        // Create a new template
        $template = new Template();
        $template->title = $validatedData['title'];
        $template->description = $validatedData['description'] ?? '';
        $template->data = json_encode($validatedData);
        $template->save();

        // Optionally, you can store additional data like respondent groups, questions, and information fields if needed
        // For simplicity, they are included in the data field

        return response()->json($template, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function destroy(string $id)
    {
        //
    }
}
