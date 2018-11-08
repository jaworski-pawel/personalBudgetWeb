<?php

	session_start();
	
	if (isset($_POST['email']))
	{
		$successful_validation=true;
		
		// Login validation
		$login = $_POST['login'];
		
		if ((strlen($login) < 3) || (strlen($login) > 20))
		{
			$successful_validation=false;
			$_SESSION['e_login']="Login musi posiadać od 3 do 20 znaków!";
		}
		
		if (ctype_alnum($login)==false)
		{
			$successful_validation=false;
			$_SESSION['e_login']="Login może składać się tylko z liter i cyfr (bez polskich znaków)!";
		}
		
		// E-mail validation
		$email = $_POST['email'];
		$sanitized_email = filter_var($email, FILTER_SANITIZE_EMAIL);
		
		if ((filter_var($sanitized_email, FILTER_VALIDATE_EMAIL)==false) || ($sanitized_email!=$email))
		{
			$successful_validation=false;
			$_SESSION['e_email']="Podaj poprawny adres e-mail!";
		}
		
		// Password validation
		$password1 = $_POST['password1'];
		$password2 = $_POST['password2'];
		
		if ((strlen($password1)<8) || (strlen($password1)>20))
		{
			$successful_validation=false;
			$_SESSION['e_password']="Hasło musi posiadać od 8 do 20 znaków!";
		}
		
		if ($password1!=$password2)
		{
			$successful_validation=false;
			$_SESSION['e_password']="Podane hasła nie są identyczne!";
		}	

		$password_hash = password_hash($password1, PASSWORD_DEFAULT);	
		
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
				// Email exists?
				$query_result = $db_connection->query("SELECT id FROM users WHERE email='$email'");
				
				if (!$query_result) throw new Exception($db_connection->error);
				
				$number_of_users = $query_result->num_rows;
				if($number_of_users > 0)
				{
					$successful_validation=false;
					$_SESSION['e_email']="Istnieje już konto przypisane do tego adresu e-mail!";
				}		

				// Login exists?
				$query_result = $db_connection->query("SELECT id FROM users WHERE username='$login'");
				
				if (!$query_result) throw new Exception($db_connection->error);
				
				$number_of_users = $query_result->num_rows;
				if($number_of_users > 0)
				{
					$successful_validation=false;
					$_SESSION['e_login']="Istnieje już użytkownik o takim loginie! Wybierz inny.";
				}
				
				if ($successful_validation==true)
				{
					// Successful validation, register
					
					if ($db_connection->query("INSERT INTO users VALUES (NULL, '$login', '$password_hash', '$email')")) {
						if ($query_result = $db_connection->query(sprintf("SELECT * FROM users WHERE username='%s'", mysqli_real_escape_string($db_connection, $login)))) {
         					$number_of_users = $query_result->num_rows;
          					if($number_of_users > 0) {
            					$user_data = $query_result->fetch_assoc();
            					$_SESSION['user_id'] = $user_data['id'];
								$query_result->free_result();
							}
							if ($db_connection->query("ALTER TABLE payment_methods_default ADD user_id INT(11) NOT NULL DEFAULT '$user_id' AFTER id")) {
								if ($db_connection->query("INSERT INTO payment_methods_assigned_to_users (id, user_id, name) SELECT id, user_id, name FROM payment_methods_default")) {
									if ($db_connection->query("ALTER TABLE payment_methods_default DROP user_id")) {
										if ($db_connection->query("ALTER TABLE expenses_category_default ADD user_id INT(11) NOT NULL DEFAULT '$user_id' AFTER id")) {
											if ($db_connection->query("INSERT INTO  expenses_category_assigned_to_users (id, user_id, name) SELECT id, user_id, name FROM expenses_category_default")) {
												if ($db_connection->query("ALTER TABLE expenses_category_default DROP user_id")) {
													if ($db_connection->query("ALTER TABLE incomes_category_default ADD user_id INT(11) NOT NULL DEFAULT '$user_id' AFTER id")) {
														if ($db_connection->query("INSERT INTO  incomes_category_assigned_to_users (id, user_id, name) SELECT id, user_id, name FROM incomes_category_default")) {
															if ($db_connection->query("ALTER TABLE incomes_category_default DROP user_id")) {
																$_SESSION['udanarejestracja']=true;
																header('Location: welcome.php');
															}
															else {
																throw new Exception($db_connection->error);
															}
														}
														else {
															throw new Exception($db_connection->error);
														}
													}
													else {
														throw new Exception($db_connection->error);
													}
												}
												else {
													throw new Exception($db_connection->error);
												}
											}
											else {
												throw new Exception($db_connection->error);
											}
										}
										else {
											throw new Exception($db_connection->error);
										}
									}
									else {
										throw new Exception($db_connection->error);
									}
								}
								else {
									throw new Exception($db_connection->error);
								}
							}
							else {
								throw new Exception($db_connection->error);
							}
						}
						else {
							throw new Exception($db_connection->error);
						}
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
			//echo '<br />Informacja developerska: '.$e;
		}
		
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
          <div class="register text-center col-sm-offset-4 col-sm-4">
            <form method="post">
              <input type="text" class="form-control input-lg" id="logininput" placeholder="login" name="login">
			  <?php
			  	if (isset($_SESSION['e_login'])) {
					echo '<div class="error">'.$_SESSION['e_login'].'</div>';
					unset($_SESSION['e_login']);
				}
			  ?>
              <input type="email" class="form-control input-lg" id="emailinput" placeholder="email" name="email">
			  <?php
			  	if (isset($_SESSION['e_email'])) {
					echo '<div class="error">'.$_SESSION['e_email'].'</div>';
					unset($_SESSION['e_email']);
				}
			  ?>
              <input type="password" class="form-control input-lg" id="passwordinput" placeholder="hasło" name="password1">
			  <?php
			  	if (isset($_SESSION['e_password'])) {
					echo '<div class="error">'.$_SESSION['e_password'].'</div>';
					unset($_SESSION['e_password']);
				}
			  ?>
              <input type="password" class="form-control input-lg" id="passwordinput" placeholder="powtórz hasło" name="password2">
              <button type="submit" class="btn btn-default btn-lg">Zarejestruj się</button>
            </form>
          </div>
      </div>
    </div>
    <footer>
        <div class="footer text-center">
            <p>&copy; Paweł Jaworski 2018</p>
        </div>
    </footer>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  </body>
</html>