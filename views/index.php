<?php
session_start();
require_once "../controllers/UserController.php";

$controller = new UserController();

$mensaje = "";
$error = "";
$editUser = null;

// CREAR
if (isset($_POST['crear'])) {
    try {
        $controller->store($_POST['nombre'], $_POST['email'], $_POST['tipo']);
    } catch(Exception $e){
        $error = $e->getMessage();
    }
}

// ACTUALIZAR
if (isset($_POST['actualizar'])) {
    $controller->update($_POST['id'], $_POST['nombre'], $_POST['email'], $_POST['tipo']);
}

// ELIMINAR
if (isset($_GET['delete'])) {
    $controller->delete($_GET['delete']);
}

// EDITAR
if (isset($_GET['edit'])) {
    $editUser = $controller->find($_GET['edit']);
}

$users = $controller->index();
?>

<!DOCTYPE html>
<html>
<head>
<title>Gestión de Usuarios</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

<h2 class="text-center mb-4">Sistema Gestión de Usuarios</h2>

<!-- FORMULARIO -->
<div class="card mb-4 shadow">
<div class="card-body">

<h5><?= $editUser ? "Editar Usuario" : "Crear Usuario" ?></h5>

<form method="POST" onsubmit="return validar()">

<?php if($editUser): ?>
<input type="hidden" name="id" value="<?= $editUser['id'] ?>">
<?php endif; ?>

<div class="row">
<div class="col-md-4">
<input type="text" name="nombre" class="form-control"
value="<?= $editUser['nombre'] ?? '' ?>" placeholder="Nombre" required>
</div>

<div class="col-md-4">
<input type="email" name="email" class="form-control"
value="<?= $editUser['email'] ?? '' ?>" placeholder="Email" required>
</div>

<div class="col-md-3">
<select name="tipo" class="form-select">
<option value="normal" <?= ($editUser['tipo'] ?? '')=="normal" ? "selected":"" ?>>Normal</option>
<option value="admin" <?= ($editUser['tipo'] ?? '')=="admin" ? "selected":"" ?>>Admin</option>
</select>
</div>

<div class="col-md-1">
<?php if($editUser): ?>
<button name="actualizar" class="btn btn-warning w-100">✓</button>
<?php else: ?>
<button name="crear" class="btn btn-primary w-100">+</button>
<?php endif; ?>
</div>

</div>
</form>

</div>
</div>

<!-- TABLA -->
<div class="card shadow">
<div class="card-body">

<h5>Lista de Usuarios</h5>

<table class="table table-bordered table-hover">
<thead class="table-dark">
<tr>
<th>ID</th>
<th>Nombre</th>
<th>Email</th>
<th>Tipo</th>
<th>Acciones</th>
</tr>
</thead>

<tbody>
<?php foreach($users as $u): ?>
<tr>
<td><?= $u['id'] ?></td>
<td><?= $u['nombre'] ?></td>
<td><?= $u['email'] ?></td>
<td>
<span class="badge bg-<?= $u['tipo']=="admin" ? "danger":"secondary" ?>">
<?= strtoupper($u['tipo']) ?>
</span>
</td>
<td>
<a href="?edit=<?= $u['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
<a href="?delete=<?= $u['id'] ?>" 
class="btn btn-sm btn-danger"
onclick="return confirm('¿Eliminar usuario?')">
Eliminar
</a>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

</div>
</div>

</div>

<!-- TOAST NOTIFICATIONS -->
<div class="toast-container position-fixed top-0 end-0 p-3">

<?php
if(!empty($_SESSION['notificacion'])){
foreach($_SESSION['notificacion'] as $n):
?>

<div class="toast align-items-center text-bg-<?= $n['tipo'] ?> border-0 show mb-2">
<div class="d-flex">
<div class="toast-body">
🔔 <?= $n['mensaje'] ?>
</div>
<button type="button" class="btn-close btn-close-white me-2 m-auto"
data-bs-dismiss="toast"></button>
</div>
</div>

<?php
endforeach;
unset($_SESSION['notificacion']);
}
?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
function validar(){
let nombre = document.querySelector("[name='nombre']").value;
if(nombre.length < 3){
alert("Nombre mínimo 3 caracteres");
return false;
}
return true;
}

// Auto ocultar notificaciones después de 3 segundos
setTimeout(() => {
document.querySelectorAll('.toast').forEach(t => {
t.classList.remove('show');
});
}, 3000);
</script>

</body>
</html>