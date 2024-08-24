<?php

class Producto {
    private $id;
    private $nombre;
    private $descripcion;
    private $precio;
    private $cantidadEnStock;

    // Constructor para inicializar las propiedades
    public function __construct($id = null, $nombre = "", $descripcion = "", $precio = 0.0, $cantidadEnStock = 0) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
        $this->cantidadEnStock = $cantidadEnStock;
    }

    // Métodos getters
    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getPrecio() {
        return $this->precio;
    }

    public function getCantidadEnStock() {
        return $this->cantidadEnStock;
    }

    // Métodos setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function setPrecio($precio) {
        $this->precio = $precio;
    }

    public function setCantidadEnStock($cantidadEnStock) {
        $this->cantidadEnStock = $cantidadEnStock;
    }

    public function toJson(){
        return json_encode([
            'id' => $this->id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'precio' => $this->precio,
            'cantidad_en_stock' => $this->cantidadEnStock
        ]);
    }
}

?>