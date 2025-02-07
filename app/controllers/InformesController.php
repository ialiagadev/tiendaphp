<?php
require_once __DIR__ . "/../models/Informes.php";

class InformesController {
    private $informesModel;

    public function __construct() {
        $this->informesModel = new Informes();
    }

    public function obtenerUsuariosAltasBajas() {
        return $this->informesModel->getUsuariosAltasBajas();
    }

    public function obtenerProductosAltasBajas() {
        return $this->informesModel->getProductosAltasBajas();
    }

    public function obtenerProductosMasVendidos() {
        return $this->informesModel->getProductosMasVendidos();
    }

    public function obtenerVentasDelMes() {
        return $this->informesModel->getVentasDelMes();
    }
    public function obtenerDetalleVentasDelMes() {
        return $this->informesModel->getDetalleVentasDelMes();  // 
    }
    
}
?>
