<?php
    if (!isset($_SESSION['user_logged_in'])) {
		header('Location: index.php');
		exit();
    }
    
    if (isset($_POST['start_date'])) {

      $good_start_date = true;
      $good_end_date = true;

      // Start date validation
      
      $start_date = $_POST['start_date'];

      if(!(preg_match('/^[0-9]{4}+\-+[0-9]{1,2}+\-+[0-9]{1,2}$/', $start_date))) {
        $good_start_date = false;
        $_SESSION['e_start_date'] = "Data musi być w formacie: RRRR-MM-DD";
      }
      else {
        $year = substr($start_date, 0, 4);
        $month = substr($start_date, 5, 2);
        $day = substr($start_date, 8, 2);
        
        if(!checkdate($month, $day, $year)) {
          $good_start_date = false;
          $_SESSION['e_start_date'] = "Niepoprawna data!";
        }
        else {
          $currentdate = date('Y-m-d');
          $currentyear = substr($currentdate, 0, 4);
          $currentmonth = substr($currentdate, 5, 2);
          $currentday = substr($currentdate, 8, 2);
        
          if($year > $currentyear) {
            $good_start_date = false;
            $_SESSION['e_start_date'] = "Data nie może być z przyszłości!";
          }
          elseif($year == $currentyear) {
            if($month > $currentmonth) {
              $good_start_date = false;
              $_SESSION['e_start_date'] = "Data nie może być z przyszłości!";
            }
            elseif($month == $currentmonth) {
              if($day > $currentday) {
                $good_start_date = false;
                $_SESSION['e_start_date'] = "Data nie może być z przyszłości!";
              }
            }
          }
        }
      }

      // End date validation
      
      $end_date = $_POST['end_date'];

      if(!(preg_match('/^[0-9]{4}+\-+[0-9]{1,2}+\-+[0-9]{1,2}$/', $end_date))) {
        $good_end_date = false;
        $_SESSION['e_end_date'] = "Data musi być w formacie: RRRR-MM-DD";
      }
      else {
        $year = substr($end_date, 0, 4);
        $month = substr($end_date, 5, 2);
        $day = substr($end_date, 8, 2);
        
        if(!checkdate($month, $day, $year)) {
          $good_end_date = false;
          $_SESSION['e_end_date'] = "Niepoprawna data!";
        }
        else {
          $currentdate = date('Y-m-d');
          $currentyear = substr($currentdate, 0, 4);
          $currentmonth = substr($currentdate, 5, 2);
          $currentday = substr($currentdate, 8, 2);
        
          if($year > $currentyear) {
            $good_end_date = false;
            $_SESSION['e_end_date'] = "Data nie może być z przyszłości!";
          }
          elseif($year == $currentyear) {
            if($month > $currentmonth) {
              $good_end_date = false;
              $_SESSION['e_end_date'] = "Data nie może być z przyszłości!";
            }
            elseif($month = $currentmonth) {
              if($day > $currentday) {
                $good_end_date = false;
                $_SESSION['e_end_date'] = "Data nie może być z przyszłości!";
              }
            }
          }
        }
      }
      if ($good_start_date && $good_end_date) {
        $_SESSION['start_date'] = $start_date;
        $_SESSION['end_date'] = $end_date;
        unset($_SESSION['show_modal_select_period']);
        header('Location: showbalance.php');
      }
    }
?>