<?php

class Payment extends Conectar{

    public function createPayment($pay_token,$pay_expires_at,$cli_id,$pay_amount)
    {
        $this->revokeLeftPayments($cli_id);
        $conectar = parent::conexion();
        $sql = "INSERT INTO payments (pay_token,pay_expires_at,cli_id,pay_amount) VALUES(?,?,?,?);";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1,$pay_token);
        $stmt->bindValue(2,$pay_expires_at);
        $stmt->bindValue(3,$cli_id);
        $stmt->bindValue(4,$pay_amount);
        $stmt->execute();
    }

    public function revokeLeftPayments($cli_id)
    {
        $conectar = parent::conexion();
        $sql = "UPDATE payments SET pay_revoked = 1 WHERE cli_id = :cli_id AND pay_approved = 0;";
        $stmt = $conectar->prepare($sql);
        $stmt->execute(['cli_id' => $cli_id]); 
    }

    public function getPayment($token)
    {
        $conectar = parent::conexion();
        $sql = "SELECT * FROM payments WHERE pay_token = :token";
        $stmt = $conectar->prepare($sql);
        $stmt->execute(['token' => $token]);
        $resp = $stmt->fetch();
        if(count($resp) > 0 ) return $resp;
        return false;
        
    }

    public function approvePayment($pay_session_id)
    {
        $conectar = parent::conexion();
        $sql = "UPDATE payments SET pay_approved = 1 WHERE pay_session_id = :pay_session_id;";
        $stmt = $conectar->prepare($sql);
        $stmt->execute(['pay_session_id' => $pay_session_id]);
        return true;
    }
}
?>