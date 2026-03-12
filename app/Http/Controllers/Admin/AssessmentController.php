<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentCategory;
use App\Models\AssessmentDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssessmentController extends Controller
{
    /**
     * Display a listing of employees to be assessed.
     */
    public function index()
    {
        
        $currentPeriod = Carbon::now()->isoFormat('MMMM YYYY'); //Variable dengan tipe data String
        
        $employees = User::whereIn('role', ['karyawan', 'user'])->get(); //Mengambil data dari database (Collection/Array)
        
        $assessedIds = Assessment::where('period', $currentPeriod) //Array & Variable Integer
            ->pluck('evaluatee_id')
            ->toArray();
            
        $totalEmployees = $employees->count();
        $assessedCount = count($assessedIds);

        // Pengkondisian ternary operator
        $progressPercentage = $totalEmployees > 0 ? round(($assessedCount / $totalEmployees) * 100) : 0;
            
        return view('admin.assessments.index', compact('employees', 'assessedIds', 'currentPeriod', 'totalEmployees', 'assessedCount', 'progressPercentage'));
    }

    /**
     * Show the assessment form for a specific employee.
     */
    public function create(User $evaluatee)
    {
        $categories = AssessmentCategory::where('is_active', true)->get();
        $currentPeriod = Carbon::now()->isoFormat('MMMM YYYY');
        
        $allEmployees = User::whereIn('role', ['karyawan', 'user'])->pluck('id')->toArray();
        $currentIndex = array_search($evaluatee->id, $allEmployees);
        $nextEmployeeId = null;
        if ($currentIndex !== false && isset($allEmployees[$currentIndex + 1])) {
            $nextEmployeeId = $allEmployees[$currentIndex + 1];
        }

        return view('admin.assessments.create', compact('evaluatee', 'categories', 'currentPeriod', 'nextEmployeeId'));
    }

    /**
     * Store the assessment in the database.
     */
    public function store(Request $request, User $evaluatee)
    {
        $request->validate([
            'scores' => 'required|array',
            'scores.*' => 'required|numeric|min:1|max:5',
            'general_notes' => 'nullable|string',
        ]);

        $currentPeriod = Carbon::now()->isoFormat('MMMM YYYY');

        DB::transaction(function () use ($request, $evaluatee, $currentPeriod) {
            $assessment = Assessment::updateOrCreate(
                [
                    'evaluatee_id' => $evaluatee->id,
                    'period' => $currentPeriod,
                ],
                [
                    'evaluator_id' => auth()->id(),
                    'assessment_date' => now(),
                    'general_notes' => $request->general_notes,
                ]
            );

            foreach ($request->scores as $categoryId => $score) {
                AssessmentDetail::updateOrCreate(
                    [
                        'assessment_id' => $assessment->id,
                        'category_id' => $categoryId,
                    ],
                    [
                        'score' => $score,
                    ]
                );
            }
        });

        if ($request->has('save_and_next') && $request->next_employee_id) {
            return redirect()->route('admin.assessments.create', $request->next_employee_id)
                ->with('success', 'Penilaian berhasil disimpan. Lanjut ke pegawai berikutnya.');
        }

        return redirect()->route('admin.assessments.index')
            ->with('success', 'Penilaian untuk ' . $evaluatee->name . ' berhasil disimpan.');
    }
}
