<?php

class Client extends Conectar{

    public function insert_client($cli_doc,$cli_name,$cli_email,$cli_cel)
    {
        $conectar = parent::conexion();
        $sql = "INSERT INTO clients (cli_doc,cli_name,cli_email,cli_cel) VALUES(?,?,?,?);";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1,$cli_doc);
        $stmt->bindValue(2,$cli_name);
        $stmt->bindValue(3,$cli_email);
        $stmt->bindValue(4,$cli_cel);
        $stmt->execute();
        return true;
    }

    public function existsClient($cli_doc,$cli_email)
    {
        $conectar = parent::conexion();
        $sql = "SELECT * FROM clients WHERE cli_doc = :cli_doc OR cli_email = :cli_email";
        $stmt = $conectar->prepare($sql);
        $stmt->execute(array(':cli_doc' => $cli_doc, ':cli_email' => $cli_email));
        $resp = $stmt->fetch();
        if($resp) return $resp;
        return false;
    }

    public function validateAccount($cli_doc,$cli_cel)
    {
        $conectar = parent::conexion();
        $sql = "SELECT * FROM clients WHERE cli_doc = :cli_doc AND cli_cel = :cli_cel";
        $stmt = $conectar->prepare($sql);
        $stmt->execute(array(':cli_doc' => $cli_doc, ':cli_cel' => $cli_cel));
        $resp = $stmt->fetch();
        if($resp) return $resp;
        return false;
    }

    public function rechargeAccount($cli_doc,$amount)
    {
        $conectar = parent::conexion();
        $sql = "UPDATE clients SET cli_balance = :amount WHERE cli_doc = :cli_doc;";
        $stmt = $conectar->prepare($sql);
        $stmt->execute(array(':amount' => $amount,':cli_doc' => $cli_doc));
        return true;
    }

    public function getClientById($cli_id)
    {
        $conectar = parent::conexion();
        $sql = "SELECT * FROM clients WHERE cli_id = :cli_id;";
        $stmt = $conectar->prepare($sql);
        $stmt->execute(array(':cli_id' => $cli_id));
        return $stmt->fetch(); 
    }

    public function updateBalance($cli_id,$balance)
    {
        $conectar = parent::conexion();
        $sql = "UPDATE clients SET cli_balance = :balance WHERE cli_id = :cli_id;";
        $stmt = $conectar->prepare($sql);
        $stmt->execute(array(':balance' => $balance,':cli_id' => $cli_id));
        return true;
    }

    public function getClientByDocAndCel($cli_doc,$cli_cel)
    {
        $conectar = parent::conexion();
        $sql = "SELECT * FROM clients WHERE cli_doc = :cli_doc AND cli_cel = :cli_cel;";
        $stmt = $conectar->prepare($sql);
        $stmt->execute(array(':cli_doc' => $cli_doc,':cli_cel' => $cli_cel));
        return $stmt->fetch(); 
    }
}
?>