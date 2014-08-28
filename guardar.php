<?php

try {
    $mongo = new Mongo();
    $db = $mongo->selectDB('prueba');
    $informe_documento = $db->selectCollection('informe');
} catch (MongoConnectionException $e) {
    echo $e->getMessage();
    exit;
} catch (MongoException $e) {
    echo $e->getMessage();
    exit;
}


$ide = (isset($_REQUEST['ide'])) ? $_REQUEST['ide'] : null;

if($ide){
    $informe_documento_objeto = new MongoId($ide);
    $informe = $informe_documento->findOne(array(
        '_id' => $informe_documento_objeto
    ));
}else{
    $informe_documento_objeto = null;
    $informe = array(
        'titulo' => null,
        'descripcion' => null,
        '_id' => null
    );
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $data = array(
        'titulo' => $titulo,
        'descripcion' => $descripcion
    );
    if($informe_documento_objeto){
        $informe_documento->update(array('_id' => $informe_documento_objeto), $data);
    }else{
        $informe_documento->insert($data);
    }
    header('Location: index.php');
    exit;
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title> Guardar </title>
    </head>
    <body>
        <h1> Guardar </h1>
        <form method="post" action="guardar.php">
            <label> Título </label>
            <br />
            <input type="text" name="titulo" required="required" value="<?php echo $informe['titulo'] ?>" />
            <br />
            <label> Descripción </label>
            <br />
            <textarea cols="50" rows="10" name="descripcion" required="required"><?php echo $informe['descripcion'] ?></textarea>
            <br />
            <input type="hidden" name="ide" value="<?php echo $informe['_id'] ?>"  />
            <input type="submit" value="Guardar" />
            <a href="index.php"> Cancelar </a>
        </form>
    </body>
</html>