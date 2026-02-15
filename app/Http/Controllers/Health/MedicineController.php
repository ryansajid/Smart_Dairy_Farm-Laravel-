<?php

namespace App\Http\Controllers\Health;

use App\Http\Controllers\Controller;
use App\Models\Health\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medicines = Medicine::paginate(10);
        return view('health.medicines.index', compact('medicines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('health.medicines.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'medicine_form' => 'required',
            'unit_cost' => 'numeric',
            'current_stock' => 'integer',
        ]);

        Medicine::create($validated);
        return redirect('/health/medicines')->with('success', 'Medicine created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $medicine = Medicine::findOrFail($id);
        return view('health.medicines.show', compact('medicine'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $medicine = Medicine::findOrFail($id);
        return view('health.medicines.edit', compact('medicine'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'medicine_form' => 'required',
            'unit_cost' => 'numeric',
            'current_stock' => 'integer',
        ]);

        $medicine = Medicine::findOrFail($id);
        $medicine->update($validated);
        return redirect('/health/medicines')->with('success', 'Medicine updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $medicine = Medicine::findOrFail($id);
        $medicine->delete();
        return redirect('/health/medicines')->with('success', 'Medicine deleted successfully');
    }
}
