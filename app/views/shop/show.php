<?php include_once (VIEWS.'header.php')?>
<h2 class="text-center"><?= $data['subtitle'] ?></h2>
<img src="<?= ROOT ?>img/<?= $data['data']->image ?>" class="rounded float-right">
<h4>Precio:</h4>
<p><?= number_format($data['data']->price,2) ?> €</p>
<?php if ($data['data']->type == 1): ?>
    <h4>Descripción:</h4>
    <?= html_entity_decode($data['data']->description) ?>
    <h4>A quien va dirigido:</h4>
    <p><?= $data['data']->people ?></p>
    <h4>Qué es necesario:</h4>
    <p><?= $data['data']->necesites ?></p>
<?php elseif ($data['data']->type == 2): ?>
    <h4>Autor</h4>
    <p><?= $data['data']->author ?></p>
    <h4>Editorial:</h4>
    <p><?= $data['data']->publisher ?></p>
    <h4>Número de páginas:</h4>
    <p><?= $data['data']->pages ?></p>
    <h4>Resumen:</h4>
    <?= html_entity_decode($data['data']->description) ?>
<?php endif ?>
<?php if(!isset($_SESSION['user'])): ?>
<a href="<?= ROOT ?>login" class="btn btn-info">Iniciar Sesion</a>
<?php else: ?>
<a href="<?= ROOT ?>cart/addproduct/<?= $data['data']->id ?>/<?= $data['user_id'] ?>" class="btn btn-info">Comprar</a>
<?php endif;?>
<?php $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
<?php if (substr($url,-5,5) == 'books'){
    $texto = "Volver al listado de libros";
}elseif (substr($url,-7,7) == 'courses') {
    $texto = "Volver al listado de cursos";
}else{
    $texto = "Volver al listado de productos";
}?>
<a href="<?= ROOT . ((empty($data['back'])) ? 'shop' : $data['back'] ) ?>" class="btn btn-success">
    <?= $texto ?>
</a>
<?php include_once (VIEWS.'footer.php')?>
