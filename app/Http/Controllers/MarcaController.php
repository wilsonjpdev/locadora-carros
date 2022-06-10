<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{
    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $marcas = $this->marca->all();
        return response($marcas, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->marca->rules(), $this->marca->feedback());

        $imagem = $request->file('imagem');
        $img_urn = $imagem->store('imagens', 'public');

        $marca = $this->marca->create([
            'nome' => $request->nome,
            'imagem' => $img_urn
        ]);

        return response($marca, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $marca = $this->marca->find($id);
        if ($marca === null) {
            return response(['erro' => 'Recurso solicitado não existe'], 404);
        }
        return $marca;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function edit(Marca $marca)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $marca = $this->marca->find($id);
        if ($marca === null) {
            return response([
                'erro' => 'Impossivel realizar a atualização, o recurso ' . $id . ' não existe'
            ], 404);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = [];

            foreach ($marca->rules() as $input => $regra) {
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas, $marca->feedback());
        } else {
            $request->validate($marca->rules(), $marca->feedback());
        }

        //remove o arquivo antigo caso um novo tenha sido enviado
        if ($request->file('imagem')) {
            Storage::disk('public')->delete($marca->imagem);
        }

        $imagem = $request->file('imagem');
        $img_urn = $imagem->store('imagens', 'public');

        $marca->update([
            'nome' => $request->nome,
            'imagem' => $img_urn
        ]);

        return $marca;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);
        Storage::disk('public')->delete($marca->imagem);

        if ($marca === null) {
            return response()->json(
                ['erro' => 'Impossivel realizar a atualização, o recurso ' . $id . ' não existe'],
                404
            );
        }
        $marca->delete();
        return ['msg' => 'Marca removida com sucesso!'];
    }
}
