<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Capturamos el rol y el ID del usuario actual
$rol = $_SESSION['rol'] ?? 'Coordinador'; 
$mi_id = $_SESSION['usuario_id'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electoral Pro | Piedecuesta & Capitanes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        :root { --primary: #1a237e; --accent: #ffd600; --bg: #f4f7fa; --indigo: #3f51b5; }
        body { background: var(--bg); font-family: 'Inter', sans-serif; color: #333; }
        .navbar { background: var(--primary) !important; border-bottom: 4px solid var(--accent); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .main-card { border: none; border-radius: 20px; box-shadow: 0 8px 24px rgba(149, 157, 165, 0.1); background: #fff; transition: 0.3s; }
        .stat-card { border-left: 5px solid var(--primary); }
        .form-select, .form-control { border-radius: 10px; padding: 12px; border: 1px solid #dee2e6; }
        .btn-save { background: var(--primary); border: none; border-radius: 12px; font-weight: 700; color: white; transition: 0.3s; }
        .btn-save:hover { background: #0d1440; transform: translateY(-2px); color: white; }
        .search-input { padding-left: 45px !important; height: 55px; border-radius: 30px !important; }
        .capitan-box { background: #f0f2ff; border: 1px dashed var(--primary); border-radius: 12px; padding: 15px; }
        .sticky-sidebar { position: sticky; top: 20px; }
        /* Estilo para la tabla de mis registros */
        .table-mini { font-size: 0.85rem; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark mb-4 py-3">
    <div class="container d-flex justify-content-between align-items-center">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <div class="bg-white p-2 rounded-circle me-3 animate__animated animate__bounceIn">
                <i class="fas fa-sitemap text-primary"></i>
            </div>
            <span class="fw-bolder fs-4">ESTRUCTURA PIEDECUESTA</span>
        </a>
        <div class="d-flex align-items-center">
            <div class="text-white me-3 d-none d-md-block text-end">
                <small class="d-block opacity-75"><?php echo $rol; ?>,</small>
                <span class="fw-bold"><?php echo $_SESSION['nombre']; ?></span>
            </div>
            
            <?php if($rol == 'Director'): ?>
            <button class="btn btn-warning fw-bold shadow-sm px-4 rounded-pill me-2" data-bs-toggle="modal" data-bs-target="#modalFiltrosExcel">
                <i class="fas fa-file-excel"></i> <span class="d-none d-sm-inline">REPORTES</span>
            </button>
            <?php endif; ?>

            <a href="logout.php" class="btn btn-danger fw-bold shadow-sm px-3 rounded-pill"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </div>
</nav>

<div class="container">
    
    <?php if($rol == 'Director'): ?>
    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <div class="card main-card p-3 stat-card animate__animated animate__fadeInLeft">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-primary text-white rounded-4 p-3 shadow-sm"><i class="fas fa-users fa-2x"></i></div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-0 small text-uppercase fw-bold">Simpatizantes</h6>
                        <h2 class="fw-bold mb-0" id="totalGeneral">0</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card main-card p-3 stat-card animate__animated animate__fadeInRight text-truncate">
                <div class="d-flex align-items-center justify-content-between h-100">
                    <div>
                        <h6 class="text-muted mb-0 small text-uppercase fw-bold">Último Ingreso</h6>
                        <p class="fw-bold mb-0 fs-5 text-primary" id="ultimoNombre">Esperando datos...</p>
                    </div>
                    <i class="fas fa-user-check text-light fa-3x d-none d-sm-block"></i>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="<?php echo ($rol == 'Director') ? 'col-lg-4' : 'col-lg-8 mx-auto'; ?>">
            <div class="sticky-sidebar">
                <div class="card main-card p-4 mb-4 shadow-sm">
                    <h5 class="fw-bold mb-4 text-primary"><i class="fas fa-user-plus me-2"></i> Captura de Datos</h5>
                    <form id="registroForm">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Cédula</label>
                            <input type="number" id="inputCedula" name="cedula" class="form-control" placeholder="Ingrese documento..." required onkeyup="validarExistencia()"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 20)">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Nombre Completo</label>
                            <input type="text" name="nombre" class="form-control text-uppercase" placeholder="APELLIDOS Y NOMBRES" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Puesto de Votación</label>
                            <?php $puestos_html = '
                                <option value="COL BALBINO GARCIA SEDE A">COL BALBINO GARCIA SEDE A</option>
                                <option value="INST LUIS CARLOS GALAN SEDE D">INST LUIS CARLOS GALAN SEDE D</option>
                                <option value="LUIS CARLOS GALAN SEDE B">LUIS CARLOS GALAN SEDE B</option>
                                <option value="ESC NORMAL SUPERIOR">ESC NORMAL SUPERIOR</option>
                                <option value="COL CABELLANO">COL CABELLANO</option>
                                <option value="COL CAVIREY">COL CAVIREY</option>
                                <option value="COL CEDECO">COL CEDECO</option>
                                <option value="INST LUIS CARLOS GALAN SEDE A">INST LUIS CARLOS GALAN SEDE A</option>
                                <option value="ESC BALBINO GARCIA SEDE C - MA">ESC BALBINO GARCIA SEDE C - MA</option>
                                <option value="COLEGIO LUIS CARLOS GALAN SEDE C">COLEGIO LUIS CARLOS GALAN SEDE C</option>
                                <option value="COL HUMBERTO GOMEZ NIGRINIS">COL HUMBERTO GOMEZ NIGRINIS</option>
                                <option value="ESCENARIO DEPORTIVO MARIE POUSSEPIN">ESCENARIO DEPORTIVO MARIE POUSSEPIN</option>
                                <option value="ESC BALBINO GARCIA SEDE B">ESC BALBINO GARCIA SEDE B</option>
                                <option value="COLEGIO CARLOS VICENTE REY SEDE D">COLEGIO CARLOS VICENTE REY SEDE D</option>
                                <option value="COLEGIO CEDECO SEDE B">COLEGIO CEDECO SEDE B</option>
                                <option value="COL VICTOR FELIX GOMEZ SEDE A">COL VICTOR FELIX GOMEZ SEDE A</option>
                                <option value="COL PROMOCION SOCIAL">COL PROMOCION SOCIAL</option>
                                <option value="COL VICTOR FELIX GOMEZ SEDE B">COL VICTOR FELIX GOMEZ SEDE B</option>
                                <option value="CAIF CAMINO A BELEN">CAIF CAMINO A BELEN</option>
                                <option value="CENTRO TABACALERO">CENTRO TABACALERO</option>
                                <option value="INST TEC CRECER Y CONSTRUIR">INST TEC CRECER Y CONSTRUIR</option>
                                <option value="CTRO INTEGRACIÓN COMUNITARIA - LA DIVA">CTRO INTEGRACIÓN COMUNITARIA - LA DIVA</option>
                                <option value="RESTAURANTE ESCOLAR TABACALERO">RESTAURANTE ESCOLAR TABACALERO</option>
                                <option value="SALON COMUNAL BARILOCHE">SALON COMUNAL BARILOCHE</option>
                                <option value="BUENOS AIRES">BUENOS AIRES</option>
                                <option value="CUROS">CUROS</option>
                                <option value="CRISTALES">CRISTALES</option>
                                <option value="GRANADILLO">GRANADILLO</option>
                                <option value="LA ESPERANZA">LA ESPERANZA</option>
                                <option value="LA COLINA">LA COLINA</option>
                                <option value="SAN FRANCISCO">SAN FRANCISCO</option>
                                <option value="SAN ISIDRO">SAN ISIDRO</option>
                                <option value="MESITAS DE SAN JAVIER">MESITAS DE SAN JAVIER</option>
                                <option value="MANZANARES">MANZANARES</option>
                                <option value="MIRAFLORES">MIRAFLORES</option>
                                <option value="MENZULI ALTO Y MENZULI BAJO">MENZULI ALTO Y MENZULI BAJO</option>
                                <option value="PESCADERO">PESCADERO</option>
                                <option value="PLANADAS">PLANADAS</option>
                                <option value="SEVILLA">SEVILLA</option>
                                <option value="SANTA RITA">SANTA RITA</option>
                                <option value="UMPALA">UMPALA</option>
                                <option value="LA VEGA">LA VEGA</option>';
                            ?>
                            <select name="lugar" class="form-select shadow-sm" required>
                                <option value="" selected disabled>Seleccione puesto...</option>
                                <?php echo $puestos_html; ?>
                            </select>
                        </div>
                        <div class="capitan-box mb-4">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="tieneCapitan" onchange="toggleCapitan()">
                                <label class="form-check-label small fw-bold text-primary" for="tieneCapitan">¿TIENE CAPITÁN / REFERIDO?</label>
                            </div>
                            <div id="divCapitan" style="display: none;" class="animate__animated animate__fadeIn">
                                <select name="capitan_id" id="selectCapitanes" class="form-select form-select-sm border-primary"></select>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-6"><input type="number" name="mesa" class="form-control" placeholder="Mesa" required oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 3)"></div>
                            <div class="col-6"><input type="text" name="telefono" class="form-control" placeholder="Teléfono" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"></div>
                        </div>
                        <button type="submit" class="btn btn-save w-100 py-3 shadow-lg" id="btnGuardar">GUARDAR REGISTRO <i class="fas fa-save ms-2"></i></button>
                    </form>
                </div>

                <?php if($rol !== 'Director'): ?>
                <div class="card main-card p-4 mt-4 shadow-sm animate__animated animate__fadeIn">
                    <h5 class="fw-bold mb-3 text-primary"><i class="fas fa-list-check me-2"></i> Mis Últimos Registros</h5>
                    <div class="table-responsive">
                        <table class="table table-mini table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>CÉDULA</th>
                                    <th>NOMBRE</th>
                                    <th>PUESTO</th>
                                    <th>MESA</th>
                                    <th>TELEEFONO</th>
                                    <th>CAPITÁN</th>
                                </tr>
                            </thead>
                            <tbody id="misRegistros">
                                </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

                <?php if($rol == 'Director'): ?>
                <div class="card main-card p-4 mb-4">
                    <h5 class="fw-bold mb-3"><i class="fas fa-chart-pie me-2 text-warning"></i> Resumen Puestos</h5>
                    <div id="listaEstadisticas" style="max-height: 200px; overflow-y: auto;"></div>
                </div>
                <div class="card main-card p-4">
                    <h5 class="fw-bold mb-3"><i class="fas fa-crown me-2 text-warning"></i> Top Capitanes</h5>
                    <div id="listaCapitanes" style="max-height: 200px; overflow-y: auto;"></div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if($rol == 'Director'): ?>
        <div class="col-lg-8">
            <div class="card main-card p-4">
                <div class="position-relative mb-4">
                    <i class="fas fa-search" style="position:absolute; left:20px; top:18px; color:#999;"></i>
                    <input type="text" id="buscador" class="form-control search-input border-0 bg-light shadow-sm" placeholder="Buscar por nombre o cédula..." onkeyup="filtrarTabla()">
                </div>
                <div class="table-responsive" style="max-height: 700px;">
                    <table class="table table-hover align-middle">
                        <thead class="table-light small text-muted">
                            <tr>
                                <th class="ps-3">DOCUMENTO</th>
                                <th>NOMBRES</th>
                                <th>PUESTO</th>
                                <th>MESA</th>
                                <th>TELEFONO</th>   
                                <th class="text-center">GESTIÓN</th>
                            </tr>
                        </thead>
                        <tbody id="tablaVotantes"></tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="modalFiltrosExcel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-filter me-2"></i> Reportes Personalizados</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="exportar_filtros.php" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Filtrar por Capitán</label>
                        <select name="capitan" id="filtroCapitanExcel" class="form-select border-primary">
                            <option value="">-- Todos los Capitanes --</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Puesto de Votación</label>
                        <select name="lugar" class="form-select border-primary">
                            <option value="">-- Todos los Puestos --</option>
                            <?php echo $puestos_html; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Mesa (Opcional)</label>
                        <input type="number" name="mesa" class="form-control" placeholder="Número de mesa">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success fw-bold rounded-pill shadow px-4">
                        <i class="fas fa-download me-2"></i>DESCARGAR EXCEL
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-edit me-2"></i> Editar Registro</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nombre Completo</label>
                        <input type="text" name="nombre" id="edit_nombre" class="form-control text-uppercase" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Puesto de Votación</label>
                        <select name="lugar" id="edit_lugar" class="form-select" required>
                            <?php echo $puestos_html; ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold">Mesa</label>
                            <input type="text" name="mesa" id="edit_mesa" class="form-control" maxlength="3" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold">Teléfono</label>
                            <input type="text" name="telefono" id="edit_telefono" class="form-control" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary fw-bold rounded-pill px-4">GUARDAR CAMBIOS</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function cargarDatos() {
        <?php if($rol == 'Director'): ?>
            fetch('listar.php').then(r => r.text()).then(d => {
                document.getElementById('tablaVotantes').innerHTML = d;
                actualizarContadoresHeader();
            });
            fetch('estadisticas.php').then(r => r.text()).then(d => { document.getElementById('listaEstadisticas').innerHTML = d; });
            fetch('dashboard_capitanes.php').then(r => r.text()).then(d => { document.getElementById('listaCapitanes').innerHTML = d; });
        <?php else: ?>
            // Carga solo los registros del brigadista logueado
            fetch('mis_registros.php').then(r => r.text()).then(d => {
                document.getElementById('misRegistros').innerHTML = d;
            });
        <?php endif; ?>
    }

    document.getElementById('modalFiltrosExcel').addEventListener('show.bs.modal', function () {
        fetch('obtener_capitanes.php').then(r => r.text()).then(html => {
            document.getElementById('filtroCapitanExcel').innerHTML = '<option value="">-- Todos los Capitanes --</option>' + html;
        });
    });

    function toggleCapitan() {
        const div = document.getElementById('divCapitan');
        const check = document.getElementById('tieneCapitan');
        const select = document.getElementById('selectCapitanes');
        if(check.checked) {
            div.style.display = 'block';
            fetch('obtener_capitanes.php').then(r => r.text()).then(html => { select.innerHTML = html; });
        } else {
            div.style.display = 'none';
            select.value = "";
        }
    }

    function actualizarContadoresHeader() {
        let filas = document.querySelectorAll("#tablaVotantes tr");
        if(document.getElementById("totalGeneral")) {
            let total = (filas.length > 0 && !filas[0].innerText.includes("No hay")) ? filas.length : 0;
            document.getElementById("totalGeneral").innerText = total;
            document.getElementById("ultimoNombre").innerText = total > 0 ? filas[0].cells[1].innerText.split('\n')[0] : "Sin registros.";
        }
    }

    function filtrarTabla() {
        let filtro = document.getElementById("buscador").value.toUpperCase();
        document.querySelectorAll("#tablaVotantes tr").forEach(f => {
            f.style.display = f.innerText.toUpperCase().includes(filtro) ? "" : "none";
        });
    }

    function validarExistencia() {
        let cedula = document.getElementById('inputCedula').value.trim();
        let existe = false;
        // Solo si la tabla existe (Director) comprobamos localmente, si no, se valida al guardar
        let celdasCedula = document.querySelectorAll("#tablaVotantes tr td:first-child");
        
        if(celdasCedula.length > 0) {
            celdasCedula.forEach(td => { if(td.innerText.trim() === cedula) existe = true; });
        }

        const input = document.getElementById('inputCedula');
        const btn = document.getElementById('btnGuardar');
        if(existe) {
            input.style.borderColor = 'red'; input.style.backgroundColor = '#fff5f5'; btn.disabled = true;
            Swal.fire({ icon: 'error', title: 'Cédula Duplicada', text: 'Esta persona ya está registrada.', toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 });
        } else {
            input.style.borderColor = ''; input.style.backgroundColor = ''; btn.disabled = false;
        }
    }

    document.getElementById('registroForm').onsubmit = function(e) {
        e.preventDefault();
        const btn = document.getElementById('btnGuardar');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> GUARDANDO...'; btn.disabled = true;
        fetch('registrar.php', { method: 'POST', body: new FormData(this) }).then(res => res.text()).then(msg => {
            btn.innerHTML = 'GUARDAR REGISTRO <i class="fas fa-save ms-2"></i>'; btn.disabled = false;
            if(msg.trim() == 'success') {
                Swal.fire({ icon: 'success', title: '¡Exitoso!', toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 });
                this.reset(); document.getElementById('tieneCapitan').checked = false; toggleCapitan(); cargarDatos();
            }
        });
    }

    function borrar(id) {
    Swal.fire({
        title: '¿Borrar?',
        text: "No se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Sí, borrar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Enviamos la petición al servidor
            fetch('borrar.php?id=' + id)
                .then(response => response.text()) // Convertimos la respuesta a texto
                .then(data => {
                    const respuesta = data.trim(); // Limpiamos espacios en blanco

                    if (respuesta === "success") {
                        Swal.fire('¡Borrado!', 'El registro ha sido eliminado.', 'success');
                        cargarDatos(); // Recargamos la tabla
                    } 
                    else if (respuesta === "es_capitan") {
                        // AQUÍ capturamos el mensaje que configuramos en PHP
                        Swal.fire('No se puede borrar', 'Esta persona es Capitán y tiene referidos a su cargo.', 'error');
                    } 
                    else {
                        Swal.fire('Error', 'No se pudo eliminar el registro.', 'error');
                    }
                });
        }
    });
}

    function prepararEditar(id, nombre, lugar, mesa, telefono) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_nombre').value = nombre;
        document.getElementById('edit_lugar').value = lugar;
        document.getElementById('edit_mesa').value = mesa;
        document.getElementById('edit_telefono').value = telefono;
        
        var modal = new bootstrap.Modal(document.getElementById('modalEditar'));
        modal.show();
    }

    document.getElementById('editForm').onsubmit = function(e) {
        e.preventDefault();
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true; btn.innerHTML = 'GUARDANDO...';

        fetch('actualizar.php', { method: 'POST', body: new FormData(this) })
        .then(res => res.text())
        .then(msg => {
            btn.disabled = false; btn.innerHTML = 'GUARDAR CAMBIOS';
            if(msg.trim() === 'success') {
                bootstrap.Modal.getInstance(document.getElementById('modalEditar')).hide();
                Swal.fire({ icon: 'success', title: '¡Actualizado!', toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 });
                cargarDatos();
            } else {
                Swal.fire('Error', 'No se pudo actualizar.', 'error');
            }
        });
    }
    window.onload = cargarDatos;
</script>
</body>
</html>