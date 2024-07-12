<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\auto;
use Inertia\Inertia;

class dashboardController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Auto::query();
    
        if ($search) {
            $query->where('nombre', 'like', '%' . $search . '%');
        }
    
        $autos = $query->paginate(10);
    
        return Inertia::render('Dashboard', [
            'autos' => $autos,
            'filters' => $request->only(['search']),
        ]);
    }
}
