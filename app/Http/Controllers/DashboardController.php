<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    //
    public function getPublicSurveys()
    {
        $publicSurveys = Survey::where('is_public', 1)->get();
        return response()->json([
            'public_surveys' => $publicSurveys
        ]);
    }
}
