<?php

if (!isset($_SESSION['user_logged_in'])) {
    header('Location: index.php');
		exit();
}

echo <<<EOL
<script type="text/javascript">

// Load google charts
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

// Draw the chart and set the chart values
function drawChart() {
    var data = google.visualization.arrayToDataTable([
    ['Kategoria', 'Kwota'],
EOL;

require_once "connect.php";

  mysqli_report(MYSQLI_REPORT_STRICT);
                           
  try {
    $db_connection = new mysqli($host, $db_user, $db_password, $db_name);
                            
    if ($db_connection->connect_errno!=0) {
      throw new Exception(mysqli_connect_errno());
    }
    else {
        $get_sum_of_categories = "SELECT ( SELECT c.name FROM expenses_category_assigned_to_users AS c WHERE c.id = e.expense_category_assigned_to_user_id ) AS category, SUM(e.amount) AS sum_of_category FROM expenses AS e WHERE (date_of_expense BETWEEN '$start_date' AND '$end_date') AND user_id = '$user_id' GROUP BY category";

        if ($query_result = $db_connection->query("$get_sum_of_categories")) {
            $number_of_categories = $query_result->num_rows;
            while($sum_of_category = $query_result->fetch_assoc()) {
                echo '[\''.$sum_of_category["category"].'\', '.$sum_of_category["sum_of_category"].'], ';
            }
        }
        else {
            throw new Exception($db_connection->error);
        }
        
      $db_connection->close();
    }
  }
  catch(Exception $e) {
    echo '<div class="error text-center">Błąd serwera! Przepraszamy za niedogodności i prosimy o wizytę w innym terminie!</div>';
    echo '<br />Informacja developerska: '.$e;
  }


echo <<<EOL
    ]);

    if(window.innerWidth>992) {
        var options = {
            'backgroundColor':'transparent',
            'title':'Wydatki',
            'width':1200,
            'height':500
        }
    }
    else if(window.innerWidth>768) {
        var options = {
            'backgroundColor':'transparent',
            'title':'Wydatki',
            'width':900,
            'height':400
        }
    }
    else if(window.innerWidth>576) {
        var options = {
            'backgroundColor':'transparent',
            'title':'Wydatki',
            'width':700,
            'height':300
        }
    }
    else {
        // Optional; add a title and set the width and height of the chart
        var options = {
            'backgroundColor':'transparent',
            'title':'Wydatki'
        }
    }
    // Display the chart inside the <div> element with id="piechart"
    var chart = new google.visualization.PieChart(document.getElementById('piechartexpenses'));
    chart.draw(data, options);
}

</script>

EOL;
?>