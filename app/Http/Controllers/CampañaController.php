<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class CampañaController extends Controller
{
    public function index()
    {
        $json = file_get_contents(base_path('db.json'));
        $data = json_decode($json, true);
    
        
        $campaña = $data['campañas'][0] ?? null;
        $fecha = $campaña['fecha_inicio'] ?? null;
        $plataforma = $campaña['plataforma'] ?? null;
        $presupuesto = $campaña['presupuesto'] ?? null;
    
        return view('campañas.index', compact('campaña', 'fecha', 'plataforma', 'presupuesto'));
    }
    
    public function show($id)
    {
        /* Desencriptar la ID
        $idDesencriptada = Crypt::decrypt($id);*/

        // Leer el archivo db.json
        $json = file_get_contents(base_path('db.json'));
        $data = json_decode($json, true);
        
        // Buscar la campaña por el ID
        $campaña = collect($data['campañas'])->firstWhere('id', $id);
        /* Buscar la campaña por el ID desencriptado
        $campaña = collect($data['campañas'])->firstWhere('id', $idDesencriptada);*/
    
        // Si no se encuentra la campaña, puedes redirigir o mostrar un error
        if (!$campaña) {
            abort(404, 'Campaña no encontrada');
        }
    
        // Pasar la campaña a la vista
        return view('campañas.show', compact('campaña'));
    }
    
    
    
}

