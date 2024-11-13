<?php

namespace App\Http\Controllers;

use App\Models\Medicion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MedicionController extends Controller
{
    public function index()
    {
        $listadoMediciones = Medicion::all();

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
            $data = [
                'id' => $medicion->id,
                'nro_cuenta' => $medicion->nro_cuenta,
                'ruta' => $medicion->ruta,
                'orden' => $medicion->orden,
                'medicion' => $medicion->medicion,
                'consumo' => $medicion->consumo,
                'fecha' => $medicion->fecha,
                'estado' => $medicion->estado_id,
                'imagenes' => $urlsPublicas, // Agregar URLs de imágenes
            ];

            $mediciones[] = $data;
        }

        return response()->json($mediciones);
    }

    public function store(Request $request)
    {
        // Validar los datos de entrada
        $validatedData = $request->validate([
            'mediciones' => 'required|array',
            'mediciones.*.nroCuenta' => 'required|integer',
            'mediciones.*.ruta' => 'required|integer',
            'mediciones.*.orden' => 'required|integer',
            'mediciones.*.medicion' => 'required|numeric',
            'mediciones.*.consumo' => 'nullable|numeric',
            'mediciones.*.fecha' => 'nullable|date_format:Y-m-d',
            'mediciones.*.fotoMedidor' => 'nullable|string',
            'mediciones.*.estadoId' => 'required|integer'
        ]);

        $resultados = []; // Array para almacenar los resultados de cada medición

        try {
            // Recorrer el array de mediciones
            foreach ($validatedData['mediciones'] as $data) {
                // Formatear la fecha correctamente, si está presente
                $formattedDate = isset($data['fecha']) ? Carbon::createFromFormat('Y-m-d', $data['fecha'])->format('Y-m-d') : null;

                // Crear una nueva medición
                $medicion = new Medicion();

                // Asignar los datos validados a la medición
                $medicion->nroCuenta = $data['nroCuenta'];
                $medicion->ruta = $data['ruta'];
                $medicion->orden = $data['orden'];
                $medicion->medicion = $data['medicion'];
                $medicion->consumo = $data['consumo'] ?? null;
                $medicion->fecha = $formattedDate;
                $medicion->fotoMedidor = $data['fotoMedidor'] ?? null;
                $medicion->estadoId = $data['estadoId'];

                // Guardar la medición en la base de datos
                if ($medicion->save()) {
                    $resultados[] = [
                        'id' => $medicion->id, // Obtener el ID generado automáticamente
                        'subida' => true
                    ];
                } else {
                    $resultados[] = [
                        'id' => null,
                        'subida' => false
                    ];
                }
            }

            // Retornar la respuesta exitosa con el estado de cada medición
            return response()->json($resultados, 201);
        } catch (\Exception $e) {
            // Manejar cualquier error inesperado
            return response()->json(['error' => 'Error al crear las mediciones', 'message' => $e->getMessage()], 500);
        }
    }

    public function obtenerImagenPorCuentaYFecha($numeroCuenta, $fecha)
    {
        // Buscar el registro en la base de datos
        $mediciones = Medicion::where('nro_cuenta', $numeroCuenta)
            ->whereDate('fecha', $fecha)
            ->get();

        // Verificar si se encontraron registros
        if ($mediciones->isEmpty()) {
            return response()->json(['error' => 'No se encontraron registros'], 404);
        }

        // Construir el nombre del archivo basado en el formato
        $nombreArchivoBase = 'Lectura_' . $numeroCuenta . '_' . $fecha;

        // Construir la ruta del directorio en el almacenamiento
        $rutaDirectorio = 'public/mediciones/' . $numeroCuenta;
        $archivos = Storage::files($rutaDirectorio);

        // Filtrar los archivos que coincidan con el formato
        $archivosEncontrados = array_filter($archivos, function ($archivo) use ($nombreArchivoBase) {
            return strpos(basename($archivo), $nombreArchivoBase) === 0;
        });

        // Verificar si se encontraron archivos
        if (empty($archivosEncontrados)) {
            return response()->json(['error' => 'Archivos no encontrados'], 404);
        }

        // Obtener las URLs públicas de los archivos encontrados
        $urlsPublicas = array_map(function ($archivo) {
            return Storage::url($archivo);
        }, $archivosEncontrados);

        return response()->json(['urls' => $urlsPublicas]);
    }

    // Cargar multiples medicions - test:
    public function cargarMediciones(Request $request)
    {
        $data = $request->input('mediciones');

        if (!is_array($data) || empty($data)) {
            return response()->json(['message' => 'No se recibieron datos válidos.'], 400);
        }

        try {
            foreach ($data as $item) {
                // Guarda los datos sin validación
                Medicion::updateOrCreate(
                    ['nro_cuenta' => $item['nro_cuenta'], 'fecha' => $item['fecha']], // Busca el registro por estos campos
                    $item // Campos a guardar
                );
            }

            return response()->json(['message' => 'Mediciones cargadas con éxito.'], 200);
        } catch (\Exception $e) {

            Log::error('Error al cargar mediciones: ' . $e->getMessage());

            return response()->json(['message' => 'Error al cargar mediciones.'], 500);
        }
    }

    public function exportarMediciones(Request $request)
    {
        // Recibe el período desde el cuerpo de la solicitud
        $periodo = $request->input('periodo');

        // Valida el período recibido
        if (!$periodo) {
            return response()->json(['error' => 'El parámetro "periodo" es requerido'], 400);
        }

        // Obtener el año actual
        $anioActual = Carbon::now()->format('Y');

        // Extraer el número del período usando una expresión regular
        preg_match('/Periodo (\d+)/', $periodo, $matches);

        // Verifica si se encontró un número de periodo
        if (isset($matches[1])) {
            $numeroPeriodo = $matches[1];
            // Formatear el periodo como YYYYPP
            $codigoPeriodo = sprintf('%s%03d', $anioActual, $numeroPeriodo); // Ejemplo: 2024001
        } else {
            return response()->json(['error' => 'El parámetro "periodo" no es válido'], 400);
        }

        // Define el formato de fecha para el archivo
        $fecha = Carbon::now()->format('Y-m-d-H_i');
        $filename = "Export_mediciones_$fecha.txt";
        $filePath = storage_path("app/public/$filename");

        // Consulta los datos filtrados por el período
        $mediciones = DB::table('mediciones')
            ->select('ruta', 'orden', 'nro_cuenta', 'medicion', 'fecha', 'estado_id', 'created_at')
            ->where('periodo', $periodo)
            ->get();

        // Verifica si se encontraron mediciones
        if ($mediciones->isEmpty()) {
            return response()->json(['error' => 'No se encontraron mediciones para el período especificado'], 404);
        }

        // Abre el archivo para escritura
        $file = fopen($filePath, 'w');

        // Inicializa el índice
        $indice = 1;

        // Escribe los datos en el archivo
        foreach ($mediciones as $medicion) {
            // Calcula el valor de 'anomalia' basado en 'estado_id'
            $anomalia = '0000';
            switch ($medicion->estado_id) {
                case 1: // Borroso
                    $anomalia = "0006"; // Anteriormente 'Sin Situación'
                    break;
                case 2: // Con Aire
                    $anomalia = "0005"; // Anteriormente 'Sin Instalar'
                    break;
                case 3: // En Interior
                    $anomalia = "0004"; // Anteriormente 'Tapado'
                    break;
                case 4: // Roto
                    $anomalia = "0003"; // Anteriormente 'Roto'
                    break;
                case 5: // Sin Instalar
                    $anomalia = "0001"; // Anteriormente 'En Interior'
                    break;
                case 6: // Sin Situación
                    $anomalia = "0000"; // Anteriormente 'Con Aire'
                    break;
                case 7: // Tapado
                    $anomalia = "0002"; // Anteriormente 'Borroso'
                    break;
                    // case 1:
                    //     $anomalia = '0000';
                    //     break;
                    // case 2:
                    //     $anomalia = '0001';
                    //     break;
                    // case 3:
                    //     $anomalia = '0002';
                    //     break;
                    // case 4:
                    //     $anomalia = '0003';
                    //     break;
                    // case 5:
                    //     $anomalia = '0004';
                    //     break;
                    // case 6:
                    //     $anomalia = '0005';
                    //     break;
            }

            // Formatea la fecha y hora
            $fecha = Carbon::parse($medicion->created_at)->format('d/m/Y');
            $hora = Carbon::parse($medicion->created_at)->format('Hi');
            // $hora = Carbon::parse($medicion->created_at)->format('H:i:s');
            $tipo_servicio = 'Servicio de Agua';

            // Formatea los datos en el formato CSV
            $line = sprintf(
                "%s,%d,%s,%s,,%s,,%s,,,,%s,,,%s,%s,%s,%s,0\n",
                $medicion->ruta ?? '',
                $indice++,
                'OBSA0002', // Categoria fija
                $medicion->orden ?? '',
                $medicion->nro_cuenta ? str_pad($medicion->nro_cuenta, 5, '0', STR_PAD_LEFT) : '',
                $medicion->medicion ?? '',
                $anomalia,
                $fecha,
                $hora,
                $codigoPeriodo,
                $tipo_servicio,
            );

            fwrite($file, $line);
        }

        fclose($file);

        // Retorna la respuesta de descarga del archivo
        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function upload(Request $request)
    {
        // Verificar si se recibe un request multipart/form-data
        if (!$request->isMethod('post') || !$request->hasFile('images')) {
            Log::error('Se esperaba un request multipart/form-data con imágenes');
            return response()->json([
                'status' => 'error',
                'message' => 'Se esperaba un request multipart/form-data con imágenes'
            ], 400);
        }

        // Verificar y decodificar JSON
        $medicionesJson = $request->input('mediciones');
        if (!$medicionesJson) {
            Log::error('Se esperaba JSON en el campo "mediciones"');
            return response()->json([
                'status' => 'error',
                'message' => 'Se esperaba JSON en el campo "mediciones"'
            ], 400);
        }

        $data = json_decode($medicionesJson, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Error al decodificar JSON', ['error' => json_last_error_msg()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Error al decodificar JSON',
                'error' => json_last_error_msg()
            ], 400);
        }

        // Validar la solicitud
        $validator = Validator::make($data, [
            '*.id' => 'nullable|integer',
            '*.nroCuenta' => 'required|integer',
            '*.ruta' => 'required|integer',
            '*.orden' => 'required|integer',
            '*.medicion' => 'required|numeric',
            '*.consumo' => 'nullable|numeric',
            '*.fecha' => 'nullable|string',
            '*.fotoMedidor' => 'nullable|string',
            '*.estadoId' => 'required|integer',
        ]);

        if ($validator->fails()) {
            Log::error('Datos de entrada inválidos', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Datos de entrada inválidos',
                'errors' => $validator->errors()
            ], 422); // Unprocessable Entity
        }

        // Guardar las mediciones
        $responses = [];

        foreach ($data as $medicionData) {
            Log::info('Procesando medición', ['data' => $medicionData]);

            $fecha = isset($medicionData['fecha']) ? Carbon::parse($medicionData['fecha'])->format('Y-m-d') : null;
            $periodo = $fecha ? $this->getPeriodo($medicionData['fecha']) : 'Desconocido';
            Log::info('PERIODO CALCULADO: ' . $periodo);
            $mappedData = [
                'nro_cuenta' => $medicionData['nroCuenta'],
                'ruta' => $medicionData['ruta'],
                'orden' => $medicionData['orden'],
                'medicion' => $medicionData['medicion'],
                'consumo' => $medicionData['consumo'] ?? null,
                'fecha' => $fecha,
                'periodo' => $periodo,
                'estado_id' => $medicionData['estadoId'],
            ];
            log::info('MAPPED DATA:' . json_encode($mappedData));

            try {
                $medicion = Medicion::create($mappedData);
                Log::info('Medición guardada', ['medicion_id' => $medicion->id]);

                $responses[] = [
                    'original_id' => $medicionData['id'] ?? null,
                    'created_id' => $medicion->id,
                    'status' => 'created',
                    'subida' => true
                ];
            } catch (\Exception $e) {
                Log::error('Error al guardar medición', ['error' => $e->getMessage()]);
                $responses[] = [
                    'original_id' => $medicionData['id'] ?? null,
                    'status' => 'error',
                    'message' => $e->getMessage(),
                    'subida' => false
                ];
            }
        }

        // Manejo de archivos subidos
        if ($request->hasFile('images')) {
            $files = $request->file('images');
            foreach ($files as $file) {
                // Extraer el nombre del archivo original
                $originalName = $file->getClientOriginalName();
                // Generar un nombre único para evitar conflictos
                $filename = $originalName;

                // Determinar el número de cuenta correspondiente
                $nroCuenta = null;
                foreach ($data as $medicionData) {
                    // Extraer solo el nombre del archivo de la ruta completa
                    $fotoMedidorName = basename($medicionData['fotoMedidor']);
                    if (isset($medicionData['fotoMedidor']) && $fotoMedidorName === $originalName) {
                        $nroCuenta = $medicionData['nroCuenta'];
                        break;
                    }
                }

                if ($nroCuenta) {
                    // Crear la carpeta si no existe
                    $directory = 'mediciones/' . $nroCuenta;
                    if (!Storage::disk('public')->exists($directory)) {
                        Storage::disk('public')->makeDirectory($directory);
                    }

                    // Guardar el archivo en el directorio correspondiente
                    $path = $file->storeAs($directory, $filename, 'public');

                    Log::info('Archivo guardado', ['filename' => $filename, 'directory' => $directory]);
                } else {
                    Log::error('No se encontró la cuenta para la imagen', ['filename' => $originalName]);
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Mediciones procesadas',
            'responses' => $responses
        ]);
    }

    private function getPeriodo($fecha)
    {
        $date = Carbon::parse($fecha);

        // Definir los días de inicio de los períodos
        $rangos = [
            'Periodo 1' => '21/12',
            'Periodo 3' => '21/02',
            'Periodo 5' => '21/04',
            'Periodo 7' => '21/06',
            'Periodo 9' => '21/08',
            'Periodo 11' => '21/10',
        ];

        // Verificar los períodos en el año actual y el anterior
        foreach ($rangos as $periodo => $inicio) {
            // Crear la fecha de inicio para el período en el año de la fecha proporcionada
            $fechaInicio = Carbon::createFromFormat('d/m', $inicio)->year($date->year);

            // Si la fecha es antes del 21 del mes de inicio, considerar el año anterior para ese periodo
            if ($date->lessThan($fechaInicio)) {
                $fechaInicio->subYear(); // Ajustamos al año anterior
            }

            // Si estamos en el 21 de diciembre, deberíamos asignar el año siguiente
            if ($date->format('d/m') === '21/12') {
                // Si es el 21/12, es el inicio del Periodo 1 del año siguiente
                $anio = $date->addYear()->format('y');
                return "{$periodo} - {$anio}";
            }

            // Comprobar si la fecha proporcionada está en el periodo correspondiente
            if ($date->gte($fechaInicio) && $date->lt($fechaInicio->copy()->addMonths(2))) {
                // Si estamos dentro del rango, asignar el periodo con el año siguiente
                if ($date->format('d/m') === '21/12' || $date->gte(Carbon::createFromFormat('d/m', '21/12')->year($date->year))) {
                    $anio = $date->addYear()->format('y');
                    return "{$periodo} - {$anio}";
                } else {
                    // Si no es 21/12, mantener el año actual
                    $anio = $date->format('y');
                    return "{$periodo} - {$anio}";
                }
            }
        }

        return 'Desconocido'; // Si no corresponde a ningún periodo
    }

    // private function getPeriodo($fecha)
    // {
    //     $date = Carbon::parse($fecha);

    //     // Definir los rangos de períodos
    //     $rangos = [
    //         'Periodo 1' => ['21/12', '20/02'],
    //         'Periodo 3' => ['21/02', '20/04'],
    //         'Periodo 5' => ['21/04', '20/06'],
    //         'Periodo 7' => ['21/06', '20/08'],
    //         'Periodo 9' => ['21/08', '20/10'],
    //         'Periodo 11' => ['21/10', '20/12'],
    //     ];

    //     foreach ($rangos as $periodo => [$inicio, $fin]) {
    //         // Crear fecha de inicio en el año de la fecha dada
    //         $fechaInicio = Carbon::createFromFormat('d/m', $inicio)->year($date->year);

    //         // Crear fecha de fin en el año de la fecha dada
    //         $fechaFin = Carbon::createFromFormat('d/m', $fin)->year($date->year);

    //         // Si el fin del periodo es anterior al inicio, incrementa el año de `fechaFin`
    //         if ($fechaFin->lessThan($fechaInicio)) {
    //             $fechaFin->addYear();
    //         }

    //         // Verificar si la fecha está dentro del rango
    //         if ($date->between($fechaInicio, $fechaFin)) {
    //             // Obtener los últimos dos dígitos del año
    //             $anio = $date->format('y');
    //             return "{$periodo} - {$anio}";
    //         }
    //     }

    //     return 'Desconocido'; // Valor por defecto en caso de error
    // }


    private function getPeriodoAnterior($periodoActual)
    {
        // Extraer el número de período y el año del período actual
        preg_match('/Periodo (\d+) - (\d+)/', $periodoActual, $matches);
        if (count($matches) < 3) {
            return 'Desconocido'; // Error en caso de que el formato no coincida
        }

        $numeroPeriodo = (int)$matches[1];
        $anio = (int)$matches[2];

        // Determinar el período anterior
        if ($numeroPeriodo === 1) {
            // Si es el primer período del año, volvemos al último período del año anterior
            $numeroPeriodo = 11;
            $anio--;
        } else {
            // Restamos 2 para ir al período anterior.
            $numeroPeriodo -= 2;
        }

        // Retornamos el período en el formato "Periodo X - YY"
        return "Periodo {$numeroPeriodo} - " . str_pad($anio % 100, 2, '0', STR_PAD_LEFT);
    }

    // Función para obtener la lectura anterior:
    public function getLecturaAnterior($nroCuenta, $fecha)
    {
        // Convertimos la fecha en un formato aceptable:
        $fecha = Carbon::parse($fecha);

        // Consultamos las mediciones anteriores a la fecha mediante el numero de cuenta:
        $mediciones = Medicion::where('nro_cuenta', $nroCuenta)
            ->where('fecha', '<', $fecha)
            ->orderBy('fecha', 'desc')
            ->get();

        // Retornar la lista de mediciones o un mensaje en caso de que no haya resultados
        return $mediciones->isEmpty()
            ? response()->json(['message' => 'No se encontraron mediciones anteriores'], 404)
            : response()->json($mediciones);
    }

    // Función para obtener todas las lecturas anteriores:
    public function getLecturasAnteriores($fecha)
    {
        // Convertimos la fecha en un formato aceptable:
        $fecha = Carbon::parse($fecha);

        // Obtenemos el período actual con la función getPeriodo
        $periodoActual = $this->getPeriodo($fecha);

        // Calculamos el período anterior
        $periodoAnterior = $this->getPeriodoAnterior($periodoActual);

        // return ['Anterior' => $periodoAnterior, 'ACtual' => $periodoActual];

        if ($periodoAnterior === 'Desconocido') {
            return response()->json(['message' => 'No se pudo determinar el período anterior'], 400);
        }

        // Consultamos las mediciones anteriores a la fecha:
        $mediciones = Medicion::with('obtenerEstado')->where('periodo', $periodoAnterior)->get();

        // Modificar el formato de la respuesta para incluir el nombre del estado directamente
        $medicionesConEstado = $mediciones->map(function ($medicion) {
            return [
                'id' => $medicion->id,
                'nro_cuenta' => $medicion->nro_cuenta,
                'ruta' => $medicion->ruta,
                'orden' => $medicion->orden,
                'medicion' => $medicion->medicion,
                'consumo' => $medicion->consumo,
                'fecha' => $medicion->fecha,
                'periodo' => $medicion->periodo,
                'estado' => $medicion->estado_nombre  // Usamos el nuevo atributo "estado_nombre"
            ];
        });

        // Retornar la lista de mediciones o un mensaje en caso de que no haya resultados
        return $medicionesConEstado->isEmpty()
            ? response()->json(['message' => 'No se encontraron mediciones anteriores'], 404)
            : response()->json($medicionesConEstado);
    }
}
