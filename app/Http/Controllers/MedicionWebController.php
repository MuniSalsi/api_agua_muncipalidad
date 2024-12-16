<?php

namespace App\Http\Controllers;

use App\Models\Medicion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MedicionWebController extends Controller
{
    public function index(Request $request)
    {
        $query = Medicion::with('obtenerEstado'); // Asegúrate de cargar la relación 'obtenerEstado'

        // Configuración para DataTables
        $start = $request->input('start', 0); // Inicio de la paginación
        $length = $request->input('length', 10); // Cantidad de registros por página
        $search = $request->input('search.value', ''); // Filtro de búsqueda

        if ($search) {
            $query->where('nro_cuenta', 'like', "%$search%")
                ->orWhere('ruta', 'like', "%$search%")
                ->orWhere('orden', 'like', "%$search%");
        }

        // Ordenar por fecha de la más nueva a la más antigua (orden descendente)
        $query->orderBy('fecha', 'desc');

        $totalRecords = $query->count(); // Total sin paginación
        $mediciones = $query->offset($start)->limit($length)->get();

        $data = $mediciones->map(function ($medicion) {
            $numeroCuenta = $medicion->nro_cuenta;
            $rutaDirectorio = "public/mediciones/{$numeroCuenta}"; // Ruta donde están almacenadas las imágenes

            // Verifica si el directorio existe
            $archivos = Storage::exists($rutaDirectorio) ? Storage::files($rutaDirectorio) : [];

            // Genera las URLs de todas las imágenes encontradas en el directorio
            $imagenes = array_map(function ($archivo) {
                return Storage::url($archivo); // Genera la URL pública de la imagen
            }, $archivos);

            // Formateamos la fecha en dd/mm/yyyy
            $fechaFormateada = $medicion->fecha ? \Carbon\Carbon::parse($medicion->fecha)->format('d/m/Y') : '';

            // Devuelve la fila de la medición con la información solicitada
            return [
                $medicion->id,
                $medicion->nro_cuenta,
                $medicion->ruta,
                $medicion->orden,
                $medicion->medicion,
                $medicion->consumo ? $medicion->consumo : '<span class="text-danger">Pendiente de calcular</span>',
                $fechaFormateada,  // Usamos la fecha formateada
                // Aquí accedemos al nombre del estado con la relación 'obtenerEstado'
                $medicion->obtenerEstado ? $medicion->obtenerEstado->tipo : 'Estado no disponible', // Cambié nombre_estado a tipo
                $medicion->periodo,
                count($imagenes) > 0 ? $imagenes : []  // Solo pasamos las URLs de las imágenes, si existen
            ];
        });

        return response()->json([
            'draw' => $request->input('draw'), // Obligatorio para DataTables
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $search ? $data->count() : $totalRecords,
            'data' => $data,  // Los datos procesados para la tabla
        ]);
    }

    // public function index(Request $request)
    // {
    //     $query = Medicion::with('obtenerEstado'); // Asegúrate de cargar la relación 'obtenerEstado'

    //     // Configuración para DataTables
    //     $start = $request->input('start', 0); // Inicio de la paginación
    //     $length = $request->input('length', 10); // Cantidad de registros por página
    //     $search = $request->input('search.value', ''); // Filtro de búsqueda

    //     if ($search) {
    //         $query->where('nro_cuenta', 'like', "%$search%")
    //             ->orWhere('ruta', 'like', "%$search%")
    //             ->orWhere('orden', 'like', "%$search%");
    //     }

    //     // Ordenar por fecha de la más nueva a la más antigua (orden descendente)
    //     $query->orderBy('fecha', 'desc');

    //     $totalRecords = $query->count(); // Total sin paginación
    //     $mediciones = $query->offset($start)->limit($length)->get();

    //     $data = $mediciones->map(function ($medicion) {
    //         $numeroCuenta = $medicion->nro_cuenta;
    //         $rutaDirectorio = "public/mediciones/{$numeroCuenta}"; // Ruta donde están almacenadas las imágenes

    //         // Verifica si el directorio existe
    //         $archivos = Storage::exists($rutaDirectorio) ? Storage::files($rutaDirectorio) : [];

    //         // Genera las URLs de todas las imágenes encontradas en el directorio
    //         $imagenes = array_map(function ($archivo) {
    //             return Storage::url($archivo); // Genera la URL pública de la imagen
    //         }, $archivos);

    //         // Devuelve la fila de la medición con la información solicitada
    //         return [
    //             $medicion->id,
    //             $medicion->nro_cuenta,
    //             $medicion->ruta,
    //             $medicion->orden,
    //             $medicion->medicion,
    //             $medicion->consumo ? $medicion->consumo : '<span class="text-danger">Pendiente de calcular</span>',
    //             $medicion->fecha,
    //             // Aquí accedemos al nombre del estado con la relación 'obtenerEstado'
    //             $medicion->obtenerEstado ? $medicion->obtenerEstado->tipo : 'Estado no disponible', // Cambié nombre_estado a tipo
    //             $medicion->periodo,
    //             count($imagenes) > 0 ? $imagenes : []  // Solo pasamos las URLs de las imágenes, si existen
    //         ];
    //     });

    //     return response()->json([
    //         'draw' => $request->input('draw'), // Obligatorio para DataTables
    //         'recordsTotal' => $totalRecords,
    //         'recordsFiltered' => $search ? $data->count() : $totalRecords,
    //         'data' => $data,  // Los datos procesados para la tabla
    //     ]);
    // }
}