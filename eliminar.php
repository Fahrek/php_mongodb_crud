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

$ide = (isset($_REQUEST['ide'])) ? $_REQUEST['ide'] : null;

if ($ide) {
    $informe_documento->remove(array('_id' => new MongoId($ide)));
}

header('Location: index.php');
exit;