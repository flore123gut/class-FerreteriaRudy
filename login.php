<?php
//Se hace llamado a la clase que establece la conexion
include_once 'php/connection.php';
//Se inicia sesion como identificador
session_start();

if(isset($_GET['close'])){
  session_unset();
  session_destroy();
  header('location: login.html');
}

if(isset($_POST['close'])){
  session_unset();
  session_destroy();
  header('location: login.html');
}
//determina el acceso, dependiendo de ello el usuario es redireccionado
if(isset($_SESSION['rol'])){
  switch($_SESSION['rol']){
    case 1:
      header('location: user.php');
      break;

    case 2:
      header('location: admin.php');
      break;

    default:
      break;
  }
}
//Se comprueba el nombre y contraseña del usuario
if(isset($_POST['username']) && isset($_POST['password'])){
  $username = $_POST['username'];
  $password = $_POST['password'];

  $database = new Connection();
  $db = $database->open();

  $query = $db->prepare('SELECT * FROM users WHERE user_user = :username AND password_user = :password');
  $query->execute(['username' => $username, 'password' => $password]);
  $row = $query->fetch(PDO::FETCH_NUM);
  //Se verifican los datos del usuario - Row inicia en la posición 0
  if($row == true){
    $valueName = $row[1] + " " + $row[2];
    $valueCi = $row[3];
    $key = $row[11];
    $estado = $row[12];
    //Se verifica el estado del usuario y se almacena la informaciòn
    if($estado == "habilitado"){
      $_SESSION['key'] = $key;
      $_SESSION['ci'] = $valueCi;
      $_SESSION['name'] = $valueName;
      //Se redirecciona al usuario
      switch($_SESSION['key']){
        case 1:
          header('location: user.php');
          break;

        case 2:
          header('location: admin.php');
          break;

        default:
          break;
      }
    }
    else{
      echo "<script languaje='javascript'>alert('Acceso Denegado!. Cuenta deshabilitada'); location.href = 'login.html';</script>";
    }
  }
  else{
    echo "<script languaje='javascript'>alert('El usuario o contraseña son incorrectos.'); location.href = 'login.html';</script>";
  }
}

?>
