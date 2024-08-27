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
                                <div class="alert alert-dismissible fade mt-2" id="modal-alert" role="alert" style="display: none; opacity: 10; text-align: center;">
                                    <span id="modal-alert-message"></span>
                                </div>
                            </div>
                            <div class="modal-body" id="modal-body-content">
                                    <!-- Spinner (Hidden by default) -->
                                    <div class="progress" id="progressBarContainer" style="display: none;">
                                        <div class="progress-bar" id="progressBar" role="progressbar" aria-label="Example with label" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                            0%
                                        </div>
                                    </div>
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
                            </div>
                        </div>
                    </div>
                </div>
                
                <script>
                        function setFechaDocumento(id) {
                            var selectElement = document.getElementById('yearSelect');
                            var selectedValue = selectElement.options[selectElement.selectedIndex].value;
                            document.getElementById('anio_documento_std_' + id).value = selectedValue;

                            // Mostrar la barra de progreso
                            var progressBarContainer = document.getElementById('progressBarContainer');
                            var progressBar = document.getElementById('progressBar');
                            progressBarContainer.style.display = 'block';

                            // Reiniciar la barra de progreso
                            progressBar.style.width = '0%';
                            progressBar.setAttribute('aria-valuenow', 0);
                            progressBar.innerText = '0%';

                            // Desactivar todos los botones
                            document.querySelectorAll('.btn-actualizar').forEach(button => {
                                button.disabled = true;
                            });

                            // Simular el progreso (puedes ajustar esto según el tiempo real de tu solicitud)
                            var progressInterval = setInterval(function() {
                                var currentValue = parseInt(progressBar.getAttribute('aria-valuenow'));
                                if (currentValue < 90) {  // Llegar hasta 90% durante la carga
                                    currentValue += 10;
                                    progressBar.style.width = currentValue + '%';
                                    progressBar.setAttribute('aria-valuenow', currentValue);
                                    progressBar.innerText = currentValue + '%';
                                } else {
                                    clearInterval(progressInterval);
                                }
                            }, 500); // Ajusta el intervalo de tiempo según sea necesario

                            // Realizar la solicitud AJAX
                            $.ajax({
                                url: document.querySelector(`#form_${id}`).action,
                                type: 'POST',
                                data: $(`#form_${id}`).serialize(),
                                success: function(response) {
                                    if (response.status === 'success') {
                                        progressBar.style.width = '100%';
                                        progressBar.setAttribute('aria-valuenow', 100);
                                        progressBar.innerText = '100%';
                                        showModalMessage('success', response.message);
                                    } else {
                                        progressBar.style.width = '100%';
                                        progressBar.setAttribute('aria-valuenow', 100);
                                        progressBar.classList.add('bg-danger');
                                        progressBar.innerText = 'Error';
                                        showModalMessage('danger', response.message);
                                    }
                                },
                                error: function(xhr) {
                                    var errorMessage = xhr.responseJSON ? xhr.responseJSON.message : 'Error inesperado.';
                                    progressBar.style.width = '100%';
                                    progressBar.setAttribute('aria-valuenow', 100);
                                    progressBar.classList.add('bg-danger');
                                    progressBar.innerText = 'Error';
                                    showModalMessage('danger', errorMessage);
                                },
                                complete: function() {
                                    onFormSubmitComplete();

                                    // Ocultar la barra de progreso después de unos segundos
                                    setTimeout(function() {
                                        progressBarContainer.style.display = 'none';
                                        progressBar.classList.remove('bg-danger');
                                    }, 2000);
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
                        }, 3000);
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
                    });
                    
                </script>