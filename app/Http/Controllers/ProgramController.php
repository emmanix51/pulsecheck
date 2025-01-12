<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{


    public function getPrograms(Request $request)
    {
        $collegeIds = $request->query('college_ids');
        if (!$collegeIds) {
            return response()->json([]);
        }

        $ids = explode(',', $collegeIds);
        $programs = Program::whereIn('college_id', $ids)->get();

        return response()->json($programs);
    }
    /**
     * Display a listing of the resource (all programs).
     */
    public function index(Request $request)
    {
        // Check if a college_id is passed and filter programs by that college
        $collegeId = $request->input('college_id');

        if ($collegeId) {
            // Get programs for the specified college
            $programs = Program::where('college_id', $collegeId)->get();
        } else {
            // If no college_id is provided, return all programs
            $programs = Program::all();
        }

        // Return the list of programs as JSON
        return response()->json($programs);
    }


    /**
     * Store a newly created program in the database.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'program_name' => 'required|string|max:255',
            'program_description' => 'required|string',
            'college_id' => 'required|exists:colleges,id',  // Ensure the college exists
        ]);

        // Create a new program record
        $program = Program::create([
            'program_name' => $request->program_name,
            'program_description' => $request->program_description,
            'college_id' => $request->college_id,
        ]);

        // Return a success response with the newly created program
        return response()->json($program, 201);
    }

    /**
     * Display the specified program.
     */
    public function show($id)
    {
        // Find the program by ID and include its associated college
        $program = Program::with('college')->findOrFail($id);

        // Return the program as JSON
        return response()->json($program);
    }

    /**
     * Update the specified program in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'program_name' => 'required|string|max:255',
            'program_description' => 'required|string',
            'college_id' => 'required|exists:colleges,id',  // Ensure the college exists
        ]);

        // Find the program by ID
        $program = Program::findOrFail($id);

        // Update the program's details
        $program->update([
            'program_name' => $request->program_name,
            'program_description' => $request->program_description,
            'college_id' => $request->college_id,
        ]);

        // Return the updated program as JSON
        return response()->json($program);
    }

    /**
     * Remove the specified program from storage.
     */
    public function destroy($id)
    {
        // Find the program by ID and delete it
        $program = Program::findOrFail($id);
        $program->delete();

        // Return a success message
        return response()->json(['message' => 'Program deleted successfully.']);
    }
}
