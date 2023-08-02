<?php

namespace App\Http\Controllers;

use App\Models\Revision;
use Illuminate\Http\Request;

class RevisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            Revision::with(
                [
                    'car' => [
                        'owner' => [
                            'cars' => [
                                'brand',
                                'revisions',
                                'owner'
                            ]
                        ],
                        'brand',
                        'revisions',
                    ]
                ]
            )
                ->orderBy('review_day', 'desc')
                ->get()
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
        $rules = [
            'car_id' => ['required', 'numeric'],
            'review_day' => ['required', 'date']
        ];

        $data = $request->validate($rules);

        $revision = Revision::create($data);

        if($revision) return response()->json(
            [
                'content' => $revision->load(
                    [
                        'car' => [
                            'owner' => [
                                'cars' => [
                                    'brand',
                                    'revisions',
                                    'owner'
                                ]
                            ],
                            'brand',
                            'revisions',
                        ]
                    ]
                ),
                'message' => 'Revisão inserida'
            ], 201);

        return response()->json(['message' => 'Erro ao inserir revisão']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Revision $revision)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Revision $revision)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Revision $revision)
    {
        $rules = [
            'car_id' => ['required', 'numeric'],
            'review_day' => ['required', 'date']
        ];

        $data = $request->validate($rules);

        if($revision->update($data))
            return response()->json(
                [
                    'content' => $revision->load(
                        [
                            'car' => [
                                'owner' => [
                                    'cars' => [
                                        'brand',
                                        'revisions',
                                        'owner'
                                    ]
                                ],
                                'brand',
                                'revisions',
                            ]
                        ]
                    ),
                    'message' => 'Revisão Alterada'
                ], 201);

        return response()->json('Erro ao alterar revisão');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Revision $revision)
    {
        $owner = $revision->car->owner;

        if($revision->delete())
        {
            return response()->json([
                'content' => [
                    'owner' => $owner->load(['cars.brand', 'cars.revisions']),
                    'revision_id' => $revision->id
                ],
                'message' => "Revisão Excluída"
            ]);
        }

        return response()->json([
            'message' => 'Não foi possível excluir a revisão'
        ]);
    }
}
