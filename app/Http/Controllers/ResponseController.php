<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Response;
use App\Models\Answer;

class ResponseController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'survey_id' => 'required|exists:surveys,id',
            'respondent_id' => 'required|exists:users,id',
            'respondent_type' => 'required|string',
            'respondent_category' => 'required|string',
            'information_fields' => 'required|array',
            'answers' => 'required|array',
        ]);

        $response = Response::create([
            'survey_id' => $validatedData['survey_id'],
            'respondent_id' => $validatedData['respondent_id'],
            'respondent_type' => $validatedData['respondent_type'],
            'respondent_category' => $validatedData['respondent_category'],
            'information_fields' => json_encode($validatedData['information_fields']),
        ]);

        foreach ($validatedData['answers'] as $question_id => $answer) {
            Answer::create([
                'response_id' => $response->id,
                'question_id' => $question_id,
                'answer_text' => $answer,
            ]);
        }

        return response()->json(['message' => 'Response submitted successfully']);
    }
}
