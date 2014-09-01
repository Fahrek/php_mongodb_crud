<?php

try {
    $mongo = new Mongo();
    $db = $mongo->selectDB('veterinaria');
    $cliente_coleccion = $db->selectCollection('cliente');
} catch (MongoConnectionException $e) {
    echo $e->getMessage();
    exit;
} catch (MongoException $e) {
    echo $e->getMessage();
    exit;
}

$lista = $cliente_coleccion->find();

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title> Lista </title>
        <link type="text/css" href="css/bootstrap.min.css" rel="stylesheet" /> 
    </head>
    <body>
        <div class="container">
            <h1> Lista </h1>
            <div class="row">
                <div class="span4">
                    <a href="guardar.php" class="btn btn-success"> Agregar nuevo cliente </a>
                </div>
            </div>
            <table id="lista_clientes" class="table">
                <thead>
                    <tr>
                        <th> Nombre y apellido </th>
                        <th> Dirección </th>
                        <th> Teléfono </th>
                        <th> Email </th>
                        <th> Mascotas </th>
                        <th>  </th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($lista->hasNext()): ?>
                        <?php $item = $lista->getNext() ?>
                        <tr>
                            <td> <?php echo $item['nombre_apellido'] ?> </td>
                            <td> <?php echo $item['direccion'] ?> </td>
                            <td> <?php echo $item['telefono'] ?> </td>
                            <td> <?php echo $item['email'] ?> </td>
                            <td> 
                                <ul>
                                    <?php foreach ($item['mascotas'] as $item2): ?> 
                                        <li>
                                            <?php echo $item2['nombre'] . ' - ' . $item2['detalle'] ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </td>
                            <td>
                                <a href="guardar.php?_id=<?php echo $item['_id'] ?>" class="btn btn-info"> Editar </a>
                                <a href="eliminar.php?_id=<?php echo $item['_id'] ?>" class="btn btn-danger eliminar"> Eliminar </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript">
            $("#lista_clientes .eliminar").each(function(){
                var href = $(this).attr('href');
                $(this).attr('href', 'javascript:void(0);');
                $(this).click(function(){
                    if(confirm('¿Está seguro/a que desa eliminar a este cliente?')){
                        location.href = href;
                    }
                });
            });
        </script>
    </body>
</html>
