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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container contenido-principal">
        <div>Esta es una seccion</div>
        <div class="active">

            <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
            Actualiza tus documentos
        </button>
        
            <!-- Modal -->
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Tipos de documentos</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal-btn"></button>
                        </div>
                        <div class="container mt-2">
                            <!-- Año Selección -->
                            <form>
                                <div class="mb-3">
                                    <label for="yearSelect" class="form-label">Año</label>
                                    <select class="form-select" id="yearSelect" name="anio_documento_std" style="width: 150px;" required>
                                        <option value="">Año</option>
                                        <?php
                                        $currentYear = date("Y");
                                        for ($year = $currentYear; $year >= 1922; $year--) {
                                            echo "<option value=\"$year\">$year</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-body" id="modal-body-content">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Tipo de documento</th>
                                        <th scope="col">Accion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tipo_docs_for_modal as $tipo_doc_for_modal)
                                        <tr>
                                            <td>{{ $tipo_doc_for_modal->nombre }}</td>
                                            <td>
                                                <form id="form_{{ $tipo_doc_for_modal->id }}" action="{{ route('sin_docs', $tipo_doc_for_modal->id) }}" method="POST" onsubmit="onFormSubmitComplete()">
                                                    @csrf
                                                    <input type="hidden" name="anio_documento_std" id="anio_documento_std_{{ $tipo_doc_for_modal->id }}" value="">
                                                    <button type="submit" class="btn btn-primary btn-sm btn-actualizar" onclick="setFechaDocumento({{ $tipo_doc_for_modal->id }})">Actualizar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-4" id="pagination-links">
                                {{ $tipo_docs_for_modal->appends(['page_principal' => request('page_principal')])->fragment('miModal')->links('vendor.pagination.paginador-modal') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                {{ $documentos->appends(['page_docs' => request('page_docs')])->links('vendor.pagination.bootstrap-5') }}
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
                                        <span>{{ Carbon::parse($documento->fFecDocumento)->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY') }}</span>
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
                                        <b>{{$documento->asunto}}</b>
                                    </span>
                                </div>
                                <div class="card-text artifact-abstract">
                                    <span>Oficina: 
                                        @foreach ($oficinas as $oficina )
                                            @if ($documento->OrganoLinealId == $oficina->idOficina)
                                                <b>{{ $oficina->NomOficina }}</b>
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
                                            @foreach ($tipo_docs_std as $tipo_doc_std )
                                                @if ($documento->tipo_documental_id == $tipo_doc_std->id)
                                                    <b>{{$tipo_doc_std->nombre}}</b>
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

<script>
    function setFechaDocumento(id) {
        var selectElement = document.getElementById('yearSelect');
        var selectedValue = selectElement.options[selectElement.selectedIndex].value;
        document.getElementById('anio_documento_std_' + id).value = selectedValue;

                // Desactivar todos los botones
            document.querySelectorAll('.btn-actualizar').forEach(button => {
            button.disabled = true;
        });

        // Enviar el formulario correspondiente
        document.querySelector(`#form_${id}`).submit();
    }
        // Reactivar botones después de completar la petición
    function onFormSubmitComplete() {
        document.querySelectorAll('.btn-actualizar').forEach(button => {
            button.disabled = false;
        });
    }
    //Codigo para el modal
    $(document).ready(function() {
        // Manejar la paginación dentro del modal usando AJAX
        $(document).on('click', '#pagination-links a', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    // Actualiza solo el contenido del modal con la nueva página
                    $('#modal-body-content').html($(data).find('#modal-body-content').html());
                }
            });
        });

        // Mantén el modal abierto si la URL tiene el hash de modal
        $('#staticBackdrop').on('show.bs.modal', function () {
            window.location.hash = "Modal_tipoDoc";
        });

        $('#staticBackdrop').on('hide.bs.modal', function () {
            window.location.hash = "";
        });

        $(window).on('popstate', function() {
            if(window.location.hash === '#Modal_tipoDoc') {
                $('#staticBackdrop').modal('show');
            } else {
                $('#staticBackdrop').modal('hide');
            }
        });
    });
</script>
