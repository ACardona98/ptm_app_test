<?php

require_once realpath(dirname(__FILE__) . '/../config/Database.php');
require_once realpath(dirname(__FILE__) . '/../models/Producto.php');

class ProductoDAO
{
    private $conn;

    // Constructor que inicializa la conexión a la base de datos
    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Método para guardar un producto (insertar o actualizar)
    public function save(Producto $producto)
    {
        if ($producto->getId() === null) {
            // Insertar un nuevo producto
            $query = "INSERT INTO productos (nombre, descripcion, precio, cantidad_en_stock) VALUES (:nombre, :descripcion, :precio, :cantidad_en_stock)";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':nombre', $producto->getNombre());
            $stmt->bindParam(':descripcion', $producto->getDescripcion());
            $stmt->bindParam(':precio', $producto->getPrecio());
            $stmt->bindParam(':cantidad_en_stock', $producto->getCantidadEnStock());

            if ($stmt->execute()) {
                $producto->setId($this->conn->lastInsertId());
                return true;
            }
        } else {
            // Actualizar un producto existente
            $query = "UPDATE productos SET nombre = :nombre, descripcion = :descripcion, precio = :precio, cantidad_en_stock = :cantidad_en_stock WHERE id = :id";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':id', $producto->getId());
            $stmt->bindParam(':nombre', $producto->getNombre());
            $stmt->bindParam(':descripcion', $producto->getDescripcion());
            $stmt->bindParam(':precio', $producto->getPrecio());
            $stmt->bindParam(':cantidad_en_stock', $producto->getCantidadEnStock());

            return $stmt->execute();
        }

        return false;
    }

    // Método para eliminar un producto
    public function delete($id)
    {
        $query = "DELETE FROM productos WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    // Método para obtener un producto por ID
    public function get($id)
    {
        $query = "SELECT * FROM productos WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $producto = new Producto(
                $row['id'],
                $row['nombre'],
                $row['descripcion'],
                $row['precio'],
                $row['cantidad_en_stock']
            );

            return $producto;
        }

        return null;
    }

    // Método para obtener todos los productos
    public function getAll()
    {
        $sql = "SELECT * FROM productos";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Método para obtener el valor total del inventario
    public function getInventoryTotalValue(){
        $query = "SELECT SUM(precio * cantidad_en_stock) total FROM productos";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return $row['total'];
        }
        return 0;
    }
}

?>