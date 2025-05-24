<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Tag(
 *     name="Métricas",
 *     description="Estadísticas generales del sistema"
 * )
 */
class MetricaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/metricas",
     *     summary="Obtener métricas generales del sistema",
     *     tags={"Métricas"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Resumen de métricas",
     *         @OA\JsonContent(
     *             @OA\Property(property="libro_mas_solicitado", type="object",
     *                 @OA\Property(property="libro_id", type="integer", example=3),
     *                 @OA\Property(property="total", type="integer", example=10)
     *             ),
     *             @OA\Property(property="ranking_libros", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="libro_id", type="integer", example=2),
     *                     @OA\Property(property="total", type="integer", example=5)
     *                 )
     *             ),
     *             @OA\Property(property="prestamos_activos", type="integer", example=4),
     *             @OA\Property(property="total_prestamos", type="integer", example=20),
     *             @OA\Property(property="total_entregados", type="integer", example=16)
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Solo los administradores pueden consultar métricas"
     *     )
     * )
     */
    public function resumen()
    {
        if (auth('api')->user()->role !== 'admin') {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        return [
            'libro_mas_solicitado' => Prestamo::select('libro_id', DB::raw('count(*) as total'))
                ->groupBy('libro_id')
                ->orderByDesc('total')
                ->with('libro')
                ->first(),

            'ranking_libros' => Prestamo::select('libro_id', DB::raw('count(*) as total'))
                ->groupBy('libro_id')
                ->orderByDesc('total')
                ->with('libro')
                ->get(),

            'prestamos_activos' => Prestamo::where('estado', 'pendiente')->count(),

            'total_prestamos' => Prestamo::count(),
            'total_entregados' => Prestamo::where('estado', 'entregado')->count(),
        ];
    }
}