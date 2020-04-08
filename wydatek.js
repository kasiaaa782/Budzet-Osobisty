window.onload = function() 
{
	setCurrentDate();
}

function setCurrentDate()
{
	var dateOnPage = document.getElementById("data");
	var today = new Date();

	var day = today.getDate().toString();
	day = (day.length === 1) ? '0' + day : day;
	
	var month = today.getMonth() + 1;
	month = (month.toString().length === 1) ? '0' + month : month;
	
	var year = today.getFullYear();

	dateOnPage.value = year + "-" + month + "-" + day ;

	console.log(dateOnPage.value)
}

//Load google charts
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

// Draw the chart and set the chart values
function drawChart() {
	var data = google.visualization.arrayToDataTable([
	['Kategoria', 'Kwota'],
	['Jedzenie', 8],
	['Mieszkanie', 2],
	['Transport', 4],
	['Telekomunikacja', 2],
	['Opieka zdrowotna', 8],
	['Ubrania', 8],
	['Higiena', 8],
	['Dzieci', 8],
	['Rozrywka', 8],
	['Wycieczka', 8],
	['Książka', 8],
	['Oszczędności', 8],
	['Na emeryturę', 8],
	['Spłata długów', 8],
	['Darowizna', 8],
	['Inne wydatki', 8]
	]);
	// Optional; add a title and set the width and height of the chart
	var options = {
		'title':'Wykres finansowy',
		is3D : true
		};
	//Display the chart inside the <div> element with id="piechart"
	var chart = new google.visualization.PieChart(document.getElementById('piechart'));
	chart.draw(data, options);
}

