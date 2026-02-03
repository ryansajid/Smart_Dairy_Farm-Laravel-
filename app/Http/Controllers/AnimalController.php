<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnimalController extends Controller
{
    public function store(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'animal_id' => 'required|string|unique:animals,animal_id',
            'breed_type' => 'required|string',
            'date' => 'required|date',
            'milk_collected' => 'nullable|numeric|min:0',
            'health_condition' => 'nullable|string|in:healthy,need_attention,sick',
            'notes' => 'nullable|string',
        ]);

        // Create new animal record
        Animal::create([
            'animal_id' => $validated['animal_id'],
            'breed_type' => $validated['breed_type'],
            'date' => $validated['date'],
            'milk_collected' => $validated['milk_collected'] ?? null,
            'health_condition' => $validated['health_condition'] ?? 'healthy',
            'user_id' => Auth::id(),
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Animal information saved successfully!');
    }

    public function getMilkProduction(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        // Get total milk collected for each day of the selected month
        $milkData = Animal::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->whereNotNull('milk_collected')
            ->selectRaw('DAY(date) as day, SUM(milk_collected) as total_milk')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        // Prepare data for 30 days
        $chartData = [];
        for ($day = 1; $day <= 30; $day++) {
            $dayData = $milkData->firstWhere('day', $day);
            $chartData[] = [
                'day' => $day,
                'milk' => $dayData ? (float) $dayData->total_milk : 0
            ];
        }

        return response()->json($chartData);
    }
}
