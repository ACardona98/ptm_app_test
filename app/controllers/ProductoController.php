<?php

require_once realpath(dirname(__FILE__) . '/../models/Producto.php');
require_once realpath(dirname(__FILE__) . '/../dao/ProductoDAO.php');

class ProductoController {
    private $productoDAO;

    public function __construct() {
        $this->productoDAO = new ProductoDAO();
    }

    // Método para manejar la solicitud HTTP
    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET':
                $this->get($_GET);
                break;
            case 'POST':
                $this->save();
                break;
            case 'PUT':
                $this->update();
                break;
            case 'DELETE':
                $this->delete();
                break;
            default:
                $this->sendResponse(405, "Método no permitido");
                break;
        }
    }

    public function list()
    {
        $productos = $this->productoDAO->getAll();
        $this->sendResponse(200, $productos);
    }

    public function get($params) {
        if (isset($params['id'])) {
            $producto = $this->productoDAO->get($params['id']);
            if ($producto) {
                $this->sendResponse(200, $producto->toJson());
            } else {
                $this->sendResponse(404, "Producto no encontrado");
            }
        } else {
            $this->sendResponse(400, "ID de producto no especificado");
        }
    }

    private function save() {
        $data = json_decode(file_get_contents('php://input'), true);
        if ($this->validateProductData($data)) {
            $producto = new Producto(null, $data['nombre'], $data['descripcion'], $data['precio'], $data['cantidad_en_stock']);
            if ($this->productoDAO->save($producto)) {
                $this->sendResponse(201, "Producto creado con éxito");
            } else {
                $this->sendResponse(500, "Error al crear el producto");
            }
        } else {
            $this->sendResponse(400, "Datos de producto inválidos");
        }
    }

    private function update() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id']) && $this->validateProductData($data)) {
            $producto = $this->productoDAO->get($data['id']);
            if ($producto) {
                $producto->setNombre($data['nombre']);
                $producto->setDescripcion($data['descripcion']);
                $producto->setPrecio($data['precio']);
                $producto->setCantidadEnStock($data['cantidad_en_stock']);
                if ($this->productoDAO->save($producto)) {
                    $this->sendResponse(200, "Producto actualizado con éxito");
                } else {
                    $this->sendResponse(500, "Error al actualizar el producto");
                }
            } else {
                $this->sendResponse(404, "Producto no encontrado");
            }
        } else {
            $this->sendResponse(400, "Datos de producto inválidos");
        }
    }

    private function delete() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id'])) {
            if ($this->productoDAO->delete($data['id'])) {
                $this->sendResponse(200, "Producto eliminado con éxito");
            } else {
                $this->sendResponse(500, "Error al eliminar el producto");
            }
        } else {
            $this->sendResponse(400, "ID de producto no especificado");
        }
    }

    public function getInventoryTotalValue(){
        $total = $this->productoDAO->getInventoryTotalValue();
        $this->sendResponse(200, $total);
    }

    public function getCombinations($number) {
        if(is_numeric($number)){
            $rows = $this->productoDAO->getAll();
            $productos = $this->getProductsFromRows($rows);

            //Si hay al menos 2 productos
            if(count($productos) >= 2) {

                $all_combinations = [];
                $combinations_ordered = [];

                //Realizar las combinaciones que cumplan la condición con 2 productos
                for($i = 0; $i < count($productos); $i++){
                    $producto1 = $productos[$i];
                    for($j = $i + 1; $j < count($productos); $j++){
                        $producto2 = $productos[$j];
                        $key = $producto1->getId() . '-' . $producto2->getId();
                        if(!array_key_exists($key, $all_combinations)){ // Se valida que no exista en el array de combinaciones
                            $value = $producto1->getPrecio() + $producto2->getPrecio();
                            if($value <= $number) { //Se valida que la suma de precios sea menor al número ingresado
                                $all_combinations[$key] = $value;
                            }
                        }
                        
                    }
                }

                //Realizar las combinaciones que cumplan la condición con 3 productos
                for($i = 0; $i < count($productos); $i++){
                    $producto1 = $productos[$i];
                    for($j = $i + 1; $j < count($productos); $j++){
                        $producto2 = $productos[$j];
                        for($k = $j + 1; $k < count($productos); $k++){
                            $producto3 = $productos[$k];
                            $key = $producto1->getId() . '-' . $producto2->getId() . '-' . $producto3->getId();
                            if(!array_key_exists($key, $all_combinations)){ // Se valida que no exista en el array de combinaciones
                                $value = $producto1->getPrecio() + $producto2->getPrecio() + $producto3->getPrecio();
                                if($value <= $number) { //Se valida que la suma de precios sea menor al número ingresado
                                    $all_combinations[$key] = $value;
                                }
                            }
                        }
                        
                    }
                }
                //Si existe al menos una combinación
                if(!empty($all_combinations)){

                    //Ordenar combinaciones de mayor a menor
                    arsort($all_combinations);

                    //Obtener los primeros 5 elementos del array
                    $combinations_ordered = array_slice($all_combinations, 0, 5);
                    
                    $combinations = [];

                    //Recorrer las combinaciones para consultar el nombre de cada producto
                    foreach($combinations_ordered as $key => $value) {
                        $ids = explode("-", $key);

                        //obtener los nombres de los productos productos por los ID
                        $nombres = array_map(function($id) use($productos){
                            
                            //Buscar el producto en el array productos
                            $productos_filtered = array_filter($productos, function($pro) use($id) {
                                return $pro->getId() == $id;
                            });

                            $producto = current($productos_filtered);
                            return $producto->getNombre();
                        }, $ids);

                        array_push($combinations, ['productos' => implode(', ', $nombres), 'valor' => $value]);
                    }
                    $this->sendResponse(200, $combinations);
                } else {
                    $this->sendResponse(400, "No hay ninguna combinación de 2 o 3 productos en que la suma de sus precios sea menor o igual al valor ingresado");
                }
            } else {
                $this->sendResponse(400, "Deben haber mínimo 2 productos para obtener combinaciones");
            }
        } else {
            $this->sendResponse(400, "Valor ingresado no es numérico");
        }
    }

    private function getProductsFromRows($rows) {
        // Convertir rows a objetos de Producto
        $productos = [];
        foreach($rows as $row) {
            $producto = new Producto(
                $row['id'],
                $row['nombre'],
                $row['descripcion'],
                $row['precio'],
                $row['cantidad_en_stock']
            );
            array_push($productos, $producto);
        }
        return $productos;
    }

    private function validateProductData($data) {
        return isset($data['nombre']) && isset($data['descripcion']) && isset($data['precio']) && isset($data['cantidad_en_stock']);
    }

    private function sendResponse($statusCode, $data) {
        http_response_code($statusCode);
        echo json_encode(['status' => $statusCode, 'data' => $data]);
    }
}

?>