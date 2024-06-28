<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\RespondentGroup;
use App\Http\Resources\SurveyResource;
use App\Http\Requests\StoreSurveyRequest;
use App\Http\Requests\UpdateSurveyRequest;

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

        // Handle file upload
        if ($request->hasFile('image')) {
            $fileName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $fileName);
            $validatedData['image'] = $fileName;
        }

        $respondentGroupsData = $validatedData['respondent_groups'] ?? [];
        unset($validatedData['respondent_groups']);

        $questionsData = $validatedData['questions'] ?? [];
        unset($validatedData['questions']);

        // Extract information fields data
        $informationFieldsData = $validatedData['information_fields'] ?? [];
        unset($validatedData['information_fields']); // Remove from main data

        $survey = new Survey($validatedData);
        $survey->save();

        // Attach respondent groups
        foreach ($respondentGroupsData as $groupData) {
            $respondentGroup = RespondentGroup::firstOrCreate([
                'type' => $groupData['type'],
                'category' => $groupData['category'],
            ]);

            $survey->respondentGroups()->attach($respondentGroup);
        }

        foreach ($questionsData as $questionData) {
            if (is_array($questionData['data'])) {
                $questionData['data'] = json_encode($questionData['data']); // Convert array to JSON string for storage
            }
            $question = new Question($questionData);
            $survey->questions()->save($question);
        }

        // foreach ($questionsData as $questionData) {
        //     $question = new Question([
        //         'question_type' => $questionData['question_type'],
        //         'question' => $questionData['question'],
        //         'description' => $questionData['description'],
        //         'data' => $questionData['data'],  // Convert array to JSON string for storage
        //     ]);
        //     $survey->questions()->save($question);
        // }
        // foreach ($questionsData as $questionData) {
        //     $questionData['data'] = json_encode($questionData['data']);  // Convert array to JSON string for storage
        //     $question = new Question($questionData);
        //     $survey->questions()->save($question);
        // }
        // Attach information fields
        foreach ($informationFieldsData as $fieldData) {
            $survey->informationFields()->create($fieldData);
        }

        $survey->load(['respondentGroups', 'questions', 'informationFields']);  // Load relationships
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
            return abort(403, 'You do not own this survey.');
        }

        $survey->load(['respondentGroups', 'questions', 'informationFields']);  // Load relationships
        return new SurveyResource($survey);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSurveyRequest $request, Survey $survey)
    {
        //
        // Validate the incoming request using UpdateSurveyRequest rules
        $validatedData = $request->validated();

        // Handle file upload
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($survey->image) {
                unlink(public_path('images') . '/' . $survey->image);
            }

            $fileName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $fileName);
            $validatedData['image'] = $fileName;
        }


        // Extract respondent groups data
        $respondentGroupsData = $validatedData['respondent_groups'] ?? [];
        unset($validatedData['respondent_groups']); // Remove from main data

        // Extract questions data
        $questionsData = $validatedData['questions'] ?? [];
        unset($validatedData['questions']); // Remove from main data

        // Extract information fields data
        $informationFieldsData = $validatedData['information_fields'] ?? [];
        unset($validatedData['information_fields']); // Remove from main data

        // Update the survey instance
        $survey->update($validatedData);

        // Update respondent groups
        if (!empty($respondentGroupsData)) {
            // Get the current respondent group IDs
            $currentGroupIds = $survey->respondentGroups()->pluck('respondent_group_id')->toArray();

            // Prepare new group IDs and data
            $newGroupIds = [];
            foreach ($respondentGroupsData as $groupData) {
                $respondentGroup = RespondentGroup::firstOrCreate([
                    'type' => $groupData['type'],
                    'category' => $groupData['category'],
                ]);
                $newGroupIds[] = $respondentGroup->id;

                // If the group is not attached, attach it
                if (!in_array($respondentGroup->id, $currentGroupIds)) {
                    $survey->respondentGroups()->attach($respondentGroup);
                }
            }

            // Detach groups that are no longer in the new list
            $groupsToDetach = array_diff($currentGroupIds, $newGroupIds);
            if (!empty($groupsToDetach)) {
                $survey->respondentGroups()->detach($groupsToDetach);
            }
        }


        // Update questions
        if (!empty($questionsData)) {
            $survey->questions()->delete();
            foreach ($questionsData as $questionData) {
                $questionData['data'] = json_encode($questionData['data']);
                $survey->questions()->create($questionData);
            }
        }

        // Update information fields
        if (!empty($informationFieldsData)) {
            $survey->informationFields()->delete();
            foreach ($informationFieldsData as $fieldData) {
                $survey->informationFields()->create($fieldData);
            }
        }

        // Return a response indicating success
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

        // Delete related questions, respondent groups, and information fields
        $survey->questions()->delete();
        $survey->respondentGroups()->detach();
        $survey->informationFields()->delete();

        // Delete the survey itself
        $survey->delete();

        return response()->json(['message' => 'Survey deleted successfully']);
    }
}
