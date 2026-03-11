<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentCategory;
use Illuminate\Http\Request;

class EmployeeAssessmentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get all assessments for this user, ordered by date descending
        $assessments = Assessment::with(['evaluator', 'assessmentDetails.category'])
            ->where('evaluatee_id', $user->id)
            ->orderBy('assessment_date', 'desc')
            ->get();
            
        // Get latest assessment for the radar chart
        $latestAssessment = $assessments->first();
        
        $chartData = [
            'labels' => [],
            'data' => []
        ];
        
        if ($latestAssessment) {
            foreach ($latestAssessment->assessmentDetails as $detail) {
                $chartData['labels'][] = $detail->category->name;
                $chartData['data'][] = $detail->score;
            }
        }

        return view('attendance.assessments.index', compact('assessments', 'chartData', 'latestAssessment'));
    }
}
