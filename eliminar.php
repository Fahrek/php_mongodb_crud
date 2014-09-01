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

if($id){
    $cliente_documento_objeto = new MongoId($id);
    $cliente_coleccion->remove(array(
        '_id' => $cliente_documento_objeto
    ));
}

header('Location: index.php');
exit;
