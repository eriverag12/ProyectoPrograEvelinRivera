<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background: linear-gradient(to right, #4fd8fe, #00fe8c);
            height: 100vh;
        }
    </style>
</head>

<body>

<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">

    <div class="card p-4 shadow" style="width: 350px;">

        <h3 class="text-center mb-3">Iniciar Sesión</h3>

        <form action="Validarlogin.php" method="POST">

            <div class="mb-3">
                <label class="form-label">usuario</label>
                <input type="text" name="usuario" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Clave</label>
                <input type="password" name="clave" class="form-control" required>
            </div>

            <button class="btn btn-danger w-100">Ingresar</button>

        </form>

    </div>

</div>

</body>
</html>