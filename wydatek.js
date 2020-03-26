window.onload = function() 
{
	setCurrentDate();
}

function setCurrentDate()
{
	var dateOnPage = document.getElementById("data");
	var today = new Date();

	var day = today.getDate().toString();
	if(day.length === 1)
	{
		day = '0' + day;
	}
	
	var month = today.getMonth() + 1;
	month = month.toString();
	if(month.length === 1)
	{
		month = '0' + month;
	}
	
	var year = today.getFullYear();

	dateOnPage.value = year + "-" + month + "-" + day ;
}