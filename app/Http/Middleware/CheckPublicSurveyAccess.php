<?php

namespace App\Http\Middleware;

use App\Models\Survey;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckPublicSurveyAccess
{
    public function handle(Request $request, Closure $next)
    {
        $slug = $request->route('slug');
        $survey = Survey::where('slug', $slug)->first();

        if (!$survey) {
            return response()->json(['error' => 'Survey not founds'], 404);
        }

        if ($survey->is_public != 1) {
            return response()->json(['error' => 'this survey is not for the publics'], 404);
        } else {
            return $next($request);
        }

        return response()->json(['error' => 'Forbidden'], 403);
    }
}
