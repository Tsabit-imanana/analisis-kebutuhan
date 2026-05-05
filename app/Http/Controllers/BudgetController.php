<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorebudgetRequest;
use App\Http\Requests\UpdatebudgetRequest;
use App\Models\budget;

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorebudgetRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(budget $budget)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(budget $budget)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatebudgetRequest $request, budget $budget)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(budget $budget)
    {
        //
    }
}
