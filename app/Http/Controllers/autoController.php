<?php

namespace App\Http\Controllers;

use App\Models\auto;
use App\Models\variable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;

class autoController extends Controller
{

    public function index()
    {
        $autos = Auto::where('user_id', auth()->id())->paginate(10);
        return Inertia::render('autos/index', [
            'autos' => $autos,
        ]);
    }

    public function create()
    {
        return Inertia::render('autos/create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'archivo' => 'required|file|mimes:docx',
            'variables' => 'nullable|array',
            'variables.*.nombre' => 'required|string|max:255',
        ]);

        $path = $request->file('archivo')->store('public/autos');
        $path = str_replace('public/', '', $path);

        $auto = Auto::create([
            'user_id' => auth()->id(),
            'nombre' => $request->nombre,
            'archivo' => $path,
        ]);

        if ($request->has('variables')) {
            foreach ($request->variables as $variable) {
                Variable::create([
                    'auto_id' => $auto->id,
                    'nombre' => $variable['nombre'],
                ]);
            }
        }

        return redirect()->route('dashboard');
    }

    public function edit(Auto $auto)
    {
        return Inertia::render('autos/edit', ['auto' => $auto]);
    }

    public function update(Request $request, Auto $auto)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'archivo' => 'nullable|file|mimes:docx',
            'variables' => 'nullable|array',
            'variables.*.nombre' => 'required|string|max:255',
        ]);

        $auto->nombre = $request->nombre;

        if ($request->hasFile('archivo')) {
            // Eliminar el archivo antiguo si existe
            if ($auto->archivo && Storage::exists('public' . $auto->archivo)) {
                Storage::delete('public/' . $auto->archivo);
            }

            // Guardar el nuevo archivo
            $path = $request->file('archivo')->store('public/autos');
            $path = str_replace('public/', '', $path);
            $auto->archivo = $path;
        }

        $auto->save();

        return redirect()->route('autos.index');
    }


    public function destroy(Auto $auto)
    {
        $auto->variables()->delete();
        if ($auto->archivo && Storage::exists('public' . $auto->archivo)) {
            Storage::delete('public/' . $auto->archivo);
        }
        $auto->delete();
        return redirect()->route('autos.index');
    }

    public function show(Auto $auto)
    {
        $variables = $auto->variables;
        return Inertia::render('autos/show', [
            'auto' => $auto,
            'variables' => $variables,
            'archivo_url' => asset('storage/' . $auto->archivo),
        ]);
    }

    public function descargar($archivo)
    {

        $path = storage_path('app/public/' . $archivo);
        if (!file_exists($path)) {
            logger('Archivo no encontrado: ' . $path);
            return response('', 404);
        }
        $name = str_replace('autos/', '', $path);
        return Response::download($path, $name);
    }

    public function generarWord(Request $request, $id)
    {
        Log::info('Inicio del método generarWord.');

        $auto = Auto::findOrFail($id);
        $path = storage_path('app/public/' . $auto->archivo);
        Log::info('Archivo a procesar: ' . $path);

        $templateProcessor = new TemplateProcessor($path);
        foreach ($request->all() as $key => $value) {
            Log::info("Procesando variable: $key con valor: $value");
            $templateProcessor->setValue($key, $value);
        }
        $tempFilePath = tempnam(sys_get_temp_dir(), 'PHPWord');
        $templateProcessor->saveAs($tempFilePath);
        Log::info('Archivo temporal creado en: ' . $tempFilePath);

        $filename = 'auto-' . $id . '-' . Str::random(10) . '.docx';

        // Verifica si el archivo temporal existe
        if (!file_exists($tempFilePath)) {
            Log::error('El archivo temporal no se creó correctamente.');
            return response()->json(['error' => 'El archivo no se generó correctamente.'], 500);
        }

        return Response::download($tempFilePath, $filename)->deleteFileAfterSend(true);
    }
}
