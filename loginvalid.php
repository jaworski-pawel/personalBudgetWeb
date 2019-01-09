<?php
	
	if ((isset($_POST['userlogin'])) && (isset($_POST['userpassword']))) {
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
        $login = $_POST['userlogin'];
        $password = $_POST['userpassword'];
        
        $login = htmlentities($login, ENT_QUOTES, "UTF-8");
      
        if ($query_result = $db_connection->query(sprintf("SELECT * FROM users WHERE username='%s'", mysqli_real_escape_string($db_connection, $login)))) {
          $number_of_users = $query_result->num_rows;
          if($number_of_users > 0) {
            $user_data = $query_result->fetch_assoc();
            
            if (password_verify($password, $user_data['password'])) {
              $_SESSION['user_logged_in'] = true;
              $_SESSION['id'] = $user_data['id'];
              
              unset($_SESSION['e_userlogin']);
              $query_result->free_result();
              $_SESSION['login_of_user'] = $login;
              header('Location: mainmenu.php');
            }
            else {
              $_SESSION['e_userlogin'] = "Nieprawidłowy login lub hasło!";
            }
            
          } else {
            $_SESSION['e_userlogin'] = "Nieprawidłowy login lub hasło!";
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
      echo '<div class="error text-center">Błąd serwera! Przepraszamy za niedogodności i prosimy o wizytę w innym terminie!</div>';
      echo '<br />Informacja developerska: '.$e;
    }
  }
?>