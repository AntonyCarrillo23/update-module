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
    public function extraer_documentos(){
        
        $documentos = DB::table('documento')
                        ->where('OrganoLinealId',25)
                        ->orderBy('fecha_documento','desc')
                        ->paginate(10);
        foreach ($documentos as $documento) {
                // Verifica si la URL es accesible
                $response = Http::head($documento->pdf_convenio);
                if($response->successful()){
                    $documento->url_valida =true;
                }else{
                    $documento->url_valida =false;
                }
                
        }
        //$tipo_doc=$documentos['tipo_documental_id'];
        $oficinas=DB::table('organos_lineales')
                        ->get();

        $tipoDoc=DB::table('tipo_documental')
                    ->get();

        return view('listar',[
            'documentos' => $documentos,
            'oficinas'=>$oficinas,
            'tipoDoc' => $tipoDoc
        ]);
    }

        
}
