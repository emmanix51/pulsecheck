<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Survey;
use App\Models\Question;
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
            // Retrieve question type based on question_id (assuming you have a Question model)
            $questionType = Question::where('id', $question_id)->value('question_type');

            if ($questionType === 'radio') {
                // Save answer in answer_scale (assuming answer is numeric)
                Answer::create([
                    'response_id' => $response->id,
                    'question_id' => $question_id,
                    'answer_scale' => $answer,
                ]);
            } elseif ($questionType === 'text') {
                // Save answer in answer_text
                Answer::create([
                    'response_id' => $response->id,
                    'question_id' => $question_id,
                    'answer_text' => $answer,
                ]);
            } else {
                // Handle other question types if needed
                // This example assumes 'radio' and 'text' are the main types
            }
        }

        return response()->json(['message' => 'Response submitted successfully']);
    }
    public function show($id)
    {
        $response = Response::with('answers')->findOrFail($id);
        return response()->json($response);
    }
}
