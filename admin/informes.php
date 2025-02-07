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
$detalleVentasMes = $controller->obtenerDetalleVentasDelMes(); // 🔹 Nuevo agregado
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>📊 Informes de la Tienda</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<?php include("../app/components/navbar.php"); ?>

<div class="container my-4">
    <h1 class="text-center">📊 Informes de la Tienda</h1>

    <!-- 🔹 Altas y Bajas de Usuarios -->
    <h3 class="mt-4">👥 Altas y Bajas de Usuarios</h3>
    <table class="table table-striped">
        <thead>
            <tr><th>ID</th><th>Nombre</th><th>Email</th><th>Fecha</th><th>Estado</th></tr>
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

    <!-- 🔹 Altas y Bajas de Productos -->
    <h3 class="mt-4">📦 Altas y Bajas de Productos</h3>
    <table class="table table-striped">
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

    <!-- 🔹 Productos Más Vendidos -->
    <h3 class="mt-4">🔥 Productos Más Vendidos</h3>
    <table class="table table-striped">
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

    <!-- 🔹 Ventas del Mes -->
    <h3 class="mt-4">📅 Ventas del Mes</h3>
    <ul>
        <?php foreach ($ventasDelMes as $venta): ?>
            <li><?= $venta['mes'] ?> - 💰 Ingresos: <strong>$<?= number_format($venta['ingresos_mensuales'], 2) ?></strong> 
                (Pedidos: <?= $venta['total_pedidos'] ?>, Ticket Promedio: $<?= $venta['ticket_promedio'] ?>)</li>
        <?php endforeach; ?>
    </ul>

    <!-- 🔹 Detalle de Pedidos Mensuales -->
    <h3 class="mt-4">📅 Detalle de Pedidos del Mes</h3>
    <table class="table table-striped">
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

<?php include("../app/components/footer.php"); ?>
</body>
</html>
