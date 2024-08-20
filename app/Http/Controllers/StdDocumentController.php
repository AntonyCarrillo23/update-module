<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

use Illuminate\Http\Request;

class StdDocumentController extends Controller
{

    public function ObtCorrelativo(Request $request){
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic dXNlcm1waWFwaTokVXNlck1QMUFQMSU=',
            'Cookie' => 'PHPSESSID=k27808mn4mlo7b0ep2mmqlhsf1'
        ])->post('https://intranet.igp.gob.pe/std/cInterfaseUsuario_SITD/mpi/ws_sad_consulta_correlativo.php', [
            'anio' => $request->input('anio'),
            'unidad_organica' => $request->input('unidad_organica'),
            'tipo_documento' => $request->input('tipo_documento'),
            'correlativo' => $request->input('correlativo'),
        ]);
    
        if ($response->successful()) {
            $data = $response->json();
            return response()->json($data);
        } else {
            return response()->json(['error' => 'Error al enviar los datos.'], 500);
        }
    }

    /*public function ObtCorrelativo(Request $request){
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic dXNlcm1waWFwaTokVXNlck1QMUFQMSU=',
            'Cookie' => 'PHPSESSID=k27808mn4mlo7b0ep2mmqlhsf1'
        ])->post('https://intranet.igp.gob.pe/std/cInterfaseUsuario_SITD/mpi/ws_sad_consulta_correlativo.php', [
            'anio' => $request->input('anio'),
            'unidad_organica' => $request->input('unidad_organica'),
            'tipo_documento' => $request->input(''),
            'correlativo' => $request->input(''),
        ]);
    
        if ($response->successful()) {
            $data = $response->json();
            $resultados = []; // Inicializar un array para almacenar los correlativos y tipos de documento

            foreach ($data['sede'] as $sede) {
                if (isset($sede['nCorrelativo']) && isset($sede['cCodTipoDoc'])) {
                    // Agregar un array asociativo con nCorrelativo y tipo_documento
                    $resultados[] = [
                        'tipo_documento' => $sede['cCodTipoDoc'],
                        'nCorrelativo' => $sede['nCorrelativo'],
                    ];
                }
            }
            return response()->json($resultados); // Retornar el array de resultados
        } else {
            return response()->json(['error' => 'Error al enviar los datos.'], 500);
        }
    }*/

    public function ObtDocumento(Request $request){
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic dXNlcm1waWFwaTokVXNlck1QMUFQMSU=',
            'Cookie' => 'PHPSESSID=k27808mn4mlo7b0ep2mmqlhsf1'
        ])->post('https://intranet.igp.gob.pe/std/cInterfaseUsuario_SITD/mpi/ws_sad_consulta_documento.php', [
            'anio' => $request->input('anio'),
            'unidad_organica' => $request->input('unidad_organica'),
            'tipo_documento' => $request->input('tipo_documento'),
            'correlativo' => $request->input('correlativo'),
        ]);
    
        if ($response->successful()) {
            $data = $response->json();
            return response()->json($data);
        } else {
            return response()->json(['error' => 'Error al enviar los datos.'], 500);
        }
    }

    public function consultar(Request $request)
    {
        
        // Define las variables
        $anio = $request->input('anio', '2023'); 
        $unidadOrganica = $request->input('unidad_organica', '5'); 
        $tipoDocumento = $request->input('tipo_documento', '12'); 
        $correlativo = $request->input('correlativo', ''); 
        
        // Obtener el último correlativo del STD
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic dXNlcm1waWFwaTokVXNlck1QMUFQMSU=',
            'Cookie' => 'PHPSESSID=k27808mn4mlo7b0ep2mmqlhsf1'
        ])->post('https://intranet.igp.gob.pe/std/cInterfaseUsuario_SITD/mpi/ws_sad_consulta_correlativo.php', [
            'anio' => $anio,
            'unidad_organica' => $unidadOrganica,
            'tipo_documento' => $tipoDocumento,
            'correlativo' => $correlativo
        ]);
    
        if ($response->successful()) {
            $resultado = $response->json();
            $ultimoCorrelativoSTD = $resultado['sede'][0]['nCorrelativo'];
    
            // Obtener el último correlativo de la BD
            $ultimoCorrelativoBD = DB::table('std_documents')
                ->where('anio', $anio)
                ->where('tipo_documental_id', $tipoDocumento)
                ->max('nro_correlativo');
    
            if (!$ultimoCorrelativoBD) {
                $ultimoCorrelativoBD = 0; 
            }
    
            // Comparar y realizar inserciones si es necesario
            if ($ultimoCorrelativoSTD > $ultimoCorrelativoBD) {
                $ultimoCorrelativoBD++;
    
                for ($i = $ultimoCorrelativoBD; $i <= $ultimoCorrelativoSTD; $i++) {
                    $response2 = Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Basic dXNlcm1waWFwaTokVXNlck1QMUFQMSU=',
                        'Cookie' => 'PHPSESSID=k27808mn4mlo7b0ep2mmqlhsf1'
                    ])->post('https://intranet.igp.gob.pe/std/cInterfaseUsuario_SITD/mpi/ws_sad_consulta_documento.php', [
                        'anio' => $anio,
                        'unidad_organica' => $unidadOrganica,
                        'tipo_documento' => $tipoDocumento,
                        'correlativo' => $i
                    ]);
    
                    if ($response2->successful()) {
                        $resultado2 = $response2->json();
                        foreach ($resultado2['sede'] as $product2) {
                            $cAsunto = $product2['cAsunto'];
                            $iCodOficinaRegistro = $product2['iCodOficinaRegistro'];
                            $cCodificacion = $product2['cCodificacion'];
                            $cCodTipoDoc = $product2['cCodTipoDoc'];
                            $fFecDocumento = $product2['fFecDocumento'];
                            $cNombreNuevo = $product2['cNombreNuevo'];
                            $anio = date('Y', strtotime($fFecDocumento));
    
                            // Determinar tipo_documental_id basado en cCodTipoDoc
                            //$tipo_documental_id = $this->getTipoDocumentalId($cCodTipoDoc);
    
                            // Insertar en la base de datos
                            DB::table('std_documents')->insert([
                                'asunto' => $cAsunto,
                                'nro_correlativo' => $i,
                                'anio' => $anio,
                                'UnidadOrganicaId' => $iCodOficinaRegistro,
                                'tipo_documental_id' => $cCodTipoDoc,
                                'cCodificacion' => $cCodificacion,
                                'fFecDocumento' => $fFecDocumento,
                                'cNombreNuevo' => $cNombreNuevo
                            ]);
                        }
                    } else {
                        return response()->json(['error' => 'Error al consultar el documento'], 500);
                    }
                }
                return response()->json('Datos insertados en la DB', 200);
            } else {
                return response()->json('El tipo documental se encuentra actualizado', 200);
            }
        } else {
            return response()->json(['error' => 'Error al consultar el correlativo'], 500);
        }
    } 
}
