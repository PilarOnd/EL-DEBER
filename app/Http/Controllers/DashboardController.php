<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DashboardController extends Controller
{
    public function index()
    {
        // Obtener el usuario actual de la sesión
        $usuario = session('usuario');
        
        // Si no hay usuario en sesión, redirigir al login
        if (!$usuario) {
            return redirect()->route('login');
        }

        // Cargar todos los archivos JSON
        $altoImpacto = json_decode(File::get(base_path('f_alto_impacto.json')), true);
        $brandedContent = json_decode(File::get(base_path('branded_content.json')), true);
        $redesSociales = json_decode(File::get(base_path('redes_sociales.json')), true);

        $pedidos = [];
        $lineasPedido = [];

        // Si es admin, mostrar todos los pedidos
        if ($usuario['rol'] === 'admin') {
            // Combinar todos los pedidos
            $pedidos = array_merge(
                array_map(function($pedido) {
                    $pedido['tipo_campaña'] = 'display';
                    return $pedido;
                }, $altoImpacto['pedido'] ?? []),
                array_map(function($pedido) {
                    $pedido['tipo_campaña'] = 'branded';
                    return $pedido;
                }, $brandedContent['pedido'] ?? []),
                array_map(function($pedido) {
                    $pedido['tipo_campaña'] = 'redes';
                    return $pedido;
                }, $redesSociales['pedido'] ?? [])
            );

            // Combinar todas las líneas de pedido
            $lineasPedido = array_merge(
                $altoImpacto['linea_pedidos'] ?? [],
                $brandedContent['linea_pedidos'] ?? [],
                $redesSociales['linea_pedidos'] ?? []
            );
        } else {
            // Si es cliente, mostrar solo sus pedidos
            $clienteId = $usuario['cliente_id'];
            \Log::info('Usuario actual:', ['usuario' => $usuario]);
            \Log::info('Cliente ID:', ['cliente_id' => $clienteId]);
            \Log::info('Branded Content data:', ['data' => $brandedContent]);

            // Obtener pedidos de alto impacto
            if (isset($altoImpacto['pedido'])) {
                foreach ($altoImpacto['pedido'] as $pedido) {
                    $lineaPedido = collect($altoImpacto['linea_pedidos'])->firstWhere('id', $pedido['id_lineadepedidos']);
                    if ($lineaPedido && $lineaPedido['cliente_id'] == $clienteId) {
                        $pedido['tipo_campaña'] = 'display';
                        $pedidos[] = $pedido;
                        $lineasPedido[] = $lineaPedido;
                    }
                }
            }

            // Obtener pedidos de branded content
            if (isset($brandedContent['pedido'])) {
                \Log::info('Branded Content pedidos:', ['pedidos' => $brandedContent['pedido']]);
                foreach ($brandedContent['pedido'] as $pedido) {
                    $lineaPedido = collect($brandedContent['linea_pedidos'])->firstWhere('id', $pedido['id_lineadepedidos']);
                    \Log::info('Línea de pedido encontrada:', ['linea_pedido' => $lineaPedido]);
                    \Log::info('Comparación de IDs:', [
                        'cliente_id_usuario' => $clienteId,
                        'cliente_id_linea' => $lineaPedido ? $lineaPedido['cliente_id'] : null
                    ]);
                    if ($lineaPedido && $lineaPedido['cliente_id'] == $clienteId) {
                        $pedido['tipo_campaña'] = 'branded';
                        $pedidos[] = $pedido;
                        $lineasPedido[] = $lineaPedido;
                    }
                }
            }

            // Obtener pedidos de redes sociales
            if (isset($redesSociales['pedido'])) {
                foreach ($redesSociales['pedido'] as $pedido) {
                    $lineaPedido = collect($redesSociales['linea_pedidos'])->firstWhere('id', $pedido['id_lineadepedidos']);
                    if ($lineaPedido && $lineaPedido['cliente_id'] == $clienteId) {
                        $pedido['tipo_campaña'] = 'redes';
                        $pedidos[] = $pedido;
                        $lineasPedido[] = $lineaPedido;
                    }
                }
            }
        }

        return view('dashboard', compact('pedidos', 'lineasPedido', 'usuario'));
    }
} 