<?php
session_start();

// Protege la página (si no está logueado lo manda al login)
if (!isset($_SESSION['usuario_id'])) {
    header("Location: /tienda_bebes/views/login.php");
    exit();
}

// cargamos la conexión y el controlador (creamos $db)
require_once __DIR__ . '/../config/db.php';
$database = new Database();
$db = $database->getConnection();

require_once __DIR__ . '/../controllers/RopaController.php';
$controller = new RopaController($db);

// Procesos: guardar / actualizar / eliminar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $precio = (float)($_POST['precio'] ?? 0);
    $stock  = (int)($_POST['stock'] ?? 0);

    if (isset($_POST['guardar'])) {
        $controller->guardar($nombre, $precio, $stock);
        header("Location: /tienda_bebes/views/ropa.php");
        exit();
    }

    if (isset($_POST['actualizar']) && !empty($_POST['id'])) {
        $id = (int)$_POST['id'];
        $controller->actualizar($id, $nombre, $precio, $stock);
        header("Location: /tienda_bebes/views/ropa.php");
        exit();
    }

    if (isset($_POST['eliminar']) && !empty($_POST['id'])) {
        $id = (int)$_POST['id'];
        $controller->eliminar($id);
        header("Location: /tienda_bebes/views/ropa.php");
        exit();
    }
}

// Para edición: si llega ?editar=ID cargamos el producto
$editarItem = null;
if (isset($_GET['editar'])) {
    $editarId = (int)$_GET['editar'];
    $editarItem = $controller->getById($editarId);
}

// Cargar lista
$ropa = $controller->index();
$rol = $_SESSION['usuario_rol'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ropa de Bebés</title>
    <link rel="stylesheet" href="/tienda_bebes/css/estilo.css">
    <style>
        /* MODAL */
        .modal {
            display: none; 
            position: fixed;
            z-index: 1000;
            left: 0; top: 0;
            width: 100%; height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
            animation: fadeIn 0.3s;
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 30px 40px;
            border-radius: 12px;
            width: 90%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            animation: slideDown 0.3s;
        }

        .modal-content h2 {
            margin-bottom: 15px;
            color: #333;
        }

        .modal-content p {
            margin-bottom: 25px;
            font-size: 16px;
            color: #555;
        }

        .modal-content button {
            padding: 10px 20px;
            background-color: #5c9ded;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .modal-content button:hover {
            background-color: #467ac9;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 20px;
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover { color: #333; }

        @keyframes fadeIn {
            from {opacity: 0;}
            to {opacity: 1;}
        }

        @keyframes slideDown {
            from {transform: translateY(-50px); opacity: 0;}
            to {transform: translateY(0); opacity: 1;}
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>👕 Ropa de Bebés</h2>
        <p><a href="/tienda_bebes/index.php">← Volver al Menú</a></p>

        <?php if ($rol === 'admin'): ?>
            <h3><?= $editarItem ? 'Editar prenda' : 'Agregar nueva prenda' ?></h3>
            <form method="POST" style="margin-bottom:16px;">
                <?php if ($editarItem): ?>
                    <input type="hidden" name="id" value="<?= $editarItem['id'] ?>">
                <?php endif; ?>

                <label>Nombre</label>
                <input type="text" name="nombre" required value="<?= htmlspecialchars($editarItem['nombre'] ?? '') ?>">

                <label>Precio</label>
                <input type="number" step="0.01" name="precio" required value="<?= htmlspecialchars($editarItem['precio'] ?? '') ?>">

                <label>Stock</label>
                <input type="number" name="stock" required value="<?= htmlspecialchars($editarItem['stock'] ?? '') ?>">

                <?php if ($editarItem): ?>
                    <button type="submit" name="actualizar">Actualizar</button>
                    <a href="/tienda_bebes/views/ropa.php" class="btn">Cancelar</a>
                <?php else: ?>
                    <button type="submit" name="guardar">Guardar</button>
                <?php endif; ?>
            </form>
        <?php endif; ?>

        <!-- Modal -->
        <div id="modalCompra" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Compra simulada</h2>
                <p id="modalText"></p>
                <button id="cerrarModal">Aceptar</button>
            </div>
        </div>

        <h3>Listado</h3>
        <table>
            <thead>
                <tr><th>ID</th><th>Nombre</th><th>Precio</th><th>Stock</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                <?php foreach ($ropa as $item): ?>
                    <tr>
                        <td><?= $item['id'] ?></td>
                        <td><?= htmlspecialchars($item['nombre']) ?></td>
                        <td>$<?= number_format((float)$item['precio'], 2) ?></td>
                        <td><?= $item['stock'] ?></td>
                        <td>
                            <?php if ($rol === 'admin'): ?>
                                <a href="?editar=<?= $item['id'] ?>">✏️ Editar</a> |
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                    <button type="submit" name="eliminar" onclick="return confirm('¿Eliminar?')">🗑️ Eliminar</button>
                                </form>
                            <?php else: ?>
                                <button onclick="mostrarCompra('<?= htmlspecialchars(addslashes($item['nombre'])) ?>')">Comprar</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>

    <script>
        const modal = document.getElementById('modalCompra');
        const modalText = document.getElementById('modalText');
        const cerrarModal = document.getElementById('cerrarModal');
        const spanClose = document.querySelector('.close');

        function mostrarCompra(nombre) {
            modalText.textContent = `Has realizado la compra de "${nombre}". ¡Gracias por tu interés!`;
            modal.style.display = 'block';
        }

        cerrarModal.onclick = () => modal.style.display = 'none';
        spanClose.onclick = () => modal.style.display = 'none';
        window.onclick = (event) => { if (event.target === modal) modal.style.display = 'none'; };
    </script>
</body>
</html>
