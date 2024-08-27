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
                                <form class="row g-2">
                                        <select class="form-select" id="yearSelect" name="anio_documento_std" style="width: 150px;" required>
                                            <option value="">Año</option>
                                            <?php
                                            $currentYear = date("Y");
                                            for ($year = $currentYear; $year >= 1922; $year--) {
                                                echo "<option value=\"$year\">$year</option>";
                                            }
                                            ?>
                                        </select>
                                        <div class="col-md-8" style="margin-left: 10px">
                                            <input type="text" class="form-control" id="searchInput" placeholder="Buscar tipo de documento...">
                                        </div>
                                </form>
                                <div class="alert alert-dismissible fade mt-2" id="modal-alert" role="alert" style="display: none; opacity: 10;">
                                    <span id="modal-alert-message"></span>
                                </div>
                            </div>
                            <div class="modal-body" id="modal-body-content">
                                <table class="table" id="documentTable">
                                    <thead>
                                        <tr>
                                            <th scope="col">Tipo de documento</th>
                                            <th scope="col">Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tipo_docs_for_modal as $tipo_doc_for_modal)
                                            <tr>
                                                <td class="document-name">{{ $tipo_doc_for_modal->nombre }}</td>
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
                <script>
                    function setFechaDocumento(id) {
                        var selectElement = document.getElementById('yearSelect');
                        var selectedValue = selectElement.options[selectElement.selectedIndex].value;
                        document.getElementById('anio_documento_std_' + id).value = selectedValue;

                        // Desactivar todos los botones
                        document.querySelectorAll('.btn-actualizar').forEach(button => {
                            button.disabled = true;
                        });

                        // Realizar la solicitud AJAX
                       $.ajax({
                            url: document.querySelector(`#form_${id}`).action,
                            type: 'POST',
                            data: $(`#form_${id}`).serialize(),
                            success: function(response) {
                                if (response.status === 'success') {
                                    showModalMessage('success', response.message);
                                } else {
                                    showModalMessage('danger', response.message);
                                }
                            },
                            error: function(xhr) {
                                var errorMessage = xhr.responseJSON ? xhr.responseJSON.message : 'Error inesperado.';
                                showModalMessage('danger', errorMessage);
                            },
                            complete: function() {
                                onFormSubmitComplete();
                            }
                        });
                    }

                    function showModalMessage(type, message) {
                        var alertElement = $('#modal-alert');
                        var alertMessageElement = $('#modal-alert-message');

                        alertElement.removeClass('alert-success alert-danger').addClass('alert-' + type);
                        alertMessageElement.text(message);

                        alertElement.show();

                        setTimeout(function() {
                            alertElement.hide();
                        }, 1000);
                    }
                        // Reactivar botones después de completar la petición
                    function onFormSubmitComplete() {
                        document.querySelectorAll('.btn-actualizar').forEach(button => {
                            button.disabled = false;
                        });
                    }
                    //Codigo para el modal
                    $(document).ready(function() {
                            // Función de búsqueda
                            $("#searchInput").on("keyup", function() {
                            var value = $(this).val().toLowerCase();
                            $("#documentTable tr").filter(function() {
                                $(this).toggle($(this).find('.document-name').text().toLowerCase().indexOf(value) > -1);
                            });
                        });
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