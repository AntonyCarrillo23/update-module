@php
    use Carbon\Carbon;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Redoc</title>
</head>
<body>
    <div class="box" id="listado">
        <div class="box-header">
            <h2>Ordenamiento:</h2>
                    <div class="box-body">
                        @foreach ($documentos as $documento)
                            <div class="active">
                                <div class="repositorio">
                                    <div class="secondary-submision">
                                        <ul class="artifact-list">
                                            <li class="artifact-item">
                                                <div class="item-wrapper">
                                                    <div class="thumbnail-wrapper">
                                                        <div class="artifact-preview">
                                                            @if ($documento->url_valida==true)
                                                                <a href="https://intranet.igp.gob.pe/redoc/assets/uploads/documentos/{{ $documento->pdf_convenio }}" target="_blank">
                                                                    <img src="https://intranet.igp.gob.pe/redoc/themes/Mirage/images/documento-firma.png" alt="Thumbnail">
                                                                </a>
                                                            @else
                                                                    <img src="https://intranet.igp.gob.pe/redoc/themes/Mirage/images/no-file.png" alt="Thumbnail">
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <dvi class="artifact-description">
                                                        <div class="artifact-info">
                                                            <span class="author">
                                                                <b>NRO EXPEDIENTE:</b>
                                                                @if ($documento->nro_expediente!=null)
                                                                    <span>{{$documento->nro_expediente}}</span>
                                                                @else
                                                                    <span>Sin expediente</span>
                                                                @endif
                                                            </span>
                                                            ||
                                                            <span class="publisher-date">
                                                                <span class="publisher">Fecha del documento:</span>
                                                                <span class="date">{{ Carbon::parse($documento->fecha_documento)->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY') }}</span>
                                                            </span>
                                                            <div class="btn-box-tool">
                                                                <a href="" class="btn-info">Editar</a>
                                                                <a href="" class="btn-danger">Eliminar</a>
                                                            </div>
                                                        </div>
                                                        <div class="artifact-title">
                                                            <span>
                                                                <b>{{$documento->nombre}}</b>
                                                            </span>
                                                        </div>
                                                        <div class="artifact-abstract">
                                                            <p>
                                                                <span>
                                                                    Oficina:
                                                                    @foreach ($oficinas as $oficina )
                                                                        @if ($documento->OrganoLinealId==$oficina->OrganoLinealId)
                                                                            {{$oficina->nombre}}
                                                                        @endif
                                                                    @endforeach
                                                                </span>
                                                            </p>
                                                            <p>
                                                                <span>
                                                                    Nro Correlativo: 
                                                                    {{$documento->nro_correlativo}}</span>
                                                            </p>
                                                            <p>
                                                                <span>
                                                                    Tipo Documental:
                                                                    @foreach ($tipoDoc as $tipo )
                                                                        @if ($documento->tipo_documental_id==$tipo->id)
                                                                            <b>{{$tipo->nombre}}</b>
                                                                        @endif
                                                                    @endforeach
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </dvi>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        @endforeach
                    </div>
                <!-- Mostrar el número de la página actual y el número total de páginas -->
                <p>Página {{ $documentos->currentPage() }} de {{ $documentos->lastPage() }}</p>

                <!-- Mostrar los enlaces de paginación -->
                <div>
                    {{ $documentos->links() }}
                </div>
        </div>
    </div>
</body>
</html>
    

  

