<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Survey;
use App\Models\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ResponseController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'survey_id' => 'required|exists:surveys,id',
            'respondent_id' => [
                Rule::requiredIf(function () use ($request) {
                    // Retrieve the survey_id from the request
                    $surveyId = $request->input('survey_id');

                    // Retrieve the is_public value from the surveys table
                    $isPublic = Survey::where('id', $surveyId)->value('is_public');

                    // Return true if is_public is false (meaning respondent_id is required)
                    return !$isPublic;
                }),
                'exists:users,id',
            ],
            'respondent_type' => 'required|string',
            'respondent_category' => 'required|string',
            'information_fields' => 'required|array',
            'answers' => 'required|array',
        ]);

        $responseData = [
            'survey_id' => $validatedData['survey_id'],
            'respondent_type' => $validatedData['respondent_type'],
            'respondent_category' => $validatedData['respondent_category'],
            'information_fields' => json_encode($validatedData['information_fields']),
        ];

        // Only add respondent_id if it is set
        if (isset($validatedData['respondent_id'])) {
            $responseData['respondent_id'] = $validatedData['respondent_id'];
        }

        $response = Response::create($responseData);

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
