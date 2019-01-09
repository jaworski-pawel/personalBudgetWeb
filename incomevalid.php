<?php
	
	if (!isset($_SESSION['user_logged_in'])) {
		header('Location: index.php');
		exit();
  }

  if (isset($_POST['amount'])) {
    $successful_validation = true;

    // Amount validation

    $amount = $_POST['amount'];

    if ((!(preg_match('/^[0-9]{1,20}$/', $amount))) && (!(preg_match('/^[0-9]{1,20}+\.+[0-9]{1,2}$/', $amount))) && (!(preg_match('/^[0-9\+]{1,20}+\,+[0-9\+]{1,2}$/', $amount)))) {
      $successful_validation = false;
      $_SESSION['e_amount'] = "Niepoprawny format kwoty!";
    }
    else {
      if (preg_match('/^[0-9]{1,20}+\,+[0-9]{1,2}$/', $amount)) {
        $amount = str_replace(",",".",$amount);
      }
    }

    // Date validation
    
    $date = $_POST['date'];

    if(!(preg_match('/^[0-9]{4}+\-+[0-9]{1,2}+\-+[0-9]{1,2}$/', $date))) {
      $successful_validation = false;
      $_SESSION['e_date'] = "Data musi być w formacie: RRRR-MM-DD";
    }
    else {
      $year = substr($date, 0, 4);
      $month = substr($date, 5, 2);
      $day = substr($date, 8, 2);
      
      if(!checkdate($month, $day, $year)) {
        $successful_validation = false;
        $_SESSION['e_date'] = "Niepoprawna data!";
      }
      else {
        $currentdate = date('Y-m-d');
        $currentyear = substr($currentdate, 0, 4);
        $currentmonth = substr($currentdate, 5, 2);
        $currentday = substr($currentdate, 8, 2);
      
        if($year > $currentyear) {
          $successful_validation = false;
          $_SESSION['e_date'] = "Data nie może być z przyszłości!";
        }
        elseif($year == $currentyear) {
          if($month > $currentmonth) {
            $successful_validation = false;
            $_SESSION['e_date'] = "Data nie może być z przyszłości!";
          }
          elseif($month = $currentmonth) {
            if($day > $currentday) {
              $successful_validation = false;
              $_SESSION['e_date'] = "Data nie może być z przyszłości!";
            }
          }
        }
      }
    }

     //Category id validation
     $category_id = $_POST['category'];
 
    // Comment validation
    $comment = $_POST['comment'];

    // Adding income

    require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT);

    try {
			$db_connection = new mysqli($host, $db_user, $db_password, $db_name);
			if ($db_connection->connect_errno!=0) {
				throw new Exception(mysqli_connect_errno());
			}
			else {
				if ($successful_validation==true) {
          $user_id = $_SESSION['id'];
					$add_income = "INSERT INTO incomes VALUES (NULL, '$user_id', '$category_id', '$amount', '$date', '$comment')";

					if ($db_connection->query("$add_income")) {
            $_SESSION['successful_operation'] = true;
            header('Location: afteraddingoperation.php');
					}
					else {
						throw new Exception($db_connection->error);
					}
							
				}
				
				$db_connection->close();
			}
			
		}
		catch(Exception $e)
		{
			echo '<div class="error text-center">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</div>';
			echo '<br />Informacja developerska: '.$e;
		}

  }
	
?>