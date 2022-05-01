<?php

class Usuario extends Conectar{

    public function insert_usuario($usu_nom,$usu_ape,$usu_correo)
    {
        $conectar = parent::conexion();
        $sql = "INSERT INTO tm_usuario (usu_nom,usu_ape,usu_correo,est) VALUES(?,?,?,1);";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1,$usu_nom);
        $stmt->bindValue(2,$usu_ape);
        $stmt->bindValue(3,$usu_correo);
        $stmt->execute();
    }
}
?>