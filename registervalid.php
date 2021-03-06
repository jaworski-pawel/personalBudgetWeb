<?php

	if (isset($_POST['email']))	{
		$successful_validation=true;
		
		// Login validation
		$login = $_POST['login'];
		
		if ((strlen($login) < 3) || (strlen($login) > 20)) {
			$successful_validation=false;
			$_SESSION['e_login']="Login musi posiadać od 3 do 20 znaków!";
		}
		
		if (ctype_alnum($login)==false)	{
			$successful_validation=false;
			$_SESSION['e_login']="Login może składać się tylko z liter i cyfr (bez polskich znaków)!";
		}
		
		// E-mail validation
		$email = $_POST['email'];
		$sanitized_email = filter_var($email, FILTER_SANITIZE_EMAIL);
		
		if ((filter_var($sanitized_email, FILTER_VALIDATE_EMAIL)==false) || ($sanitized_email!=$email))	{
			$successful_validation=false;
			$_SESSION['e_email']="Podaj poprawny adres e-mail!";
		}
		
		// Password validation
		$password1 = $_POST['password1'];
		$password2 = $_POST['password2'];
		
		if ((strlen($password1)<8) || (strlen($password1)>20)) {
			$successful_validation=false;
			$_SESSION['e_password']="Hasło musi posiadać od 8 do 20 znaków!";
		}
		
		if ($password1!=$password2)	{
			$successful_validation=false;
			$_SESSION['e_password']="Podane hasła nie są identyczne!";
		}	

		$password_hash = password_hash($password1, PASSWORD_DEFAULT);	
		
		require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT);
		
		try {
			$db_connection = new mysqli($host, $db_user, $db_password, $db_name);
			if ($db_connection->connect_errno!=0)	{
				throw new Exception(mysqli_connect_errno());
			}
			else {
				// Email exists?
				$query_result = $db_connection->query("SELECT id FROM users WHERE email='$email'");
				
				if (!$query_result) throw new Exception($db_connection->error);
				
				$number_of_users = $query_result->num_rows;
				if($number_of_users > 0) {
					$successful_validation=false;
					$_SESSION['e_email']="Istnieje już konto przypisane do tego adresu e-mail!";
				}		

				// Login exists?
				$query_result = $db_connection->query("SELECT id FROM users WHERE username='$login'");
				
				if (!$query_result) throw new Exception($db_connection->error);
				
				$number_of_users = $query_result->num_rows;
				if($number_of_users > 0) {
					$successful_validation=false;
					$_SESSION['e_login']="Istnieje już użytkownik o takim loginie! Wybierz inny.";
				}
				
				if ($successful_validation==true)
				{
					// Successful validation, register
					$create_user ="INSERT INTO users VALUES (NULL, '$login', '$password_hash', '$email')";


					if ($db_connection->query("$create_user")) {
						if ($query_result = $db_connection->query(sprintf("SELECT * FROM users WHERE username='%s'", mysqli_real_escape_string($db_connection, $login)))) {
         					$number_of_users = $query_result->num_rows;
          					if($number_of_users > 0) {
            					$user_data = $query_result->fetch_assoc();
								$_SESSION['user_id'] = $user_data['id'];
								$user_id = $_SESSION['user_id'];
								$query_result->free_result();

								$add_user_id_in_payment_methods_default = "ALTER TABLE payment_methods_default ADD user_id INT(11) NOT NULL DEFAULT '$user_id' AFTER id";
								$copy_payment_methods_default = "INSERT INTO payment_methods_assigned_to_users (user_id, name) SELECT user_id, name FROM payment_methods_default";
								$drop_user_id_from_payment_methods = "ALTER TABLE payment_methods_default DROP user_id";

								$add_user_id_in_expenses_category_default = "ALTER TABLE expenses_category_default ADD user_id INT(11) NOT NULL DEFAULT '$user_id' AFTER id";
								$copy_expenses_category_default = "INSERT INTO expenses_category_assigned_to_users (user_id, name) SELECT user_id, name FROM expenses_category_default";
								$drop_user_id_from_expenses_category = "ALTER TABLE expenses_category_default DROP user_id";

								$add_user_id_in_incomes_category_default = "ALTER TABLE incomes_category_default ADD user_id INT(11) NOT NULL DEFAULT '$user_id' AFTER id";
								$copy_incomes_category_default = "INSERT INTO incomes_category_assigned_to_users (user_id, name) SELECT user_id, name FROM incomes_category_default";
								$drop_user_id_from_incomes_category = "ALTER TABLE incomes_category_default DROP user_id";

								if ($db_connection->query("$add_user_id_in_payment_methods_default")) {
									if ($db_connection->query("$copy_payment_methods_default")) {
										if ($db_connection->query("$drop_user_id_from_payment_methods")) {
											if ($db_connection->query("$add_user_id_in_expenses_category_default")) {
												if ($db_connection->query("$copy_expenses_category_default")) {
													if ($db_connection->query("$drop_user_id_from_expenses_category")) {
														if ($db_connection->query("$add_user_id_in_incomes_category_default")) {
															if ($db_connection->query("$copy_incomes_category_default")) {
																if ($db_connection->query("$drop_user_id_from_incomes_category")) {
																	$_SESSION['successful_registration']=true;
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
								echo '<div class="error">Błąd odczytu ID z bazy danych. Skontaktuj się z twórcą strony.</div>';
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
			echo '<br />Informacja developerska: '.$e;
		}
		
	}
	
	
?>