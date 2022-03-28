<?php
class Connection{

  protected $conn;
  private $server = 'mysql:host=localhost; dbname=ferreteria';
  private $user = 'root';
  private $pass = '';
  private $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                           PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);

  public function open(){
    try{
      $this->conn = new PDO($this->server,
                            $this->user,
                            $this->pass,
                            $this->options);
      return $this->conn;
    }
    catch(PDOException $e){
      echo "Ocurrio un problema en la conexion: ".$e->getMessage();
    }
  }

  public function close(){
    $this->conn = null;
  }
}
?>
