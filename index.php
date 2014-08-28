<?php

try {
    $mongo = new Mongo();
    $db = $mongo->selectDB('prueba');
    $informe_documento = $db->selectCollection('informe');
    $lista = $informe_documento->find();
} catch (MongoConnectionException $e) {
    echo $e->getMessage();
    exit;
} catch (MongoException $e) {
    echo $e->getMessage();
    exit;
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title> Lista </title>
    </head>
    <body>
        <h1> Lista </h1>
        <p>
            <a href="guardar.php"> Crear nuevo </a>
        </p>
        <ul>
            <?php while ($lista->hasNext()): ?>
                <?php $item = $lista->getNext() ?>
                <li> 
                    <h2> <?php echo $item['titulo'] ?> </h2>
                    <p> 
                        <a href="guardar.php?ide=<?php echo $item['_id']  ?>"> Editar </a>
                        |
                        <a href="eliminar.php?ide=<?php echo $item['_id']  ?>"> Eliminar </a>
                    </p>
                    <hr />
                </li>
            <?php endwhile; ?>
        </ul>
    </body>
</html>