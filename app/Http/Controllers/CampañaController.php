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
        // Obtener el usuario de la sesión
        $usuario = session('usuario');
        if (!$usuario) {
            return redirect()->route('login');
        }

        // Cargar datos de campañas display
        $displayData = json_decode(File::get(base_path('f_alto_impacto.json')), true);
        $display = [];
        if (isset($displayData['pedido'])) {
            foreach ($displayData['pedido'] as $pedido) {
                // Obtener la línea de pedido correspondiente
                $lineaPedido = collect($displayData['linea_pedidos'])->firstWhere('id', $pedido['id_lineadepedidos']);
                if ($lineaPedido) {
                    // Obtener el cliente usando el cliente_id de la línea de pedido
                    $cliente = collect($displayData['clientes'])->firstWhere('id', $lineaPedido['cliente_id']);
                    // Si es administrador o el pedido pertenece al usuario
                    if ($usuario['nombre'] === 'Administrador' || ($cliente && $cliente['nombre'] === $usuario['nombre'])) {
                        $pedido['cliente'] = $cliente;
                        $display[] = $pedido;
                    }
                }
            }
        }

        // Cargar datos de campañas branded content
        $brandedData = json_decode(File::get(base_path('branded_content.json')), true);
        $branded = [];
        if (isset($brandedData['pedido'])) {
            foreach ($brandedData['pedido'] as $pedido) {
                // Obtener la línea de pedido correspondiente
                $lineaPedido = collect($brandedData['linea_pedidos'])->firstWhere('id', $pedido['id_lineadepedidos']);
                if ($lineaPedido) {
                    // Obtener el cliente usando el cliente_id de la línea de pedido
                    $cliente = collect($brandedData['cliente'])->firstWhere('id', $lineaPedido['cliente_id']);
                    // Si es administrador o el pedido pertenece al usuario
                    if ($usuario['nombre'] === 'Administrador' || ($cliente && $cliente['nombre'] === $usuario['nombre'])) {
                        $pedido['cliente'] = $cliente;
                        $branded[] = $pedido;
                    }
                }
            }
        }

        // Cargar datos de campañas redes sociales
        $redesData = json_decode(File::get(base_path('redes_sociales.json')), true);
        $redes = [];
        if (isset($redesData['pedido'])) {
            foreach ($redesData['pedido'] as $pedido) {
                // Obtener la línea de pedido correspondiente
                $lineaPedido = collect($redesData['linea_pedidos'])->firstWhere('id', $pedido['id_lineadepedidos']);
                if ($lineaPedido) {
                    // Obtener el cliente usando el cliente_id de la línea de pedido
                    $cliente = collect($redesData['cliente'])->firstWhere('id', $lineaPedido['cliente_id']);
                    // Si es administrador o el pedido pertenece al usuario
                    if ($usuario['nombre'] === 'Administrador' || ($cliente && $cliente['nombre'] === $usuario['nombre'])) {
                        $pedido['cliente'] = $cliente;
                        $redes[] = $pedido;
                    }
                }
            }
        }

        return view('campañas.index', compact('display', 'branded', 'redes'));
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
        // Obtener el usuario de la sesión
        $usuario = session('usuario');
        if (!$usuario) {
            return redirect()->route('login');
        }

        $data = json_decode(File::get(base_path('branded_content.json')), true);
        
        // Verificar si existe el pedido
        $pedido = collect($data['pedido'] ?? [])->firstWhere('id', $id);
        if (!$pedido) {
            abort(404, 'Pedido no encontrado');
        }

        // Verificar si existe la línea de pedido
        $lineaPedido = collect($data['linea_pedidos'] ?? [])->firstWhere('id', $pedido['id_lineadepedidos']);
        if (!$lineaPedido) {
            abort(404, 'Línea de pedido no encontrada');
        }

        // Verificar si existe el cliente
        $cliente = collect($data['cliente'] ?? [])->firstWhere('id', $lineaPedido['cliente_id']);
        if (!$cliente) {
            abort(404, 'Cliente no encontrado');
        }

        // Verificar que el pedido pertenece al usuario o es administrador
        if ($usuario['nombre'] !== 'Administrador' && $cliente['nombre'] !== $usuario['nombre']) {
            abort(403, 'No tienes permiso para ver este pedido');
        }

        $creatividades = collect($data['creatividades'] ?? [])->where('pedido_id', $id)->values();
        
        return view('campañas.branded', compact('pedido', 'cliente', 'creatividades'));
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
        // Obtener el usuario de la sesión
        $usuario = session('usuario');
        if (!$usuario) {
            return redirect()->route('login');
        }

        $data = json_decode(File::get(base_path('f_alto_impacto.json')), true);
        $pedido = collect($data['pedido'])->firstWhere('id', $id);

        // Verificar que el pedido existe
        if (!$pedido) {
            abort(404, 'El pedido no existe');
        }

        $linea_pedido = collect($data['linea_pedidos'])->firstWhere('id', $pedido['id_lineadepedidos']);
        $cliente = collect($data['clientes'])->firstWhere('id', $linea_pedido['cliente_id']);

        // Verificar que el pedido pertenece al usuario o es administrador
        if (!$cliente || ($usuario['nombre'] !== 'Administrador' && $cliente['nombre'] !== $usuario['nombre'])) {
            abort(403, 'No tienes permiso para ver este pedido');
        }

        $creatividades = collect($data['creatividades'])->where('pedido_id', $id)->values();
        
        // Preparar datos del histograma
        $histograma = [
            'fechas' => [],
            'impresiones' => [],
            'clics' => [],
            'ctr' => []
        ];

        foreach ($pedido['histograma_diario'] as $fecha => $datos) {
            $histograma['fechas'][] = \Carbon\Carbon::parse($fecha)->locale('es')->isoFormat('D [de] MMMM');
            $histograma['impresiones'][] = $datos['impresiones'];
            $histograma['clics'][] = $datos['clics'];
            $ctr = $datos['impresiones'] > 0 ? round(($datos['clics'] / $datos['impresiones']) * 100, 2) : 0;
            $histograma['ctr'][] = $ctr;
        }

        // Calcular métricas totales
        $displayTakeover = [
            'metricas_totales' => [
                'impresiones' => $pedido['impresiones'],
                'clics' => $pedido['clics'],
                'ctr' => $pedido['impresiones'] > 0 ? round(($pedido['clics'] / $pedido['impresiones']) * 100, 2) : 0
            ],
            'totales_dispositivos' => [
                'mobile' => [
                    'impresiones' => $pedido['dispositivos']['mobile']['impresiones'],
                    'clics' => $pedido['dispositivos']['mobile']['clics'],
                    'ctr' => $pedido['dispositivos']['mobile']['impresiones'] > 0 ? 
                        round(($pedido['dispositivos']['mobile']['clics'] / $pedido['dispositivos']['mobile']['impresiones']) * 100, 2) : 0
                ],
                'desktop' => [
                    'impresiones' => $pedido['dispositivos']['desktop']['impresiones'],
                    'clics' => $pedido['dispositivos']['desktop']['clics'],
                    'ctr' => $pedido['dispositivos']['desktop']['impresiones'] > 0 ? 
                        round(($pedido['dispositivos']['desktop']['clics'] / $pedido['dispositivos']['desktop']['impresiones']) * 100, 2) : 0
                ]
            ],
            'resultados_bloque' => collect($pedido['histograma_diario'])->map(function($item, $fecha) {
                $ctr = $item['impresiones'] > 0 ? round(($item['clics'] / $item['impresiones']) * 100, 2) : 0;
                return [
                    'nombre' => $fecha,
                    'fecha' => $fecha,
                    'impresiones' => $item['impresiones'],
                    'clics' => $item['clics'],
                    'ctr' => $ctr
                ];
            })->values()->all()
        ];

        return view('campañas.display', compact('pedido', 'cliente', 'creatividades', 'linea_pedido', 'displayTakeover', 'histograma'));
    }

    public function showRedes($id)
    {
        // Obtener el usuario de la sesión
        $usuario = session('usuario');
        if (!$usuario) {
            return redirect()->route('login');
        }

        $data = json_decode(File::get(base_path('redes_sociales.json')), true);
        
        // Verificar si existe el pedido
        $pedido = collect($data['pedido'] ?? [])->firstWhere('id', $id);
        if (!$pedido) {
            abort(404, 'Pedido no encontrado');
        }

        // Verificar si existe la línea de pedido
        $lineaPedido = collect($data['linea_pedidos'] ?? [])->firstWhere('id', $pedido['id_lineadepedidos']);
        if (!$lineaPedido) {
            abort(404, 'Línea de pedido no encontrada');
        }

        // Verificar si existe el cliente
        $cliente = collect($data['cliente'] ?? [])->firstWhere('id', $lineaPedido['cliente_id']);
        if (!$cliente) {
            abort(404, 'Cliente no encontrado');
        }

        // Verificar que el pedido pertenece al usuario o es administrador
        if ($usuario['nombre'] !== 'Administrador' && $cliente['nombre'] !== $usuario['nombre']) {
            abort(403, 'No tienes permiso para ver este pedido');
        }

        $creatividades = collect($data['creatividades'] ?? [])->where('pedido_id', $id)->values();
        
        return view('campañas.redes', compact('pedido', 'cliente', 'creatividades'));
    }

    public function createDisplay()
    {
        $data = json_decode(File::get(base_path('f_alto_impacto.json')), true);
        $clientes = $data['clientes'] ?? [];
        return view('/campañas/campañasdisplay/create_display', compact('clientes'));
    }

    public function createBranded()
    {
        $data = json_decode(File::get(base_path('branded_content.json')), true);
        $clientes = $data['cliente'] ?? [];
        return view('/campañas/campañasbranded/create_branded', compact('clientes'));
    }

    public function createRedes()
    {
        $data = json_decode(File::get(base_path('redes_sociales.json')), true);
        $clientes = $data['cliente'] ?? [];
        return view('/campañas/campañasredes/create_redes', compact('clientes'));
    }

    public function storeDisplay(Request $request)
    {
        try {
            $data = json_decode(File::get(base_path('f_alto_impacto.json')), true);
            
            // Crear nuevo ID
            $newId = count($data['pedido']) + 1;
            
            // Crear nueva línea de pedido
            $lineaPedidoId = count($data['linea_pedidos']) + 1;
            $lineaPedido = [
                'id' => $lineaPedidoId,
                'cliente_id' => $request->cliente_id,
                'fecha_hora_inicio' => $request->fecha_hora_inicio,
                'fecha_hora_fin' => $request->fecha_hora_fin,
                'estado' => $request->estado
            ];
            
            // Crear nuevo pedido
            $pedido = [
                'id' => $newId,
                'id_lineadepedidos' => $lineaPedidoId,
                'nombre' => $request->nombre,
                'banner' => $request->banner,
                'fecha_hora_inicio' => $request->fecha_hora_inicio,
                'fecha_hora_fin' => $request->fecha_hora_fin,
                'estado' => $request->estado
            ];
            
            // Agregar a los arrays existentes
            $data['linea_pedidos'][] = $lineaPedido;
            $data['pedido'][] = $pedido;
            
            // Guardar en el archivo JSON
            File::put(base_path('f_alto_impacto.json'), json_encode($data, JSON_PRETTY_PRINT));
            
            return redirect()->route('campañas.index')->with('success', 'Campaña Display creada exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al crear la campaña: ' . $e->getMessage());
        }
    }

    public function storeBranded(Request $request)
    {
        try {
            $data = json_decode(File::get(base_path('branded_content.json')), true);
            
            // Crear nuevo ID
            $newId = count($data['pedido']) + 1;
            
            // Crear nueva línea de pedido
            $lineaPedidoId = count($data['linea_pedidos']) + 1;
            $lineaPedido = [
                'id' => $lineaPedidoId,
                'cliente_id' => $request->cliente_id,
                'fecha_hora_inicio' => $request->fecha_hora_inicio,
                'fecha_hora_fin' => $request->fecha_hora_fin,
                'estado' => $request->estado
            ];
            
            // Crear nuevo pedido
            $pedido = [
                'id' => $newId,
                'id_lineadepedidos' => $lineaPedidoId,
                'nombre' => $request->nombre,
                'contenido' => $request->contenido,
                'fecha_hora_inicio' => $request->fecha_hora_inicio,
                'fecha_hora_fin' => $request->fecha_hora_fin,
                'estado' => $request->estado
            ];
            
            // Agregar a los arrays existentes
            $data['linea_pedidos'][] = $lineaPedido;
            $data['pedido'][] = $pedido;
            
            // Guardar en el archivo JSON
            File::put(base_path('branded_content.json'), json_encode($data, JSON_PRETTY_PRINT));
            
            return redirect()->route('campañas.index')->with('success', 'Campaña Branded Content creada exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al crear la campaña: ' . $e->getMessage());
        }
    }

    public function storeRedes(Request $request)
    {
        try {
            $data = json_decode(File::get(base_path('redes_sociales.json')), true);
            
            // Crear nuevo ID
            $newId = count($data['pedido']) + 1;
            
            // Crear nueva línea de pedido
            $lineaPedidoId = count($data['linea_pedidos']) + 1;
            $lineaPedido = [
                'id' => $lineaPedidoId,
                'cliente_id' => $request->cliente_id,
                'estado' => $request->estado,
                'tipo' => 'Estandar',
                'objetivo' => 0,
                'tarifa' => [
                    'monto' => 0,
                    'moneda' => 'USD'
                ]
            ];
            
            // Crear nuevo pedido
            $pedido = [
                'id' => $newId,
                'id_lineadepedidos' => $lineaPedidoId,
                'nombre' => $request->nombre,
                'estado' => $request->estado,
                'tipo' => 'Estandar',
                'fecha_hora_inicio' => $request->fecha_hora_inicio,
                'fecha_hora_fin' => $request->fecha_hora_fin,
                'facebook' => [
                    'visualizaciones' => 0,
                    'alcance' => 0,
                    'interacciones' => 0,
                    'clics_enlaces' => 0,
                    'reacciones' => 0,
                    'comentarios' => 0,
                    'compartidos' => 0,
                    'guardados' => 0
                ],
                'instagram' => [
                    'visualizaciones' => 0,
                    'alcance' => 0,
                    'interacciones' => 0,
                    'me_gusta' => 0,
                    'comentarios' => 0,
                    'compartidos' => 0,
                    'guardados' => 0
                ]
            ];
            
            // Agregar a los arrays existentes
            $data['linea_pedidos'][] = $lineaPedido;
            $data['pedido'][] = $pedido;
            
            // Guardar en el archivo JSON
            File::put(base_path('redes_sociales.json'), json_encode($data, JSON_PRETTY_PRINT));
            
            return redirect()->route('campañas.index')->with('success', 'Campaña de Redes Sociales creada exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al crear la campaña: ' . $e->getMessage());
        }
    }
}