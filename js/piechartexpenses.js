// Load google charts
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

// Draw the chart and set the chart values
function drawChart() {
  var data = google.visualization.arrayToDataTable([
  ['Kategoria', 'Kwota'],
  ['Jedzenie', 3],
  ['Mieszkanie', 4],
  ['Transport', 3],
  ['Rozrywka', 2],
  ['Higiena', 1],
  ['Inne wydatki', 5],
]);

  if(window.innerWidth>992) {
    var options = {
      'backgroundColor':'transparent',
      'title':'Wydatki',
      'width':1200,
      'height':500,
      'legend': {'textStyle': {'color':'#ffffff'}},
      'titleTextStyle': {'color':'#ffffff'}
    }
  }
  else if(window.innerWidth>768) {
    var options = {
      'backgroundColor':'transparent',
      'title':'Wydatki',
      'width':900,
      'height':400,
      'legend': {'textStyle': {'color':'#ffffff'}},
      'titleTextStyle': {'color':'#ffffff'}
    }
  }
  else if(window.innerWidth>576) {
    var options = {
      'backgroundColor':'transparent',
      'title':'Wydatki',
      'width':700,
      'height':300,
      'legend': {'textStyle': {'color':'#ffffff'}},
      'titleTextStyle': {'color':'#ffffff'}
    }
  }
  else {
     // Optional; add a title and set the width and height of the chart
     var options = {
      'backgroundColor':'transparent',
      'title':'Wydatki',
      'legend': {'textStyle': {'color':'#ffffff'}},
      'titleTextStyle': {'color':'#ffffff'}
    }
  }

  // Display the chart inside the <div> element with id="piechart"
  var chart = new google.visualization.PieChart(document.getElementById('piechartexpenses'));
  chart.draw(data, options);
}

