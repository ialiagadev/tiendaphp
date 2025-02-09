<?php
session_start();
require_once "../app/controllers/InformesController.php";

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$controller = new InformesController();
$usuariosAltasBajas = $controller->obtenerUsuariosAltasBajas();
$productosAltasBajas = $controller->obtenerProductosAltasBajas();
$productosMasVendidos = $controller->obtenerProductosMasVendidos();
$ventasDelMes = $controller->obtenerVentasDelMes();
$detalleVentasMes = $controller->obtenerDetalleVentasDelMes();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>📊 Informes de la Tienda</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
                html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .collapsible-header {
            cursor: pointer;
            background-color: #007bff;
            color: white;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
        }
        .collapsible-header:hover {
            background-color: #0056b3;
        }
        .collapsible-content {
            display: none;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        th {
            cursor: pointer;
        }
    </style>
</head>
<body>
<?php include("../app/components/admin_navbar.php"); ?>

<div class="container my-4">
    <h1 class="text-center">📊 Informes de la Tienda</h1>

    <!-- 🔹 Altas y Bajas de Usuarios -->
    <div class="collapsible-header">👥 Altas y Bajas de Usuarios ▼</div>
    <div class="collapsible-content">
        <table class="table table-striped sortable">
            <thead>
                <tr><th data-column="id">ID</th><th data-column="nombre">Nombre</th><th data-column="email">Email</th><th data-column="fecha">Fecha</th><th data-column="activo">Estado</th></tr>
            </thead>
            <tbody>
                <?php foreach ($usuariosAltasBajas as $u): ?>
                    <tr class="<?= $u['activo'] ? 'table-success' : 'table-danger' ?>">
                        <td><?= $u['id'] ?></td>
                        <td><?= htmlspecialchars($u['nombre']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= $u['fecha'] ?></td>
                        <td><?= $u['activo'] ? "✅ Alta" : "❌ Baja" ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- 🔹 Altas y Bajas de Productos -->
    <div class="collapsible-header">📦 Altas y Bajas de Productos ▼</div>
    <div class="collapsible-content">
        <table class="table table-striped sortable">
            <thead>
                <tr><th>ID</th><th>Nombre</th><th>Categoría</th><th>Precio</th><th>Fecha</th><th>Estado</th></tr>
            </thead>
            <tbody>
                <?php foreach ($productosAltasBajas as $p): ?>
                    <tr class="<?= $p['activo'] ? 'table-success' : 'table-danger' ?>">
                        <td><?= $p['id'] ?></td>
                        <td><?= htmlspecialchars($p['nombre']) ?></td>
                        <td><?= htmlspecialchars($p['categoria']) ?></td>
                        <td>$<?= number_format($p['precio'], 2) ?></td>
                        <td><?= $p['fecha'] ?></td>
                        <td><?= $p['activo'] ? "✅ Disponible" : "❌ Descatalogado" ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- 🔹 Productos Más Vendidos -->
    <div class="collapsible-header">🔥 Productos Más Vendidos ▼</div>
    <div class="collapsible-content">
        <table class="table table-striped sortable">
            <thead>
                <tr><th>ID</th><th>Nombre</th><th>Categoría</th><th>Precio</th><th>Unidades Vendidas</th></tr>
            </thead>
            <tbody>
                <?php foreach ($productosMasVendidos as $p): ?>
                    <tr>
                        <td><?= $p['id'] ?></td>
                        <td><?= htmlspecialchars($p['nombre']) ?></td>
                        <td><?= htmlspecialchars($p['categoria']) ?></td>
                        <td>$<?= number_format($p['precio'], 2) ?></td>
                        <td><?= $p['total_vendido'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- 🔹 Ventas del Mes -->
    <div class="collapsible-header">📅 Ventas del Mes ▼</div>
    <div class="collapsible-content">
        <ul>
            <?php foreach ($ventasDelMes as $venta): ?>
                <li><?= $venta['mes'] ?> - 💰 Ingresos: <strong>$<?= number_format($venta['ingresos_mensuales'], 2) ?></strong> 
                    (Pedidos: <?= $venta['total_pedidos'] ?>, Ticket Promedio: $<?= $venta['ticket_promedio'] ?>)</li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- 🔹 Detalle de Pedidos Mensuales -->
    <div class="collapsible-header">📅 Detalle de Pedidos del Mes ▼</div>
    <div class="collapsible-content">
        <table class="table table-striped sortable">
            <thead>
                <tr>
                    <th>Pedido ID</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Productos Comprados</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($detalleVentasMes as $pedido): ?>
                    <tr>
                        <td><?= $pedido['pedido_id'] ?></td>
                        <td><?= $pedido['fecha'] ?></td>
                        <td><?= htmlspecialchars($pedido['cliente']) ?></td>
                        <td>$<?= number_format($pedido['total'], 2) ?></td>
                        <td><?= ucfirst($pedido['estado']) ?></td>
                        <td><?= htmlspecialchars($pedido['productos']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include("../app/components/footer.php"); ?>

<script>
    // Manejo de desplegables
    $(".collapsible-header").click(function() {
        $(this).next(".collapsible-content").slideToggle();
    });

    // Función para ordenar las tablas
    $("th").click(function() {
        let table = $(this).parents("table.sortable");
        let rows = table.find("tbody tr").toArray();
        let index = $(this).index();
        let ascending = $(this).hasClass("asc");

        rows.sort((a, b) => {
            let A = $(a).children("td").eq(index).text();
            let B = $(b).children("td").eq(index).text();
            return ascending ? A.localeCompare(B, undefined, {numeric: true}) : B.localeCompare(A, undefined, {numeric: true});
        });

        table.find("tbody").append(rows);
        $(this).toggleClass("asc");
    });
</script>

</body>
</html>
