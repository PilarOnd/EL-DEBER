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

        // Cargar datos de campañas display (reportes - archivo original)
        $displayData = json_decode(File::get(base_path('f_alto_impacto.json')), true);
        $displayReportes = [];
        if (isset($displayData['linea_pedidos'])) {
            foreach ($displayData['linea_pedidos'] as $lineaPedido) {
                $cliente = collect($displayData['clientes'])->firstWhere('id', $lineaPedido['cliente_id']);
                $pedido = collect($displayData['pedido'])->firstWhere('id_lineadepedidos', $lineaPedido['id']);
                if ($lineaPedido && $cliente && $pedido) {
                    // Si es administrador o el cliente_id coincide con el del usuario
                    if ($usuario['nombre'] === 'Administrador' || (isset($usuario['cliente_id']) && $cliente['id'] == $usuario['cliente_id'])) {
                        $lineaPedido['cliente'] = $cliente;
                        $lineaPedido['pedido'] = $pedido;
                        $displayReportes[] = $lineaPedido;
                    }
                }
            }
        }

        // Cargar campañas display creadas desde el formulario
        $displayCreadas = [];
        $displayCreadasPath = base_path('campañas_display_creadas.json');
        if (File::exists($displayCreadasPath)) {
            $displayCreadasData = json_decode(File::get($displayCreadasPath), true);
            if (isset($displayCreadasData['campañas'])) {
                foreach ($displayCreadasData['campañas'] as $campaña) {
                    // Si es administrador o el cliente_id coincide con el del usuario
                    if ($usuario['nombre'] === 'Administrador' || (isset($usuario['cliente_id']) && $campaña['cliente_id'] == $usuario['cliente_id'])) {
                        // Buscar información del cliente si existe en los datos de display
                        $cliente = collect($displayData['clientes'] ?? [])->firstWhere('id', $campaña['cliente_id']);
                        
                                                 // Si no se encuentra el cliente en displayData, buscar en usuarios.json
                         if (!$cliente) {
                             try {
                                 $usuariosData = json_decode(File::get(base_path('usuarios.json')), true);
                                 $usuarioCliente = collect($usuariosData['usuarios'])->firstWhere('cliente_id', $campaña['cliente_id']);
                                 
                                 if ($usuarioCliente) {
                                     $cliente = [
                                         'id' => $campaña['cliente_id'],
                                         'nombre' => $usuarioCliente['nombre']
                                     ];
                                 } else {
                                     $cliente = [
                                         'id' => $campaña['cliente_id'],
                                         'nombre' => 'Cliente ID: ' . $campaña['cliente_id']
                                     ];
                                 }
                             } catch (\Exception $e) {
                                 $cliente = [
                                     'id' => $campaña['cliente_id'],
                                     'nombre' => 'Cliente ID: ' . $campaña['cliente_id']
                                 ];
                             }
                         }
                        
                        // Agregar información del cliente a la campaña
                        $campaña['cliente'] = $cliente;
                        
                        // Marcar como campaña creada desde formulario
                        $campaña['es_nueva'] = true;
                        
                        $displayCreadas[] = $campaña;
                    }
                }
            }
        }

        // Combinar campañas de reportes y creadas
        $display = array_merge($displayReportes, $displayCreadas);

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

    // Métodos y funciones de reportes migrados a ReporteController:
    // - showBranded
    // - showDisplay
    // - showRedes
    // - showDigital
    // - calcularTotalesBranded
    // - calcularTotales
    // - calcularEfectividad

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

    public function verJson()
    {
        try {
            $jsonPath = base_path('campañas_display_creadas.json');
            
            if (!File::exists($jsonPath)) {
                return response()->json([
                    'error' => 'Archivo no encontrado',
                    'message' => 'El archivo de campañas display no existe aún'
                ], 404);
            }
            
            $data = json_decode(File::get($jsonPath), true);
            
            return response()->json($data, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al leer el archivo',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function indexDisplay()
    {
        try {
            // Cargar campañas display creadas desde el formulario
            $jsonPath = base_path('campañas_display_creadas.json');
            $campañas = [];
            $metadata = [
                'total_campañas' => 0,
                'ultima_actualizacion' => 'N/A'
            ];

            if (File::exists($jsonPath)) {
                $data = json_decode(File::get($jsonPath), true);
                $campañas = $data['campañas'] ?? [];
                $metadata = $data['metadata'] ?? $metadata;

                // Obtener usuario actual para filtrar campañas
                $usuario = session('usuario');
                if ($usuario && $usuario['nombre'] !== 'Administrador') {
                    // Filtrar solo las campañas del usuario actual
                    $campañas = array_filter($campañas, function($campaña) use ($usuario) {
                        return isset($usuario['cliente_id']) && $campaña['cliente_id'] == $usuario['cliente_id'];
                    });
                }
            }

            // Calcular estadísticas
            $totalCampañas = count($campañas);
            $campañasActivas = count(array_filter($campañas, function($c) { return $c['estado'] === 'activo'; }));
            $cpmTotal = array_sum(array_column($campañas, 'cpm'));
            
            // Formatear fecha de última actualización
            $ultimaActualizacion = 'N/A';
            if (isset($metadata['ultima_actualizacion']) && $metadata['ultima_actualizacion'] !== 'N/A') {
                try {
                    $ultimaActualizacion = \Carbon\Carbon::parse($metadata['ultima_actualizacion'])->format('d/m/Y H:i');
                } catch (\Exception $e) {
                    $ultimaActualizacion = 'N/A';
                }
            }

            return view('campañas.campañasdisplay.index_display', compact(
                'campañas',
                'totalCampañas',
                'campañasActivas',
                'cpmTotal',
                'ultimaActualizacion'
            ));

        } catch (\Exception $e) {
            Log::error('Error al cargar campañas display:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return view('campañas.campañasdisplay.index_display', [
                'campañas' => [],
                'totalCampañas' => 0,
                'campañasActivas' => 0,
                'cpmTotal' => 0,
                'ultimaActualizacion' => 'N/A',
                'error' => 'Error al cargar las campañas: ' . $e->getMessage()
            ]);
        }
    }

    public function createDisplay()
    {
        try {
            $jsonPath = base_path('medidas.json');
            
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

            // Cargar lista de clientes para el selector
            $clientes = [];
            $usuario = session('usuario');
            
            if ($usuario['nombre'] === 'Administrador') {
                // Si es administrador, cargar todos los clientes disponibles desde usuarios.json
                $usuariosData = json_decode(File::get(base_path('usuarios.json')), true);
                $clientes = [];
                
                // Filtrar solo usuarios con rol 'cliente' (excluir administrador)
                foreach ($usuariosData['usuarios'] as $usuarioData) {
                    if ($usuarioData['rol'] === 'cliente') {
                        $clientes[] = [
                            'id' => $usuarioData['cliente_id'],
                            'nombre' => $usuarioData['nombre']
                        ];
                    }
                }
            }

            return view('campañas.campañasdisplay.create_display', [
                'medidasBanners' => $medidasBanners,
                'clientes' => $clientes,
                'esAdministrador' => $usuario['nombre'] === 'Administrador'
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar medidas de banners:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // En caso de error, usar los valores por defecto
            $usuario = session('usuario');
            $clientes = [];
            
            if ($usuario['nombre'] === 'Administrador') {
                try {
                    $usuariosData = json_decode(File::get(base_path('usuarios.json')), true);
                    $clientes = [];
                    
                    // Filtrar solo usuarios con rol 'cliente' (excluir administrador)
                    foreach ($usuariosData['usuarios'] as $usuarioData) {
                        if ($usuarioData['rol'] === 'cliente') {
                            $clientes[] = [
                                'id' => $usuarioData['cliente_id'],
                                'nombre' => $usuarioData['nombre']
                            ];
                        }
                    }
                } catch (\Exception $clienteError) {
                    $clientes = [];
                }
            }
            
            return view('campañas.campañasdisplay.create_display', [
                'medidasBanners' => $this->medidasBannersDefault,
                'clientes' => $clientes,
                'esAdministrador' => $usuario['nombre'] === 'Administrador',
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
            // Validar los datos del formulario
            $validationRules = [
                'nombre_campaña' => 'required|string|max:255',
                'cpm' => 'required',
                'cpm_otro' => 'nullable|numeric|min:1',
                'posicion' => 'required|string',
                'device_target' => 'required|in:desktop,mobile,both',
                'fecha_inicio_date' => 'required|date|after_or_equal:today',
                'fecha_fin_date' => 'required|date|after_or_equal:fecha_inicio_date',
                'hora_inicio' => 'required|string',
                'hora_fin' => 'required|string',
            ];

            // Si es administrador, validar que seleccione cliente
            $usuario = session('usuario');
            if ($usuario['nombre'] === 'Administrador') {
                $validationRules['cliente_id'] = 'required|numeric|min:1';
            }

            // Validación condicional para CPM
            if ($request->cpm === 'otro') {
                $validationRules['cpm_otro'] = 'required|numeric|min:1';
            } else {
                $validationRules['cpm'] = 'required|numeric|min:1';
            }

            // Agregar validaciones para creatividades basadas en la posición y dispositivo seleccionado
            $this->agregarValidacionesCreatividades($request, $validationRules);

            $request->validate($validationRules, [
                'nombre_campaña.required' => 'El nombre de la campaña es obligatorio.',
                'nombre_campaña.max' => 'El nombre de la campaña no puede exceder 255 caracteres.',
                'cpm.required' => 'Debe seleccionar o ingresar un valor de CPM.',
                'cpm.numeric' => 'El CPM debe ser un número válido.',
                'cpm_otro.required' => 'Debe ingresar el valor de CPM personalizado.',
                'cpm_otro.numeric' => 'El CPM personalizado debe ser un número válido.',
                'posicion.required' => 'Debe seleccionar una posición para la campaña.',
                'device_target.required' => 'Debe seleccionar el tipo de dispositivo.',
                'fecha_inicio_date.required' => 'La fecha de inicio es obligatoria.',
                'fecha_inicio_date.after_or_equal' => 'La fecha de inicio no puede ser anterior a hoy.',
                'fecha_fin_date.required' => 'La fecha de finalización es obligatoria.',
                'fecha_fin_date.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
                'hora_inicio.required' => 'La hora de inicio es obligatoria.',
                'hora_fin.required' => 'La hora de finalización es obligatoria.',
                'cliente_id.required' => 'Debe seleccionar un cliente para la campaña.',
                'cliente_id.numeric' => 'El ID del cliente debe ser un número válido.',
            ]);

            // Procesar el CPM (usar cpm_otro si se seleccionó "otro")
            $cpmFinal = $request->cpm === 'otro' ? $request->cpm_otro : $request->cpm;

            // Procesar las fechas
            $fechaInicio = $request->fecha_inicio_date . 'T' . $request->hora_inicio;
            $fechaFin = $request->fecha_fin_date . 'T' . $request->hora_fin;

            // Manejar subida de creatividades
            $creatividades = $this->procesarCreatividades($request);

            // Cargar datos existentes del nuevo archivo para campañas creadas
            $jsonPath = base_path('campañas_display_creadas.json');
            if (!File::exists($jsonPath)) {
                // Crear estructura inicial si no existe
                $data = [
                    'metadata' => [
                        'version' => '1.0',
                        'descripcion' => 'Archivo para almacenar campañas display creadas desde el formulario',
                        'ultima_actualizacion' => now()->toISOString(),
                        'total_campañas' => 0
                    ],
                    'campañas' => []
                ];
            } else {
                $data = json_decode(File::get($jsonPath), true);
            }

            // Usuario ya obtenido arriba para validaciones
            
            // Determinar cliente_id según el usuario
            if ($usuario['nombre'] === 'Administrador') {
                // Si es administrador, usar el cliente_id del request si se proporciona
                $clienteId = $request->cliente_id ?? 1;
            } else {
                // Si es usuario cliente, usar su cliente_id
                $clienteId = $usuario['cliente_id'] ?? 1;
            }

            // Crear nuevo ID basado en las campañas existentes
            $newId = count($data['campañas']) + 1;
            
            // Crear nueva campaña con estructura simplificada
            $nuevaCampaña = [
                'id' => $newId,
                'cliente_id' => $clienteId,
                'nombre' => $request->nombre_campaña,
                'posicion' => $request->posicion,
                'cpm' => $cpmFinal,
                'device_target' => $request->device_target,
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'estado' => 'activo',
                'creatividades' => $creatividades,
                'created_at' => now()->toISOString(),
                'updated_at' => now()->toISOString(),
                'usuario_creador' => $usuario['nombre'] ?? 'Usuario desconocido'
            ];
            
            // Agregar la nueva campaña al array
            $data['campañas'][] = $nuevaCampaña;
            
            // Actualizar metadata
            $data['metadata']['total_campañas'] = count($data['campañas']);
            $data['metadata']['ultima_actualizacion'] = now()->toISOString();
            
            // Guardar en el archivo JSON
            File::put($jsonPath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            
            Log::info('Campaña display guardada exitosamente:', [
                'campaña_id' => $newId,
                'nombre' => $request->nombre_campaña,
                'archivo_json' => $jsonPath,
                'creatividades_procesadas' => count($creatividades['desktop']) + count($creatividades['mobile'])
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Campaña Display creada exitosamente',
                'redirect' => route('campañas.index'),
                'campaña_id' => $newId
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error al crear campaña display:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al crear la campaña: ' . $e->getMessage()
            ], 500);
        }
    }

    private function procesarCreatividades(Request $request)
    {
        $creatividades = [
            'desktop' => [],
            'mobile' => []
        ];

        // Crear directorio para creatividades si no existe
        $uploadPath = public_path('uploads/creatividades');
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        // Procesar archivos subidos
        foreach ($request->allFiles() as $key => $file) {
            if (strpos($key, 'creatividad_') === 0) {
                // Extraer información del nombre del campo: creatividad_{dispositivo}_{posicion}
                $parts = explode('_', $key);
                if (count($parts) >= 3) {
                    $dispositivo = $parts[1]; // desktop o mobile
                    $posicion = implode('_', array_slice($parts, 2)); // resto del nombre
                    
                    // Obtener información del archivo ANTES de moverlo
                    $nombreOriginal = $file->getClientOriginalName();
                    $tamaño = $file->getSize();
                    $tipo = $file->getClientMimeType();
                    $extension = $file->getClientOriginalExtension();
                    
                    // Generar nombre único para el archivo
                    $nombreArchivo = time() . '_' . $dispositivo . '_' . $posicion . '.' . $extension;
                    
                    // Mover archivo al directorio de uploads
                    $file->move($uploadPath, $nombreArchivo);
                    
                    // Guardar información del archivo
                    $creatividades[$dispositivo][$posicion] = [
                        'nombre_original' => $nombreOriginal,
                        'nombre_archivo' => $nombreArchivo,
                        'ruta' => 'uploads/creatividades/' . $nombreArchivo,
                        'tamaño' => $tamaño,
                        'tipo' => $tipo,
                        'subido_at' => now()->toISOString()
                    ];
                }
            }
        }

        return $creatividades;
    }

    private function agregarValidacionesCreatividades(Request $request, &$validationRules)
    {
        $posicion = $request->posicion;
        $deviceTarget = $request->device_target;
        
        if (!$posicion) return;

        // Obtener las medidas de banners
        try {
            $jsonPath = base_path('medidas.json');
            if (File::exists($jsonPath)) {
                $data = json_decode(File::get($jsonPath), true);
                $medidasBanners = $data['medidas_banners'] ?? $this->medidasBannersDefault;
            } else {
                $medidasBanners = $this->medidasBannersDefault;
            }
        } catch (\Exception $e) {
            $medidasBanners = $this->medidasBannersDefault;
        }

        $medidas = $medidasBanners[$posicion] ?? null;
        if (!$medidas) return;

        // Agregar validaciones para desktop
        if (($deviceTarget === 'desktop' || $deviceTarget === 'both') && isset($medidas['desktop'])) {
            if (is_array($medidas['desktop'])) {
                foreach ($medidas['desktop'] as $pos => $medida) {
                    $fieldName = "creatividad_desktop_{$pos}";
                    $validationRules[$fieldName] = 'required|file|image|max:5120'; // Max 5MB
                }
            } else {
                $validationRules['creatividad_desktop_principal'] = 'required|file|image|max:5120';
            }
        }

        // Agregar validaciones para mobile
        if (($deviceTarget === 'mobile' || $deviceTarget === 'both') && isset($medidas['mobile'])) {
            if (is_array($medidas['mobile'])) {
                foreach ($medidas['mobile'] as $pos => $medida) {
                    $fieldName = "creatividad_mobile_{$pos}";
                    $validationRules[$fieldName] = 'required|file|image|max:5120'; // Max 5MB
                }
            } else {
                $validationRules['creatividad_mobile_principal'] = 'required|file|image|max:5120';
            }
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