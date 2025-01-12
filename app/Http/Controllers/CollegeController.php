<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\College;
use Illuminate\Http\Request;

class CollegeController extends Controller
{
    /**
     * Display a listing of the colleges.
     */
    public function index()
    {
        // Get all colleges from the database
        $colleges = College::all();
        
        // Return the list of colleges as JSON
        return response()->json($colleges);
    }

    /**
     * Store a newly created college in the database.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'college_name' => 'required|string|max:255',
            'short_name' => 'required|string|max:50',
        ]);

        // Create a new college record
        $college = College::create([
            'college_name' => $request->college_name,
            'short_name' => $request->short_name,
        ]);

        // Return a success response with the newly created college
        return response()->json($college, 201);
    }

    /**
     * Display the specified college.
     */
    public function show($id)
    {
        // Find the college by ID
        $college = College::findOrFail($id);

        // Return the college as JSON
        return response()->json($college);
    }

    /**
     * Update the specified college in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'college_name' => 'required|string|max:255',
            'short_name' => 'required|string|max:50',
        ]);

        // Find the college by ID
        $college = College::findOrFail($id);

        // Update the college's information
        $college->update([
            'college_name' => $request->college_name,
            'short_name' => $request->short_name,
        ]);

        // Return the updated college as JSON
        return response()->json($college);
    }

    /**
     * Remove the specified college from storage.
     */
    public function destroy($id)
    {
        // Find the college by ID and delete it
        $college = College::findOrFail($id);
        $college->delete();

        // Return a success message
        return response()->json(['message' => 'College deleted successfully.']);
    }
}
