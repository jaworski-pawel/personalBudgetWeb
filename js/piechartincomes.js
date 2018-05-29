// Load google charts
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

// Draw the chart and set the chart values
function drawChart() {
  var data = google.visualization.arrayToDataTable([
  ['Kategoria', 'Kwota'],
  ['Wynagrodzenie', 8],
  ['Odsetki bankowe', 2],
  ['Sprzedaż na allegro', 4],
  ['Inne', 2],
]);

  // Optional; add a title and set the width and height of the chart
  var options = {'title':'Przychody', 'width':1000, 'height':500};

  // Display the chart inside the <div> element with id="piechart"
  var chart = new google.visualization.PieChart(document.getElementById('piechartincomes'));
  chart.draw(data, options);
}

