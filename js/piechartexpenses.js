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

  // Optional; add a title and set the width and height of the chart
  var options = {'title':'Wydatki', 'width':1000, 'height':500};

  // Display the chart inside the <div> element with id="piechart"
  var chart = new google.visualization.PieChart(document.getElementById('piechartexpenses'));
  chart.draw(data, options);
}

