<?php

	session_start();
	
	if (!isset($_SESSION['user_logged_in']))
	{
		header('Location: index.php');
		exit();
  }
  
  require_once "expensevalid.php";

  $currentdate = date('Y-m-d');
	
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <title>Personal Budget</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat&amp;subset=latin-ext" rel="stylesheet"> 
  <link rel="stylesheet" href="css/style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<nav class="navbar navbar-inverse visible-xs">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="#"><span class="glyphicon glyphicon-piggy-bank"></span>PERSONAL BUDGET</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li class="active"><a href="mainmenu.php"><span class="glyphicon glyphicon-home"></span>Menu główne</a></li>
        <li><a href="addincome.php"><span class="glyphicon glyphicon-export"></span>Dodaj przychód</a></li>
        <li><a href="addexpense.php"><span class="glyphicon glyphicon-import"></span>Dodaj wydatek</a></li>
        <li><a href="showbalance.php"><span class="glyphicon glyphicon-list-alt"></span>Przeglądaj bilans</a></li>
        <li><a href="settings.php"><span class="glyphicon glyphicon-cog"></span>Ustawienia</a></li>
        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span>Wyloguj się</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container-fluid">
  <div class="row content">
    <div class="col-sm-2 sidenav hidden-xs">
        <div class="logo">
          <a href="mainmenu.php">
            <span class="glyphicon glyphicon-piggy-bank"></span>PERSONAL BUDGET
          </a>
        </div>
      <hr />
      <ul class="nav nav-pills nav-stacked">
        <li><a href="mainmenu.php"><span class="glyphicon glyphicon-home"></span>Menu główne</a></li>
        <li><a href="addincome.php"><span class="glyphicon glyphicon-export"></span>Dodaj przychód</a></li>
        <li class="active"><a href="addexpense.php"><span class="glyphicon glyphicon-import"></span>Dodaj wydatek</a></li>
        <li><a href="showbalance.php"><span class="glyphicon glyphicon-list-alt"></span>Przeglądaj bilans</a></li>
        <li><a href="settings.php"><span class="glyphicon glyphicon-cog"></span>Ustawienia</a></li>
        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span>Wyloguj się</a></li>
      </ul>
    </div>
    
    <div class="col-sm-10 main-content">
      <div class="text-right user-profile">
        <span class="glyphicon glyphicon-user"></span>
        <?php
          echo $_SESSION['login_of_user'];
        ?>
      </div>
    </div>

    <div class="col-sm-10 main-content">
      <div class="row text-center">
          <diV class="well col-sm-6 col-sm-offset-3">
            <form method="post">
              <fieldset class="operation">
                <legend><span class="glyphicon glyphicon-import"></span>Dodaj wydatek</legend>
                
                <!-- Amount -->

                <label for="amount">Kwota:</label>
                <div class="input-group">
                  <span class="input-group-addon" id="amount"><span class="glyphicon glyphicon-usd"></span></span>
                  <input type="text" class="form-control" aria-describedby="amount" name="amount">
                </div> 
                <?php
                if (isset($_SESSION['e_amount'])) {
                  echo '<div class="alert alert-danger" role="alert">'.$_SESSION['e_amount'].'</div>';
                  unset($_SESSION['e_amount']);
                }
                ?>

                <!-- Date -->

                <label for="date">Data: </label>
                <div class="input-group">
                  <span class="input-group-addon" id="date"><span class="glyphicon glyphicon-calendar"></span></span>
                  <input type="date" class="form-control" value="<?php echo "$currentdate"?>" aria-describedby="date" name="date">
                </div>
                <?php
                if (isset($_SESSION['e_date'])) {
                  echo '<div class="alert alert-danger" role="alert">'.$_SESSION['e_date'].'</div>';
                  unset($_SESSION['e_date']);
                }
                ?>

                <!-- Pay -->

                <label for="pay">Sposób płatności: </label>
                <div class="input-group">
                  <span class="input-group-addon" id="category"><span class="glyphicon glyphicon-credit-card"></span></span>
                  <select id="pay" name="pay" class="form-control">
                  <?php
                      require_once "connect.php";
                      mysqli_report(MYSQLI_REPORT_STRICT);
                        
                      try 
                      {
                        $db_connection = new mysqli($host, $db_user, $db_password, $db_name);
                        
                        if ($db_connection->connect_errno!=0)
                        {
                          throw new Exception(mysqli_connect_errno());
                        }
                        else
                        {
                          if ($query_result = $db_connection->query(sprintf("SELECT * FROM payment_methods_assigned_to_users WHERE user_id='%s'", mysqli_real_escape_string($db_connection, $_SESSION['id'])))) {
                            $number_of_payment_methods = $query_result->num_rows;
                            if($number_of_payment_methods > 0) {
                              while($payment_methods = $query_result->fetch_assoc()) {
                                echo '<option value="'.$payment_methods["id"].'">'.$payment_methods["name"].'</option>';
                              }
                            }
                            else {
                              $successful_validation = false;
                              $_SESSION['e_payment_methods'] = "Nie udało się pobrać metod płatności z bazy danych";
                            }
                          }
                          else
                          {
                            throw new Exception($db_connection->error);
                          }
                          
                          $db_connection->close();
                        }
                      }
                      catch(Exception $e)
                      {
                        echo '<div class="alert alert-danger" role="alert">Błąd serwera! Przepraszamy za niedogodności i prosimy o wizytę w innym terminie!</div>';
                        //echo '<br />Informacja developerska: '.$e;
                      }
                    ?>
                  </select>
                </div>
                <?php
                if (isset($_SESSION['e_payment_methods'])) {
                  echo '<div class="alert alert-danger" role="alert">'.$_SESSION['e_payment_methods'].'</div>';
                  unset($_SESSION['e_payment_methods']);
                }
                ?>

                <!-- Category -->

                <label for="category">Kategoria: </label>
                <div class="input-group">
                  <span class="input-group-addon" id="category"><span class="glyphicon glyphicon-th-list"></span></span>
                  <select aria-describedby="category" name="category" class="form-control">
                  <?php
                  require_once "connect.php";
                  mysqli_report(MYSQLI_REPORT_STRICT);
                          
                  try {
                    $db_connection = new mysqli($host, $db_user, $db_password, $db_name);
                          
                    if ($db_connection->connect_errno!=0) {
                      throw new Exception(mysqli_connect_errno());
                    }
                    else {
                      if ($query_result = $db_connection->query(sprintf("SELECT * FROM incomes_category_assigned_to_users WHERE user_id='%s'", mysqli_real_escape_string($db_connection, $_SESSION['id'])))) {
                        $number_of_categories = $query_result->num_rows;
                        if($number_of_categories > 0) {
                          while($category = $query_result->fetch_assoc()) {
                            echo '<option value="'.$category["id"].'">'.$category["name"].'</option>';
                          }
                        }
                        else {
                          $successful_validation = false;
                          $_SESSION['e_category'] = "Nie udało się pobrać kategorii z bazy danych";
                        }
                      }
                      else {
                        throw new Exception($db_connection->error);
                      }
                              
                      $db_connection->close();
                    }
                  }
                  catch(Exception $e) {
                    echo '<div class="alert alert-danger" role="alert">Błąd serwera! Przepraszamy za niedogodności i prosimy o wizytę w innym terminie!</div>';
                    //echo '<br />Informacja developerska: '.$e;
                  }
                  ?>
                  </select>
                </div>
                <?php
                if (isset($_SESSION['e_category'])) {
                  echo '<div class="alert alert-danger" role="alert">'.$_SESSION['e_category'].'</div>';
                  unset($_SESSION['e_']);
                }
                ?>

                <!-- Comment -->

                <label for="comment">Komentarz: </label>
                <div class="input-group">
                  <span class="input-group-addon" id="comment"><span class="glyphicon glyphicon-comment"></span></span>
                  <textarea aria-describedby="comment" class="form-control" name="comment"></textarea>
                </div> 
                
                <?php
                  if (isset($_SESSION['e_comment'])) {
                  echo '<div class="alert alert-danger" role="alert">'.$_SESSION['e_comment'].'</div>';
                  unset($_SESSION['e_comment']);
                  }
                ?>
                <div class="buttons text-center">
                  <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span>Dodaj</button>
                  <a href="mainmenu.php"><button type="button" class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>Anuluj</button></a>
                </div>
              </fieldset>
            </form>

            <!-- Modal after operation-->

            <div class="modal fade" id="modal-after-operation" role="dialog">
              <div class="modal-dialog">
                
                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Sukces!</h4>
                  </div>
                  <div class="modal-body">
                    <p>Pomyślnie dodano operację. Możesz dodać kolejną operację lub zobaczyć bilans.</p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal"><span class="glyphicon glyphicon-plus"></span>Dodaj kolejny</button>
                    <a href="showbalance.php"><button type="button" class="btn btn-info"><span class="glyphicon glyphicon-list-alt"></span>Przeglądaj bilans</button></a>
                  </div>
                </div>
                
              </div>
            </div>
            
            <?php
            if (isset($_SESSION['show_modal_after_operation'])) {
              echo '<script type="text/javascript">$(\'#modal-after-operation\').modal(\'show\')</script>';
              unset($_SESSION['show_modal_after_operation']);
            }
            ?>

        </div>
      </div>
    </div>
  </div>
</div>

<footer class="col-sm-12 footer text-center"><p>&copy; Paweł Jaworski 2018</p></footer>
</body>
</html>
