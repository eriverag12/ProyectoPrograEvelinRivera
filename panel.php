<?php
session_start();
include("DB.php");

if (!isset($_SESSION["usuario"])) {
    header("Location: index.php");
    exit;
}


$sql_c = "SELECT DISTINCT origen AS ciudad FROM rutas
          UNION
          SELECT DISTINCT destino AS ciudad FROM rutas";
$res_c = mysqli_query($conexion, $sql_c);

$ciudades = [];
while ($r = mysqli_fetch_assoc($res_c)) {
    $ciudades[] = $r["ciudad"];
}

$mensaje = "";
$tipo = "";
$camino = [];

// aqui uso digistra
    function dijkstra($grafo, $inicio, $destino) {

        $dist = [];
        $visitado = [];
        $prev = [];

        foreach ($grafo as $nodo => $v) {
            $dist[$nodo] = INF;
            $visitado[$nodo] = false;
        }

        $dist[$inicio] = 0;

        while (true) {

            $min = INF;
            $u = null;

            foreach ($dist as $nodo => $d) {
                if (!$visitado[$nodo] && $d < $min) {
                    $min = $d;
                    $u = $nodo;
                }
            }

            if ($u === null) break;
            if ($u == $destino) break;

            $visitado[$u] = true;

            foreach ($grafo[$u] as $vecino => $peso) {

                $alt = $dist[$u] + $peso;

                if ($alt < $dist[$vecino]) {
                    $dist[$vecino] = $alt;
                    $prev[$vecino] = $u;
                }
            }
        }

        if (!isset($dist[$destino]) || $dist[$destino] == INF) {
            return [INF, []];
        }

    // aqui contruyo el camino del algoritmo
    $camino = [];
    $actual = $destino;

    while (isset($prev[$actual])) {
        array_unshift($camino, $actual);
        $actual = $prev[$actual];
    }

    array_unshift($camino, $inicio);

    return [$dist[$destino], $camino];
}

// aqui calculo la distancia
if (isset($_POST["calcular"])) {

    $origen = $_POST["origen"];
    $destino = $_POST["destino"];

    if ($origen == $destino) {
        $mensaje = "Distancia Total: 0 km";
        $tipo = "success";
        $camino = [$origen];

    } else {

        $sql = "SELECT * FROM rutas";
        $res = mysqli_query($conexion, $sql);

        $grafo = [];

        while ($fila = mysqli_fetch_assoc($res)) {

            $u = $fila["origen"];
            $v = $fila["destino"];
            $peso = (int)$fila["distancia"];

            // aqui coloque el grafo bidireccional
            $grafo[$u][$v] = $peso;
            $grafo[$v][$u] = $peso;
        }

        list($distancia, $camino) = dijkstra($grafo, $origen, $destino);

        if ($distancia == INF) {
            $mensaje = "No existe ruta disponible";
            $tipo = "danger";
        } else {
            $mensaje = "Distancia Total: $distancia km";
            $tipo = "success";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Sistema de rutas proyecto2</h3>

        <a href="logout.php" class="btn btn-danger">
            Cerrar sesión
        </a>
    </div>

    <div class="row">

       
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    
                </div>

                <div class="card-body">
                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label">Origen</label>
                            <select name="origen" class="form-select" required>
                                <?php foreach($ciudades as $c){ ?>
                                    <option value="<?php echo $c; ?>"
                                        <?php if(isset($_POST["origen"]) && $_POST["origen"] == $c) echo "selected"; ?>>
                                        <?php echo $c; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Destino</label>
                            <select name="destino" class="form-select" required>
                                <?php foreach($ciudades as $c){ ?>
                                    <option value="<?php echo $c; ?>"
                                        <?php if(isset($_POST["destino"]) && $_POST["destino"] == $c) echo "selected"; ?>>
                                        <?php echo $c; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <button class="btn btn-success w-100" name="calcular">
                            Buscar Ruta
                        </button>

                    </form>
                </div>
            </div>
        </div>

        
        <div class="col-md-7">
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    Resultado del Análisis
                </div>

                <div class="card-body">

                    <?php if($mensaje != ""){ ?>

                        <?php if($tipo == "success"){ ?>

                            <h4 class="text-success"><?php echo $mensaje; ?></h4>
                            <hr>

                            <h5>Itinerario:</h5>

                            <?php foreach($camino as $i => $c){ ?>
                                <span class="badge bg-info text-dark p-2"><?php echo $c; ?></span>

                                <?php if($i < count($camino)-1){ ?>
                                    <span class="mx-2">→</span>
                                <?php 
                                } 
                                ?>

                            <?php 
                            } 
                            ?>

                        <?php 
                        } 
                        else 
                        { 
                            ?>

                            <div class="alert alert-danger mb-0">
                                <?php echo $mensaje; ?>
                            </div>

                        <?php 
                        } 
                        ?>

                    <?php 
                    } 
                    else 
                    { 
                        ?>

                        <div class="text-muted">
                           
                        </div>

                    <?php 
                    } 
                    ?>

                </div>
            </div>
        </div>

    </div>

</div>

</body>
</html>