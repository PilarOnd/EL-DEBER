<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ReporteController extends Controller
{
    public function index()
    {
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

        return view('reportes.index', compact('display', 'branded', 'redes'));
    }

    public function showBranded($id)
    {
        $usuario = session('usuario');
        if (!$usuario) {
            return redirect()->route('login');
        }
        $data = json_decode(File::get(base_path('branded_content.json')), true);
        $lineaPedido = collect($data['linea_pedidos'] ?? [])->firstWhere('id', $id);
        if (!$lineaPedido) abort(404, 'Línea de pedido no encontrada');
        $pedido = collect($data['pedido'] ?? [])->firstWhere('id_lineadepedidos', $lineaPedido['id']);
        if (!$pedido) abort(404, 'Pedido no encontrado para la línea de pedido');
        $cliente = collect($data['cliente'] ?? [])->firstWhere('id', $lineaPedido['cliente_id']);
        if (!$cliente) abort(404, 'Cliente no encontrado para la línea de pedido');
        if ($usuario['nombre'] !== 'Administrador' && (!isset($usuario['cliente_id']) || $cliente['id'] != $usuario['cliente_id'])) abort(403, 'No tienes permiso para ver este pedido');
        $creatividades = collect($data['creatividades'] ?? [])->where('pedido_id', $pedido['id'])->values();
        $totales = $this->calcularTotalesBranded($pedido, $creatividades);
        $pedidos = [$pedido];
        return view('reportes.branded', compact('pedido', 'cliente', 'creatividades', 'lineaPedido', 'totales', 'pedidos'));
    }

    public function showDisplay($id)
    {
        $usuario = session('usuario');
        if (!$usuario) return redirect()->route('login');
        $data = json_decode(File::get(base_path('f_alto_impacto.json')), true);
        $linea_pedido = collect($data['linea_pedidos'])->firstWhere('id', $id);
        if (!$linea_pedido) abort(404, 'Línea de pedido no encontrada');
        $pedido = collect($data['pedido'])->firstWhere('id_lineadepedidos', $linea_pedido['id']);
        if (!$pedido) abort(404, 'Pedido no encontrado para la línea de pedido');
        $cliente = collect($data['clientes'])->firstWhere('id', $linea_pedido['cliente_id']);
        if (!$cliente) abort(404, 'Cliente no encontrado para la línea de pedido');
        if (!$cliente || ($usuario['nombre'] !== 'Administrador' && $cliente['nombre'] !== $usuario['nombre'])) abort(403, 'No tienes permiso para ver este pedido');
        $creatividades = collect($data['creatividades'])->where('pedido_id', $pedido['id'])->values();
        $totalImpresiones = $pedido['impresiones'] ?? 0;
        $porcentajePresupuesto = $linea_pedido['objetivo'] > 0 ? min(100, ($totalImpresiones / $linea_pedido['objetivo']) * 100) : 0;
        $efectividad = $linea_pedido['objetivo'] > 0 ? round(($totalImpresiones / $linea_pedido['objetivo']) * 100, 2) : 0;
        $histograma = [ 'fechas' => [], 'impresiones' => [], 'clics' => [], 'ctr' => [] ];
        foreach ($pedido['histograma_diario'] as $fecha => $datos) {
            $histograma['fechas'][] = \Carbon\Carbon::parse($fecha)->locale('es')->isoFormat('D [de] MMMM');
            $histograma['impresiones'][] = $datos['impresiones'];
            $histograma['clics'][] = $datos['clics'];
            $ctr = $datos['impresiones'] > 0 ? round(($datos['clics'] / $datos['impresiones']) * 100, 2) : 0;
            $histograma['ctr'][] = $ctr;
        }
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
                    'ctr' => $pedido['dispositivos']['mobile']['impresiones'] > 0 ? round(($pedido['dispositivos']['mobile']['clics'] / $pedido['dispositivos']['mobile']['impresiones']) * 100, 2) : 0
                ],
                'desktop' => [
                    'impresiones' => $pedido['dispositivos']['desktop']['impresiones'],
                    'clics' => $pedido['dispositivos']['desktop']['clics'],
                    'ctr' => $pedido['dispositivos']['desktop']['impresiones'] > 0 ? round(($pedido['dispositivos']['desktop']['clics'] / $pedido['dispositivos']['desktop']['impresiones']) * 100, 2) : 0
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
        return view('reportes.display', compact('pedido', 'cliente', 'creatividades', 'linea_pedido', 'displayTakeover', 'histograma', 'totalImpresiones', 'porcentajePresupuesto', 'efectividad'));
    }

    public function showRedes($id)
    {
        $usuario = session('usuario');
        if (!$usuario) return redirect()->route('login');
        $data = json_decode(File::get(base_path('redes_sociales.json')), true);
        $lineaPedido = collect($data['linea_pedidos'] ?? [])->firstWhere('id', $id);
        if (!$lineaPedido) abort(404, 'Línea de pedido no encontrada');
        $pedidos = collect($data['pedido'] ?? [])->where('id_lineadepedidos', $lineaPedido['id'])->values();
        if ($pedidos->isEmpty()) abort(404, 'Pedidos no encontrados para la línea de pedido');
        $cliente = collect($data['cliente'] ?? [])->firstWhere('id', $lineaPedido['cliente_id']);
        if (!$cliente) abort(404, 'Cliente no encontrado para la línea de pedido');
        if ($usuario['nombre'] !== 'Administrador' && (!isset($usuario['cliente_id']) || $cliente['id'] != $usuario['cliente_id'])) abort(403, 'No tienes permiso para ver este pedido');
        $creatividades = collect($data['creatividades'] ?? [])->whereIn('pedido_id', $pedidos->pluck('id'))->values();
        $totalImpresiones = 0;
        foreach ($pedidos as $pedido) {
            if (isset($pedido['facebook']['visualizaciones'])) $totalImpresiones += $pedido['facebook']['visualizaciones'];
            if (isset($pedido['instagram']['visualizaciones'])) $totalImpresiones += $pedido['instagram']['visualizaciones'];
        }
        $porcentajePresupuesto = $lineaPedido['objetivo'] > 0 ? min(100, ($totalImpresiones / $lineaPedido['objetivo']) * 100) : 0;
        $efectividad = $lineaPedido['objetivo'] > 0 ? round(($totalImpresiones / $lineaPedido['objetivo']) * 100, 2) : 0;
        return view('reportes.campañas_digitales', compact('pedidos', 'cliente', 'creatividades', 'lineaPedido', 'totalImpresiones', 'porcentajePresupuesto', 'efectividad'));
    }

    public function showDigital($id)
    {
        $json = file_get_contents(base_path('redes_sociales.json'));
        $data = json_decode($json, true);
        $lineaPedido = collect($data['linea_pedidos'])->firstWhere('id', $id);
        if (!$lineaPedido) abort(404, 'Campaña no encontrada');
        $cliente = collect($data['cliente'])->firstWhere('id', $lineaPedido['cliente_id']);
        if (!$cliente) abort(404, 'Cliente no encontrado');
        $pedidos = collect($data['pedido'])->where('id_lineadepedidos', $lineaPedido['id'])->all();
        $totalImpresiones = collect($pedidos)->sum(function($p) { return $p['facebook']['visualizaciones'] + $p['instagram']['visualizaciones']; });
        $efectividad = $this->calcularEfectividad($totalImpresiones, $lineaPedido['objetivo']);
        $creatividades = collect($data['creatividades'])->filter(function($creatividad) use ($pedidos) { return collect($pedidos)->pluck('id')->contains($creatividad['pedido_id']); })->map(function($creatividad) { $creatividad['redes_sociales'] = array_map('ucfirst', $creatividad['redes_sociales']); return $creatividad; })->values()->all();
        if (empty($creatividades)) { \Log::info('No se encontraron creatividades para el pedido'); }
        $porcentajePresupuesto = $lineaPedido['objetivo'] > 0 ? min(100, ($totalImpresiones / $lineaPedido['objetivo']) * 100) : 0;
        return view('reportes.campañas_digitales', compact('cliente', 'lineaPedido', 'pedidos', 'creatividades', 'totalImpresiones', 'efectividad', 'porcentajePresupuesto'));
    }

    // Funciones auxiliares
    private function calcularTotalesBranded($pedido, $creatividades)
    {
        $totales = [ 'impresiones' => 0, 'clics' => 0, 'detalles_por_creatividad' => [] ];
        foreach ($creatividades as $creatividad) {
            $impresiones = 0; $clics = 0;
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
                $impresiones = $pedido['web']['vistas'] ?? 0;
                $clics = $pedido['web']['usuarios_activos'] ?? 0;
                $dispositivo = $creatividad['dispositivo'];
                if (is_array($dispositivo)) {
                    $impresiones = $pedido[$dispositivo[0]]['visualizaciones'] ?? 0;
                    $clics = $pedido[$dispositivo[0]]['interacciones'] ?? 0;
                }
            }
            $ctr = $impresiones > 0 ? round(($clics / $impresiones) * 100, 2) : 0;
            $totales['detalles_por_creatividad'][] = [ 'impresiones' => $impresiones, 'clics' => $clics, 'ctr' => $ctr ];
            $totales['impresiones'] += $impresiones;
            $totales['clics'] += $clics;
        }
        $totales['ctr'] = $totales['impresiones'] > 0 ? round(($totales['clics'] / $totales['impresiones']) * 100, 2) : 0;
        return $totales;
    }
    private function calcularTotales($creatividades)
    {
        $impresiones = 0; $clics = 0;
        foreach ($creatividades as $creatividad) {
            if (isset($creatividad['rendimiento']['metricas'])) {
                $impresiones += $creatividad['rendimiento']['metricas']['impresiones'] ?? 0;
                $clics += $creatividad['rendimiento']['metricas']['clics'] ?? 0;
            }
        }
        return [ 'impresiones' => $impresiones, 'clics' => $clics, 'ctr' => $impresiones > 0 ? round(($clics / $impresiones) * 100, 2) : 0 ];
    }
    private function calcularEfectividad($logrado, $objetivo, $maximo = 100)
    {
        if ($objetivo <= 0) return 0;
        $porcentaje = ($logrado / $objetivo) * 100;
        return round(min($porcentaje, $maximo), 2);
    }
}
