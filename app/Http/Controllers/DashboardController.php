<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Survey;
use App\Models\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    //
    public function getDashboard($id)
    {
        $user = User::findOrFail($id);
        Log::info($user->role);

        if ($user->role === 'admin') {
            $surveys = Survey::all();
            $users = User::all()->count();
            $data = [$users, $surveys];
        } elseif ($user->role === 'surveymaker') {
            $surveys = Survey::where('user_id', $id)->get();
            $surveyResponses = [];
            foreach ($surveys as $survey) {
                $surveyResponses[] = $survey->responses;
            }
            $data = [$surveys, $surveyResponses];
        } else {
            $responses = Response::where('respondent_id', $id)->get();
            $surveysAnswered = [];
            $surveys = Survey::all();
            foreach ($surveys as $survey) {
                foreach ($responses as $response) {
                    if ($survey->id == $response->survey_id) {
                        $surveysAnswered[] = $survey;
                    }
                }
            }
            $data = [$responses, $surveysAnswered];


            // return response()->json([
            // ]);
        }
        return response()->json([
            'role' => $user->role,
            'user_name' => $user->first_name,
            'data' => $data
        ]);
    }


    public function getPublicSurveys()
    {
        $currentDateTime = now();
        // $publicSurveys = Survey::where('is_public', 1)->where('expire_date', '>=', $currentDateTime)->get();
        $publicSurveys = Survey::where('is_public', 1)->paginate(1);
        return response()->json([
            'public_surveys' => $publicSurveys
        ]);
    }

    public function getRespondentSurveys($id)
    {
        Log::info($id);


        // Fetch the respondent details
        $respondent = User::where('id', $id)->firstOrFail();

        // Retrieve surveys that match respondent's type or category
        $surveys = Survey::whereHas('respondentGroups', function ($query) use ($respondent) {
            $query->where('type', $respondent->respondent_type)
                ->orWhere('category', $respondent->category);
        })
            ->with(['respondentGroups'])
            ->get();

        // Fetch respondent's responses
        $respondentResponses = Response::where('respondent_id', $id)->pluck('survey_id')->toArray();

        // Iterate through each survey to check if respondent has answered
        foreach ($surveys as $survey) {
            $survey->responded = in_array($survey->id, $respondentResponses);
        }

        // Return JSON response with surveys including responded status
        return response()->json($surveys);
    }
    public function getRespondentResponses($id)
    {
        // Log the $id for debugging
        Log::info($id);

        // Fetch the respondent details
        $respondent = User::where('id', $id)->firstOrFail();

        // Retrieve responses with surveys and answers
        $responses = Response::with(['survey' => function ($query) {
            $query->select('id', 'title'); // Select only 'id' and 'title' columns from 'surveys' table
        }])
            ->where('respondent_id', $id)
            ->get();

        // Calculate average answer_scale per response and add it to each response object
        $responses->each(function ($response) {
            $total = 0;
            $count = $response->answers->count();

            foreach ($response->answers as $answer) {
                $total += $answer->answer_scale;
            }

            $response->average_answer_scale = $count > 0 ? $total / $count : 0;
        });

        // Return JSON response with surveys including responded status and average_answer_scale
        return response()->json($responses);
    }
}
