<?php
    session_start();

  if (!isset($_SESSION['user_logged_in'])) {
		header('Location: index.php');
		exit();
  }

  $_SESSION['show_modal_select_period'] = true;
  header('Location: showbalance.php');
  exit();
?>