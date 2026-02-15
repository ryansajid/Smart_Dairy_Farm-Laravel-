<?php

namespace App\Http\Controllers\Health;

use App\Http\Controllers\Controller;
use App\Models\Health\Disease;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DiseaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Disease::query();

        // Filter by disease type
        if ($request->has('type') && $request->type) {
            $query->where('disease_type', $request->type);
        }

        // Filter by severity
        if ($request->has('severity') && $request->severity) {
            $query->where('severity', $request->severity);
        }

        // Filter zoonotic diseases
        if ($request->has('zoonotic') && $request->zoonotic === 'true') {
            $query->where('zoonotic', true);
        }

        // Filter notifiable diseases
        if ($request->has('notifiable') && $request->notifiable === 'true') {
            $query->where('notifiable', true);
        }

        // Search by name
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $diseases = $query->orderBy('name')->paginate(10);
        
        $statistics = [
            'total_diseases' => Disease::count(),
            'zoonotic_count' => Disease::where('zoonotic', true)->count(),
            'notifiable_count' => Disease::where('notifiable', true)->count(),
        ];

        // Check if request expects JSON response
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $diseases,
                'message' => 'Diseases retrieved successfully'
            ]);
        }

        return view('health.diseases.index', compact('diseases', 'statistics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('health.diseases.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'disease_type' => 'required|in:bacterial,viral,parasitic,fungal,metabolic,nutritional,other',
            'incubation_period_days' => 'nullable|integer|min:0',
            'transmission_methods' => 'nullable|string',
            'clinical_signs' => 'nullable|string',
            'treatment_protocol' => 'nullable|string',
            'prevention' => 'nullable|string',
            'zoonotic' => 'nullable|boolean',
            'notifiable' => 'nullable|boolean',
            'severity' => 'required|in:low,medium,high,critical',
        ]);

        $disease = Disease::create($validated);

        return response()->json([
            'success' => true,
            'data' => $disease,
            'message' => 'Disease created successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $disease = Disease::findOrFail($id);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $disease,
                'message' => 'Disease retrieved successfully'
            ]);
        }

        return view('health.diseases.show', compact('disease'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $disease = Disease::findOrFail($id);
        return view('health.diseases.edit', compact('disease'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $disease = Disease::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'disease_type' => 'sometimes|required|in:bacterial,viral,parasitic,fungal,metabolic,nutritional,other',
            'incubation_period_days' => 'nullable|integer|min:0',
            'transmission_methods' => 'nullable|string',
            'clinical_signs' => 'nullable|string',
            'treatment_protocol' => 'nullable|string',
            'prevention' => 'nullable|string',
            'zoonotic' => 'nullable|boolean',
            'notifiable' => 'nullable|boolean',
            'severity' => 'sometimes|required|in:low,medium,high,critical',
        ]);

        $disease->update($validated);

        return response()->json([
            'success' => true,
            'data' => $disease,
            'message' => 'Disease updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $disease = Disease::findOrFail($id);
        $disease->delete();

        return response()->json([
            'success' => true,
            'message' => 'Disease deleted successfully'
        ]);
    }

    /**
     * Get disease statistics
     */
    public function statistics(): JsonResponse
    {
        $statistics = [
            'total_diseases' => Disease::count(),
            'by_type' => Disease::groupBy('disease_type')->select('disease_type', \DB::raw('count(*) as count'))->get(),
            'by_severity' => Disease::groupBy('severity')->select('severity', \DB::raw('count(*) as count'))->get(),
            'zoonotic_count' => Disease::where('zoonotic', true)->count(),
            'notifiable_count' => Disease::where('notifiable', true)->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $statistics,
            'message' => 'Disease statistics retrieved successfully'
        ]);
    }
}
