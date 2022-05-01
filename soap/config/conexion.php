<?php

class Conectar{
    protected $host;

    protected function conexion()
    {
        try {
            $conectar = $this->host = new PDO('mysql:host=localhost;dbname=prueba_epayco','root','');
            return $conectar;
        } catch (Exception $e) {
            print "Error!: ".$e->getMessage()."<br/>";
            die();
        }
    }
}


?>