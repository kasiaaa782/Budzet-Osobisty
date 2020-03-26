function ustawDate()
{
	var dzisiaj = new Date();
	var dzien = dzisiaj.getDate();
	var miesiac = dzisiaj.getMonth()+1;
	var rok = dzisiaj.getFullYear();

	var poprawnyFormatDaty = rok + "-" + miesiac + "-" + dzien;
}