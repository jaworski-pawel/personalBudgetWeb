var totalincomes = $('#totalincomes').text();
var totalexpenses = $('#totalexpenses').text();
var balance = totalincomes - totalexpenses;

$('#summary').html('Twój bilans to: ' + balance + " zł");

if(balance < 0) {
    $('#comment').html('Uważaj, wpadasz w długi!');
    $('#comment').css('color', '#ff0000');
}
else {
    $('#comment').html('Gratulacje. Świetnie zarządzasz finansami!');
    $('#comment').css('color', '#008000');
}