@php
    use Carbon\Carbon;
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Redoc</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container contenido-principal">
        <div>Esta es una seccion</div>
        <div class="active">
            <div>
                <button type="button" class="btn btn-primary">Actualizar</button>
            </div>
            <div class="mt-4">
                {{ $documentos->links('vendor.pagination.bootstrap-5') }}
            </div>
            <div>
                <h2>Ordenamiento:</h2>
            </div>
            <hr>
            <div class="card-body" style="border: none">
                    @foreach ($documentos as $documento)
                        <li class="d-flex mb-4">
                            <div class="flex-shrink-0">
                                <div class="thumbnail-wrapper mb-3">
                                    @if ($documento->url_valida)
                                        <a href="https://intranet.igp.gob.pe/redoc/assets/uploads/documentos/{{ $documento->pdf_convenio}}" target="_blank">
                                            <img src="https://intranet.igp.gob.pe/redoc/themes/Mirage/images/documento-firma.png" class="img-thumbnail" alt="Thumbnail" >
                                        </a>
                                    @else
                                        <img src="https://intranet.igp.gob.pe/redoc/themes/Mirage/images/no-file.png" class="img-thumbnail" alt="Thumbnail">
                                    @endif
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="card-text artifact-info">
                                    <span>
                                        <b>NRO EXPEDIENTE:</b>
                                        <span style="font-weight: bold; font-size: large;">
                                            @if ($documento->nro_expediente)
                                            {{ $documento->nro_expediente }}
                                            @else
                                                Sin expediente
                                            @endif
                                        </span>
                                    </span> 
                                    <span class="publisher-date">
                                        <span>| Fecha del documento:</span> 
                                        <span>{{ Carbon::parse($documento->fecha_documento)->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY') }}</span>
                                    </span>
                                    <br>
                                    <div style="text-align: right">
                                        <form action="{{ route('editar_documento', $documento->id) }}" method="GET">
                                            <button type="submit" class="btn btn-info">Editar</button>
                                        </form>
                                        <a href="#" class="btn btn-danger">Eliminar</a>
                                    </div>
                                </div>
                                <div class="card-title artifact-title">
                                    <span>
                                        <b>{{$documento->nombre}}</b>
                                    </span>
                                </div>
                                <div class="card-text artifact-abstract">
                                    <span>Oficina: 
                                        @foreach ($oficinas as $oficina )
                                            @if ($documento->OrganoLinealId == $oficina->OrganoLinealId)
                                                <b>{{ $oficina->nombre }}</b>
                                            @endif
                                        @endforeach
                                    </span>
                                    <p>
                                        <span>Nro Correlativo:
                                            <b>{{ $documento->nro_correlativo }}</b>
                                        </span> 
                                    </p>
                                    <p>
                                        <span>Tipo Documental:
                                            @foreach ($tipoDoc as $tipo )
                                                @if ($documento->tipo_documental_id == $tipo->id)
                                                    <b>{{ $tipo->nombre }}</b>
                                                @endif
                                            @endforeach
                                        </span> 
                                    </p>
                                </div>
                            </div>
                        </li>
                        <hr>
                    @endforeach
            </div>
        </div>
    </div>
</body>
</html>