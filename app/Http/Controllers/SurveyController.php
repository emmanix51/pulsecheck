<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Survey;
use App\Models\Question;
use App\Models\QuestionGroup;
use App\Models\QuestionSection;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Mail\SurveyDistributed;
use App\Models\RespondentGroup;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\SurveyResource;
use App\Http\Requests\StoreSurveyRequest;
use App\Notifications\SurveyNotification;
use App\Http\Requests\UpdateSurveyRequest;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        return SurveyResource::collection(Survey::where('user_id', $user->id)->paginate(12));
    }
    public function getSurveys(Request $request)
    {
        // $user = $request->user();
        return SurveyResource::collection(Survey::orderBy('created_at', 'desc')->paginate(6));
    }

    public function getOwnSurveys(Request $request)
    {
        $user = $request->user();
        return SurveyResource::collection(Survey::where('user_id', $user->id)->paginate(6));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSurveyRequest $request)
    {
        // Validate the incoming request using StoreSurveyRequest rules
        $validatedData = $request->validated();

        $respondentGroupsData = $validatedData['respondent_groups'] ?? [];
        unset($validatedData['respondent_groups']);

        // $questionsData = $validatedData['questions'] ?? [];
        // unset($validatedData['questions']);


        $informationFieldsData = $validatedData['information_fields'] ?? [];
        unset($validatedData['information_fields']);


        Log::info('Validated Data:', $validatedData);
        $survey = new Survey($validatedData);
        $survey->save();


        foreach ($respondentGroupsData as $groupData) {
            $respondentGroup = RespondentGroup::firstOrCreate([
                'type' => $groupData['type'],
                'category' => $groupData['category'],
                'college_ids' => isset($groupData['college_ids']) ? json_encode($groupData['college_ids']) : null,  // Encode as JSON if it's an array
                'program_ids' => isset($groupData['program_ids']) ? json_encode($groupData['program_ids']) : null,
            ]);

            $survey->respondentGroups()->attach($respondentGroup);
        }


        $questionSectionsData = $validatedData['question_sections'] ?? [];
        // $questionGroupsData = $validatedData['question_groups'] ?? [];
        foreach ($questionSectionsData as $sectionData) {
            $questionSection = QuestionSection::create([
                'survey_id' => $survey->id,
                'section_number' => $sectionData['section_number'],
                'section_label' => $sectionData['section_label'],
                'section_instruction' => $sectionData['section_instruction'],
            ]);
            foreach ($sectionData['question_groups'] as $groupData) {
                $questionGroup = QuestionGroup::create([
                    'survey_id' => $survey->id,
                    'question_section_id' => $questionSection->id,
                    'format' => $groupData['format'],
                    'number' => $groupData['number'],
                    'label' => $groupData['label'],
                    'group_question' => $groupData['group_question'],
                    'question_instruction' => $groupData['question_instruction'],
                    'category_label' => $groupData['category_label'] ?? "",
                    'question_categories' => json_encode($groupData['question_categories'] ?? []),
                ]);

                foreach ($groupData['questions'] ?? [] as $questionData) {
                    if (is_array($questionData['data'])) {
                        $questionData['data'] = json_encode($questionData['data']);
                    }
                    $questionData['question_group_id'] = $questionGroup->id;
                    $question = new Question($questionData);
                    $survey->questions()->save($question);
                    $questionGroup->questions()->save($question);
                }
            }
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


        $survey->load(['respondentGroups', 'questionSections.question_groups', 'questions', 'informationFields']);  // Load relationships
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

        $survey->load(['respondentGroups', 'questionGroups', 'questionGroups.questions', 'informationFields']);  // Load relationships
        Log::info($survey);
        return new SurveyResource($survey);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSurveyRequest $request, Survey $survey)
    {
        $validatedData = $request->validated();

        // Define non-affecting fields
        $nonAffectingFields = ['title', 'status', 'is_public', 'instruction', 'description', 'expire_date'];

        // Extract non-affecting fields from validated data
        $nonAffectingChanges = array_intersect_key($validatedData, array_flip($nonAffectingFields));

        // Extract nested data
        $respondentGroupsData = $validatedData['respondent_groups'] ?? [];
        $questionSectionsData = $validatedData['question_sections'] ?? [];
        $informationFieldsData = $validatedData['information_fields'] ?? [];
        unset($validatedData['respondent_groups'], $validatedData['question_sections'], $validatedData['information_fields']);

        // Determine if there are any structural changes
        $affectingChangesExist = !empty(array_diff_key($validatedData, array_flip($nonAffectingFields)))
            || !empty($respondentGroupsData)
            || !empty($questionSectionsData)
            || !empty($informationFieldsData);

        // Delete responses and answers if there are structural changes
        if ($affectingChangesExist) {
            $survey->responses()->each(function ($response) {
                $response->answers()->delete();
                $response->delete();
            });
        }

        // Update non-affecting fields
        $survey->update($nonAffectingChanges);

        // Update respondent groups
        if (!empty($respondentGroupsData)) {
            $currentGroupIds = $survey->respondentGroups()->pluck('respondent_group_id')->toArray();
            $newGroupIds = [];

            foreach ($respondentGroupsData as $groupData) {
                $respondentGroup = RespondentGroup::firstOrCreate([
                    'type' => $groupData['type'],
                    'category' => $groupData['category'],
                    'college_ids' => isset($groupData['college_ids']) ? json_encode($groupData['college_ids']) : null,
                    'program_ids' => isset($groupData['program_ids']) ? json_encode($groupData['program_ids']) : null,
                ]);
                $newGroupIds[] = $respondentGroup->id;

                if (!in_array($respondentGroup->id, $currentGroupIds)) {
                    $survey->respondentGroups()->attach($respondentGroup);
                }
            }

            $groupsToDetach = array_diff($currentGroupIds, $newGroupIds);
            if (!empty($groupsToDetach)) {
                $survey->respondentGroups()->detach($groupsToDetach);
            }
        }

        // Update question sections
        if (!empty($questionSectionsData)) {
            $currentSectionIds = $survey->questionSections()->pluck('id')->toArray();
            $newSectionIds = [];

            foreach ($questionSectionsData as $sectionData) {
                $questionSection = QuestionSection::updateOrCreate(
                    [
                        'survey_id' => $survey->id,
                        'section_number' => $sectionData['section_number'],
                    ],
                    [
                        'section_label' => $sectionData['section_label'],
                        'section_instruction' => $sectionData['section_instruction'],
                    ]
                );
                $newSectionIds[] = $questionSection->id;

                // Update question groups
                if (isset($sectionData['question_groups'])) {
                    $currentGroupIds = $questionSection->question_groups()->pluck('id')->toArray();
                    $newGroupIds = [];

                    foreach ($sectionData['question_groups'] as $groupData) {
                        $questionGroup = QuestionGroup::updateOrCreate(
                            [
                                'survey_id' => $survey->id,
                                'question_section_id' => $questionSection->id,
                                'number' => $groupData['number'],
                            ],
                            [
                                'format' => $groupData['format'],
                                'label' => $groupData['label'],
                                'group_question' => $groupData['group_question'],
                                'category_label' => $groupData['category_label'],
                                'question_instruction' => $groupData['question_instruction'],
                                'question_categories' => json_encode($groupData['question_categories'] ?? []),
                            ]
                        );
                        $newGroupIds[] = $questionGroup->id;

                        // Update questions
                        if (isset($groupData['questions'])) {
                            $currentQuestionIds = $questionGroup->questions()->pluck('id')->toArray();
                            $newQuestionIds = [];

                            foreach ($groupData['questions'] as $questionData) {
                                $questionData['data'] = is_array($questionData['data']) ? json_encode($questionData['data']) : $questionData['data'];
                                $question = Question::updateOrCreate(
                                    [
                                        'id' => $questionData['id'] ?? null,
                                        'survey_id' => $survey->id,
                                        'question_group_id' => $questionGroup->id,
                                    ],
                                    $questionData
                                );
                                $newQuestionIds[] = $question->id;
                            }

                            $questionsToDelete = array_diff($currentQuestionIds, $newQuestionIds);
                            if (!empty($questionsToDelete)) {
                                Question::whereIn('id', $questionsToDelete)->delete();
                            }
                        }
                    }

                    $groupsToDelete = array_diff($currentGroupIds, $newGroupIds);
                    if (!empty($groupsToDelete)) {
                        QuestionGroup::whereIn('id', $groupsToDelete)->delete();
                    }
                }
            }

            $sectionsToDelete = array_diff($currentSectionIds, $newSectionIds);
            if (!empty($sectionsToDelete)) {
                QuestionSection::whereIn('id', $sectionsToDelete)->delete();
            }
        }

        // Update information fields
        if (!empty($informationFieldsData)) {
            $survey->informationFields()->delete();
            foreach ($informationFieldsData as $fieldData) {
                $survey->informationFields()->create($fieldData);
            }
        }

        return new SurveyResource($survey);
    }

    // public function update(UpdateSurveyRequest $request, Survey $survey)
    // {

    //     $validatedData = $request->validated();

    //     // Define non-affecting fields
    //     $nonAffectingFields = ['title', 'status', 'is_public', 'instruction', 'description', 'expire_date'];

    //     // Extract non-affecting fields from validated data
    //     $nonAffectingChanges = array_intersect_key($validatedData, array_flip($nonAffectingFields));


    //     $respondentGroupsData = $validatedData['respondent_groups'] ?? [];
    //     unset($validatedData['respondent_groups']); // Remove from main data

    //     // $questionGroupsData = $validatedData['question_groups'] ?? [];
    //     // unset($validatedData['question_groups']); // Remove from main data


    //     // $questionsData = $validatedData['questions'] ?? [];
    //     // unset($validatedData['questions']); // Remove from main data


    //     $informationFieldsData = $validatedData['information_fields'] ?? [];
    //     unset($validatedData['information_fields']); // Remove from main data


    //     // Determine if there are any structural changes
    //     $affectingChangesExist = !empty(array_diff_key($validatedData, array_flip($nonAffectingFields)))
    //         || !empty($respondentGroupsData)
    //         || !empty($questionSectionsData)
    //         || !empty($informationFieldsData);


    //     // Check if only non-affecting fields were updated
    //     // Delete responses and answers if there are structural changes
    //     if ($affectingChangesExist) {
    //         $survey->responses()->each(function ($response) {
    //             $response->answers()->delete();
    //             $response->delete();
    //         });
    //     }

    //     // Update non-affecting fields
    //     $survey->update($nonAffectingChanges);


    //     // Update respondent groups
    //     if (!empty($respondentGroupsData)) {
    //         // Get the current respondent group IDs
    //         $currentGroupIds = $survey->respondentGroups()->pluck('respondent_group_id')->toArray();

    //         // Prepare new group IDs and data
    //         $newGroupIds = [];
    //         foreach ($respondentGroupsData as $groupData) {
    //             $respondentGroup = RespondentGroup::firstOrCreate([
    //                 'type' => $groupData['type'],
    //                 'category' => $groupData['category'],
    //                 'college_ids' => isset($groupData['college_ids']) ? json_encode($groupData['college_ids']) : null,  // Encode as JSON if it's an array
    //                 'program_ids' => isset($groupData['program_ids']) ? json_encode($groupData['program_ids']) : null,
    //             ]);
    //             $newGroupIds[] = $respondentGroup->id;


    //             if (!in_array($respondentGroup->id, $currentGroupIds)) {
    //                 $survey->respondentGroups()->attach($respondentGroup);
    //             }
    //         }


    //         $groupsToDetach = array_diff($currentGroupIds, $newGroupIds);
    //         if (!empty($groupsToDetach)) {
    //             $survey->respondentGroups()->detach($groupsToDetach);
    //         }
    //     }

    //     // Question sections update logic
    //     $questionSectionsData = $validatedData['question_sections'] ?? [];
    //     if (!empty($questionSectionsData)) {
    //         $currentSectionIds = $survey->questionSections()->pluck('id')->toArray();
    //         $newSectionIds = [];

    //         foreach ($questionSectionsData as $sectionData) {
    //             // Update or create question sections
    //             $questionSection = QuestionSection::updateOrCreate(
    //                 [
    //                     'survey_id' => $survey->id,
    //                     'section_number' => $sectionData['section_number'],
    //                 ],
    //                 [
    //                     'section_label' => $sectionData['section_label'],
    //                     'section_instruction' => $sectionData['section_instruction'],
    //                 ]
    //             );
    //             $newSectionIds[] = $questionSection->id;

    //             // Handle question groups within each section
    //             if (isset($sectionData['question_groups'])) {
    //                 $currentGroupIds = $questionSection->question_groups()->pluck('id')->toArray();
    //                 $newGroupIds = [];

    //                 foreach ($sectionData['question_groups'] as $groupData) {
    //                     $questionGroup = QuestionGroup::updateOrCreate(
    //                         [
    //                             'survey_id' => $survey->id,
    //                             'question_section_id' => $questionSection->id,
    //                             'number' => $groupData['number'],
    //                         ],
    //                         [
    //                             'format' => $groupData['format'],
    //                             'label' => $groupData['label'],
    //                             'group_question' => $groupData['group_question'],
    //                             'category_label' => $groupData['category_label'],
    //                             'question_instruction' => $groupData['question_instruction'],
    //                             'question_categories' => json_encode($groupData['question_categories'] ?? []),
    //                         ]
    //                     );
    //                     $newGroupIds[] = $questionGroup->id;

    //                     // Handle questions within each group
    //                     if (isset($groupData['questions'])) {
    //                         $currentQuestionIds = $questionGroup->questions()->pluck('id')->toArray();
    //                         $newQuestionIds = [];

    //                         foreach ($groupData['questions'] as $questionData) {
    //                             $questionData['data'] = is_array($questionData['data']) ? json_encode($questionData['data']) : $questionData['data'];
    //                             $questionData['question_group_id'] = $questionGroup->id;

    //                             $question = Question::updateOrCreate(
    //                                 [
    //                                     'id' => $questionData['id'] ?? null,
    //                                     'survey_id' => $survey->id,
    //                                     'question_group_id' => $questionGroup->id,
    //                                 ],
    //                                 $questionData
    //                             );
    //                             $newQuestionIds[] = $question->id;
    //                         }

    //                         // Remove questions that no longer exist
    //                         $questionsToDelete = array_diff($currentQuestionIds, $newQuestionIds);
    //                         if (!empty($questionsToDelete)) {
    //                             Question::whereIn('id', $questionsToDelete)->delete();
    //                         }
    //                     }
    //                 }

    //                 // Remove groups that are no longer in the request
    //                 $groupsToDelete = array_diff($currentGroupIds, $newGroupIds);
    //                 if (!empty($groupsToDelete)) {
    //                     QuestionGroup::whereIn('id', $groupsToDelete)->delete();
    //                 }
    //             }
    //         }

    //         // Remove sections that are no longer in the request
    //         $sectionsToDelete = array_diff($currentSectionIds, $newSectionIds);
    //         if (!empty($sectionsToDelete)) {
    //             QuestionSection::whereIn('id', $sectionsToDelete)->delete();
    //         }
    //     }



    //     if (!empty($informationFieldsData)) {
    //         $survey->informationFields()->delete();
    //         foreach ($informationFieldsData as $fieldData) {
    //             $survey->informationFields()->create($fieldData);
    //         }
    //     }

    //     return new SurveyResource($survey);
    // }

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

        // Delete the surveyy
        $survey->delete();

        return response()->json(['message' => 'Survey deleted successfully']);
    }

    public function showBySlug($slug)
    {
        // $survey = Survey::where('slug', $slug)->with(['questions','questionGroups', 'respondentGroups', 'informationFields'])->first();
        $survey = Survey::where('slug', $slug)->with(['questionSections.question_groups.questions', 'respondentGroups', 'informationFields'])->first();


        if (!$survey) {
            return response()->json(['error' => 'Survey not found'], 404);
        }

        return response()->json(['data' => $survey], 200);
    }
    public function showPublicBySlug($slug)
    {
        $survey = Survey::where('slug', $slug)->with(['questionSections.question_groups.questions', 'respondentGroups', 'informationFields'])->first();

        if (!$survey) {
            return response()->json(['error' => 'Survey not founsd'], 404);
        }

        if ($survey->is_public != true) {
            return response()->json(['error' => 'this survey is not for the publisc'], 404);
        }

        return response()->json(['data' => $survey], 200);
    }

    public function distribute($surveyId)
    {
        $survey = Survey::findOrFail($surveyId);

        // Fetch all respondent groups related to the survey
        $respondentGroups = $survey->respondentGroups;
        Log::info($respondentGroups);

        $notifiedRespondents = []; // To track notified respondents

        foreach ($respondentGroups as $group) {
            $respondentsQuery = User::where('respondent_type', $group->type);

            if ($group->category) {
                $categories = array_map('trim', explode(',', $group->category)); // Split and trim categories
                $respondentsQuery->where(function ($query) use ($categories) {
                    foreach ($categories as $category) {
                        $query->orWhereRaw('FIND_IN_SET(?, category)', [$category]);
                    }
                });
            }

            $respondents = $respondentsQuery->get();
            Log::info($respondents);

            // Notify each respondent
            foreach ($respondents as $respondent) {
                Notification::create([
                    'user_id' => $respondent->id,
                    'title' => 'New Survey Available',
                    'message' => "A new survey titled '{$survey->title}' is available for you to take.",
                    'read' => false,
                ]);

                Mail::to($respondent->email)->send(new SurveyDistributed($survey, $respondent));

                $notifiedRespondents[] = $respondent;
            }
        }

        return response()->json([
            'message' => 'Survey distributed and respondents notified successfully',
            'respondents' => $notifiedRespondents,
        ]);
    }


    // public function distribute($surveyId)
    // {
    //     $survey = Survey::findOrFail($surveyId);

    //     // Fetch all respondent groups related to the survey
    //     $respondentGroups = $survey->respondentGroups;
    //     Log::info($respondentGroups);

    //     $notifiedRespondents = []; // To track notified respondents

    //     foreach ($respondentGroups as $group) {
    //         $respondentsQuery = User::where('respondent_type', $group->type);

    //         if ($group->type === 'student' || $group->type === 'faculty') {
    //             // For "student", check if the user's category exists in the group's category list
    //             $categories = explode(',', $group->category); // Split the group categories by commas
    //             $respondentsQuery->where(function ($query) use ($categories) {
    //                 foreach ($categories as $category) {
    //                     $query->orWhere('category', trim($category)); // Match trimmed category
    //                 }
    //             });
    //         } else {
    //             // For other types, match exact category
    //             $respondentsQuery->where('category', $group->category);
    //         }

    //         $respondents = $respondentsQuery->get();
    //         Log::info($respondents);

    //         // Notify each respondent
    //         foreach ($respondents as $respondent) {
    //             Notification::create([
    //                 'user_id' => $respondent->id,
    //                 'title' => 'New Survey Available',
    //                 'message' => "A new survey titled '{$survey->title}' is available for you to take.",
    //                 'read' => false,
    //             ]);

    //             Mail::to($respondent->email)->send(new SurveyDistributed($survey, $respondent));

    //             $notifiedRespondents[] = $respondent;
    //         }
    //     }

    //     return response()->json([
    //         'message' => 'Survey distributed and respondents notified successfully',
    //         'respondents' => $notifiedRespondents,
    //     ]);
    // }




    public function notifyRespondents($surveyId)
    {
        $survey = Survey::findOrFail($surveyId);

        // Fetch respondent groups
        $respondentGroups = $survey->respondentGroups;

        $notifiedRespondents = [];

        foreach ($respondentGroups as $group) {
            $respondentsQuery = User::where('respondent_type', $group->type);

            if ($group->type === 'student') {
                $respondentsQuery->whereRaw('FIND_IN_SET(?, category)', [$group->category]);
            } else {
                $respondentsQuery->where('category', $group->category);
            }

            $respondents = $respondentsQuery->get();

            foreach ($respondents as $respondent) {
                $respondent->notify(new SurveyNotification($survey));
                $notifiedRespondents[] = $respondent;
            }
        }

        return response()->json([
            'message' => 'Respondents notified successfully',
            'respondents' => $notifiedRespondents,
        ]);
    }
}
