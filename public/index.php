<?php

require_once '../app/controllers/ProductoController.php';

$controller = new ProductoController();

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'list':
            $controller->list();
            break;
        case 'get':
            $controller->get($_GET);
            break;
        case 'getTotal':
        	$controller->getInventoryTotalValue();
        	break;
        case 'getCombinations':
        	$controller->getCombinations($_GET['number']);
        	break;
        default:
            $controller->handleRequest();
            break;
    }
} else {
    $controller->handleRequest();
}
?>