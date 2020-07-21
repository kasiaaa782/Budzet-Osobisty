<?php
	session_start();

	//upewnienie się czy formularz został przesłany submitem
	if(isset($_POST['email'])){
		//założenie, że walidacja udała się (tzw. flaga)
		$everything_OK = true;
		
		//sprawdzenie poprawności imienia - długości (od 3 do 20 znaków) i bez dodatkowych znaków
		$nick = $_POST['nickname'];
		if((strlen($nick)<3) || (strlen($nick)>20)){
			$wszystko_OK = false; 
			$_SESSION['e_nick'] = $_POST['nickname']; 
		}
		$checkName = '/^[A-ZŁŚ]{1}+[a-ząęółśżźćń]+$/';

		if(preg_match($checkName, $nick) == false){
			$wszystko_OK = false; 
			$_SESSION['e_nick'] = $_POST['nickname'];
		}
		
		if((strlen($nick)<3) || (strlen($nick)>20)){
			$wszystko_OK = false; 
			$_SESSION['e_nick'] = $_POST['nickname']; 
		}

		//sprawdzanie poprawności adresu email
		$email = $_POST["email"];
		$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);

		if((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || $emailB!=$email){
			$wszystko_OK = false; 
			$_SESSION['e_email'] = $_POST["email"];
		}

		//sprawdzanie poprawności hasła
		$pass1 = $_POST['pass1'];
		$pass2 = $_POST['pass2'];

		if((strlen($pass1)<8) || (strlen($pass1)>20)){
			$wszystko_OK = false; 
			$_SESSION['e_pass'] = $_POST['pass1']; 
		}

		if($pass1!=$pass2){
			$wszystko_OK = false; 
			$_SESSION['e_pass2'] = $_POST['pass2']; 
		}

		$pass_hash = password_hash($pass1, PASSWORD_DEFAULT);
		echo $pass_hash; exit();

		if($everything_OK == true){
			//testy zaliczone, dodajemy użytkownika do bazy
		}
	}



?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Budżet osobisty - załóż darmowe konto</title>
	<meta name="description" content="Chcesz zapanować nad finansami? Załóż swój osobisty budżet! Sprawdź, jak to zrobić!" />
	<meta name="keywords" content="budżet, budżet osobisty, budżet domowy, finanse, wydatki, przychody, bilans finansowy" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="style.css" type="text/css" />
	<link rel="stylesheet" href="css/money.css" type="text/css" />
	<link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,700&display=swap" rel="stylesheet">	
	
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>

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
						<h2>Rejestracja</h2>
						<div id="sentence">
							Zarejestruj się, aby móc utworzyć swój osobisty budżet!
						</div>
						<form method="post">
							<div>	
								<label><input id="name" type="text" name="nickname" placeholder="Imię"></label>
								<?php								
									if(isset($_SESSION['e_nick'])){
										echo "<div class='error'>To nie jest poprawne imię!</div>";
										unset($_SESSION['e_nick']);
									}
								?>
							</div>
							<div>								
								<label><input type="email" name="email" placeholder="E-mail"></label>
								<?php								
									if(isset($_SESSION['e_email'])){
										echo "<div class='error'>Podaj poprawny adres e-mail!</div>";
										unset($_SESSION['e_email']);
									}
								?>
							</div>
							<div>	
								<label><input type="password" name="pass1" placeholder="Hasło"></label>
								<?php								
									if(isset($_SESSION['e_pass'])){
										echo "<div class='error'>Hasło musi posiadać od 8 do 20 znaków!</div>";
										unset($_SESSION['e_pass']);
									}
								?>
							</div>
							<div>	
								<label><input type="password" name="pass2" placeholder="Powtórz hasło"></label>
								<?php								
									if(isset($_SESSION['e_pass2'])){
										echo "<div class='error'>Podane hasła nie są identyczne!</div>";
										unset($_SESSION['e_pass2']);
									}
								?>
							</div>


							<div class = "text-left ml-3 mt-1">	
								<label><input type="checkbox" name="regulamin" class="mr-2">Akceptuję regulamin</label>
							</div>
							<div class="g-recaptcha mt-1" data-sitekey="6LfGF7QZAAAAAMGmHHv7RLh8M0iVvoSHBlb6Codv"></div>
							<div>
								<input id="submit_log" type="submit" value="Zarejestruj się">
							</div>
						</form>
						<div id="attention">
							<a href="logowanie.php">Jeżeli posiadasz konto, kliknij tutaj aby się zalogować <i class="icon-ok"></i></a>
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