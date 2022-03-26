<?php
session_start();
include_once 'php/connection.php';
$database = new Connection();
$db = $database->open();
$query = "";
$output = "";

if(isset($_GET['userDelete'])){
  $id=(int) $_GET['userDelete'];

  $queryLevel = "DELETE FROM users WHERE id_user='".$id."' LIMIT 1";
  $resultLevel = $db->query($queryLevel);
  $resultLevel->execute();

  header('Location: admin.php');
}

if(isset($_POST['add'])){
  $name = $_POST['newName'];
  $ci = $_POST['newCi'];
  $user = $_POST['newUser'];
  $password = $_POST['newPassword'];
  $access = $_POST['newAccess'];

  $queryCi = "SELECT * FROM users WHERE ci_user='".$ci."'";
  $resultCi = $db->query($queryCi);
  $resultCi->execute();

  $queryUser = "SELECT * FROM users WHERE user_user='".$user."'";
  $resultUser = $db->query($queryUser);
  $resultUser->execute();

  if ($resultCi->rowCount() > 0 || $resultUser->rowCount() > 0) {
    if($resultUser->rowCount() > 0){
      echo "<script languaje='javascript'>alert('Este nombre usuario ya existe.'); location.href = 'admin.php';</script>";
    }
    else{
      echo "<script languaje='javascript'>alert('Esta cuenta ya existe, contactarse con un administrador si tiene problemas para conectarse.'); location.href = 'admin.php';</script>";
    }

  }
  else {
    $sql = "INSERT INTO users (user_user, password_user, name_user, ci_user, access_user, state_user)
            VALUES ('".$user."', '".$password."', '".$name."', '".$ci."','".$access."', 'habilitado')";

    $db->exec($sql);
    echo "<script languaje='javascript'>alert('Usuario agregado correctamente.'); location.href = 'admin.php';</script>";
  }
}

if(isset($_POST["cilook"]) || isset($_POST["habilitados"]) || isset($_POST["deshabilitados"])){
  if(isset($_POST["cilook"])){
    $query = "SELECT * FROM users WHERE ci_user ='".$_POST["cilook"]."' ";
  }
  if(isset($_POST["habilitados"])){
    $query = "SELECT * FROM users WHERE state_user ='habilitado' ";
  }
  if(isset($_POST["deshabilitados"])){
    $query = "SELECT * FROM users WHERE state_user ='deshabilitado' ";
  }

  $result = $db->query($query);
  $output .= '
              <table class="content-table">
                <thead>
                  <tr class="active-row">
                    <th class="bg-primary" scope="col">ID</th>
                    <th class="bg-primary" scope="col">USUARIO</th>
                    <th class="bg-primary" scope="col">PASSWORD</th>
                    <th class="bg-primary" scope="col">NOMBRE</th>
                    <th class="bg-primary" scope="col">CI</th>
                    <th class="bg-primary" scope="col">ACCESO</th>
                    <th class="bg-primary" scope="col">ESTADO</th>
                    <th class="bg-primary" scope="col">OPCIONES</th>
                  </tr>
                </thead>
     ';
     if($result->rowCount() > 0)
     {
       $value = '';
          while($res = $result->fetch(PDO::FETCH_BOTH))
          {
            if($res["state_user"] == 'habilitado'){
              $value= "Deshabilitar";
            }
            else{
              $value= "Habilitar";
            }
               $output .= '
               <tbody>
                    <tr>
                         <td>'. $res["id_user"] .'</td>
                         <td>'. $res["user_user"] .'</td>
                         <td>'. $res["password_user"] .'</td>
                         <td>'. $res["name_user"] .'</td>
                         <td>'. $res["ci_user"] .'</td>
                         <td>'. $res["access_user"] .'</td>
                         <td>'. $res["state_user"] .'</td>
                         <td>
                           <a href="admin.php?userState='.$res['id_user'].'" class="btnAction btn btn-info">
                             '.$value.'
                           </a>
                           <a href="adminUpdate.php?userList='.$res['id_user'].'" class="btn">Editar</a>
                           <a href="function.php?userDelete='.$res['id_user'].'" class="btn">Eliminar</a>
                        </td>
                    </tr>
                  </tbody>
               ';
          }
     }
     else
     {
          $output .= '
               <tr>
                    <td colspan="8" style="text-align:center;">Datos no encontrados</td>
               </tr>
          ';
     }
     $output .= '</table>';
     echo $output;
   }
 ?>
