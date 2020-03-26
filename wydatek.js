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
	
	/*if(day.length === 1)
	{
		day = '0' + day;
	}*/
	
	var month = today.getMonth() + 1;
	month = (month.toString().length === 1) ? '0' + month : month;
	
	var year = today.getFullYear();

	dateOnPage.value = year + "-" + month + "-" + day ;
}