<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOwnerRequest;
use App\Http\Requests\UpdateOwnerRequest;
use App\Models\Owner;

class OwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Owner::with(['cars.brand', 'cars.revisions'])->get());
    }

    /**
     * Display a listing of the resource.
     */
    public function by_sex()
    {
        $genders = Owner::with(['cars.brand', 'cars.revisions'])->get()->groupBy('gender');

        $separated = $genders->map(function ($gender){
            return $gender->avg('age');
        });

        return response()->json([
            $genders,
            $separated
        ]);
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
    public function store(StoreOwnerRequest $request)
    {
        $owner = Owner::create($request->validated());

        if($owner){
            return response()->json(
                [
                    'content' => $owner->load(['cars.brand', 'cars.revisions']),
                    'message' => 'Proprietário inserido'
                ]
                , 201);
        }

        return response()->json('Erro ao inserir proprietário');
    }

    /**
     * Display the specified resource.
     */
    public function show(Owner $owner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Owner $owner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOwnerRequest $request, Owner $owner)
    {
        if($owner->update($request->validated())){

            return response()->json([
                'content' => $owner->load(['cars.brand', 'cars.revisions']),
                'message' => 'Proprietário Alterado'
            ], 201);
        }

        return response()->json('Erro ao alterar proprietário');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Owner $owner)
    {
        //
    }
}
