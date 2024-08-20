<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container contenido-principal" >
        <div class="active">
            <div class="ds-static-div secondary recent-submission">
                <section class="contend">
                    <div class="contend-wrapper">
                        <section class="contend-header">
                            <h1>Actualizar documento:</h1>
                        </section>
                        <section class="contend">
                            <div class="contanier-fluid">
                                <div class="ui-sortable">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel panel-primary">
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <form method="POST" action="{{route('actualizar_documento',$doc_update->id)}}" class="form-horizontal blog_settings row-border" enctype="multipart/form-data">
                                                                @csrf
                                                                <input type="hidden" name="id" value="{{ $doc_update->id }}">
                                                                <div class="form-group">
                                                                    <label for="nro_expediente" class="col-sm-2 control-label text-primary">
                                                                        <span class="text-danger">*</span>
                                                                        Nro Expediente
                                                                    </label>
                                                                    <div class="col-sm-10">
                                                                        <input class="form-control" type="text" id="nro_expediente" name="nro_expediente"  maxlength="250" style="width: 100%" value="{{$doc_update->nro_expediente}}">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="asunto" class="col-sm-2 control-label text-primary">
                                                                        <span class="text-danger">*</span>
                                                                        Asunto
                                                                    </label>
                                                                    <div class="col-sm-10">
                                                                        <input class="form-control" type="text" id="id_asunto" name="asunto" maxlength="250" style="width: 100%" value="{{$doc_update->nombre}}">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="serie" class="col-sm-2 control-label text-primary">
                                                                        <span class="text-danger">*</span>
                                                                        Serie
                                                                    </label>
                                                                    <div class="col-sm-4">
                                                                        <select name="serie" id="id_serie" class="form-control select2 select2-hidden-accessible" disabled>
                                                                            <option value="{{$doc_update->serie_id}}">{{$serie_name_doc->nombre}}</option>
                                                                        </select>
                                                                    </div>
                                                                    <label for="subserie" class="col-sm-2 control-label text-primary">
                                                                        <span>*</span>
                                                                        SubSerie
                                                                    </label>
                                                                    <div class="col-sm-4">
                                                                        <select name="subserie" id="id_subserie" class="form-control select2 select2-hidden-accessible" disabled>
                                                                            <option value="{{$doc_update->subserie_id}}">{{$subSerie_name_doc->nombre}}</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="tipo_documental" class="col-sm-2 control-label text-primary">
                                                                        <span class="text-danger">*</span>
                                                                        Tipo Documental
                                                                    </label>
                                                                    <div class="col-sm-4">
                                                                        <select name="tipo_documental" id="id_tipo_documental" class="form-control select2 select2-hidden-accessible" disabled>
                                                                            <option value="{{$doc_update->tipo_documental_id}}">{{$tipoDoc->nombre}}</option>
                                                                        </select>
                                                                    </div>
                                                                    <!--<label for="col-sm-2 control-label text-primary">Ordenamiento</label>
                                                                    <div class="col-sm-4">
                                                                        <select name="ordenamiento" id="id_ordenamiento" class="form-control select2 select2-hidden-accessible">
                                                                            <option value="">Escoja un ordenamiento</option>
                                                                            <option value="">Ordenamiento1</option>
                                                                        </select>
                                                                    </div>-->
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="nro_correlativo" class="col-sm-2 control-label text-primary">
                                                                        <span class="text-danger">*</span>
                                                                        Nro Correlativo
                                                                    </label>
                                                                    <div class="col-sm-10">
                                                                        <input class="form-control" type="text" id="id_nro_correlativo" name="nro_correlativo"   style="width: 150px" value="{{$doc_update->nro_correlativo}}">
                                                                    </div>
                                                                    <label for="nro_folios" class="col-sm-2 control-label text-primary">
                                                                        <span class="text-danger">*</span>
                                                                        Nro de Folios
                                                                    </label>
                                                                    <div class="col-sm-10">
                                                                        <input class="form-control" type="text" id="id_nro_folios" name="nro_folios"   style="width: 150px" value="{{$doc_update->nro_folios}}">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="fecha_documento" class="col-sm-2 control-label text-primary">
                                                                        <span class="text-danger">*</span>
                                                                        Fecha documento
                                                                    </label>
                                                                    <div class="col-sm-10">
                                                                        <input class="form-control" type="date" id="id_fecha_documento" name="fecha_documento"  style="width: 150px" value="{{$doc_update->fecha_documento}}">
                                                                    </div>
                                                                    <label for="col-sm-2 control-label text-primary">Vinculo con Organo Lineal</label>
                                                                    <div class="col-sm-4">
                                                                        @if ($org_lin_vinculado!=null)
                                                                            <select name="organo_lineal" id="organo_lineal" class="form-control select2 select2-hidden-accessible" disabled>
                                                                                <option value="{{$doc_update->OrganoLinealIdVinculo}}">{{$org_lin_vinculado->nombre}}</option>
                                                                            </select>
                                                                        @else
                                                                            <select name="organo_lineal" id="organo_lineal" class="form-control select2 select2-hidden-accessible" disabled>
                                                                                <option value="0">No hay organo vinculado</option>
                                                                            </select>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <br>
                                                                <div>
                                                                    <button type="submit">Actualizar</button>
                                                                </div>
                                                                @if ($errors->any())
                                                                    <ul class="error-messages">
                                                                        @foreach ($errors->all() as $error)
                                                                            <li>{{ $error }}</li>
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </section>
            </div>
        </div>
    </div>
</body>
</html>