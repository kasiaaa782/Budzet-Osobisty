<?php
	session_start();

	//upewnienie się czy formularz został przesłany submitem
	if(isset($_POST['email'])){
		//założenie, że walidacja udała się (tzw. flaga)
		$everything_OK = true;
		
		//sprawdzenie poprawności imienia - długości (od 3 do 20 znaków) i bez dodatkowych znaków
		$nick = $_POST['nickname'];
		if((strlen($nick)<3) || (strlen($nick)>20)){
			$everything_OK = false; 
			$_SESSION['e_nick'] = $_POST['nickname']; 
		}
		$checkName = '/^[A-ZŁŚ]{1}+[a-ząęółśżźćń]+$/';

		if(preg_match($checkName, $nick) == false){
			$everything_OK = false; 
			$_SESSION['e_nick'] = $_POST['nickname'];
		}
		
		if((strlen($nick)<3) || (strlen($nick)>20)){
			$everything_OK = false; 
			$_SESSION['e_nick'] = $_POST['nickname']; 
		}

		//sprawdzanie poprawności adresu email
		$email = $_POST["email"];
		$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);

		if((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || $emailB!=$email){
			$everything_OK = false; 
			$_SESSION['e_email'] = "Podaj poprawny adres e-mail!";
		}

		//sprawdzanie poprawności hasła
		$pass1 = $_POST['pass1'];
		$pass2 = $_POST['pass2'];

		if((strlen($pass1)<8) || (strlen($pass1)>20)){
			$everything_OK = false; 
			$_SESSION['e_pass'] = $_POST['pass1']; 
		}

		if($pass1!=$pass2){
			$everything_OK = false; 
			$_SESSION['e_pass2'] = $_POST['pass2']; 
		}

		$pass_hash = password_hash($pass1, PASSWORD_DEFAULT);

		//Czy zaakceptowano regulamin?
		if(!isset($_POST['rules'])){
			$everything_OK = false; 
			$_SESSION['e_rules'] = "Potwierdź akceptację regulaminu!"; 
		}
		
		//sprawdzenie reCAPTCHA
		$secret = '6LfD_7wZAAAAAPmDQvgE9QJjiwT__HkfmsE88in1';
		$check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
		$answer = json_decode($check);

		if($answer->success==false){
			$everything_OK = false; 
			$_SESSION['e_bot'] = "Potwierdź, że nie jesteś botem!"; 
		}

		//zapamiętaj wprowadzone dane
		$_SESSION['fr_nick'] = $nick;
		$_SESSION['fr_email'] = $email;
		$_SESSION['fr_pass1'] = $pass1;
		$_SESSION['fr_pass2'] = $pass2;
		if(isset($_POST['rules']))	$_SESSION['fr_rules'] = true;

		require_once 'database.php';

		//sprawdzenie czy email istnieje w bazie
		$userQuery = $db->prepare('SELECT id FROM users WHERE email = :email');
		$userQuery->bindValue(':email', $email, PDO::PARAM_STR);
		$userQuery->execute();
		//dostajemy dane id w szufladkach tablicy asjocjacyjnej o nazwie tablic takich jak w bazie danych
		$user = $userQuery->fetch();

		if($user){
			$everything_OK = false; 
			$_SESSION['e_email'] = "Istnieje już konto o takim adresie, wybierz inny!";
		} 

		if($everything_OK == true){
			//testy zaliczone, dodajemy użytkownika do bazy
			$query = $db->prepare('INSERT INTO users VALUES (NULL, :username, :password, :email)');
			$query->bindValue(':username', $nick, PDO::PARAM_STR);
			$query->bindValue(':password', $pass_hash, PDO::PARAM_STR);
			$query->bindValue(':email', $email, PDO::PARAM_STR);
			$query->execute();
			$_SESSION['successful_registration'] = 'Rejestracja przebiegła pomyślnie!';
		
			//Usuwanie zmiennych, które pamiętały wartości wprowadzone do formularza
			if(isset($_SESSION['fr_nick'])) unset($_SESSION['fr_nick']);
			if(isset($_SESSION['fr_email'])) unset($_SESSION['fr_email']);
			if(isset($_SESSION['fr_pass1'])) unset($_SESSION['fr_pass1']);
			if(isset($_SESSION['fr_pass2'])) unset($_SESSION['fr_pass2']);
			if(isset($_SESSION['fr_rules'])) unset($_SESSION['fr_rules']);

			//Usuwanie błędów rejestracji 
			if(isset($_SESSION['e_nick'])) unset($_SESSION['e_nick']);
			if(isset($_SESSION['e_email'])) unset($_SESSION['e_email']);
			if(isset($_SESSION['e_pass'])) unset($_SESSION['e_pass']);
			if(isset($_SESSION['e_pass2'])) unset($_SESSION['e_pass2']);
			if(isset($_SESSION['e_rules'])) unset($_SESSION['e_rules']);
			if(isset($_SESSION['e_bot'])) unset($_SESSION['e_bot']);
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
						<div class="sentence">
							Zarejestruj się, aby móc utworzyć swój osobisty budżet!
						</div>
						<form method="post">
							<div>	
								<label><input id="name" type="text" value = "<?php 
									if(isset($_SESSION['fr_nick'])){
										echo $_SESSION['fr_nick'];
										unset($_SESSION['fr_nick']);
									}
								?>" name="nickname" placeholder="Imię"></label>
								<?php								
									if(isset($_SESSION['e_nick'])){
										echo "<div class='error'>To nie jest poprawne imię!</div>";
										unset($_SESSION['e_nick']);
									}
								?>
							</div>
							<div>								
								<label><input type="email" value = "<?php 
									if(isset($_SESSION['fr_email'])){
										echo $_SESSION['fr_email'];
										unset($_SESSION['fr_email']);
									}
								?>" name="email" placeholder="E-mail" ></label>
								<?php								
									if(isset($_SESSION['e_email'])){
										echo '<div class="error">'.$_SESSION['e_email'].'</div>';
										unset($_SESSION['e_email']);
									}
								?>
							</div>
							<div>	
								<label><input type="password" value = "<?php 
									if(isset($_SESSION['fr_pass1'])){
										echo $_SESSION['fr_pass1'];
										unset($_SESSION['fr_pass1']);
									}
								?>" name="pass1" placeholder="Hasło"></label>
								<?php								
									if(isset($_SESSION['e_pass'])){
										echo "<div class='error'>Hasło musi posiadać od 8 do 20 znaków!</div>";
										unset($_SESSION['e_pass']);
									}
								?>
							</div>
							<div>	
								<label><input type="password" value = "<?php 
									if(isset($_SESSION['fr_pass2'])){
										echo $_SESSION['fr_pass2'];
										unset($_SESSION['fr_pass2']);
									}
								?>" name="pass2" placeholder="Powtórz hasło"></label>
								<?php								
									if(isset($_SESSION['e_pass2'])){
										echo "<div class='error'>Podane hasła nie są identyczne!</div>";
										unset($_SESSION['e_pass2']);
									}
								?>
							</div>
							<div class="text-left ml-3 mt-1">	
								<label><input type="checkbox" name="rules" class="mr-2" <?php 
									if(isset($_SESSION['fr_rules'])){
										echo "checked";
										unset($_SESSION['fr_rules']);
									}
								?>>Akceptuję regulamin</label>
							</div>
								<?php
									if(isset($_SESSION["e_rules"])){
										echo '<div class="error">'.$_SESSION['e_rules']."</div>";
										unset($_SESSION['e_rules']);
									}
								?>
							<div class="g-recaptcha mt-2 mb-2" data-sitekey="6LfD_7wZAAAAAKByS5gdsmA1paGplrNJxLk_QO_W"></div>
							<?php
								if(isset($_SESSION["e_bot"])){
									echo '<div class="error text-center mr-4">'.$_SESSION['e_bot']."</div>";
									unset($_SESSION['e_bot']);
								}
							?>
							
							<div>
								<input id="submit_log" type="submit" value="Zarejestruj się">
							</div>
							<?php
								if(isset($_SESSION['successful_registration'])){
									echo '<div class="success">'.$_SESSION['successful_registration']."</div>";
									unset($_SESSION['successful_registration']);
								}
							?>
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