<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DocumentoController extends Controller
{
    public function prueba(){
        $documentos = DB::table('documento')->get();

        return view('doc_db',['documentos' => $documentos]);
    }

    public function sinc_std_redoc(Request $request, $id_tipo_doc){
        try{

            $year = $request->input('anio_documento_std');
            //dd($fecha_documento);
            // Define las variables
            $anio = $request->input('anio', $year); 
            $unidadOrganica = $request->input('unidad_organica', '4'); 
            $tipoDocumento = $request->input('tipo_documento', $id_tipo_doc); 
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

                if (!empty($resultado['sede'])) {
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
            
                                            $cadena =$cCodificacion;
                                            // Divide la cadena usando el primer guion como delimitador
                                            $partes = explode('-', $cadena, 3);
                                    
                                            // Combina las dos primeras partes para obtener el número de expediente
                                            $numeroExpediente = $partes[0] . '-' . $partes[1];
                                            $oficina_std=DB::table('oficina_std')
                                                    ->select('idOrg_Lin_std')
                                                    ->where('idOficina',$iCodOficinaRegistro)
                                                    ->first();
            
                                            if ($oficina_std) {
                                                        $idOrg_Lin_std = $oficina_std->idOrg_Lin_std;
                                                    } else {
                                                        $idOrg_Lin_std = null; // o maneja el caso en que no se encuentre ningún registro
                                            }
                                            
                                            // Insertar en la base de datos
                                            DB::table('std_documents')->insert([
                                                'asunto' => $cAsunto,
                                                'nro_correlativo' => $i,
                                                'anio' => $anio,
                                                'OrganoLinealId' => $idOrg_Lin_std,
                                                'UnidadOrganicaId' => $iCodOficinaRegistro,
                                                'tipo_documental_id' => $cCodTipoDoc,
                                                'cCodificacion' => $cCodificacion,
                                                'fFecDocumento' => $fFecDocumento,
                                                'cNombreNuevo' => $cNombreNuevo,
                                                'nro_expediente' =>$numeroExpediente
                                            ]);
                                        }
                                    } else {
                                        return response()->json(['error' => 'Error al consultar el documento!'], 500);
                                    }
                                }

                                return response()->json([
                                    'status' => 'success', 
                                    'message' => 'Datos insertados correctamente'
                                ]);
                            } else {
                                return response()->json([
                                    'status' => 'success', 
                                    'message' => 'El tipo documental se encuentra actualizado'
                                ]);
                            }
                } else{
                    return response()->json(['status' => 'error', 'message' => 'No se encontraron documentos para el tipo documental!'], 404);
                }
            } else {
                return response()->json(['status' => 'error', 'message' => 'Error al consultar el correlativo!'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function extraer_documentos(){
        /*$user=DB::table('user')
            ->get();
        
        $organoLinealUser=7;

        $documentos = DB::table('std_documents')
                    ->join('oficina_std', 'std_documents.OrganoLinealId', '=', 'oficina_std.id_OrganoLineal')
                    ->select('std_documents.asunto', 'oficina_std.NomOficina')
                    ->get();*/
        $organoLinealUser=4;
        $documentos = DB::table('std_documents')
                        ->where('UnidadOrganicaId',$organoLinealUser)
                        ->orderBy('fFecDocumento','desc')
                        ->orderBy('nro_correlativo','desc')
                        ->paginate(10,['*'], 'page_docs');


        $url_base_doc='https://intranet.igp.gob.pe/redoc/assets/uploads/documentos/';
        foreach ($documentos as $documento) {
                // Verifica si la URL es accesible
                $response = Http::head($url_base_doc.$documento->cNombreNuevo);
                if($response->successful()){
                    $documento->url_valida =true;
                }else{
                    $documento->url_valida =false;
                }
                
        }

        $tipo_docs_std=DB::table('tipo_documento_STD')
                        ->get();

        //$tipo_doc=$documentos['tipo_documental_id'];
        $oficinas=DB::table('oficina_std')
                        ->get();
        $tipo_docs_for_modal=DB::table('tipo_documento_STD')
                        ->get();
                        //->paginate(50,['*'], 'page_modal');
       /* $tipoDoc=DB::table('tipo_documento_std')
                    ->get();*/

        return view('listar',[
            'documentos' => $documentos,
            'tipo_docs_std'=>$tipo_docs_std,
            'oficinas'=>$oficinas,
            'tipo_docs_for_modal'=>$tipo_docs_for_modal
            //'tipoDoc' => $tipoDoc
        ]);
    }


    public function editar_documento($id){
        $doc_update=DB::table('std_documents')
        ->where('id',$id)
        ->first();

        $serie_name_doc=DB::table('serie')
        ->select('nombre')
        ->where('id',$doc_update->serie_id)
        ->first();

        $subSerie_name_doc=DB::table('sub_serie')
        ->select('nombre')
        ->where('id',$doc_update->subserie_id)
        ->first();

        $tipoDoc=DB::table('tipo_documental')
        ->select('nombre')
        ->where('id',$doc_update->tipo_documental_id)
        ->first();

        if ($doc_update->OrganoLinealIdVinculo!=null) {
            $org_lin_vinculado=DB::table('organos_lineales')
            ->select('nombre')
            ->where('OrganoLinealId',$doc_update->OrganoLinealIdVinculo)
            ->first();
        }else{
            $org_lin_vinculado=null;
        }

        return view('editar',[
            'doc_update'=>$doc_update,
            'serie_name_doc'=>$serie_name_doc,
            'subSerie_name_doc'=>$subSerie_name_doc,
            'tipoDoc'=>$tipoDoc,
            'org_lin_vinculado'=>$org_lin_vinculado
    ]);
    
    }
    public function actualizar_documento(Request $request, $id)
    {
        // Validar los datos recibidos del formulario
        $validatedData = $request->validate([
            'nro_expediente' => 'required|string|max:255',
            'asunto' => 'required|string|max:255',
            'nro_correlativo' => 'required|int',
            'nro_folios' => 'required|int',
            'fecha_documento' => 'required|date'
        ]);
    
        // Actualizar el registro en la base de datos
        DB::table('documento')
            ->where('id', $id)
            ->update([
                'nro_expediente'=>$validatedData['nro_expediente'],
                'nombre' => $validatedData['asunto'],
                'nro_correlativo'=>$validatedData['nro_correlativo'],
                'nro_folios'=>$validatedData['nro_folios'],
                'fecha_documento'=>$validatedData['fecha_documento'],
                'modified' => now()
            ]);
    
        // Redirigir a la vista 'listar' después de la actualización
        return redirect()->route('listar')->with('success', 'Documento actualizado con éxito');
    }

    /*public function sincronizar_redoc_std(Request $request){
        // Define las variables
        $anio = $request->input('anio', ''); 
        $unidadOrganica = $request->input('unidad_organica', ''); 
        $tipoDocumento = $request->input('tipo_documento', ''); 
        $correlativo = $request->input('correlativo', ''); 

            // Inicializar variables para rastrear el estado
        $datosInsertados = false;
        $datosExistentes = false;
            
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

            for ($k=0; $k <count($resultados) ; $k++) { 
                $ultimoCorrelativoSTD = $resultados[$k]['nCorrelativo'];
                // Obtener el último correlativo de la BD
                    $ultimoCorrelativoBD = DB::table('std_documents')
                    ->where('anio', $anio)
                    ->where('tipo_documental_id', $resultados[$k]['tipo_documento'])
                    ->max('nro_correlativo');

                if (!$ultimoCorrelativoBD) {
                    $ultimoCorrelativoBD = 0; 
                }
                            // Comparar y realizar inserciones si es necesario
                if ($ultimoCorrelativoSTD > $ultimoCorrelativoBD) {
                    $ultimoCorrelativoBD++;
                    $datosInsertados = true; // Marcamos que se insertarán datos

                    for ($i = $ultimoCorrelativoBD; $i <= $ultimoCorrelativoSTD; $i++) {
                        $response2 = Http::withHeaders([
                            'Content-Type' => 'application/json',
                            'Authorization' => 'Basic dXNlcm1waWFwaTokVXNlck1QMUFQMSU=',
                            'Cookie' => 'PHPSESSID=k27808mn4mlo7b0ep2mmqlhsf1'
                        ])->post('https://intranet.igp.gob.pe/std/cInterfaseUsuario_SITD/mpi/ws_sad_consulta_documento.php', [
                            'anio' => $anio,
                            'unidad_organica' => $unidadOrganica,
                            'tipo_documento' => $resultados[$k]['tipo_documento'],
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

                                $cadena =$cCodificacion;
                                // Divide la cadena usando el primer guion como delimitador
                                $partes = explode('-', $cadena, 3);
                        
                                // Combina las dos primeras partes para obtener el número de expediente
                                $numeroExpediente = $partes[0] . '-' . $partes[1];
                                // Determinar tipo_documental_id basado en cCodTipoDoc
                                //$tipo_documental_id = $this->getTipoDocumentalId($cCodTipoDoc);
        
                                // Insertar en la base de datos
                                DB::table('std_documents')->insert([
                                    'asunto' => $cAsunto,
                                    'nro_correlativo' => $i,
                                    'anio' => $anio,
                                    'OrganoLinealId' => $iCodOficinaRegistro,
                                    'tipo_documental_id' => $cCodTipoDoc,
                                    'cCodificacion' => $cCodificacion,
                                    'fFecDocumento' => $fFecDocumento,
                                    'cNombreNuevo' => $cNombreNuevo,
                                    'nro_expediente' =>$numeroExpediente
                                ]);
                            }
                        } else {
                            return response()->json(['error' => 'Error al consultar el documento'], 500);
                        }
                    }
                    //return response()->json('Datos insertados en la DB', 200);
                } else {
                    //return response()->json('El tipo documental se encuentra actualizado', 200);
                    $datosExistentes = true;
                }
            
            }
                    // Verificar el estado y retornar el mensaje adecuado
            if ($datosInsertados) {
                return response()->json('Datos insertados en la DB', 200);
            } elseif ($datosExistentes) {
                return response()->json('El tipo documental se encuentra actualizado', 200);
            } else {
                return response()->json('No se realizaron cambios', 200);
            }

        } else {
            return response()->json(['error' => 'Error al consultar el correlativo'], 500);
        }
    }*/
  
}
