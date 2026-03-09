<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Seguro | Piedecuesta 2026</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        :root { --primary: #1a237e; --accent: #ffd600; }
        
        body { 
            /* RUTA CORREGIDA A TU CARPETA imagenes */
            background: linear-gradient(rgba(26, 35, 126, 0.7), rgba(26, 35, 126, 0.7)), 
                        url('imagenes/piedecuesta.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-family: 'Inter', sans-serif;
            margin: 0;
        }

        .login-card { 
            border: none;
            border-radius: 25px; 
            width: 100%; 
            max-width: 420px; 
            padding: 40px; 
            background: rgba(255, 255, 255, 0.93); 
            box-shadow: 0 25px 50px rgba(0,0,0,0.5); 
            backdrop-filter: blur(10px);
        }

        .logo-circle {
            width: 70px;
            height: 70px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 28px;
            box-shadow: 0 10px 20px rgba(26, 35, 126, 0.3);
        }

        .form-label { font-weight: 700; color: var(--primary); font-size: 0.8rem; letter-spacing: 0.5px; }
        
        .input-group-text { 
            background: #f8f9fa; 
            border-right: none; 
            color: var(--primary); 
            border-radius: 12px 0 0 12px; 
        }
        
        .form-control { 
            border-left: none; 
            border-radius: 0 12px 12px 0; 
            padding: 12px;
            background: #f8f9fa;
        }

        .form-control:focus { 
            box-shadow: none; 
            background: #fff;
            border-color: var(--primary);
        }

        .input-group:focus-within .input-group-text {
            border-color: var(--primary);
            background: #fff;
        }

        .btn-login { 
            background: var(--primary); 
            border: none; 
            border-radius: 15px; 
            font-weight: 700; 
            padding: 14px;
            letter-spacing: 1px;
            transition: 0.3s;
            color: white;
            margin-top: 10px;
        }

        .btn-login:hover { 
            background: #0d1440; 
            transform: translateY(-2px); 
            box-shadow: 0 8px 15px rgba(0,0,0,0.3);
            color: white;
        }

        .alert-error {
            background: #fff5f5;
            color: #d32f2f;
            border-radius: 12px;
            padding: 8px;
            font-size: 0.75rem;
            border: 1px solid #ffcdd2;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="login-card animate__animated animate__zoomIn">
        <div class="text-center">
            <div class="logo-circle">
                <i class="fas fa-user-shield"></i>
            </div>
            <h3 class="fw-bolder text-primary mb-1">PIEDECUESTA 2026</h3>
            <p class="text-muted small mb-4 text-uppercase fw-bold" style="letter-spacing: 1px; font-size: 0.7rem;">Panel Administrativo</p>
        </div>

        <?php if(isset($_GET['error'])): ?>
            <div class="alert-error animate__animated animate__shakeX text-center">
                <i class="fas fa-exclamation-circle me-1"></i> Usuario o contraseña incorrectos
            </div>
        <?php endif; ?>

        <form action="validar_login.php" method="POST">
            <div class="mb-3">
                <label class="form-label text-uppercase">Usuario</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" name="user" class="form-control" placeholder="Escriba su usuario" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label text-uppercase">Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                    <input type="password" name="pass" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn btn-login w-100 text-uppercase shadow">
                Entrar al Sistema <i class="fas fa-sign-in-alt ms-2"></i>
            </button>
        </form>

        <div class="text-center mt-4 pt-2 border-top">
            <p class="text-muted mb-0" style="font-size: 0.7rem;">© 2026 Comando Electoral</p>
            <span class="badge bg-primary opacity-75 mt-1" style="font-size: 0.6rem;">v4.5 SECURE</span>
        </div>
    </div>

</body>
</html>