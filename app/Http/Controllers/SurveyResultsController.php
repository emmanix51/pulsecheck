<?php


// app/Http/Controllers/SurveyResultsController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\Response;
use App\Models\Answer;

class SurveyResultsController extends Controller
{
    // public function getDescriptiveAnalysis(Request $request, $id)
    // {
    //     $survey = Survey::with(['respondentGroups', 'questions'])->findOrFail($id);
    //     $query = Response::where('survey_id', $id);

    //     // Apply filters if provided
    //     if ($request->has('respondent_type')) {
    //         $query->where('respondent_type', $request->input('respondent_type'));
    //     }
    //     if ($request->has('information_fields')) {
    //         foreach ($request->input('information_fields') as $key => $value) {
    //             $query->whereJsonContains('information_fields->' . $key, $value);
    //         }
    //     }

    //     $responses = $query->get();

    //     $totalResponses = $responses->count();
    //     $overallMean = $responses->flatMap(function ($response) {
    //         return $response->answers->pluck('score'); // Assuming 'score' is the field for likert scale responses in answers
    //     })->avg();

    //     return response()->json([
    //         'survey' => $survey,
    //         'total_responses' => $totalResponses,
    //         'overall_mean' => $overallMean,
    //     ]);
    // }

    public function show(Request $request, $id)
    {
        $survey = Survey::where('id', $id)
            ->with(['questions', 'respondentGroups', 'informationFields', 'responses.answers'])
            ->first();

        if (!$survey) {
            return response()->json(['error' => 'Survey not found'], 404);
        }

        $totalResponses = $survey->responses->count();

        $totalAnswerScale = $survey->responses->flatMap(function ($response) {
            return $response->answers;
        })->sum('answer_scale');

        $totalAnswers = $survey->responses->flatMap(function ($response) {
            return $response->answers;
        })->count();

        $averageAnswerScale = $totalAnswers > 0 ? $totalAnswerScale / $totalAnswers : 0;

        return response()->json([
            'survey' => $survey, 'totalResponses' => $totalResponses,
            'totalAnswerScale' => $totalAnswerScale,
            'averageAnswerScale' => $averageAnswerScale,
        ]);
    }

    public function getSurveyDetails($id)
    {
        $survey = Survey::with(['respondentGroups', 'informationFields'])->findOrFail($id);

        return response()->json([
            'survey' => $survey,
            'respondentGroups' => $survey->respondentGroups,
            'informationFields' => $survey->informationFields,
        ]);
    }


    public function showDescriptiveData(Request $request, $id)
    {
        $query = Survey::with(['questions', 'respondentGroups', 'informationFields', 'responses.answers'])
            ->findOrFail($id)
            ->responses();

        if ($request->has('respondent_groups') && count($request->respondent_groups) > 0) {
            $query->whereIn('respondent_type', $request->respondent_groups);
        }

        if ($request->has('respondent_categories') && count($request->respondent_categories) > 0) {
            $query->whereIn('respondent_category', $request->respondent_categories);
        }

        if ($request->has('information_field')) {
            foreach ($request->information_field as $key => $values) {
                if (is_array($values)) {
                    foreach ($values as $value) {
                        $query->whereJsonContains('information_fields->' . $key, $value);
                    }
                } else {
                    $query->whereJsonContains('information_fields->' . $key, $values);
                }
            }
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $filteredResponses = $query->get();

        $totalResponses = $filteredResponses->count();
        $totalAnswerScale = $filteredResponses->flatMap(function ($response) {
            return $response->answers;
        })->sum('answer_scale');

        $totalAnswers = $filteredResponses->flatMap(function ($response) {
            return $response->answers;
        })->count();

        $averageAnswerScale = $totalAnswers > 0 ? $totalAnswerScale / $totalAnswers : 0;

        return response()->json([
            'filteredResponses' => $filteredResponses,
            'totalResponses' => $totalResponses,
            'totalAnswerScale' => $totalAnswerScale,
            'averageAnswerScale' => $averageAnswerScale,
        ]);
    }
}
