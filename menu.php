<?php
	session_start();

	if(!isset($_SESSION['logged_id'])){
		header('Location: logowanie.php');
	}
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
					<div id="title"> 
						Witaj<?php echo " ".$_SESSION['username']."! " ?>
					</div>
					<div id="sentence_expense" class="mb-3 mt-2">
						Znajdujesz się na stronie swojego budżetu osobistego! <br /> 
						Korzystaj z dostępnych opcji, aby móc jak najlepiej kontrolować swoje finanse!
					</div>
				</div>
				<div class="mb-4" >
					<img src="img/finanse.jpg" alt="Finanse">
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