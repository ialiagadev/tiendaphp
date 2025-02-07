<?php
require_once __DIR__ . "/../../config/db.php";

class Informes {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    // 🔹 Obtener altas y bajas de usuarios
    public function getUsuariosAltasBajas() {
        $stmt = $this->pdo->query("
            SELECT id, nombre, email, DATE(created_at) AS fecha, activo
            FROM usuarios
            ORDER BY created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔹 Obtener altas y bajas de productos
    public function getProductosAltasBajas() {
        $stmt = $this->pdo->query("
            SELECT p.id, p.nombre, c.nombre AS categoria, p.precio, p.activo, DATE(p.created_at) AS fecha
            FROM productos p
            LEFT JOIN categorias c ON p.categoria_id = c.id
            ORDER BY p.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔹 Productos más vendidos con más detalles
    public function getProductosMasVendidos() {
        $stmt = $this->pdo->query("
            SELECT pr.id, pr.nombre, c.nombre AS categoria, pr.precio, SUM(pp.cantidad) AS total_vendido
            FROM pedidos_productos pp
            INNER JOIN productos pr ON pp.producto_id = pr.id
            INNER JOIN categorias c ON pr.categoria_id = c.id
            INNER JOIN pedidos p ON pp.pedido_id = p.id
            WHERE p.estado <> 'cancelado'
            GROUP BY pr.id
            ORDER BY total_vendido DESC
            LIMIT 10
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔹 Ventas del mes con ingresos y ticket promedio
    public function getVentasDelMes() {
        $stmt = $this->pdo->query("
            SELECT 
                DATE_FORMAT(fecha, '%Y-%m') AS mes, 
                SUM(total) AS ingresos_mensuales, 
                COUNT(*) AS total_pedidos, 
                ROUND(SUM(total) / COUNT(*), 2) AS ticket_promedio
            FROM pedidos 
            WHERE estado <> 'cancelado'
            GROUP BY mes
            ORDER BY mes DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getDetalleVentasDelMes() {
        $sql = "SELECT 
                    p.id AS pedido_id,
                    p.fecha,
                    u.nombre AS cliente,
                    p.total,
                    p.estado,
                    GROUP_CONCAT(CONCAT(pr.nombre, ' (', pp.cantidad, 'x $', pp.precio_unitario, ')') SEPARATOR ', ') AS productos
                FROM pedidos p
                JOIN usuarios u ON p.usuario_id = u.id
                JOIN pedidos_productos pp ON p.id = pp.pedido_id
                JOIN productos pr ON pp.producto_id = pr.id
                WHERE p.estado != 'cancelado'
                GROUP BY p.id, p.fecha, u.nombre, p.total, p.estado
                ORDER BY p.fecha DESC";
    
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    
}


?>
