<?php
	session_start();
	
	if ((isset($_SESSION['is_loged_in'])) && ($_SESSION['is_loged_in']==true))
	{
		header('Location: mainmenu.php');
		exit();
  }

  require_once "registervalid.php";
  require_once "loginvalid.php";
?>

<!DOCTYPE html>
<html lang="pl_PL">
<head>
  <title>Personal Budget</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat&amp;subset=latin-ext" rel="stylesheet"> 
  <link rel="stylesheet" href="css/start.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container-fluid">
  <div class="row content">
      <div class="col-md-6 col-md-offset-3 well text-center">
        <h2><span class="glyphicon glyphicon-piggy-bank"></span>PERSONAL BUDGET</h2>
        <h4>Zarządzaj swoim budżetem!</h4>
        <hr />
        <p>PERSONAL BUDGET to aplikacja do zarządzania własnym, domowym budżetem. Możesz w niej dodawać wydatki, przychody, przeglądać bilans swoich operacji w prosty i przejrzysty sposób. Zaloguj się lub jeśli nie posiadasz konta zerejestruj się całkowicie za darmo!</p>
        
        <!-- Register -->
        <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#modalRegister"><span class="glyphicon glyphicon-pencil"></span>Rejestracja</button>

        <div class="modal fade" id="modalRegister" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="glyphicon glyphicon-pencil"></span>Rejestracja</h4>
              </div>
              <div class="modal-body">
                <form method="post">
                  <div class="input-group">
                    <span class="input-group-addon" id="logininput"><span class="glyphicon glyphicon-user"></span></span>
                    <input type="text" class="form-control" placeholder="login" aria-describedby="logininput" name="login" id="logininput">
                  </div>
                  <?php
                  if (isset($_SESSION['e_login'])) {
                    echo '<script type="text/javascript">$(\'#modalRegister\').modal(\'show\')</script>';
                    echo '<div class="alert alert-danger" role="alert">'.$_SESSION['e_login'].'</div>';
                    unset($_SESSION['e_login']);
                  }
                  ?>
                  <div class="input-group">
                    <span class="input-group-addon" id="emailinput"><span class="glyphicon glyphicon-envelope"></span></span>
                    <input type="email" class="form-control" placeholder="email" aria-describedby="emailinput" name="email" id="emailinput">
                  </div>
                  <?php
                  if (isset($_SESSION['e_email'])) {
                    echo '<script type="text/javascript">$(\'#modalRegister\').modal(\'show\')</script>';
                    echo '<div class="alert alert-danger" role="alert">'.$_SESSION['e_email'].'</div>';
                    unset($_SESSION['e_email']);
                  }
                  ?>
                  <div class="input-group">
                    <span class="input-group-addon" id="passwordinput"><span class="glyphicon glyphicon-eye-close"></span></span>
                    <input type="password" class="form-control" placeholder="hasło" aria-describedby="passwordinput" name="password1">
                  </div>
                  <?php
                  if (isset($_SESSION['e_password'])) {
                    echo '<script type="text/javascript">$(\'#modalRegister\').modal(\'show\')</script>';
                    echo '<div class="alert alert-danger" role="alert">'.$_SESSION['e_password'].'</div>';
                    unset($_SESSION['e_password']);
                  }
                  ?>
                  <div class="input-group">
                    <span class="input-group-addon" id="passwordinput"><span class="glyphicon glyphicon-eye-close"></span></span>
                    <input type="password" class="form-control" placeholder="hasło" aria-describedby="passwordinput" name="password2">
                  </div>
                  <button type="button" class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>Anuluj</button>
                  <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span>Zarejestuj się</button>
                </form>
              </div>
            </div>
          </div>
        </div>

        <!-- Login -->
        <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#modalLogin"><span class="glyphicon glyphicon-log-in"></span>Logowanie</button>

        <div class="modal fade" id="modalLogin" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="glyphicon glyphicon-log-in"></span>Logowanie</h4>
              </div>
              <div class="modal-body">
                <?php 
                if (isset($_SESSION['e_userlogin'])) {
                  echo '<script type="text/javascript">$(\'#modalLogin\').modal(\'show\')</script>';
				    	    echo '<div class="alert alert-danger" role="alert">'.$_SESSION['e_userlogin'].'</div>';
					        unset($_SESSION['e_userlogin']);
				        }
                ?>
              <form method="post">
                <div class="input-group">
                  <span class="input-group-addon" id="logininput"><span class="glyphicon glyphicon-user"></span></span>
                  <input type="text" class="form-control" placeholder="login" aria-describedby="logininput" name="userlogin">
                </div> 
                <div class="input-group">
                  <span class="input-group-addon" id="passwordinput"><span class="glyphicon glyphicon-eye-close"></span></span>
                  <input type="password" class="form-control" placeholder="hasło" aria-describedby="passwordinput" name="userpassword">
                </div> 
                <button type="button" class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>Anuluj</button>
                <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span>Zaloguj się</button>
              </form>
              </div>
            </div>
          </div>
        </div>
      </div>
  </div>
</div>
<footer class="col-sm-12 footer text-center"><p>&copy; Paweł Jaworski 2018</p></footer>

</body>
</html>
