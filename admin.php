<?php
  session_start();
  include_once 'php/connection.php';
  $database = new Connection();
  $db = $database->open();

  $query = "SELECT * FROM users ORDER BY access_user ASC";
  $result = $db->query($query);

  if(!isset($_SESSION['key'])){
    header('location: login.html');
  }
  else{
    if($_SESSION['key'] != 2){
      header('location: login.html');
    }
  }

  if(isset($_GET['userState'])){
    $id=(int) $_GET['userState'];
    $updateState = '';
    $state = '';
    $buscar_id=$db->prepare('SELECT * FROM users WHERE id_user=:id LIMIT 1');
		$buscar_id->execute(array(
			':id'=>$id
		));
		$resultado=$buscar_id->fetch();
    if($resultado){
      $state = $resultado['state_user'];
    }

    if($state == 'habilitado'){
      $updateState = 'deshabilitado';
    }
    if($state == 'deshabilitado'){
      $updateState = 'habilitado';
    }
    if($updateState != ''){
      $sdb = $db->prepare(' UPDATE users SET
      state_user=:updateState WHERE id_user=:id;');

      $state = $sdb->execute([
      ':updateState' =>$updateState,
      ':id' =>$id]);
      header('location: admin.php');
    }
  }
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
      <div class="wrapper">
        <header>
          <nav>
            <div class="logo" style="width: 250px;">
              <div class="col-div-6">
              <span style="font-size:30px;cursor:pointer; color: white;" class="nav"  ><p class="logo"><span style="color: #f7403b;">R</span>-Rudy</p></span>
              </div>
            </div>

            <div class="menu">
              <ul>
                <li><a href="admin.php">Inicio</a></li>
                <li><a href="login.php?close" class="black">Cerrar sesión</a></li>
              </ul>
            </div>
          </nav>
        </header>
      </div>

    <div class="container" style="width:1000px;">

      <div class="col-md-3">
           <input type="text" name="userlook" id="cilook" class="form-control" placeholder="CI Usuario" />
      </div>
      <div class="col-md-5">
           <input type="button" name="filte" id="filte" value="Buscar" class="btn btn-info" />
           <input type="button" name="habilitados"  id="habilitados" value="Habilitados" class="btn btn-info" />
           <input type="button" name="deshabilitados"  id="deshabilitados" value="Deshabilitados" class="btn btn-info" />
           <a href="adminAdd.php">
           <input type="button" name="addUser"  value="Agregar" class="btn btn-info">
           </a>
      </div>
    </div>

    <div class="seccion">
      <form>
        <h2></h2>
        <div id="order_table">
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
                <th class="bg-primary" scope="col"></th>
              </tr>
            </thead>

            <?php
            foreach($result as $res):?>
            <tbody>
              <tr>
                <td><?php echo $res["id_user"]; ?></td>
                <td><?php echo $res["user_user"]; ?></td>
                <td><?php echo $res["password_user"]; ?></td>
                <td><?php echo $res["name_user"]; ?></td>
                <td><?php echo $res["ci_user"]; ?></td>
                <td><?php echo $res["access_user"]; ?></td>
                <td><?php echo $res["state_user"]; ?></td>
                <td>
                  <a href="admin.php?userState=<?php echo $res['id_user']; ?>" class="btnAction btn btn-info">
                    <?php
                      if($res["state_user"] == 'habilitado'){
                        echo "Deshabilitar";
                      }
                      else{
                        echo "Habilitar";
                      }
                    ?>
                  </a>
                  <a href="adminUpdate.php?userList=<?php echo $res['id_user']; ?>" class="btnAction btn btn-info">Editar</a>
                  <a href="function.php?userDelete=<?php echo $res['id_user']; ?>" class="btnAction btn btn-info">Eliminar</a>
                </td>
              </tr>
            </tbody>
            <?php endforeach ?>
          </table>
        </div>
      </form>
    </div>
  </body>
</html>

<script>
     $(document).ready(function(){
          $('#filte').click(function(){
               var cilook = $("#cilook").val();
               if(cilook != '')
               {
                    $.ajax({
                         url:"function.php",
                         method:"POST",
                         data:{cilook:cilook},
                         success:function(data)
                         {
                              $('#order_table').html(data);
                         }
                    });
               }
               else
               {
                    alert("Por favor, introduzca el ci de usuario.");
               }
          });

          $('#habilitados').click(function(){
               var habilitados = $("#habilitados").val();
               $.ajax({
                         url:"function.php",
                         method:"POST",
                         data:{habilitados:habilitados},
                         success:function(data)
                         {
                              $('#order_table').html(data);
                         }
              });
          });

          $('#deshabilitados').click(function(){
               var deshabilitados = $("#deshabilitados").val();
               $.ajax({
                         url:"function.php",
                         method:"POST",
                         data:{deshabilitados:deshabilitados},
                         success:function(data)
                         {
                              $('#order_table').html(data);
                         }
              });
          });
     });
</script>
