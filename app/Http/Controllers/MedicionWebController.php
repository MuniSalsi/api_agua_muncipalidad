<?php

namespace App\Http\Controllers;

use App\Models\Medicion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MedicionWebController extends Controller
{
    public function index()
    {
        $listadoMediciones = Medicion::with('obtenerEstado')->get();

        if ($listadoMediciones->isEmpty()) {
            return response()->json(['error' => 'No se cargaron mediciones'], 404);
        }

        $mediciones = [];
        foreach ($listadoMediciones as $medicion) {
            // Obtener el número de cuenta y la fecha para la búsqueda de imágenes
            $numeroCuenta = $medicion->nro_cuenta;
            $fecha = $medicion->fecha;

            // Construir el nombre base del archivo
            $nombreArchivoBase = 'Lectura_' . $numeroCuenta . '_' . $fecha;

            // Construir la ruta del directorio en el almacenamiento
            $rutaDirectorio = 'public/mediciones/' . $numeroCuenta;
            $archivos = Storage::files($rutaDirectorio);

            // Filtrar los archivos que coincidan con el formato
            $archivosEncontrados = array_filter($archivos, function ($archivo) use ($nombreArchivoBase) {
                return strpos(basename($archivo), $nombreArchivoBase) === 0;
            });

            // Obtener las URLs públicas de los archivos encontrados
            $urlsPublicas = array_map(function ($archivo) {
                return Storage::url($archivo);
            }, $archivosEncontrados);

            // Preparar los datos de la medición
            $data = (object)[ // Convertir a objeto anónimo
                'id' => $medicion->id,
                'nro_cuenta' => $medicion->nro_cuenta,
                'ruta' => $medicion->ruta,
                'orden' => $medicion->orden,
                'medicion' => $medicion->medicion,
                'consumo' => $medicion->consumo,
                'fecha' => $medicion->fecha,
                'estado' => $medicion->obtenerEstado->tipo, // Obtener el nombre del estado
                'imagenes' => $urlsPublicas,
            ];

            $mediciones[] = $data;
        }

        return view('index', compact('mediciones'));
    }
}