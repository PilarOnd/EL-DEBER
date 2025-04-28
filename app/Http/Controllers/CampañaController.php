<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class CampañaController extends Controller
{
    private function getJsonData()
    {
        $json = file_get_contents(base_path('branded_content.json'));
        return json_decode($json, true);
    }

    private $medidasBannersDefault = [
        'premium' => [
            'desktop' => [
                'top' => '970x90',
                'l1' => '120x600',
                'l2' => '120x600',
                'c1' => '300x250'
            ],
            'mobile' => [
                'top' => '320x100',
                'a1' => '300x250'
            ]
        ],
        'gold' => [
            'desktop' => [
                'a1' => '300x250',
                'a2' => '300x250',
                'c2' => '300x250',
                'sb1' => '300x250',
                'sb2' => '300x600'
            ],
            'mobile' => [
                'a2' => '300x250',
                'a3' => '300x250',
                'c2' => '300x250',
                'a1' => '300x100'
            ]
        ],
        'social' => [
            'desktop' => [
                'c3' => '300x250',
                'c4' => '300x250',
                'layer' => '970x250',
                'c2' => '300x250',
                'sidebar' => '300x600'
            ],
            'mobile' => [
                'cinturon2' => '320x100',
                'c3' => '300x250'
            ]
        ],
        'take_over' => [
            'desktop' => '800x400',
            'mobile' => '300x400'
        ],
        'sticky' => [
            'desktop' => '970x90',
            'mobile' => '320x100'
        ]
    ];

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
        if (isset($displayData['linea_pedidos'])) {
            foreach ($displayData['linea_pedidos'] as $lineaPedido) {
                $cliente = collect($displayData['clientes'])->firstWhere('id', $lineaPedido['cliente_id']);
                $pedido = collect($displayData['pedido'])->firstWhere('id_lineadepedidos', $lineaPedido['id']);
                if ($lineaPedido && $cliente && $pedido) {
                    // Si es administrador o el cliente_id coincide con el del usuario
                    if ($usuario['nombre'] === 'Administrador' || (isset($usuario['cliente_id']) && $cliente['id'] == $usuario['cliente_id'])) {
                        $lineaPedido['cliente'] = $cliente;
                        $lineaPedido['pedido'] = $pedido;
                        $display[] = $lineaPedido;
                    }
                }
            }
        }

        // Cargar datos de campañas branded content
        $brandedData = json_decode(File::get(base_path('branded_content.json')), true);
        $branded = [];
        if (isset($brandedData['linea_pedidos'])) {
            foreach ($brandedData['linea_pedidos'] as $lineaPedido) {
                $cliente = collect($brandedData['cliente'])->firstWhere('id', $lineaPedido['cliente_id']);
                $pedido = collect($brandedData['pedido'])->firstWhere('id_lineadepedidos', $lineaPedido['id']);
                if ($lineaPedido && $cliente && $pedido) {
                    if ($usuario['nombre'] === 'Administrador' || (isset($usuario['cliente_id']) && $cliente['id'] == $usuario['cliente_id'])) {
                        $lineaPedido['cliente'] = $cliente;
                        $lineaPedido['pedido'] = $pedido;
                        $branded[] = $lineaPedido;
                    }
                }
            }
        }

        // Cargar datos de campañas redes sociales
        $redesData = json_decode(File::get(base_path('redes_sociales.json')), true);
        $redes = [];
        if (isset($redesData['linea_pedidos'])) {
            foreach ($redesData['linea_pedidos'] as $lineaPedido) {
                $cliente = collect($redesData['cliente'])->firstWhere('id', $lineaPedido['cliente_id']);
                $pedido = collect($redesData['pedido'])->firstWhere('id_lineadepedidos', $lineaPedido['id']);
                if ($lineaPedido && $cliente && $pedido) {
                    if ($usuario['nombre'] === 'Administrador' || (isset($usuario['cliente_id']) && $cliente['id'] == $usuario['cliente_id'])) {
                        $lineaPedido['cliente'] = $cliente;
                        $lineaPedido['pedido'] = $pedido;
                        $redes[] = $lineaPedido;
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
        // Buscar la línea de pedido por id
        $lineaPedido = collect($data['linea_pedidos'] ?? [])->firstWhere('id', $id);
        if (!$lineaPedido) {
            abort(404, 'Línea de pedido no encontrada');
        }
        // Buscar el pedido relacionado
        $pedido = collect($data['pedido'] ?? [])->firstWhere('id_lineadepedidos', $lineaPedido['id']);
        if (!$pedido) {
            abort(404, 'Pedido no encontrado para la línea de pedido');
        }
        // Buscar el cliente
        $cliente = collect($data['cliente'] ?? [])->firstWhere('id', $lineaPedido['cliente_id']);
        if (!$cliente) {
            abort(404, 'Cliente no encontrado para la línea de pedido');
        }
        // Verificar que el pedido pertenece al usuario o es administrador
        if ($usuario['nombre'] !== 'Administrador' && (!isset($usuario['cliente_id']) || $cliente['id'] != $usuario['cliente_id'])) {
            abort(403, 'No tienes permiso para ver este pedido');
        }
        $creatividades = collect($data['creatividades'] ?? [])->where('pedido_id', $pedido['id'])->values();
        $totales = $this->calcularTotalesBranded($pedido, $creatividades);
        $pedidos = [$pedido];
        return view('campañas.branded', compact('pedido', 'cliente', 'creatividades', 'lineaPedido', 'totales', 'pedidos'));
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
        // Buscar la línea de pedido por id
        $linea_pedido = collect($data['linea_pedidos'])->firstWhere('id', $id);
        if (!$linea_pedido) {
            abort(404, 'Línea de pedido no encontrada');
        }
        // Buscar el pedido relacionado
        $pedido = collect($data['pedido'])->firstWhere('id_lineadepedidos', $linea_pedido['id']);
        if (!$pedido) {
            abort(404, 'Pedido no encontrado para la línea de pedido');
        }
        $cliente = collect($data['clientes'])->firstWhere('id', $linea_pedido['cliente_id']);
        if (!$cliente) {
            abort(404, 'Cliente no encontrado para la línea de pedido');
        }
        // Verificar que el pedido pertenece al usuario o es administrador
        if (!$cliente || ($usuario['nombre'] !== 'Administrador' && $cliente['nombre'] !== $usuario['nombre'])) {
            abort(403, 'No tienes permiso para ver este pedido');
        }
        $creatividades = collect($data['creatividades'])->where('pedido_id', $pedido['id'])->values();
        // Calcular métricas
        $totalImpresiones = $pedido['impresiones'] ?? 0;
        $porcentajePresupuesto = $linea_pedido['objetivo'] > 0 ? min(100, ($totalImpresiones / $linea_pedido['objetivo']) * 100) : 0;
        $efectividad = $linea_pedido['objetivo'] > 0 ? round(($totalImpresiones / $linea_pedido['objetivo']) * 100, 2) : 0;
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
        return view('campañas.display', compact('pedido', 'cliente', 'creatividades', 'linea_pedido', 'displayTakeover', 'histograma', 'totalImpresiones', 'porcentajePresupuesto', 'efectividad'));
    }

    public function showRedes($id)
    {
        // Obtener el usuario de la sesión
        $usuario = session('usuario');
        if (!$usuario) {
            return redirect()->route('login');
        }

        $data = json_decode(File::get(base_path('redes_sociales.json')), true);
        // Buscar la línea de pedido por id
        $lineaPedido = collect($data['linea_pedidos'] ?? [])->firstWhere('id', $id);
        if (!$lineaPedido) {
            abort(404, 'Línea de pedido no encontrada');
        }
        // Buscar los pedidos relacionados
        $pedidos = collect($data['pedido'] ?? [])->where('id_lineadepedidos', $lineaPedido['id'])->values();
        if ($pedidos->isEmpty()) {
            abort(404, 'Pedidos no encontrados para la línea de pedido');
        }
        // Buscar el cliente
        $cliente = collect($data['cliente'] ?? [])->firstWhere('id', $lineaPedido['cliente_id']);
        if (!$cliente) {
            abort(404, 'Cliente no encontrado para la línea de pedido');
        }
        // Verificar que el pedido pertenece al usuario o es administrador
        if ($usuario['nombre'] !== 'Administrador' && (!isset($usuario['cliente_id']) || $cliente['id'] != $usuario['cliente_id'])) {
            abort(403, 'No tienes permiso para ver este pedido');
        }
        $creatividades = collect($data['creatividades'] ?? [])->whereIn('pedido_id', $pedidos->pluck('id'))->values();
        // Calcular métricas
        $totalImpresiones = 0;
        foreach ($pedidos as $pedido) {
            if (isset($pedido['facebook']['visualizaciones'])) {
                $totalImpresiones += $pedido['facebook']['visualizaciones'];
            }
            if (isset($pedido['instagram']['visualizaciones'])) {
                $totalImpresiones += $pedido['instagram']['visualizaciones'];
            }
        }
        $porcentajePresupuesto = $lineaPedido['objetivo'] > 0 ? min(100, ($totalImpresiones / $lineaPedido['objetivo']) * 100) : 0;
        $efectividad = $lineaPedido['objetivo'] > 0 ? round(($totalImpresiones / $lineaPedido['objetivo']) * 100, 2) : 0;
        return view('campañas.campañas_digitales', compact('pedidos', 'cliente', 'creatividades', 'lineaPedido', 'totalImpresiones', 'porcentajePresupuesto', 'efectividad'));
    }

    public function createDisplay()
    {
        try {
            $jsonPath = base_path('f_alto_impacto.json');
            
            if (!File::exists($jsonPath)) {
                throw new \Exception('El archivo de medidas de banners no existe');
            }

            $jsonContent = File::get($jsonPath);
            $data = json_decode($jsonContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Error al decodificar el JSON: ' . json_last_error_msg());
            }

            $medidasBanners = $data['medidas_banners'] ?? null;

            if (!$medidasBanners) {
                Log::warning('No se encontraron medidas de banners en el JSON, usando valores por defecto');
                $medidasBanners = $this->medidasBannersDefault;
            }

            Log::info('Datos que se enviarán a la vista:', [
                'medidas_banners' => $medidasBanners,
                'tiene_datos' => !empty($medidasBanners),
                'claves_disponibles' => array_keys($medidasBanners)
            ]);

            return view('campañas.campañasdisplay.create_display', [
                'medidasBanners' => $medidasBanners
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar medidas de banners:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // En caso de error, usar los valores por defecto
            return view('campañas.campañasdisplay.create_display', [
                'medidasBanners' => $this->medidasBannersDefault,
                'error' => 'Error al cargar las medidas de banners: ' . $e->getMessage()
            ]);
        }
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