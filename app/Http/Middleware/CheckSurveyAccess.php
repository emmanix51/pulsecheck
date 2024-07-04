<?php

namespace App\Http\Middleware;

use App\Models\Survey;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckSurveyAccess
{
    public function handle(Request $request, Closure $next)
    {
        $slug = $request->route('slug');
        $survey = Survey::where('slug', $slug)->first();

        if (!$survey) {
            return response()->json(['error' => 'Survey not found'], 404);
        }

        if ($survey->is_public) {
            return $next($request);
        }

        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $userType = $user->respondent_type;
        $userCategory = $user->category;

        if (!is_null($survey->respondentGroups)) {
            foreach ($survey->respondentGroups as $group) {
                if ($group->type === $userType) {
                    return $next($request);
                }
            }
        }

        return response()->json(['error' => 'Forbidden'], 403);
    }
}
