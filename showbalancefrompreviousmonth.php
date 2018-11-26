<?php
    session_start();

    if (!isset($_SESSION['user_logged_in'])) {
		header('Location: index.php');
		exit();
    }

    $current_year = date('Y');
    $current_month = date('m');

    if ($current_month == "01") {
      $previous_month = "12";
    }
    elseif ($current_month == "02") {
      $previous_month = "01";
    }
    elseif ($current_month == "03") {
      $previous_month = "02";
    }
    elseif ($current_month == "04") {
      $previous_month = "03";
    }
    elseif ($current_month == "05") {
      $previous_month = "04";
    }
    elseif ($current_month == "06") {
      $previous_month = "05";
    }
    elseif ($current_month == "07") {
      $previous_month = "06";
    }
    elseif ($current_month == "08") {
      $previous_month = "07";
    }
    elseif ($current_month == "09") {
      $previous_month = "08";
    }
    elseif ($current_month == "10") {
      $previous_month = "09";
    }
    elseif ($current_month == "11") {
      $previous_month = "10";
    }
    elseif ($current_month == "12") {
      $previous_month = "11";
    }

    $_SESSION['start_date'] = $current_year."-".$previous_month."-01";
    $_SESSION['end_date'] = $current_year."-".$previous_month."-31";

    header('Location: showbalance.php');
?>