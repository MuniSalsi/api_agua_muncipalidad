<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicion;
use App\Models\FotoMedicion;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TxtController extends Controller
{

    public function showFormTxt()
    {
        return view('archivos.procesar_txt');
    }

    public function processTxtFile(Request $request)
    {
        // Validar que el archivo ha sido subido
        $request->validate([
            'txt_file' => 'required|file|mimes:txt|max:10240', // Limitar tamaño de archivo a 10MB
        ]);

        // Obtener el archivo subido
        $file = $request->file('txt_file');

        // Leer el contenido del archivo
        $lines = file($file->getRealPath(), FILE_IGNORE_NEW_LINES);

        foreach ($lines as $line) {
            // Extrae el número de cuenta (por ejemplo, 790, 802, 804)
            preg_match('/\/storage\/mediciones\/(\d+)\//', $line, $matches);
            $accountNumber = $matches[1] ?? null;

            if ($accountNumber) {
                // Busca las mediciones en la base de datos que coincidan con el número de cuenta
                $mediciones = Medicion::where('nro_cuenta', $accountNumber)->get();

                if ($mediciones->isNotEmpty()) {
                    // Extrae la fecha de la cadena de la imagen (formato Y-m-d) o timestamp Unix
                    preg_match('/(\d{4}-\d{2}-\d{2})/', $line, $dateMatches);
                    $imageDate = $dateMatches[1] ?? null;

                    // Si no se encuentra una fecha legible, busca un timestamp Unix
                    if (!$imageDate) {
                        preg_match('/(\d{10})$/', $line, $timestampMatches);
                        $imageTimestamp = $timestampMatches[1] ?? null;
                        if ($imageTimestamp) {
                            $imageDate = Carbon::createFromTimestamp($imageTimestamp)->toDateString();
                        }
                    }

                    if ($imageDate) {
                        // Recorre las mediciones para encontrar la que coincida con la fecha de la imagen
                        foreach ($mediciones as $medicion) {
                            $medicionDate = Carbon::parse($medicion->fecha); // La fecha de la medición en formato Y-m-d
                            $imageDateTime = Carbon::createFromFormat('Y-m-d', $imageDate);

                            // Si la fecha de la imagen coincide con la fecha de la medición
                            if ($medicionDate->isSameDay($imageDateTime)) {
                                // Crear un registro en fotos_mediciones
                                FotoMedicion::create([
                                    'url_foto' => $line,
                                    'medicion_id' => $medicion->id,
                                ]);
                            }
                        }
                    }
                }
            }
        }

        return response()->json(['message' => 'Proceso completado.'], 200);
    }
}
