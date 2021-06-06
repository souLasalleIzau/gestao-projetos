<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Client;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = Auth::user();
        $clients = Client::paginate(10);

        $vars = [];
        $vars['user'] = $user;
        $vars['clients'] = $clients;

        return view('client.index', $vars);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $user = Auth::user();

        $vars = [];
        $vars['user'] = $user;

        return view('client.create', $vars);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'CPF'   => ['required', 'max:19'],
            'name'  => ['required', 'max:190'],
        ]);
        
        // Client fill
        $clientFill = [
            'CPF'   => $validated['CPF'],
            'name'  => $validated['name'],
        ];

        $clientDB = Client::create($clientFill);

        if ($clientDB):
            return redirect()
                ->route('client.index')
                ->with('success', ['Cliente cadastrado com sucesso.']);
        endif;

        return redirect()
            ->back()
            ->with('error', ['Desculpa, não foi possível cadastrar cliente.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $user = Auth::user();
        $client = Client::find($id);

        if ($client):
            $vars = [];
            $vars['user'] = $user;
            $vars['client'] = $client;

            return view('client.show', $vars);
        endif;

        return redirect()
            ->back()
            ->with('error', ['Desculpa, cliente não encontrado.']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $user = Auth::user();
        $client = Client::find($id);

        if ($client):
            $vars = [];
            $vars['user'] = $user;
            $vars['client'] = $client;

            return view('client.edit', $vars);
        endif;

        return redirect()
            ->back()
            ->with('error', ['Desculpa, cliente não encontrado.']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validated = $request->validate([
            'name'  => ['required', 'max:190'],
        ]);

        $client = Client::find($id);

        if ($client):
            $client->name = $validated['name'];

            if ($client->save()):
                return redirect()
                    ->route('client.show', ['client_id' => $client->id])
                    ->with('success', ['Cliente atualizado com sucesso.']);
            endif;
        endif;

        return redirect()
            ->route('client.index')
            ->with('success', ['Desculpa, cliente não encontrado.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $client = Client::find($id);

        if ($client):
            if ($client->delete()):
                return redirect()
                    ->route('client.index')
                    ->with('success', ['Cliente deletado com sucesso.']);
            endif;
        endif;

        return redirect()
            ->route('client.index')
            ->with('success', ['Desculpa, cliente não encontrado.']);
    }
}
