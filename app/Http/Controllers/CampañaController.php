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
    
        // Buscar la creatividad asociada a un cliente
        $creatividades = collect($data['creatividades'])->where('campaña_id', $campaña['id'])->all();
    
        if (!$creatividades) {
            abort(404, 'Creatividad no encontrada');
        }
    
        // Calcular CTR para cada creatividad
        $creatividades = collect($creatividades)->map(function ($creatividad) {
            $impresiones = $creatividad['rendimiento']['impresiones'];
            $clics = $creatividad['rendimiento']['clics'];
            
            // Calcular CTR
            $creatividad['rendimiento']['ctr'] = $impresiones > 0 
                ? round(($clics / $impresiones) * 100, 2)
                : 0;
                
            return $creatividad;
        })->all();
    
        $totales = $this->calcularTotales($creatividades);
    
        // Actualizar las impresiones de la campaña con el total calculado
        $campaña['impresiones'] = $totales['impresiones'];
    
        // Calcular el % de efectividad
        $efectividad = $this->calcularEfectividad($campaña['impresiones'], $campaña['objetivo']);
    
        // Formatear fechas
        $campaña['fecha_inicio_formatted'] = date('d M Y', strtotime($campaña['fecha_inicio']));
        $campaña['fecha_fin_formatted'] = date('d M Y', strtotime($campaña['fecha_fin']));
    
        // Pasar los datos a la vista
        return view('campañas.show', compact('campaña', 'cliente', 'efectividad', 'creatividades', 'totales'));
    }

    private function calcularTotales($creatividades)
    {
        $impresiones = 0;
        $clics = 0;

        foreach ($creatividades as $creatividad) {
            if (isset($creatividad['rendimiento']['metricas'])) {
                $impresiones += $creatividad['rendimiento']['metricas']['impresiones'] ?? 0;
                $clics += $creatividad['rendimiento']['metricas']['clics'] ?? 0;
            }
        }

        return [
            'impresiones' => $impresiones,
            'clics' => $clics,
            'ctr' => $impresiones > 0 ? round(($clics / $impresiones) * 100, 2) : 0
        ];
    }
    // Función para calcular la efectividad basada en impresiones y objetivo
    private function calcularEfectividad($impresiones, $objetivo)
    {
        if ($objetivo <= 0) {
            return 0;
        }

        return round(($impresiones / $objetivo) * 100, 2);
    }

    public function showDigital($id)
    {
        // Leer el archivo JSON
        $json = file_get_contents(base_path('prueba.json'));
        $data = json_decode($json, true);
        
        // Obtener los datos necesarios
        $cliente = $data['cliente'][0];
        $linea_pedido = collect($data['linea_pedidos'])->firstWhere('id', $id);
        
        if (!$linea_pedido) {
            abort(404, 'Campaña no encontrada');
        }
        
        // Obtener los pedidos relacionados
        $pedidos = collect($data['pedido'])->where('linea_pedido_id', $id)->all();
        
        // Obtener los pedido_ids
        $pedido_ids = collect($pedidos)->pluck('id')->all();
        
        // Obtener los formatos de campaña digital relacionados con los pedidos
        $formato_campaña_digital = collect($data['formato_campaña_digital'])
            ->whereIn('pedido_id', $pedido_ids)
            ->all();
        
        // Debugging: Imprimir información para verificar
        \Log::info('Pedido IDs:', $pedido_ids);
        \Log::info('Creatividades disponibles:', $data['creatividad']);
        
        // Obtener las creatividades relacionadas con los pedidos
        $creatividad = collect($data['creatividad'])
            ->filter(function($item) use ($pedido_ids) {
                return in_array($item['pedido_id'], $pedido_ids);
            })
            ->values()
            ->all();
        
        // Debugging: Imprimir creatividades filtradas
        \Log::info('Creatividades filtradas:', $creatividad);
        
        return view('campañas.campañas_digitales', compact(
            'cliente',
            'linea_pedido',
            'pedidos',
            'formato_campaña_digital',
            'creatividad'
        ));
    }

    public function todasCampañas($id)
    {
        // Leer el archivo JSON
        $json = file_get_contents(base_path('base.json'));
        $data = json_decode($json, true);
        
        // Obtener los datos necesarios
        $cliente = $data['cliente'][0];
        $linea_pedido = collect($data['linea_pedidos'])->firstWhere('id', $id);
        
        if (!$linea_pedido) {
            abort(404, 'Campaña no encontrada');
        }
        
        // Obtener los pedidos relacionados
        $pedidos = collect($data['pedidos'])->where('linea_pedido_id', $id)->all();
        
        // Obtener los pedido_ids
        $pedido_ids = collect($pedidos)->pluck('id')->all();
        
        // Obtener creatividades relacionadas con los pedidos
        $creatividades = collect($data['creatividades'])
            ->filter(function($item) use ($pedido_ids) {
                return in_array($item['pedido_id'], $pedido_ids);
            })
            ->map(function ($creatividad) {
                // Asegurarnos de que exista la estructura base de rendimiento
                if (!isset($creatividad['rendimiento'])) {
                    $creatividad['rendimiento'] = [];
                }

                // Procesar métricas de Facebook si existen
                if (isset($creatividad['rendimiento']['facebook'])) {
                    $facebook = $creatividad['rendimiento']['facebook'];
                    $visualizaciones = $facebook['visualizaciones'] ?? 0;
                    $clics = $facebook['clics_enlaces'] ?? 0;
                    
                    $creatividad['rendimiento']['facebook']['ctr'] = 
                        $visualizaciones > 0 ? round(($clics / $visualizaciones) * 100, 2) : 0;
                }
                
                // Procesar métricas de Instagram si existen
                if (isset($creatividad['rendimiento']['instagram'])) {
                    $instagram = $creatividad['rendimiento']['instagram'];
                    $visualizaciones = $instagram['visualizaciones'] ?? 0;
                    $me_gusta = $instagram['me_gusta'] ?? 0;
                    
                    $creatividad['rendimiento']['instagram']['ctr'] = 
                        $visualizaciones > 0 ? round(($me_gusta / $visualizaciones) * 100, 2) : 0;
                }
                
                return $creatividad;
            })
            ->values()
            ->all();

        // Calcular totales para display
        $totalesDisplay = collect($creatividades)
            ->filter(function($creatividad) {
                return $creatividad['tipo_formato'] === 'display';
            })
            ->reduce(function($carry, $creatividad) {
                $carry['impresiones'] += $creatividad['rendimiento']['metricas']['impresiones'] ?? 0;
                $carry['clics'] += $creatividad['rendimiento']['metricas']['clics'] ?? 0;
                return $carry;
            }, ['impresiones' => 0, 'clics' => 0]);

        // Calcular CTR
        $totalesDisplay['ctr'] = $totalesDisplay['impresiones'] > 0 
            ? round(($totalesDisplay['clics'] / $totalesDisplay['impresiones']) * 100, 2) 
            : 0;

        // Calcular totales generales y efectividad
        $totales = $this->calcularTotales($creatividades);
        $efectividad = $this->calcularEfectividad($totales['impresiones'], $linea_pedido['objetivo']);

        // Preparar datos de campaña
        $campaña = [
            'nombre' => $linea_pedido['tipo'],
            'fecha_inicio' => $linea_pedido['fecha_hora_inicio'],
            'fecha_fin' => $linea_pedido['fecha_hora_fin'],
            'objetivo' => $linea_pedido['objetivo'],
            'presupuesto' => [
                'monto' => $linea_pedido['tarifa']['cpd'],
                'moneda' => $linea_pedido['tarifa']['moneda']
            ],
            'impresiones' => $totales['impresiones'],
            'plataforma' => [
                'actualización' => date('Y-m-d'),
                'redes_sociales' => ['Facebook', 'Instagram']
            ]
        ];

        // Obtener datos de display takeover
        $displayTakeover = null;
        if (isset($data['formatos']['display'])) {
            $displays = collect($data['formatos']['display']);
            $displayTakeover = $displays
                ->filter(function($item) use ($pedido_ids) {
                    return in_array($item['pedido_id'] ?? null, $pedido_ids);
                })
                ->map(function ($display) {
                    return [
                        'metricas_totales' => $display['metricas_totales'] ?? [],
                        'resultados_dispositivo' => $display['resultados_dispositivo'] ?? [],
                        'resultados_bloque' => $display['resultados_bloque'] ?? []
                    ];
                })
                ->first();
        }

        if ($displayTakeover) {
            $campaña['plataforma']['display'] = $displayTakeover;
        }

        return view('campañas.todascampañas', compact(
            'campaña',
            'cliente',
            'creatividades',
            'efectividad',
            'displayTakeover',
            'totales',
            'totalesDisplay'
        ));
    }
}