<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Crypt;

class CampañaController extends Controller
{
    private function getJsonData()
    {
        $json = file_get_contents(base_path('db.json'));
        return json_decode($json, true);
    }

    public function index()
    {
        $data = $this->getJsonData();
        
        // Obtener todas las campañas y sus clientes relacionados
        $campañas = collect($data['campañas'])->map(function ($campaña) use ($data) {
            $cliente = collect($data['cliente'])->firstWhere('id', $campaña['cliente_id']);
            return array_merge($campaña, ['cliente' => $cliente]);
        });

        return view('campañas.index', compact('campañas'));
    }

    public function show($id)
    {
        $data = $this->getJsonData();

        // Buscar la campaña por el ID
        $campaña = collect($data['campañas'])->firstWhere('id', $id);

        if (!$campaña) {
            abort(404, 'Campaña no encontrada');
        }

        // Buscar el cliente asociado a la campaña
        $cliente = collect($data['cliente'])->firstWhere('id', $campaña['cliente_id']);

        if (!$cliente) {
            abort(404, 'Cliente no encontrado');
        }

        // Calcular el % de efectividad
        $efectividad = $this->calcularEfectividad($campaña['impresiones'], $campaña['objetivo']);

        // Formatear fechas
        $campaña['fecha_inicio_formatted'] = date('d M Y', strtotime($campaña['fecha_inicio']));
        $campaña['fecha_fin_formatted'] = date('d M Y', strtotime($campaña['fecha_fin']));

        return view('campañas.show', compact('campaña', 'cliente', 'efectividad'));
    }

    private function calcularEfectividad($impresiones, $objetivo)
    {
        if ($objetivo <= 0) {
            return 0;
        }

        return round(($impresiones / $objetivo) * 100, 2);
    }
}