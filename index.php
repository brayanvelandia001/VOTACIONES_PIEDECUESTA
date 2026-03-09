<?php
// 1. EL CANDADO: Si no hay sesión, rebota al login.php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
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
        .bg-indigo { background: var(--indigo); }
        .sticky-sidebar { position: sticky; top: 20px; }
        .analytics-item { border-bottom: 1px solid #eee; padding: 10px; transition: 0.2s; }
        .analytics-item:hover { background: #f8f9fa; }
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
                <small class="d-block opacity-75">Bienvenido,</small>
                <span class="fw-bold"><?php echo $_SESSION['nombre']; ?></span>
            </div>
            
            <button class="btn btn-warning fw-bold shadow-sm px-4 rounded-pill me-2" onclick="exportarExcel()">
                <i class="fas fa-file-excel"></i> <span class="d-none d-sm-inline">EXCEL</span>
            </button>
            
            <a href="logout.php" class="btn btn-danger fw-bold shadow-sm px-3 rounded-pill">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <div class="card main-card p-3 stat-card animate__animated animate__fadeInLeft">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-primary text-white rounded-4 p-3 shadow-sm">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
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

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="sticky-sidebar">
                <div class="card main-card p-4 mb-4 shadow-sm">
                    <h5 class="fw-bold mb-4 text-primary"><i class="fas fa-user-plus me-2"></i> Captura de Datos</h5>
                    <form id="registroForm">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Cédula</label>
                            <input type="number" id="inputCedula" name="cedula" class="form-control" placeholder="Ingrese documento..." required onkeyup="validarExistencia()">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Nombre Completo</label>
                            <input type="text" name="nombre" class="form-control text-uppercase" placeholder="APELLIDOS Y NOMBRES" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Puesto de Votación</label>
                            <select name="lugar" class="form-select shadow-sm" required>
                                <option value="" selected disabled>Seleccione puesto...</option>
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
                                <option value="LA VEGA">LA VEGA</option>
                            </select>
                        </div>

                        <div class="capitan-box mb-4">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="tieneCapitan" onchange="toggleCapitan()">
                                <label class="form-check-label small fw-bold text-primary" for="tieneCapitan">¿TIENE CAPITÁN / REFERIDO?</label>
                            </div>
                            <div id="divCapitan" style="display: none;" class="animate__animated animate__fadeIn">
                                <select name="capitan_id" id="selectCapitanes" class="form-select form-select-sm border-primary">
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-6"><input type="number" name="mesa" class="form-control" placeholder="Mesa" required></div>
                            <div class="col-6"><input type="text" name="telefono" class="form-control" placeholder="Teléfono"></div>
                        </div>
                        <button type="submit" class="btn btn-save w-100 py-3 shadow-lg" id="btnGuardar">
                            GUARDAR REGISTRO <i class="fas fa-save ms-2"></i>
                        </button>
                    </form>
                </div>

                <div class="card main-card p-4 mb-4">
                    <h5 class="fw-bold mb-3"><i class="fas fa-chart-pie me-2 text-warning"></i> Resumen Puestos</h5>
                    <div id="listaEstadisticas" style="max-height: 200px; overflow-y: auto;"></div>
                </div>

                <div class="card main-card p-4">
                    <h5 class="fw-bold mb-3"><i class="fas fa-crown me-2 text-warning"></i> Top Capitanes</h5>
                    <div id="listaCapitanes" style="max-height: 200px; overflow-y: auto;"></div>
                </div>
            </div>
        </div>

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
                                <th class="text-center">GESTIÓN</th>
                            </tr>
                        </thead>
                        <tbody id="tablaVotantes"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function cargarDatos() {
        fetch('listar.php').then(r => r.text()).then(d => {
            document.getElementById('tablaVotantes').innerHTML = d;
            actualizarContadoresHeader();
        });

        fetch('estadisticas.php').then(r => r.text()).then(d => {
            document.getElementById('listaEstadisticas').innerHTML = d;
        });

        fetch('dashboard_capitanes.php').then(r => r.text()).then(d => {
            document.getElementById('listaCapitanes').innerHTML = d;
        });
        
        if(document.getElementById('tieneCapitan').checked) toggleCapitan();
    }

    function toggleCapitan() {
        const div = document.getElementById('divCapitan');
        const check = document.getElementById('tieneCapitan');
        const select = document.getElementById('selectCapitanes');

        if(check.checked) {
            div.style.display = 'block';
            fetch('obtener_capitanes.php').then(r => r.text()).then(html => {
                select.innerHTML = html;
            });
        } else {
            div.style.display = 'none';
            select.value = "";
        }
    }

    function actualizarContadoresHeader() {
        let filas = document.querySelectorAll("#tablaVotantes tr");
        let total = (filas.length > 0 && !filas[0].innerText.includes("No hay")) ? filas.length : 0;
        document.getElementById("totalGeneral").innerText = total;
        if(total > 0) {
            document.getElementById("ultimoNombre").innerText = filas[0].cells[1].innerText.split('\n')[0];
        } else {
            document.getElementById("ultimoNombre").innerText = "Sin registros.";
        }
    }

    function filtrarTabla() {
        let filtro = document.getElementById("buscador").value.toUpperCase();
        let filas = document.querySelectorAll("#tablaVotantes tr");
        filas.forEach(f => {
            f.style.display = f.innerText.toUpperCase().includes(filtro) ? "" : "none";
        });
    }

    function validarExistencia() {
        let cedula = document.getElementById('inputCedula').value.trim();
        let filas = document.querySelectorAll("#tablaVotantes tr td:first-child");
        let existe = false;
        
        if(cedula !== "") {
            filas.forEach(td => { if(td.innerText.trim() === cedula) existe = true; });
        }

        const input = document.getElementById('inputCedula');
        const btn = document.getElementById('btnGuardar');

        if(existe) {
            input.style.borderColor = 'red';
            input.style.backgroundColor = '#fff5f5';
            btn.disabled = true;
        } else {
            input.style.borderColor = '';
            input.style.backgroundColor = '';
            btn.disabled = false;
        }
    }

    document.getElementById('registroForm').onsubmit = function(e) {
        e.preventDefault();
        const btn = document.getElementById('btnGuardar');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> GUARDANDO...';
        btn.disabled = true;

        fetch('registrar.php', { method: 'POST', body: new FormData(this) })
        .then(res => res.text()).then(msg => {
            btn.innerHTML = 'GUARDAR REGISTRO <i class="fas fa-save ms-2"></i>';
            btn.disabled = false;
            if(msg.trim() == 'success') {
                Swal.fire({ icon: 'success', title: '¡Registro Exitoso!', toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 });
                this.reset();
                document.getElementById('tieneCapitan').checked = false;
                toggleCapitan();
                cargarDatos();
            }
        });
    }

    function borrar(id) {
        Swal.fire({ 
            title: '¿Eliminar registro?', 
            text: "Esta acción no se puede deshacer",
            icon: 'warning', 
            showCancelButton: true, 
            confirmButtonColor: '#d33', 
            confirmButtonText: 'Sí, borrar' 
        }).then(r => {
            if(r.isConfirmed) fetch('borrar.php?id='+id).then(() => cargarDatos());
        });
    }

    function exportarExcel() { window.location.href = 'exportar.php'; }
    window.onload = cargarDatos;
</script>
</body>
</html>