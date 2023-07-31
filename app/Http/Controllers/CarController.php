<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Models\Car;
use App\Models\Owner;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            Car::with(['brand', 'owner', 'revisions'])
                ->get()
                ->sortBy('owner.name')
                ->values()
                ->all()
        );
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
    public function store(StoreCarRequest $request)
    {
        $car = Car::create($request->validated());

        if($car) return response()->json('Carro inserido', 201);

        return response()->json('Erro ao inserir carro');
    }

    /**
     * Display the specified resource.
     */
    public function show(Car $car)
    {
        //
    }

    public function showByOwners()
    {
        return Owner::with('cars')
            ->orderBy('name')
            ->get();
    }

    public function countByGender()
    {
        return Owner::with('cars')                      // Carrega as relações com a tabela de carros
            ->get()                                             // Busca dados na Tabela
            ->groupBy('gender')                         // Agrupa por Gênero
            ->map(                                              // Percorre cada Gênero
                function ($gender){
                    return $gender
                        ->map(                                  // Percorre cada proprietário do Gênero
                            function($owner){
                                return $owner->cars->count();   // Conta quantos carros cada proprietário tem
                            }
                        )
                        ->sum();                                // Soma os carros dos proprietários
                }
            );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Car $car)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCarRequest $request, Car $car)
    {
        if($car->update($request->validated()))
            return response()->json('Carro Alterado', 201);

        return response()->json('Erro ao alterar carro');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car)
    {
        //
    }
}
