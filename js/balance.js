var totalincomes = $('#totalincomes').text();
var totalexpenses = $('#totalexpenses').text();
var balance = (totalincomes - totalexpenses).toFixed(2);

$('#summary').html('Twój bilans to: ' + balance + " zł");

if(balance < 0) {
    $('#comment').html('Uważaj, wpadasz w długi!');
    $('#comment').css('color', '#ff0000');
}
else {
    $('#comment').html('Gratulacje. Świetnie zarządzasz finansami!');
    $('#comment').css('color', '#008000');
}
if (totalincomes == 0) {
    $("#table-incomes").css({"display": "none"});
    $("#piechartincomes").css({"display": "none"});
    $('#summary').before('<div class="alert alert-danger">Nie ma przychodów do wyświetlenia w danym okresie!</div>');
}

if (totalexpenses == 0) {
    $("#table-expenses").css({"display": "none"});
    $("#piechartexpenses").css({"display": "none"});
    $('#summary').before('<div class="alert alert-danger">Nie ma wydatków do wyświetlenia w danym okresie!</div>');
}