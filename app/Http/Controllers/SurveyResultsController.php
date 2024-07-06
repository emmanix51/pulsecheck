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
        // Enable query log for debugging
        \Illuminate\Support\Facades\DB::enableQueryLog();

        // Base query
        $query = Response::where('survey_id', $id);

        // Filter by respondent types and categories
        if ($request->has('respondent_types') || $request->has('respondent_categories')) {
            $respondentTypes = $request->get('respondent_types', []);
            $respondentCategories = $request->get('respondent_categories', []);

            $query->where(function ($q) use ($respondentTypes, $respondentCategories) {
                foreach ($respondentTypes as $type) {
                    $q->orWhere(function ($subQuery) use ($type, $respondentCategories) {
                        $subQuery->where('respondent_type', $type);
                        if (!empty($respondentCategories)) {
                            $subQuery->whereIn('respondent_category', $respondentCategories);
                        }
                    });
                }
            });
        }

        // Filter by information fields
        if ($request->has('information_field')) {
            $informationFields = $request->get('information_field');
            $query->where(function ($q) use ($informationFields) {
                foreach ($informationFields as $field => $values) {
                    $q->where(function ($subQuery) use ($field, $values) {
                        foreach ($values as $value) {
                            $subQuery->orWhereJsonContains('information_fields->' . $field, $value);
                        }
                    });
                }
            });
        }

        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
        }

        // Log the actual query for debugging
        // \Illuminate\Support\Facades\Log::info($query->toSql());
        // \Illuminate\Support\Facades\Log::info($query->getBindings());

        // Get the filtered responses
        $filteredResponses = $query->with('answers')->get();

        // Calculate the required statistics
        $totalResponses = $filteredResponses->count();
        \Illuminate\Support\Facades\Log::info($totalResponses);
        $totalAnswers = 0;
        foreach ($filteredResponses as $response) {
            $totalAnswers += $response->answers->count();
        }

        $totalAnswerScale = $filteredResponses->sum(function ($response) {
            return $response->answers->sum('answer_scale');
        });
        $averageAnswerScale = $totalResponses > 0 ? $totalAnswerScale / $totalAnswers : 0;

        return response()->json([
            'filteredResponses' => $filteredResponses,
            'totalResponses' => $totalResponses,
            'totalAnswerScale' => $totalAnswerScale,
            'averageAnswerScale' => $averageAnswerScale,
        ]);
    }
}
