<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="content">
        <h1>Gestión de Productos</h1>
        <form id="productoForm">
            <input type="hidden" id="id" name="id">

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" required></textarea>

            <label for="precio">Precio:</label>
            <input type="number" step="0.01" id="precio" name="precio" required>

            <label for="cantidad_en_stock">Cantidad en Stock:</label>
            <input type="number" id="cantidad_en_stock" name="cantidad_en_stock" required>

            <button type="submit">Guardar</button>
            <button type="button" id="deleteButton" onclick="deleteProducto()">Eliminar</button>
            <button type="button" onclick="resetForm()">Cancelar</button>
        </form>

        <h2>Lista de Productos</h2>
        <table id="productosTable">
            <thead>
                <tr>
                    <th class="sort-asc" onclick="sortTable(0)">ID</th>
                    <th onclick="sortTable(1)">Nombre</th>
                    <th onclick="sortTable(2)">Descripción</th>
                    <th onclick="sortTable(3)">Precio</th>
                    <th onclick="sortTable(4)">Cantidad en Stock</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

        <h3>Total inventario: <span id="total"></span></h1>

        <h3>Obtener combinaciones (precio menor a valor ingresado)</h1>
        <label for="number_to_compare">Ingrese un número:</label>
        <input type="number" id="number_to_compare" name="number_to_compare" required>
        <button type="button" id="getCombinationsButton" onclick="getCombinations()">Obtener combinaciones</button>

        <table id="combinacionesTable">
            <thead>
                <tr>
                    <th>Cont</th>
                    <th>Productos</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

        <!-- Modal -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Sabías que...</h2>
                <p>
                    <ul id="catData"></ul>
                </p>
            </div>
        </div>  
    </div>

    <footer class="footer">
        <div class="footer-content">
            <h5>&copy; 2024 Prueba Técnica Alejandro Cardona Suaza.</h5>
            <p id="uselessFacts">
            </p>
        </div>
    </footer>

    <script src="../assets/js/productos.js"></script>

</body>
</html>