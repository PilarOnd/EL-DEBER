<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Crypt;

class CampañaController extends Controller
{
    private function getJsonData()
    {
        $json = file_get_contents(base_path('branded_content.json'));
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
 
    private function calcularTotalesBranded($pedido, $creatividades)
    {
        $totales = [
            'impresiones' => 0,
            'clics' => 0,
            'detalles_por_creatividad' => []
        ];

        foreach ($creatividades as $creatividad) {
            $impresiones = 0;
            $clics = 0;

            // Si la creatividad es para redes sociales
            if (isset($creatividad['redes_sociales'])) {
                foreach ($creatividad['redes_sociales'] as $red) {
                    $red = strtolower($red);
                    if ($red === 'facebook') {
                        $impresiones = $pedido['redes_sociales']['facebook']['visualizaciones'] ?? 0;
                        $clics = $pedido['redes_sociales']['facebook']['clics_enlaces'] ?? 0;
                    } elseif ($red === 'instagram') {
                        $impresiones = $pedido['redes_sociales']['instagram']['visualizaciones'] ?? 0;
                        $clics = $pedido['redes_sociales']['instagram']['clics_enlaces'] ?? 0;
                    } elseif ($red === 'web') {
                        $impresiones = $pedido['web']['vistas'] ?? 0;
                        $clics = $pedido['web']['usuarios_activos'] ?? 0;
                    }
                }
            } else {
                // Para creatividades web
                $impresiones = $pedido['web']['vistas'] ?? 0;
                $clics = $pedido['web']['usuarios_activos'] ?? 0;
                $dispositivo = $creatividad['dispositivo'];

                if (is_array($dispositivo)) {
                    $impresiones = $pedido[$dispositivo[0]]['visualizaciones'] ?? 0;
                    $clics = $pedido[$dispositivo[0]]['interacciones'] ?? 0;
                }
            }

            $ctr = $impresiones > 0 ? round(($clics / $impresiones) * 100, 2) : 0;

            $totales['detalles_por_creatividad'][] = [
                'impresiones' => $impresiones,
                'clics' => $clics,
                'ctr' => $ctr
            ];

            $totales['impresiones'] += $impresiones;
            $totales['clics'] += $clics;
        }

        $totales['ctr'] = $totales['impresiones'] > 0 ? 
            round(($totales['clics'] / $totales['impresiones']) * 100, 2) : 0;

        return $totales;
    }

    public function showBranded($id)
    {
        // Leer el archivo JSON
        $json = file_get_contents(base_path('branded_content.json'));
        $data = json_decode($json, true);
        
        // Obtener los datos necesarios
        $cliente = $data['cliente'][0];
        $linea_pedido = collect($data['linea_pedidos'])->firstWhere('id', $id);
        $pedido = $data['pedido'][0];
        $creatividades = $data['creatividades'];
        
        if (!$linea_pedido) {
            abort(404, 'Campaña no encontrada');
        }

        // Calcular totales
        $totales = $this->calcularTotalesBranded($pedido, $creatividades);

        // Calcular la efectividad
        $efectividad = $this->calcularEfectividad($pedido['web']['vistas'], $linea_pedido['objetivo']);
        
        $pedidos = $data['pedido'];
        
        // Pasar los datos a la vista
        return view('campañas.branded', compact(
            'cliente', 
            'linea_pedido', 
            'pedido', 
            'efectividad', 
            'creatividades', 
            'pedidos',
            'totales'
        ));
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
    private function calcularEfectividad($logrado, $objetivo, $maximo = 100)
    {
        if ($objetivo <= 0) {
            return 0;
        }

        $porcentaje = ($logrado / $objetivo) * 100;
        return round(min($porcentaje, $maximo), 2);
    }

    //VISTA DE REDES SOCIALES | CAMPAÑA DIGITAL
    public function showDigital($id)
    {
        // Leer el archivo JSON
        $json = file_get_contents(base_path('redes_sociales.json'));
        $data = json_decode($json, true);
        
        // Obtener los datos necesarios
        $cliente = $data['cliente'];
        $linea_pedido = collect($data['linea_pedidos'])->firstWhere('id', $id);
        
        if (!$linea_pedido) {
            abort(404, 'Campaña no encontrada');
        }
        
        // Obtener los pedidos relacionados con la línea de pedido
        $pedidos = collect($data['pedido'])
            ->where('id_lineadepedidos', $linea_pedido['id'])
            ->all();
        
        // Calcular el total de impresiones
        $totalImpresiones = collect($pedidos)->sum(function($p) {
            return $p['facebook']['visualizaciones'] + $p['instagram']['visualizaciones'];
        });

        // Calcular la efectividad usando la función global
        $efectividad = $this->calcularEfectividad($totalImpresiones, $linea_pedido['objetivo']);
        
        // Obtener las creatividades relacionadas
        $creatividades = collect($data['creatividades'])
            ->filter(function($creatividad) use ($pedidos) {
                return collect($pedidos)
                    ->pluck('id')
                    ->contains($creatividad['pedido_id']);
            })
            ->map(function($creatividad) {
                $creatividad['redes_sociales'] = array_map('ucfirst', $creatividad['redes_sociales']);
                return $creatividad;
            })
            ->values()
            ->all();

        // Verificar si hay datos
        if (empty($creatividades)) {
            \Log::info('No se encontraron creatividades para el pedido');
        }
        
        $porcentajePresupuesto = $linea_pedido['objetivo'] > 0 ? min(100, ($totalImpresiones / $linea_pedido['objetivo']) * 100) : 0;
        
        return view('campañas.campañas_digitales', compact(
            'cliente',
            'linea_pedido',
            'pedidos',
            'creatividades',
            'totalImpresiones',
            'efectividad',
            'porcentajePresupuesto'
        ));
    }

    public function todasCampañas($id)
    {
        $json = file_get_contents(base_path('base.json'));
        $data = json_decode($json, true);;

        // Obtener datos del cliente
        $cliente = $data['cliente'][0];

        // Obtener la línea de pedido
        $linea_pedido = collect($data['linea_pedidos'])->firstWhere('id', $id);

        // Obtener el pedido relacionado
        $pedido = collect($data['pedidos'])->firstWhere('linea_pedido_id', $linea_pedido['id']);

        if (!$pedido) {
            abort(404, 'No se encontró el pedido asociado a esta campaña');
        }

        // Obtener creatividades
        $creatividades = $data['creatividades'];

        // Obtener formatos
        $campañaDigital = collect($data['formatos']['campaña_digital'])->firstWhere('pedido_id', $pedido['id']);
        $brandedContent = collect($data['formatos']['branded_content'])->firstWhere('pedido_id', $pedido['id']);
        $displayTakeover = collect($data['formatos']['display'])->firstWhere('pedido_id', $pedido['id']);

        // Calcular totales y efectividad
        $totales = [
            'impresiones' => 0,
            // ... otros totales
        ];
        
        $efectividad = 0;
        if ($linea_pedido['objetivo'] > 0) {
            $efectividad = round(($totales['impresiones'] / $linea_pedido['objetivo']) * 100, 2);
        }

        // Retornar la vista con todas las variables necesarias
        return view('campañas.todascampañas', compact(
            'cliente',
            'linea_pedido',
            'pedido',
            'creatividades',
            'campañaDigital',
            'brandedContent',
            'displayTakeover',
            'totales',
            'efectividad'
        ));
    }

    public function showDisplay($id)
    {
        // Leer el archivo JSON
        $json = file_get_contents(base_path('f_alto_impacto.json'));
        $data = json_decode($json, true);

        // Obtener la línea de pedido
        $linea_pedido = collect($data['lineas_pedido'])->firstWhere('id', $id);
        if (!$linea_pedido) {
            abort(404, 'Campaña no encontrada');
        }

        // Obtener datos del cliente basado en el cliente_id de la línea de pedido
        $cliente = collect($data['clientes'])->firstWhere('id', $linea_pedido['cliente_id']);
        if (!$cliente) {
            abort(404, 'Cliente no encontrado');
        }

        // Obtener el pedido relacionado
        $pedido = collect($data['pedido'])->firstWhere('id_lineadepedidos', $linea_pedido['id']);

        if (!$pedido) {
            abort(404, 'No se encontró el pedido asociado a esta campaña');
        }

        // Calcular la efectividad usando la función global
        $efectividad = $this->calcularEfectividad($pedido['impresiones'], $linea_pedido['objetivo']);

        // Obtener creatividades relacionadas
        $creatividades = collect($data['creatividades'])
            ->where('pedido_id', $pedido['id'])
            ->values()
            ->all();

        // Preparar datos para el display takeover
        $displayTakeover = [
            'metricas_totales' => [
                'impresiones' => $pedido['impresiones'],
                'clics' => $pedido['clics'],
                'ctr' => $pedido['clics'] > 0 ? round(($pedido['clics'] / $pedido['impresiones']) * 100, 2) : 0,
                'efectividad' => $efectividad
            ],
            'resultados_bloque' => []
        ];

        // Generar array de fechas desde fecha_hora_inicio hasta fecha_hora_fin
        $fechaInicio = \Carbon\Carbon::parse($pedido['fecha_hora_inicio'])->startOfDay();
        $fechaFin = \Carbon\Carbon::parse($pedido['fecha_hora_fin'])->startOfDay();
        $fechas = [];
        
        for($fecha = clone $fechaInicio; $fecha->lte($fechaFin); $fecha->addDay()) {
            $fechas[] = $fecha->format('Y-m-d');
        }

        foreach ($fechas as $fecha) {
            if (isset($pedido['histograma_diario'][$fecha])) {
                $datos = $pedido['histograma_diario'][$fecha];
            } else {
                // Si no hay datos para esta fecha, usar valores en cero
                $datos = [
                    'impresiones' => 0,
                    'clics' => 0
                ];
            }
            
            // Desktop
            $displayTakeover['resultados_bloque'][] = [
                'nombre' => 'Desktop',
                'fecha' => $fecha,
                'impresiones' => round($datos['impresiones'] * 0.12),
                'clics' => round($datos['clics'] * 0.31),
                'ctr' => 0
            ];

            // Mobile
            $displayTakeover['resultados_bloque'][] = [
                'nombre' => 'Mobile',
                'fecha' => $fecha,
                'impresiones' => round($datos['impresiones'] * 0.88),
                'clics' => round($datos['clics'] * 0.69),
                'ctr' => 0
            ];
        }

        // Calcular CTR para cada bloque
        foreach ($displayTakeover['resultados_bloque'] as &$bloque) {
            $bloque['ctr'] = $bloque['impresiones'] > 0 ? 
                round(($bloque['clics'] / $bloque['impresiones']) * 100, 2) : 0;
        }

        // Calcular totales por dispositivo
        $totalesDesktop = [
            'impresiones' => collect($displayTakeover['resultados_bloque'])
                ->where('nombre', 'Desktop')
                ->sum('impresiones'),
            'clics' => collect($displayTakeover['resultados_bloque'])
                ->where('nombre', 'Desktop')
                ->sum('clics')
        ];
        
        $totalesMobile = [
            'impresiones' => collect($displayTakeover['resultados_bloque'])
                ->where('nombre', 'Mobile')
                ->sum('impresiones'),
            'clics' => collect($displayTakeover['resultados_bloque'])
                ->where('nombre', 'Mobile')
                ->sum('clics')
        ];
        
        $totalesDesktop['ctr'] = $totalesDesktop['impresiones'] > 0 
            ? round(($totalesDesktop['clics'] / $totalesDesktop['impresiones']) * 100, 2) 
            : 0;
        
        $totalesMobile['ctr'] = $totalesMobile['impresiones'] > 0 
            ? round(($totalesMobile['clics'] / $totalesMobile['impresiones']) * 100, 2) 
            : 0;

        // Agregar los totales al array displayTakeover
        $displayTakeover['totales_dispositivos'] = [
            'desktop' => $totalesDesktop,
            'mobile' => $totalesMobile
        ];

        // Preparar datos del histograma
        $histograma = [
            'fechas' => [],
            'impresiones' => [],
            'clics' => [],
            'ctr' => []
        ];

        foreach ($pedido['histograma_diario'] as $fecha => $datos) {
            $histograma['fechas'][] = \Carbon\Carbon::parse($fecha)->format('d/m');
            $histograma['impresiones'][] = $datos['impresiones'];
            $histograma['clics'][] = $datos['clics'];
            $histograma['ctr'][] = $datos['clics'] > 0 ? round(($datos['clics'] / $datos['impresiones']) * 100, 2) : 0;
        }

        return view('campañas.display', compact(
            'cliente',
            'linea_pedido',
            'pedido',
            'creatividades',
            'displayTakeover',
            'histograma'
        ));
    }
}