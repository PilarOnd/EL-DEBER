<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function mostrarReporte()
    {
        // Leer el archivo JSON
        $json = file_get_contents(public_path('data/reporte.json'));

        // Decodificar JSON a un array de PHP
        $data = json_decode($json, true);

        // Enviar los datos a la vista
        return view('reporte', compact('data'));
    }
}
