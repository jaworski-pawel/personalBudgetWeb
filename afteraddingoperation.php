<?php

	session_start();
	
	if (!isset($_SESSION['user_logged_in']))
	{
		header('Location: index.php');
		exit();
  }
  
  if (!isset($_SESSION['successful_operation']))
	{
		header('Location: index.php');
		exit();
  }
  
	else
	{
		unset($_SESSION['successful_operation']);
	}
	
	$_SESSION['show_modal_after_operation'] = true;

	if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != "") {
		$previous_url = $_SERVER['HTTP_REFERER'];
	} else {
		$previous_url = "index.php";
	}

	header("Location: ".$previous_url);
?>
