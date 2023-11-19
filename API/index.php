<?php

require 'flight/Flight.php';

Flight::register('db', 'PDO', array("sqlsrv:server = tcp:pia.database.windows.net,1433; Database = PIA", "Gama", "GamitaServer23."), function($db){
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
});
Flight::route('GET /usuarios', function () {
    $info = Flight::db()->prepare("SELECT * FROM usuarios");
    $info->execute();
    $datos = $info->fetchAll();
    Flight::json($datos);
});

Flight::route('POST /usuarios', function () {
    $nombre = Flight::request()->query->nombre;
    $apellido = Flight::request()->query->apellido;
    $correo = Flight::request()->query->correo;
    $UIDNfc = Flight::request()->query->UIDNfc;
    $nivel = Flight::request()->query->nivel;

    $sql = "INSERT INTO usuarios (nombre, apellido, correo, UIDNfc,nivel) VALUES (?,?,?,?,?)";
    $info = Flight::db()->prepare($sql);
    $info->bindParam(1,$nombre);
    $info->bindParam(2,$apellido);
    $info->bindParam(3,$correo);
    $info->bindParam(4,$UIDNfc);
    $info->bindParam(5,$nivel);
    $info->execute();
    Flight::jsonp("Usuario Agregado");
});

Flight::route('DELETE /usuarios', function(){
    $id = Flight::request()->query->id;
    $sql = "DELETE FROM usuarios WHERE id=?";
    $info = Flight::db()->prepare($sql);
    $info->bindParam(1,$id);
    $info->execute();
    Flight::jsonp("Usuario Eliminado");

});

Flight::route('PUT /usuarios', function(){
    $id = Flight::request()->query->id;
    $nombre = Flight::request()->query->nombre;
    $apellido = Flight::request()->query->apellido;
    $correo = Flight::request()->query->correo;
    $UIDNfc = Flight::request()->query->UIDNfc;
    $nivel = Flight::request()->query->nivel;

    $sql = "UPDATE usuarios SET nombre=?, apellido=?, correo=?, UIDNfc=?,nivel=? WHERE id=?";
 
    $info = Flight::db()->prepare($sql);
    $info->bindParam(1,$nombre);
    $info->bindParam(2,$apellido);
    $info->bindParam(3,$correo);
    $info->bindParam(4,$UIDNfc);
    $info->bindParam(5,$nivel);
    $info->bindParam(6,$id);
    $info->execute();
    Flight::jsonp("Usuario Actualizado");

});

Flight::route('GET /usuarios/@id', function($id){
    $sql = "SELECT * FROM usuarios WHERE id = ?";
    $info = Flight::db()->prepare($sql);
    $info->bindParam(1,$id);

    $info->execute();
    $datos = $info->fetchAll();
    Flight::json($datos);

});


Flight::start();
