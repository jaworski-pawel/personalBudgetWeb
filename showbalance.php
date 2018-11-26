<?php
    session_start();

    if (!isset($_SESSION['user_logged_in'])) {
		header('Location: index.php');
		exit();
    }
?>
<!DOCTYPE html>
<html lang="pl_PL">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Aplikcja internetowa do zarządzania domowym budżetem.">
    <meta name="keywords" content="budżet, finanse, pieniądze, zarządzanie, ekonomia">
    <meta name="author" content="Paweł Jaworski">
    <title>Budżet domowy</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet"> 
    <link rel="stylesheet" type="text/css" media="screen" href="css/main.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
      <header>
        <div class="page-header text-center">
          <h1>Budżet domowy</h1>
          <p>Zaplanuj swoje finanse!</p>
        </div>
      </header>
        <div class="content">
            <div class="balance">
              <div class="dropdown text-right">
                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                  Okres
                  <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                  <li><a href="showbalancefromcurrentmonth.php">Bieżący miesiąc</a></li>
                  <li><a href="#">Poprzedni miesiąc</a></li>
                  <li><a href="showbalancefromcurrentyear.php">Bieżący rok</a></li>
                  <li role="separator" class="divider"></li>
                  <li><a href="#">Niestandardowe</a></li>
                </ul>
            </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Przychód</th>
                            <th>Kategoria</th>
                            <th>Data</th>
                            <th>Kwota</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                      require_once "connect.php";
                      mysqli_report(MYSQLI_REPORT_STRICT);

                      if (!isset($_SESSION['start_date'])) {
                        $_SESSION['start_date'] = "1970-01-01";
                        $_SESSION['end_date'] = $currentdate = date('Y-m-d');
                    }
                        
                      try 
                      {
                        $db_connection = new mysqli($host, $db_user, $db_password, $db_name);
                        
                        if ($db_connection->connect_errno!=0)
                        {
                          throw new Exception(mysqli_connect_errno());
                        }
                        else
                        {
                          $start_date = mysqli_real_escape_string($db_connection, $_SESSION['start_date']);
                          $end_date = mysqli_real_escape_string($db_connection, $_SESSION['end_date']);
                          $user_id = mysqli_real_escape_string($db_connection, $_SESSION['id']);
                          $get_incomes = "SELECT i.income_comment, c.name AS category, i.date_of_income, i.amount FROM incomes AS i NATURAL JOIN incomes_category_assigned_to_users AS c WHERE (i.date_of_income BETWEEN '$start_date' AND '$end_date') AND user_id = '$user_id' ";
                          
                          if ($query_result = $db_connection->query("$get_incomes")) {
                            $number_of_incomes = $query_result->num_rows;
                            if($number_of_incomes > 0) {
                              while($incomes = $query_result->fetch_assoc()) {
                                echo '<tr><td>'.$incomes["income_comment"].'</td><td>'.$incomes["category"].'</td><td>'.$incomes["date_of_income"].'</td><td>'.$incomes["amount"];
                              }
                            }
                            else {
                              $_SESSION['e_payment_methods'] = "Nie ma wydatków do wyświetlenia.";
                            }
                          }
                          else
                          {
                            throw new Exception($db_connection->error);
                          }
                          $get_sum_of_incomes = "SELECT SUM(amount) AS sum_of_incomes FROM incomes WHERE (date_of_income BETWEEN '$start_date' AND '$end_date') AND user_id = '$user_id'";
                          if ($query_result = $db_connection->query("$get_sum_of_incomes")) {
                            $sum_of_incomes = $query_result->fetch_assoc();
                            echo '<tr><td colspan="3">Razem</td><td id="totalincomes">'.$sum_of_incomes["sum_of_incomes"].'</td></tr>';
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
                        echo '<div class="error text-center">Błąd serwera! Przepraszamy za niedogodności i prosimy o wizytę w innym terminie!</div>';
                        //echo '<br />Informacja developerska: '.$e;
                      }
                    ?>
                    </tbody>
                </table>

                <div id="piechartincomes"></div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Wydatek</th>
                            <th>Sposób płatności</th>
                            <th>Kategoria</th>
                            <th>Data</th>
                            <th>Kwota</th>
                        </tr>
                    </thead>
                    <tbody>
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
                            else {

                                $get_expenses = "SELECT e.expense_comment, ( SELECT c.name FROM expenses_category_assigned_to_users AS c WHERE c.id = e.expense_category_assigned_to_user_id ) AS category, ( SELECT p.name FROM payment_methods_assigned_to_users AS p WHERE p.id = e.payment_method_assigned_to_user_id ) AS payment, e.date_of_expense, e.amount FROM expenses AS e WHERE (e.date_of_expense BETWEEN '$start_date' AND '$end_date') AND user_id = '$user_id' ";
                    
                                if ($query_result = $db_connection->query("$get_expenses")) {
                                    $number_of_expenses = $query_result->num_rows;
                                    if($number_of_expenses > 0) {
                                        while($expenses = $query_result->fetch_assoc()) {
                                            echo '<tr><td>'.$expenses["expense_comment"].'</td><td>'.$expenses["payment"].'</td><td>'.$expenses["category"].'</td><td>'.$expenses["date_of_expense"].'</td><td>'.$expenses["amount"];
                                        }
                                    }
                                    else {
                                    $_SESSION['e_payment_methods'] = "Nie ma wydatków do wyświetlenia.";
                                    }
                                }
                            else {
                                throw new Exception($db_connection->error);
                            }
                            $get_sum_of_expenses = "SELECT SUM(amount) AS sum_of_expenses FROM expenses WHERE (date_of_expense BETWEEN '$start_date' AND '$end_date') AND user_id = '$user_id'";
                            if ($query_result = $db_connection->query("$get_sum_of_expenses")) {
                                $sum_of_expenses = $query_result->fetch_assoc();
                                echo '<tr><td colspan="4">Razem</td><td id="totalexpenses">'.$sum_of_expenses["sum_of_expenses"].'</td></tr>';
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
                            echo '<div class="error text-center">Błąd serwera! Przepraszamy za niedogodności i prosimy o wizytę w innym terminie!</div>';
                            //echo '<br />Informacja developerska: '.$e;
                        }
                        ?>
                    </tbody>
                </table>

                <div id="piechartexpenses"></div>
                <div class="text-center" id="summary"></div>
                <div class="text-center" id="comment"></div>
                <div class="text-center col-sm-offset-4 col-sm-4">
                    <a href="mainmenu.php"><button type="button" class="btn btn-default btn-lg btn-block">Menu główne</button></a>
                </div>
            </div>
        </div>
    </div> 
    <footer>
        <div class="footer text-center">
            <p>&copy; Paweł Jaworski 2018</p>
        </div>
    </footer>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> 
    <script type="text/javascript" src="js/piechartincomes.js"></script> 
    <script type="text/javascript" src="js/piechartexpenses.js"></script> 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/balance.js"></script>
</body>
</html>