<?php

namespace App\Http\Controllers;

use App\Models\juzgado;
use Illuminate\Http\Request;
use Inertia\Inertia;

class juzgadoController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
        ]);

        $query = juzgado::query();

        if ($request->has('search') && $request->input('search') !== null) {
            $search = $request->input('search');
            $query->where('nombre', 'like', "%{$search}%")
                ->orWhere('descripcion', 'like', "%{$search}%");
        }

        $juzgados = $query->paginate(10)->appends(['search' => $request->input('search')]);

        return Inertia::render('juzgados/index', [
            'juzgados' => $juzgados,
            'filters' => $request->only(['search']),
            'errors' => session('errors') ? session('errors')->getBag('default')->getMessages() : (object) [],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
        ]);

        try {
            juzgado::create($request->only('nombre', 'descripcion'));
            return redirect()->back()->with('success', 'Juzgado creado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocurrió un error al crear el juzgado');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
        ]);

        try {
            $juzgado = juzgado::findOrFail($id);
            $juzgado->update($request->only('nombre', 'descripcion'));
            return redirect()->back()->with('success', 'Juzgado actualizado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocurrió un error al actualizar el juzgado');
        }
    }

    public function destroy($id)
    {
        try {
            $juzgado = juzgado::findOrFail($id);
            $juzgado->delete();
            return redirect()->back()->with('success', 'Juzgado eliminado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocurrió un error al eliminar el juzgado');
        }
    }
}
