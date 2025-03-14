<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        return view('menu');
    }

    public function filtrarCampañas(Request $request)
    {
        try {
            $jsonPath = base_path('base.json');
            $jsonData = json_decode(file_get_contents($jsonPath), true);
            
            // Agregar log para debugging
            \Log::info('Filtrando campañas:', [
                'año' => $request->año,
                'mes' => $request->mes
            ]);
            
            $lineaPedidos = collect($jsonData['linea_pedidos']);
            $clientes = collect($jsonData['cliente'])->keyBy('id');

            // Filtrar por año
            if ($request->año && $request->año !== 'Todo') {
                $lineaPedidos = $lineaPedidos->filter(function($pedido) use ($request) {
                    return date('Y', strtotime($pedido['fecha_hora_inicio'])) == $request->año;
                });
            }

            // Filtrar por mes
            if ($request->mes && $request->mes !== 'Todo') {
                $meses = [
                    'ENERO' => '01', 'FEBRERO' => '02', 'MARZO' => '03',
                    'ABRIL' => '04', 'MAYO' => '05', 'JUNIO' => '06',
                    'JULIO' => '07', 'AGOSTO' => '08', 'SEPTIEMBRE' => '09',
                    'OCTUBRE' => '10', 'NOVIEMBRE' => '11', 'DICIEMBRE' => '12'
                ];

                $mesNumero = $meses[$request->mes];
                
                $lineaPedidos = $lineaPedidos->filter(function($pedido) use ($mesNumero) {
                    return date('m', strtotime($pedido['fecha_hora_inicio'])) === $mesNumero;
                });
            }

            // Preparar datos para la vista
            $campañas = $lineaPedidos->map(function($pedido) use ($clientes) {
                $cliente = $clientes[$pedido['cliente_id']] ?? null;
                return [
                    'id' => $pedido['id'],
                    'nombre' => $cliente ? $cliente['nombre'] . ' - Descargas ' . date('Y', strtotime($pedido['fecha_hora_inicio'])) : 'Cliente no encontrado',
                    'cliente_nombre' => $cliente ? $cliente['nombre'] : 'Cliente no encontrado',
                    'fecha_inicio' => date('d/m/Y', strtotime($pedido['fecha_hora_inicio'])),
                    'fecha_fin' => date('d/m/Y', strtotime($pedido['fecha_hora_fin'])),
                    'objetivo' => $pedido['objetivo'],
                    'presupuesto' => [
                        'monto' => $pedido['tarifa']['cpd'],
                        'moneda' => $pedido['tarifa']['moneda']
                    ],
                    'estado' => $pedido['estado'],
                    'tipo' => $pedido['tipo']
                ];
            });

            return response()->json($campañas->values());
        } catch (\Exception $e) {
            \Log::error('Error al filtrar campañas: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
