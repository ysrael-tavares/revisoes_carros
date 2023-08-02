<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            Brand::with(['cars' => ['brand', 'owner']])
                ->get()
                ->sortByDesc('number_cars')
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
    public function store(Request $request)
    {
        $rules = ['name' => ['required', 'string']];

        $data = $request->validate($rules);

        $brand = Brand::create($data);

        if($brand) return response()->json('Marca inserida', 201);

        return response()->json('Erro ao inserir marca');
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $rules = ['name' => ['required', 'string']];

        $data = $request->validate($rules);

        if($brand->update($data))
            return response()->json('Marca Alterada', 201);

        return response()->json('Erro ao alterar marca');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        foreach($brand->cars as $car)
        {
            foreach ($car->revisions as $revision)
            {
                $revision->delete();
            }

            $car->delete();
        }

        if($brand->delete())
        {
            return response()->json([
                'content' => [
                    'brand_id' => $brand->id,
                ],
                'message' => "Marca Excluída"
            ]);
        }

        return response()->json([
            'message' => 'Não foi possível excluir a marca'
        ]);
    }
}
