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
    public function getAllResponses(Request $request)
    {
        // Fetch responses with answers, ordered by created_at in descending order, and paginated with 6 results
        $responses = Response::orderBy('created_at', 'desc')
            ->with('answers', 'survey')  // Eager load the related answers
            ->paginate(6);

        // Calculate the average answer scale for each response
        $responses->getCollection()->transform(function ($response) {
            $totalScale = 0;
            $validAnswersCount = 0;

            // Loop through the answers to calculate the total scale and valid answer count
            foreach ($response->answers as $answer) {
                // Only count answers with a valid (non-null) answer_scale
                if ($answer->answer_scale !== null) {
                    $totalScale += $answer->answer_scale;
                    $validAnswersCount++;
                }
            }

            // Calculate the average scale (only if there are valid answers)
            $averageScale = $validAnswersCount > 0 ? $totalScale / $validAnswersCount : 0;

            // Add the average scale to the response object
            $response->average_scale = $averageScale;
            if ($averageScale >= 3.5) {
                $interpretation = "High Satisfaction";
            } else if ($averageScale >= 3) {
                $interpretation = "Neutral Satisfaction";
            } else {
                $interpretation = "Low Satisfaction";
            }

            $response->interpretation = $interpretation;

            return $response;
        });

        return $responses;
    }

    public function getAllResponsesToMySurveys(Request $request)
    {

        $userId = auth()->id();
        // Fetch responses with answers, ordered by created_at in descending order, and paginated with 6 results
        $responses = Response::whereHas('survey', function ($query) use ($userId) {
            // Only include responses from surveys where the user_id matches the current user
            $query->where('user_id', $userId);
        })
            ->orderBy('created_at', 'desc')
            ->with('answers', 'survey')  // Eager load the related answers and survey
            ->paginate(6);

        // Calculate the average answer scale for each response
        $responses->getCollection()->transform(function ($response) {
            $totalScale = 0;
            $validAnswersCount = 0;

            // Loop through the answers to calculate the total scale and valid answer count
            foreach ($response->answers as $answer) {
                // Only count answers with a valid (non-null) answer_scale
                if ($answer->answer_scale !== null) {
                    $totalScale += $answer->answer_scale;
                    $validAnswersCount++;
                }
            }

            // Calculate the average scale (only if there are valid answers)
            $averageScale = $validAnswersCount > 0 ? $totalScale / $validAnswersCount : 0;

            // Add the average scale to the response object
            $response->average_scale = $averageScale;
            if ($averageScale >= 3.5) {
                $interpretation = "High Satisfaction";
            } else if ($averageScale >= 3) {
                $interpretation = "Neutral Satisfaction";
            } else {
                $interpretation = "Low Satisfaction";
            }

            $response->interpretation = $interpretation;

            return $response;
        });

        return $responses;
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'survey_id' => 'required|exists:surveys,id',
            'respondent_id' => [
                Rule::requiredIf(function () use ($request) {

                    $surveyId = $request->input('survey_id');


                    $isPublic = Survey::where('id', $surveyId)->value('is_public');


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

            $questionType = Question::where('id', $question_id)->value('question_type');

            if ($questionType === 'radio') {

                Answer::create([
                    'response_id' => $response->id,
                    'question_id' => $question_id,
                    'answer_scale' => $answer,
                ]);
            } elseif ($questionType === 'text') {

                Answer::create([
                    'response_id' => $response->id,
                    'question_id' => $question_id,
                    'answer_text' => $answer,
                ]);
            } else {
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
