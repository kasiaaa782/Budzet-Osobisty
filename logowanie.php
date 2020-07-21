<?php
	session_start();

	if(isset($_SESSION['logged_id'])){
		header('Location: menu.php');
		exit();
	}
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Budżet osobisty - logowanie</title>
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
			<div id="header">
				<div id="logo">
					<h1>Budżet osobisty</h1>
					<h4>Zapanuj nad swoimi finansami</h4>
				</div>
			</div>
		</header>
		<main>
			<div class="container">
				<section id="content" > 
					<div class="row text-center justify-content-center">
						<h2>Logowanie</h2>
						<div id="sentence" >
							Zaloguj się, aby móc skontrolować swoje finanse!
						</div>	
						<form action="zaloguj.php" method="post" enctype="multipart/form-data">
							<div>								
								<label><input type="email" name="email" placeholder="E-mail" 
								<?php 
										if(isset($_SESSION['given_email'])) echo 'value="'.$_SESSION['given_email'].'"' ;
										if(isset($_SESSION['bad_attempt'])) echo 'value="'.$_SESSION['bad_attempt'].'"';
								?>></label>
							</div>

							<?php
								if(isset($_SESSION['given_email'])){
									echo "<div class='error'>Niewłaściwy adres!</div>";
									unset($_SESSION['given_email']);
								}
							?>

							<div>	
								<label><input type="password" name="pass" placeholder="Hasło"></label>
							</div>

							<?php
								if(isset($_SESSION['bad_attempt'])){
									echo "<div class='error'>Nieprawidłowy adres lub hasło!</div>";
									unset($_SESSION['bad_attempt']);
								}
							?>
							<div>
								<input id="submit_log" type="submit" value="Zaloguj się">
							</div>
						</form>
						<div id="attention">
							<a href="rejestracja.php">Jeżeli nie posiadasz konta, kliknij tutaj aby się zarejestrować <i class="icon-ok"></i></a>
						</div>
					</div>			
				</section>
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