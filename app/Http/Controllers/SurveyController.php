<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Http\Requests\StoreSurveyRequest;
use App\Http\Requests\UpdateSurveyRequest;
use App\Http\Resources\SurveyResource;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        return SurveyResource::collection(Survey::where('user_id', $user->id)->paginate(5));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSurveyRequest $request)
    {
        //

        // Validate the incoming request using StoreSurveyRequest rules
        $validatedData = $request->validated();

        // Extract respondent groups data
        $respondentGroupsData = $validatedData['respondent_groups'] ?? [];
        unset($validatedData['respondent_groups']); // Remove from main data

        // Create a new survey instance
        $survey = new Survey($validatedData);

        // Save the survey
        $survey->save();

        // Attach respondent groups
        foreach ($respondentGroupsData as $groupData) {
            $survey->respondentGroups()->create([
                'respondent_type' => $groupData['respondent_type'],
                'categories' => isset($groupData['categories']) ? explode(',', $groupData['categories']) : [],
            ]);
        }

        // Return a response indicating success
        return new SurveyResource($survey);
    }

    /**
     * Display the specified resource.
     */
    public function show(Survey $survey, Request $request)
    {
        //
        $user = $request->user();
        if ($user->id !== $survey->user_id) {
            return abort(403, 'dili ikaw tagiya');
        }
        return new SurveyResource($survey);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSurveyRequest $request, Survey $survey)
    {
        //
        $survey->update($request->validated());
        return new SurveyResource($survey);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Survey $survey, Request $request)
    {
        //
        $user = $request->user();
        if ($user->id !== $survey->user_id) {
            return abort(403, 'dili imohang survey');
        }

        $survey->delete();

        return response('', 204);
    }
}
