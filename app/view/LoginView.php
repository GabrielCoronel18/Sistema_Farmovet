<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmovet - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/Login.css">
</head>
<body>

    <div class="login-card">
        <div class="login-header">
            <h1>Farmovet</h1>
            <p class="text-muted mt-2">Iniciar Sesion</p>
        </div>

        <form action="?url=Dashboard" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Usuario</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Escribe tu usuario" required>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Escribe tu contraseña" required>
            </div>
            <button type="submit" class="btn btn-purple w-100 py-2">Ingresar</button>
        </form>
    </div>

</body>
</html>
