<?php
	session_start();
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Budżet osobisty</title>
	<meta name="description" content="Chcesz zapanować nad finansami? Załóż swój osobisty budżet! Sprawdź, jak to zrobić!" />
	<meta name="keywords" content="budżet, budżet osobisty, budżet domowy, finanse, wydatki, przychody, bilans finansowy" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="style.css" type="text/css" />
	<link rel="stylesheet" href="css/money.css" type="text/css" />
	<link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,700&display=swap" rel="stylesheet">	
	<script type="text/javascript" src="budget.js"></script>
	
</head>
<body>
	<!-- Aby działał plik css w php-->
	<style type="text/css">
		<?php 
			include './style.css'; 
		?>
	</style>

	<div id="wrapper">
		<header>
			<div id="logo">
				<h1>Budżet osobisty</h1>
				<h4>Zapanuj nad swoimi finansami</h4>
			</div>
			<nav class="navbar navbar-dark navbar-expand-lg">
				<button class="navbar-toggler mx-auto" type="button" data-toggle="collapse" data-target="#mainmenu" aria-controls="mainmenu" aria-expanded="false" aria-label="Przełącznik nawigacji">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="mainmenu" >
					<ol class="navbar-nav mx-auto">
						<li class="nav-item mx-auto"><a class="nav-link" href="menu.php">Strona główna</a></li>
						<li class="nav-item mx-auto"><a class="nav-link" href="przychod.php">Dodaj przychód</a></li>
						<li class="nav-item mx-auto"><a class="nav-link" href="wydatek.php">Dodaj wydatek</a></li>
						<li class="nav-item mx-auto"><a class="nav-link" href="bilans.php">Przeglądaj bilans</a></li>
						<li class="nav-item mx-auto"><a class="nav-link" href="#">Ustawienia</a></li>
						<li class="nav-item mx-auto"><a class="nav-link" href="wylogowanie.php">Wyloguj się</a></li>
					</ol>
				</div>
			</nav>
		</header>
		<main>
			<div id="content_expense">
				<div class="container">
					<div id="title">Dodawanie wydatku</div>
					<div id="sentence_expense">Uzupełnij poniższe dane dotyczące nowego wydatku</div>
					<form method="post" enctype="text/plain">
						<div class="row justify-content-center mt-3 mb-2">
							<div class="mr-4">
								<label>Kwota : <input type="text" name="amount"  id="amount" ></label>
							</div>
							<div class="ml-4">
								<label>Data : <input type="date" name="date" id="date1"></label>
							</div>
						</div>
						<div class="row justify-content-center">
							<div class="col-sm-12 col-md-3 mb-2">Sposób płatności:</div>
							<div>
								<label class="mr-4"><input type="radio" name="payment" value="" class="mr-2">gotówka</label>
								<label class="mr-4"><input type="radio" name="payment" value="" class="mr-2">karta debetowa</label>
								<label><input type="radio" name="platnosc" value="" class="mr-2">karta kredytowa</label>
							</div>
						</div>
						<div class="row justify-content-center mt-1">
							<div class="col-12 mb-2">Kategoria wydatku:</div>
							<div class="row col-6 col-md-8 col-lg-12 justify-content-center ml-5">
								<div class="categories">
									<div><label><input type="radio" value="1" name="category" class="mr-2">Jedzenie</label></div>
									<div><label><input type="radio" value="2" name="category" class="mr-2">Mieszkanie</label></div>
									<div><label><input type="radio" value="3" name="category" class="mr-2">Transport</label></div>
									<div><label><input type="radio" value="4" name="category" class="mr-2">Telekomunikacja</label></div>
								</div>
								<div class="categories">
									<div><label><input type="radio" value="5" name="category" class="mr-2">Opieka zdrowotna</label></div>
									<div><label><input type="radio" value="6" name="category" class="mr-2">Ubranie</label></div>
									<div><label><input type="radio" value="7" name="category" class="mr-2">Higiena</label></div>
									<div><label><input type="radio" value="8" name="category" class="mr-2">Dzieci</label></div>
								</div>
								<div class="categories">
									<div><label><input type="radio" value="9" name="category" class="mr-2">Rozrywka</label></div>
									<div><label><input type="radio" value="10" name="category" class="mr-2">Wycieczka</label></div>
									<div><label><input type="radio" value="11" name="category" class="mr-2">Książki</label></div>
									<div><label><input type="radio" value="12" name="category" class="mr-2">Oszczędności</label></div>
								</div>
								<div class="categories">	
									<div><label><input type="radio" value="13" name="category" class="mr-2">Emerytura</label></div>
									<div><label><input type="radio" value="14" name="category" class="mr-2">Spłata długów</label></div>
									<div><label><input type="radio" value="15" name="category" class="mr-2">Darowizna</label></div>
									<div><label><input type="radio" value="16" name="category" class="mr-2">Inne wydatki</label></div>
								</div>
							</div>
						</div>
						<div class="row justify-content-center">
							<textarea name="comment" id="comment" rows="1" cols="70" placeholder="Krótki komentarz" onfocus="this.placeholder=''" onblur="this.placeholder='Krótki komentarz'"></textarea>
						</div>
						<div class="row justify-content-center mt-3">
								<input id="expense_submit" type="submit" value="Dodaj wydatek" class="mr-5"> 
								<input id="expense_button" type="button" value="Anuluj"> 
						</div>					
					</form>
				</div>
			</div>
		</main>
		<footer id="footer">
			2020 &copy; Wszelkie prawa zastrzeżone 
			<i class="icon-mail"></i> katarzyna.niemiec.programista@gmail.com
	     </footer>
	</div>	
	
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>    
	<script src="js/bootstrap.min.js"></script>	
</body>
</html>