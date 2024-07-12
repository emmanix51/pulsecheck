<?php


// app/Http/Controllers/SurveyResultsController.php

namespace App\Http\Controllers;


use App\Models\Answer;
use App\Models\Survey;
use App\Models\Question;
use App\Models\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;

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
        $survey = Survey::with(['respondentGroups', 'informationFields', 'questions'])->findOrFail($id);
        $allResponses = $survey->responses;
        // \Illuminate\Support\Facades\Log::info($allResponses);
        return response()->json([
            'allResponses' => $allResponses,
            'survey' => $survey,
            'respondentGroups' => $survey->respondentGroups,
            'informationFields' => $survey->informationFields,
            'questions' => $survey->questions,
        ]);
    }

    public function getSurveyQuestions($id)
    {
        $survey = Survey::with('questions')->findOrFail($id);
        $surveyTitle = $survey->title;
        $questions = $survey->questions;
        return response()->json([
            'questions' => $questions,
            'surveyTitle' => $surveyTitle
        ]);
    }

    public function getSurveyQuestion($id)
    {
        $question = Question::where('id', $id)->first();
        $survey = $question->survey_id;
        $responses = Response::where('survey_id', $survey)->with('answers')->get();
        $responseAnswers = $responses->flatMap->answers->where('question_id', $id);
        $responseAnswersData = [];
        foreach ($responses as $response) {
            foreach ($response->answers as $answer) {
                Log::info($answer->question_id);

                if ($answer->question_id == $id) {
                    $responseAnswersData[] = [
                        'respondent_type' => $response->respondent_type,
                        'respondent_category' => $response->respondent_category,
                        'info_field' => json_decode($response->information_fields, true),
                        'answer_scale' => $answer->answer_scale,
                        'response_id' => $response->id
                        // 'question' => $question
                    ];
                }
            }
        }
        return response()->json([
            // 'question' => $question,
            // 'survey' => $survey,
            // 'answers' => $answers,
            // 'responses' => $responses,
            'responseAnswersData' => $responseAnswersData,
            // 'responseAnswer' => $responseAnswers

        ]);
    }

    public function getSurveyResponses($surveyId)
    {
        $responses = DB::table('responses')
            ->where('survey_id', $surveyId)
            ->join('answers', 'responses.id', '=', 'answers.response_id')
            ->select('answers.question_id', 'answers.answer')
            ->get();

        return $responses;
    }


    public function getResponse($id)
    {
        $response = Response::with(['answers', 'answers.question'])->findorFail($id);
        // Iterate through each answer in $response->answers
        foreach ($response->answers as $answer) {
            // Access the answer_scale property of each answer object
            $answerScale = $answer->answer_scale;

            // Access the question associated with the answer
            $question = $answer->question;

            // Build an array containing both answer scale and question details
            $answerDetails[] = [
                'answer_scale' => $answerScale,
                'question' => $question
            ];
        }
        \Illuminate\Support\Facades\Log::info($response);
        return response()->json([
            'response' => $response,
            'answerDetails' => $answerDetails,
        ]);
    }


    public function showDescriptiveData(Request $request, $id)
    {
        // Enable query log for debugging
        \Illuminate\Support\Facades\DB::enableQueryLog();

        // \Illuminate\Support\Facades\Log::info($request->query());
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
                    // Ensure $values is always an array for consistency
                    $values = is_array($values) ? $values : [$values];

                    foreach ($values as $value) {
                        // Log the field and value for debugging
                        // \Illuminate\Support\Facades\Log::info([$field => $value]);

                        // Use whereJsonContains for JSON fields
                        $q->orWhereJsonContains('information_fields->' . $field, $value);
                    }
                }
            });
        }

        if ($request->has('question_ids')) {
            $questionIds = $request->get('question_ids');

            // Join with the answers table to filter by question IDs
            $query->whereHas('answers', function ($q) use ($questionIds) {
                $q->whereIn('question_id', $questionIds);
            });
        }
        \Illuminate\Support\Facades\Log::info($query->toSql());

        // // Filter by date range
        // if ($request->has('start_date') && $request->has('end_date')) {
        //     $startDate = $request->get('start_date');
        //     $endDate = $request->get('end_date');
        //     if ($startDate && $endDate) {
        //         $query->whereBetween('created_at', [$startDate, $endDate]);
        //     }
        // }

        // Log the actual query for debugging
        // \Illuminate\Support\Facades\Log::info($query->toSql());
        // \Illuminate\Support\Facades\Log::info($query->getBindings());


        // Get the filtered responses
        $filteredResponses = $query->with(['answers' => function ($q) use ($request) {
            if ($request->has('question_ids')) {
                $questionIds = $request->get('question_ids');
                $q->whereIn('question_id', $questionIds);
            }
        }])->get();

        // Calculate the required statistics
        $totalResponses = $filteredResponses->count();
        $totalAnswers = 0;
        $totalAnswerScale = 0;

        foreach ($filteredResponses as $response) {
            foreach ($response->answers as $answer) {
                $totalAnswers++;
                $totalAnswerScale += $answer->answer_scale;
            }
        }

        $averageAnswerScale = $totalAnswers > 0 ? $totalAnswerScale / $totalAnswers : 0;

        $allResponses = Response::where('survey_id', $id);


        return response()->json([
            'allResponses' => $allResponses,
            'filteredResponses' => $filteredResponses,
            'totalResponses' => $totalResponses,
            'totalAnswers' => $totalAnswers,
            'averageAnswerScale' => $averageAnswerScale,
        ]);
    }

    public function exportAllResponses($id)
    {
        // Log the survey ID
        Log::info('Survey ID: ' . $id);

        // Fetch all responses for the given survey ID
        $responses = Response::where('survey_id', $id)->get();

        // Check if any responses are found
        if ($responses->isEmpty()) {
            Log::info('No responses found for survey ID: ' . $id);
            return response()->json('error bruh');
        } else {
            Log::info('Responses found: ' . $responses->count());
            Log::info('First response: ' . $responses->first()->toJson());
            $fileName = 'survey_responses_' . $id . '.csv';

            return (new FastExcel($responses))->download($fileName);
        }

        // Define the file name

    }

    // // FOR VISUALS

    public function surveyResultsVisual($id)
    {
        $survey = Survey::with(['respondentGroups', 'informationFields', 'questions'])->findOrFail($id);
        $respondent_types = [];
        foreach ($survey->respondentGroups as $respondentGroup) {
            $respondent_types[] = $respondentGroup->type;
        }
        $respondent_categories = [];

        foreach ($survey->respondentGroups as $respondentGroup) {
            // Split the comma-separated categories into an array
            $categories = explode(',', $respondentGroup->category);

            // Add each category to the $respondent_categories array
            foreach ($categories as $category) {
                $respondent_categories[] = trim($category); // Trim whitespace around each category if needed
            }
        }
        // // Iterate over respondent groups and collect respondent types
        // // $informationFields = $survey->information_fields->toJson();
        // foreach ($survey->information_fields as $information_field) {

        //     $info_label = $information_field->label   
        // }
        // Log::info('Respondent Types:', $informationFields);
        $responses = Response::where('survey_id', $id)->get();
        $responseDetails = [];


        foreach ($responses as $response) {
            foreach ($response->answers as $answer) {
                $responseDetails[] = [
                    'response_id' => $response->id,
                    'respondent_id' => $response->respondent_id,
                    'respondent_type' => $response->respondent_type,
                    'respondent_category' => $response->respondent_category,
                    'information_fields' => json_decode($response->information_fields, true),
                    'created_at' => $response->created_at,
                    'updated_at' => $response->updated_at,
                    'question_id' => $answer->question_id,
                    'question' => $answer->question->question,
                    'question_type' => $answer->question->question_type,
                    'answer_text' => $answer->answer_text,
                    'answer_scale' => $answer->answer_scale,
                ];
            }
        }

        return response()->json([
            'survey' => $survey,
            'responseDetails' => $responseDetails,
            'respondent_types' => $respondent_types,
            'respondent_categories' => $respondent_categories,
        ]);
    }

    // PCA
    public function performPCA($id)
    {
        $survey = Survey::with('responses.answers')->findOrFail($id);

        if ($survey->responses->isEmpty()) {
            return response()->json(['error' => 'No responses found for this survey'], 404);
        }

        // Prepare data for PCA
        $data = [];
        foreach ($survey->responses as $response) {
            foreach ($response->answers as $answer) {
                $data[] = [
                    'response_id' => $response->id,
                    'question_id' => $answer->question_id,
                    'answer_scale' => $answer->answer_scale,
                ];
            }
        }

        // Save data to a CSV file
        $fileName = 'pca_input.csv';
        $filePath = storage_path('app/' . $fileName);
        $file = fopen($filePath, 'w');
        fputcsv($file, ['response_id', 'question_id', 'answer_scale']);
        foreach ($data as $row) {
            fputcsv($file, $row);
        }
        fclose($file);

        // Call the Python script for PCA
        $pythonScriptPath = base_path('scripts/pca_analysis.py');
        $process = new Process(['python3', $pythonScriptPath, $filePath]);
        $process->run();

        // Check for errors
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        // Parse the PCA result
        $pcaResult = json_decode($process->getOutput(), true);

        // Clean up the CSV file
        Storage::delete($fileName);

        return response()->json([
            'surveyTitle' => $survey->title,
            'pcaData' => $pcaResult,
        ]);
    }

    // public function surveyResultsVisual($id)
    // {
    //     $survey = Survey::findOrFail($id);
    //     $responses = Response::where('survey_id', $id)->get();
    //     // $questions = $survey->questions;
    //     // Log::info('questions are :' . $questions);
    //     // Log::info('responses is :' . $responses->toJson());
    //     foreach ($responses as $response) {
    //         // Log::info('responses is :' . $response);
    //         foreach ($response->answers as $answer) {
    //             // Access the answer_scale property of each answer object
    //             $answerScale = $answer->answer_scale;

    //             // Access the question associated with the answer
    //             $question = $answer->question;

    //             // Build an array containing both answer scale and question details
    //             $answerDetails[] = [
    //                 'answer_scale' => $answerScale,
    //                 'question' => $question
    //             ];
    //             // Log::info('answers is :' . $answerScale);
    //             // Log::info('question is :' . $question);
    //         }
    //     }
    //     // $answers = $responses->answers;
    //     // Log::info('Survey is :' . $survey);
    //     return response()->json([
    //         // 'questions' => $questions,
    //         // 'answerDetails' => $answerDetails,
    //         'responses' => $responses
    //     ]);
    // }


    // This works
    // public function showDescriptiveData(Request $request, $id)
    // {
    //     $query = Survey::with(['questions', 'respondentGroups', 'informationFields', 'responses.answers'])
    //         ->findOrFail($id)
    //         ->responses();

    //     // Simplify to only check respondent_groups filter
    //     if ($request->has('respondent_groups') && count($request->respondent_groups) > 0) {
    //         $query->whereIn('respondent_type', $request->respondent_groups);
    //     }

    //     $filteredResponses = $query->get();

    //     $answers = $filteredResponses->flatMap(function ($response) {
    //         return $response->answers;
    //     });

    //     $totalResponses = $filteredResponses->count();
    //     $totalAnswerScale = $answers->sum('answer_scale');
    //     $totalAnswers = $answers->count();

    //     $averageAnswerScale = $totalAnswers > 0 ? $totalAnswerScale / $totalAnswers : 0;

    //     $queries = \Illuminate\Support\Facades\DB::getQueryLog();
    //     \Illuminate\Support\Facades\Log::info($queries);

    //     return response()->json([
    //         'filteredResponses' => $filteredResponses,
    //         'totalResponses' => $totalResponses,
    //         'totalAnswerScale' => $totalAnswerScale,
    //         'averageAnswerScale' => $averageAnswerScale,
    //     ]);
    // }
}
