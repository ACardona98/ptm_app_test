document.addEventListener("DOMContentLoaded", function() {
    loadProductos();
    loadTotal();
    getCatData();
    getUselessFacts();
    document.getElementById("productoForm").addEventListener("submit", function(e) {
        e.preventDefault();
        saveProducto();
    });
});

function loadProductos() {
    fetch('../index.php?action=list')
        .then(response => response.json())
        .then(data => {

            const productos = data.data;
            const tbody = document.querySelector("#productosTable tbody");
            tbody.innerHTML = "";

            //Recorrer los productos para ser mostrados en la tabla de productos
            productos.forEach(producto => {
                const row = document.createElement("tr");

                row.innerHTML = `
                    <td>${producto.id}</td>
                    <td>${producto.nombre}</td>
                    <td>${producto.descripcion}</td>
                    <td>${producto.precio}</td>
                    <td>${producto.cantidad_en_stock}</td>
                    <td>
                        <button onclick="editProducto(${producto.id})">Editar</button>
                    </td>
                `;

                tbody.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Error cargando productos:', error);
            alert('Hubo un problema al cargar los productos. Por favor, intenta nuevamente.');
        });
}

function loadTotal(){
    fetch('../index.php?action=getTotal')
        .then(response => response.json())
        .then(data => {
            const spanTotal = document.getElementById("total");
            spanTotal.innerText = data.data;

        })
        .catch(error => {
            console.error('Error cargando total:', error);
        });
}

function getCombinations() {
    const numberToCompare = document.getElementById("number_to_compare").value;

    fetch(`../index.php?action=getCombinations&number=${numberToCompare}`)
        .then(response => response.json())
        .then(data => {
            if(data.status == 200){
                const combinations = data.data;

                const tbody = document.querySelector("#combinacionesTable tbody");
                tbody.innerHTML = "";
                let c = 1;

                //Recorrer las combinaciones para ser mostradas en la tabla de combinaciones
                combinations.forEach(combination => {
                    const row = document.createElement("tr");

                    row.innerHTML = `
                        <td>${c++}</td>
                        <td>${combination.productos}</td>
                        <td>${combination.valor}</td>
                    `;

                    tbody.appendChild(row);
                });
            } else {
                alert(data.data);
            }
        })
        .catch(error => {
            console.error('Error cargando combinaciones:', error);
        });
}

function saveProducto() {
    const form = document.getElementById("productoForm");
    const formData = new FormData(form);

    //Validar si el método debe ser POST o PUT dependiendo si tiene o no ID el producto
    const id = formData.get("id");
    const method = id ? "PUT" : "POST";

    fetch('../index.php', {
        method: method,
        body: JSON.stringify(Object.fromEntries(formData)),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        alert(data.data);
        resetForm();
        loadProductos();
        loadTotal();
    })
    .catch(error => {
        console.error('Error guardando el producto:', error);
        alert('Hubo un problema al guardar el producto. Por favor, intenta nuevamente.');
    });
}

function editProducto(id) {
    fetch(`../index.php?action=get&id=${id}`)
        .then(response => response.json())
        .then(data => {
            //Agregar en los inputs los valores retornados del producto
            const producto = JSON.parse(data.data);
            document.getElementById("id").value = producto.id;
            document.getElementById("nombre").value = producto.nombre;
            document.getElementById("descripcion").value = producto.descripcion;
            document.getElementById("precio").value = producto.precio;
            document.getElementById("cantidad_en_stock").value = producto.cantidad_en_stock;
        })
        .catch(error => {
            console.error('Error cargando el producto:', error);
            alert('Hubo un problema al cargar el producto. Por favor, intenta nuevamente.');
        });
}

function deleteProducto() {
    const id = document.getElementById("id").value;

    if (id) {
        if (confirm("¿Estás seguro de que deseas eliminar este producto?")) {
            fetch('../index.php', {
                method: 'DELETE',
                body: JSON.stringify({ id: id }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                alert(data.data);
                resetForm();
                loadProductos();
                loadTotal();
            })
            .catch(error => {
                console.error('Error eliminando el producto:', error);
                alert('Hubo un problema al eliminar el producto. Por favor, intenta nuevamente.');
            });
        }
    } else {
        alert("Selecciona un producto para eliminar");
    }
}

function resetForm() {
    document.getElementById("productoForm").reset();
    document.getElementById("id").value = "";
}

function getCatData(){
    //Consultar API de datos de gatos
    fetch('https://meowfacts.herokuapp.com/?count=2&lang=esp')
        .then(response => response.json())
        .then(data => {
            let catData = data.data.reduce(
                (accumulator, currentValue) => `${accumulator}
                    <li>${currentValue}</li> `,
                "",
            );
            //agregar el dato en el modal
            document.getElementById("catData").innerHTML = catData;
            //mostrar modal
            showModal();
        })
        .catch(error => {
            console.error('Error cargando datos de gatos:', error);
        });
}

function getUselessFacts(){
    //Consultar API de uselessfacts
    fetch('https://uselessfacts.jsph.pl/api/v2/facts/today')
        .then(response => response.json())
        .then(data => {
            //agregar en el footer el dato inútil
            let uselessFact = data.text;
            document.getElementById("uselessFacts").innerHTML = uselessFact;
        })
        .catch(error => {
            console.error('Error cargando datos inútiles:', error);
        });
}

function showModal(){
    // Obtener el modal
    var modal = document.getElementById("myModal");

    // Obtener el botón que cierra el modal
    var span = document.getElementsByClassName("close")[0];

    // Mostrar el modal al cargar la página
    modal.style.display = "block";

    // Cuando el usuario hace clic en <span> (x), cierra el modal
    span.onclick = function() {
        modal.style.display = "none";
    }


    // Cuando el usuario hace clic fuera del contenido del modal, cierra el modal
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
}

function sortTable(columnIndex) {
    const table = document.getElementById("productosTable");
    const tbody = table.tBodies[0];
    const rows = Array.from(tbody.rows);
    const isAscending = table.querySelectorAll("th")[columnIndex].classList.toggle("sort-asc");
    
    // Alterna la dirección de ordenación
    if (!isAscending) {
        table.querySelectorAll("th")[columnIndex].classList.remove("sort-asc");
        table.querySelectorAll("th")[columnIndex].classList.add("sort-desc");
    } else {
        table.querySelectorAll("th")[columnIndex].classList.remove("sort-desc");
        table.querySelectorAll("th")[columnIndex].classList.add("sort-asc");
    }

    // Tipo de datos a ordenar
    const compare = (rowA, rowB) => {
        const cellA = rowA.cells[columnIndex].innerText.trim();
        const cellB = rowB.cells[columnIndex].innerText.trim();

        const numA = parseFloat(cellA);
        const numB = parseFloat(cellB);

        if (!isNaN(numA) && !isNaN(numB)) {
            return isAscending ? numA - numB : numB - numA;
        } else {
            return isAscending
                ? cellA.localeCompare(cellB)
                : cellB.localeCompare(cellA);
        }
    };

    // Ordenar las filas
    rows.sort(compare);

    // Remover filas antiguas
    while (tbody.firstChild) {
        tbody.removeChild(tbody.firstChild);
    }

    // Agregar las filas ordenadas
    tbody.append(...rows);
}