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

$id = (isset($_REQUEST['_id'])) ? $_REQUEST['_id'] : null;

if ($id) {
    $cliente_documento_objeto = new MongoId($id);
    $cliente = $cliente_coleccion->findOne(array(
        '_id' => $cliente_documento_objeto
    ));
} else {
    $cliente_documento_objeto = null;
    $cliente = array(
        '_id' => null,
        'nombre_apellido' => null,
        'direccion' => null,
        'telefono' => null,
        'email' => null,
        'mascotas' => array()
    );
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_apellido = $_POST['nombre_apellido'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $mascotas_nombre = $_POST['mascotas_nombre'];
    $mascotas_detalle = $_POST['mascotas_detalle'];
    $mascotas = array();
    for ($i = 0; $i < count($mascotas_nombre); $i++) {
        $mascotas[] = array(
            'nombre' => $mascotas_nombre[$i],
            'detalle' => $mascotas_detalle[$i]
        );
    }
    $data = array(
        'nombre_apellido' => $nombre_apellido,
        'direccion' => $direccion,
        'telefono' => $telefono,
        'email' => $email,
        'mascotas' => $mascotas
    );
    if($cliente_documento_objeto){
        $cliente_coleccion->update(array('_id' => $cliente_documento_objeto), $data);
    }else{
        $cliente_coleccion->insert($data);
    }    
    header('Location: index.php');
    exit;
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title> Guardar cliente </title>
        <link type="text/css" href="css/bootstrap.min.css" rel="stylesheet" /> 
    </head>
    <body>
        <div class="container">
            <h1> Guardar cliente </h1>
            <form method="post" action="guardar.php">
                <div class="row">
                    <div class="span2">
                        <label> Nombre y apellido </label>
                    </div>
                    <div class="span4">
                        <input type="text" name="nombre_apellido" required="required" value="<?php echo $cliente['nombre_apellido'] ?>" />
                    </div>
                    <div class="span2">
                        <label> Dirección </label>
                    </div>
                    <div class="span4">
                        <input type="text" name="direccion" required="required" value="<?php echo $cliente['direccion'] ?>" />
                    </div>
                </div>
                <div class="row">
                    <div class="span2">
                        <label> Teléfono </label>
                    </div>
                    <div class="span4">
                        <input type="text" name="telefono" required="required" value="<?php echo $cliente['telefono'] ?>" />
                    </div>
                    <div class="span2">
                        <label> Email </label>
                    </div>
                    <div class="span4">
                        <input type="email" name="email" required="required" value="<?php echo $cliente['email'] ?>" />
                    </div>
                </div>
                <div class="row">
                    <div class="span4">
                        <a id="agregar_mascota_link" href="javascript:void(0);" class="text-success"> Agregar máscota </a>
                    </div>
                </div>
                <br />
                <div id="mascotas">
                    <?php foreach ($cliente['mascotas'] as $item): ?>
                        <div class="row">
                            <div class="span2"> 
                                <label> Nombre </label> 
                            </div>
                            <div class="span4"> 
                                <input type="text" name="mascotas_nombre[]" required="required" value="<?php echo $item['nombre'] ?>" /> 
                            </div>
                            <div class="span2"> 
                                <label> Detalle </label> 
                            </div>
                            <div class="span3"> 
                                <input type="text" name="mascotas_detalle[]" required="required" value="<?php echo $item['detalle'] ?>" /> 
                            </div>
                            <div class="span1"> 
                                <a href="javascript:void(0);" class="text-error eliminar"> Eliminar </a> 
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>                
                <div class="row">
                    <div class="span4">
                        <input type="hidden" name="_id" value="<?php echo $cliente['_id'] ?>" />
                        <input type="submit" value="Guardar" class="btn btn-success" />
                        <a href="index.php" class="btn btn-danger"> Cancelar </a>
                    </div>
                </div>
            </form>
        </div>
        <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript">
            $("#agregar_mascota_link").on('click', function() {
                var row = '<div class="row">';
                row += '<div class="span2"> <label> Nombre </label> </div>';
                row += '<div class="span4"> <input type="text" name="mascotas_nombre[]" required="required" /> </div>';
                row += '<div class="span2"> <label> Detalle </label> </div>';
                row += '<div class="span3"> <input type="text" name="mascotas_detalle[]" required="required" /> </div>';
                row += '<div class="span1"> <a href="javascript:void(0);" class="text-error eliminar"> Eliminar </a> </div>';
                row += '</div>';
                $("#mascotas").append(row);
            });
            $("#mascotas").on('click', '.eliminar', function() {
                var row = $(this).parents('.row').eq(0);
                row.remove();
            });
        </script>
    </body>
</html>
