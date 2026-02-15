<?php

namespace App\Http\Controllers\Health;

use App\Http\Controllers\Controller;
use App\Models\Health\DiseaseCase;
use App\Models\Health\Disease;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DiseaseCaseController extends Controller
{
    /**
     * Display a listing of disease cases.
     */
    public function index(Request $request): JsonResponse
    {
        $query = DiseaseCase::with(['disease', 'animal']);

        // Filter by disease
        if ($request->has('disease_id') && $request->disease_id) {
            $query->where('disease_id', $request->disease_id);
        }

        // Filter by severity
        if ($request->has('severity') && $request->severity) {
            $query->where('severity', $request->severity);
        }

        // Filter by outcome
        if ($request->has('outcome') && $request->outcome) {
            $query->where('outcome', $request->outcome);
        }

        // Filter active cases (not recovered/deceased)
        if ($request->has('active') && $request->active === 'true') {
            $query->whereNull('outcome');
        }

        // Filter by date range
        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('onset_date', '>=', $request->from_date);
        }
        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('onset_date', '<=', $request->to_date);
        }

        $cases = $query->orderBy('onset_date', 'desc')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $cases,
            'message' => 'Disease cases retrieved successfully'
        ]);
    }

    /**
     * Store a newly created disease case.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'animal_id' => 'required|exists:animals,id',
            'disease_id' => 'required|exists:diseases,id',
            'onset_date' => 'required|date',
            'diagnosis_date' => 'nullable|date',
            'severity' => 'nullable|in:mild,moderate,severe',
            'isolation_status' => 'nullable|boolean',
            'outcome' => 'nullable|in:recovered,chronic,deceased,culled',
            'outcome_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $validated['case_number'] = DiseaseCase::generateCaseNumber();
        $validated['recorded_by'] = auth()->id();

        $case = DiseaseCase::create($validated);

        return response()->json([
            'success' => true,
            'data' => $case->load(['disease', 'animal']),
            'message' => 'Disease case recorded successfully'
        ], 201);
    }

    /**
     * Display the specified disease case.
     */
    public function show(string $id): JsonResponse
    {
        $case = DiseaseCase::with(['disease', 'animal', 'treatments', 'outbreakAnimals.outbreak'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $case,
            'message' => 'Disease case retrieved successfully'
        ]);
    }

    /**
     * Update the specified disease case.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $case = DiseaseCase::findOrFail($id);

        $validated = $request->validate([
            'animal_id' => 'sometimes|required|exists:animals,id',
            'disease_id' => 'sometimes|required|exists:diseases,id',
            'onset_date' => 'sometimes|required|date',
            'diagnosis_date' => 'nullable|date',
            'severity' => 'nullable|in:mild,moderate,severe',
            'isolation_status' => 'nullable|boolean',
            'outcome' => 'nullable|in:recovered,chronic,deceased,culled',
            'outcome_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $case->update($validated);

        return response()->json([
            'success' => true,
            'data' => $case->load(['disease', 'animal']),
            'message' => 'Disease case updated successfully'
        ]);
    }

    /**
     * Remove the specified disease case.
     */
    public function destroy(string $id): JsonResponse
    {
        $case = DiseaseCase::findOrFail($id);
        $case->delete();

        return response()->json([
            'success' => true,
            'message' => 'Disease case deleted successfully'
        ]);
    }

    /**
     * Record outcome for a disease case.
     */
    public function recordOutcome(Request $request, string $id): JsonResponse
    {
        $case = DiseaseCase::findOrFail($id);

        $validated = $request->validate([
            'outcome' => 'required|in:recovered,chronic,deceased,culled',
            'outcome_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $case->update($validated);

        return response()->json([
            'success' => true,
            'data' => $case,
            'message' => 'Outcome recorded successfully'
        ]);
    }

    /**
     * Get disease case statistics.
     */
    public function statistics(): JsonResponse
    {
        $statistics = [
            'total_cases' => DiseaseCase::count(),
            'active_cases' => DiseaseCase::whereNull('outcome')->count(),
            'by_severity' => DiseaseCase::groupBy('severity')
                ->select('severity', \DB::raw('count(*) as count'))
                ->get(),
            'by_outcome' => DiseaseCase::groupBy('outcome')
                ->select('outcome', \DB::raw('count(*) as count'))
                ->get(),
            'recent_cases' => DiseaseCase::with(['disease', 'animal'])
                ->whereDate('created_at', '>=', now()->subDays(30))
                ->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $statistics,
            'message' => 'Disease case statistics retrieved successfully'
        ]);
    }
}
