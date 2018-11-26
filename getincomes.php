<?php
  if (!isset($_SESSION['user_logged_in'])) {
    header('Location: index.php');
		exit();
  }

  require_once "connect.php";
  mysqli_report(MYSQLI_REPORT_STRICT);
    
  if (!isset($_SESSION['start_date'])) {
    $_SESSION['start_date'] = "1970-01-01";
    $_SESSION['end_date'] = $currentdate = date('Y-m-d');
  }
                        
  try {
    $db_connection = new mysqli($host, $db_user, $db_password, $db_name);
                      
    if ($db_connection->connect_errno!=0) {
      throw new Exception(mysqli_connect_errno());
    }
    else {
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
      else {
        throw new Exception($db_connection->error);
      }
      
      $get_sum_of_incomes = "SELECT SUM(amount) AS sum_of_incomes FROM incomes WHERE (date_of_income BETWEEN '$start_date' AND '$end_date') AND user_id = '$user_id'";
        
      if ($query_result = $db_connection->query("$get_sum_of_incomes")) {
        $sum_of_incomes = $query_result->fetch_assoc();
        echo '<tr><td colspan="3">Razem</td><td id="totalincomes">'.$sum_of_incomes["sum_of_incomes"].'</td></tr>';
      }
      else {                  
        throw new Exception($db_connection->error);
      }

      $db_connection->close();
    }
  }                
  catch(Exception $e) {
    echo '<div class="error text-center">Błąd serwera! Przepraszamy za niedogodności i prosimy o wizytę w innym terminie!</div>';
    //echo '<br />Informacja developerska: '.$e;
  }
?>