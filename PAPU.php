<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Alumnos - Bootstrap + PHP</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        table th, table td {
            text-align: center;
        }
        .alert {
            font-size: 1.2em;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <h1 class="mb-4 text-center">Lista de Personas</h1>

    <?php
    $host = "127.0.0.1";
    $usuario = "root";
    $contrasena = "";
    $base_datos = "escueladb";

    $conn = new mysqli($host, $usuario, $contrasena, $base_datos);

    if ($conn->connect_error) {
        die('<div class="alert alert-danger">Conexión fallida: ' . $conn->connect_error . '</div>');
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['insertar'])) {
        $legajo = trim($_POST['n_legajo'] ?? '');
        $dni = trim($_POST['dni'] ?? '');
        $curso = trim($_POST['curso'] ?? '');

        if ($legajo && $dni && $curso) {
            $sql_insert = "INSERT INTO pesona2 (n_legajo, dni, curso) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql_insert);
            $stmt->bind_param("sss", $legajo, $dni, $curso);

            if ($stmt->execute()) {
                echo '<div class="alert alert-success">Persona insertada correctamente.</div>';
            } else {
                echo '<div class="alert alert-danger">Error al insertar: ' . $conn->error . '</div>';
            }

            $stmt->close();
        } else {
            echo '<div class="alert alert-warning">Todos los campos son obligatorios.</div>';
        }
    }

    // Mostrar la tabla
    $sql = "SELECT pk_persona, n_legajo, dni, curso FROM pesona2";
    $resultado = $conn->query($sql);

    if ($resultado->num_rows > 0) {
        echo '<table class="table table-striped table-bordered">';
        echo '<thead class="table-dark">';
        echo '<tr>';
        echo '<th>id</th>';
        echo '<th>legajo</th>';
        echo '<th>dni</th>';
        echo '<th>curso</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        while ($fila = $resultado->fetch_assoc()) {
            echo '<tr>';
            echo '<td class="table-primary">' . $fila["pk_persona"] . '</td>';
            echo '<td class="table-secondary">' . htmlspecialchars($fila["n_legajo"]) . '</td>';
            echo '<td class="table-success">' . htmlspecialchars($fila["dni"]) . '</td>';
            echo '<td class="table-warning">' . htmlspecialchars($fila["curso"]) . '</td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
    } else {
        echo '<div class="alert alert-info">No hay resultados en la base de datos.</div>';
    }

    $conn->close();
    ?>

    <!-- Formulario completo -->
    <div class="mt-5">
        <h2 class="text-center">Agregar Nueva Persona</h2>
        <form action="" method="POST">
            <div class="form-group mb-3">
                <label for="n_legajo">Legajo</label>
                <input type="text" class="form-control" id="n_legajo" name="n_legajo" placeholder="Ingrese legajo" required>
            </div>

            <div class="form-group mb-3">
                <label for="dni">DNI</label>
                <input type="text" class="form-control" id="dni" name="dni" placeholder="Ingrese DNI" required>
            </div>

            <div class="form-group mb-3">
                <label for="curso">Curso</label>
                <input type="text" class="form-control" id="curso" name="curso" placeholder="Ingrese curso" required>
            </div>

            <button type="submit" name="insertar" class="btn btn-primary w-100">Insertar Persona</button>
        </form>
    </div>
</div>

</body>
</html>
