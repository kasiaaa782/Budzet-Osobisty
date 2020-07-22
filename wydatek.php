<?php
	session_start();

	if(!isset($_SESSION['logged_id'])){
		header('Location: logowanie.php');
	} else {
		
		if(isset($_POST['date'])){
		
			$everything_OK = true;

			$date = $_POST['date'];
			$_SESSION['fr_date'] = $date;
			
			$amount = $_POST['amount'];
			$_SESSION['fr_amount'] = $amount;

			if($amount == ''){
				$everything_OK = false;
				$_SESSION['e_data'] = "Uzupełnij dane!";
			}
		
			if(isset($_POST['payment'])){
				$payment = $_POST['payment'];
				$_SESSION['fr_payment'] = $payment;
			} else {
				$everything_OK = false;
				$_SESSION['e_payment'] = "Wybierz rodzaj płatności!";
			}
		
			if(isset($_POST['category'])){
				$category = $_POST['category'];
				$_SESSION['fr_category'] = $category;
			} else {
				$everything_OK = false;
				$_SESSION['e_category'] = "Wybierz kategorię!";
			}
		
			if(isset($_POST['comment'])){
				$comment = $_POST['comment'];
				$_SESSION['fr_comment'] = $comment;
			}

			require_once 'database.php';

			if($everything_OK == true){
				//testy zaliczone, dodajemy wydatek do bazy
				$query = $db->prepare('INSERT INTO expenses VALUES (NULL, :user_id, :expense_category_assigned_to_user_id, :payment_method_assigned_to_user_id, :amount, :date_of_expense, :expense_comment)');
				$query->bindValue(':user_id', $_SESSION['logged_id'], PDO::PARAM_INT);
				$query->bindValue(':expense_category_assigned_to_user_id', $category, PDO::PARAM_INT);
				$query->bindValue(':payment_method_assigned_to_user_id', $payment, PDO::PARAM_INT);
				$query->bindValue(':amount', $amount, PDO::PARAM_STR);
				$query->bindValue(':date_of_expense', $date, PDO::PARAM_STR);
				$query->bindValue(':expense_comment', $comment, PDO::PARAM_STR);
				$query->execute();
				$_SESSION['successful_expense'] = 'Zaksięgowano nowy wydatek!';
			
				//Usuwanie zmiennych, które pamiętały wartości wprowadzone do formularza
				if(isset($_SESSION['fr_date'])) unset($_SESSION['fr_date']);
				if(isset($_SESSION['fr_amount'])) unset($_SESSION['fr_amount']);
				if(isset($_SESSION['fr_payment'])) unset($_SESSION['fr_payment']);
				if(isset($_SESSION['fr_category'])) unset($_SESSION['fr_category']);
				if(isset($_SESSION['fr_comment'])) unset($_SESSION['fr_comment']);
	
				//Usuwanie błędów
				if(isset($_SESSION['e_data'])) unset($_SESSION['e_data']);
				if(isset($_SESSION['e_payment'])) unset($_SESSION['e_payment']);
				if(isset($_SESSION['e_category'])) unset($_SESSION['e_category']);
			}	
		}
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
	
	<script type="text/javascript" src="budget.js"></script>

</head>
<body>
	<!-- Aby działał plik css w php-->
	<style type="text/css">
		<?php 
			include './style.css'; 
		?>
	</style>
	<!-- Aby działał plik js w php-->
	<script type="text/javascript">
		<?php 
			include './budget.js'; 
		?>
	</script>

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
			<section id="content_expense">
				<div class="container">
					<div id="title">Dodawanie wydatku</div>
					<div id="sentence_expense">Uzupełnij poniższe dane dotyczące nowego wydatku</div>
					<form method="post">
						<div class="row justify-content-center mt-3 mb-2">
							<div class="mr-4">
								<label>Kwota : <input type="number" step="0.01" value = "<?php 
									if(isset($_SESSION['fr_amount'])){
										echo $_SESSION['fr_amount'];
										unset($_SESSION['fr_amount']);
									}
								?>" name="amount" id="amount" ></label>
							</div>
							<div class="ml-4">
								<label>Data : <input type="date" name="date" id="date1" 
								<?php
									if(isset($_SESSION['fr_date'])){
										echo "value = ".$_SESSION['fr_date']; 
										unset($_SESSION['fr_date']);
									}
								?>
								></label>
							</div>
							<?php								
								if(isset($_SESSION['e_data'])){
									echo '<div class="error text-center mr-4 col-12">'.$_SESSION['e_data'].'</div>';
									unset($_SESSION['e_data']);
								}
							?>
						</div>
						<div class="row justify-content-center">
							<div class="col-sm-12 col-md-3 mb-2">Sposób płatności:</div>
							<div>
								<label class="mr-4"><input type="radio" name="payment" value="1" class="mr-2" 
									<?php if(isset($_SESSION['fr_payment']) && $_SESSION['fr_payment'] == 1){ 
										echo 'checked'; 
										unset($_SESSION['fr_payment']);
									}
									?> >Gotówka</label>
								<label class="mr-4"><input type="radio" name="payment" value="2" class="mr-2"
									<?php if(isset($_SESSION['fr_payment']) && $_SESSION['fr_payment'] == 2){ 
										echo 'checked'; 
										unset($_SESSION['fr_payment']);
									}
									?> >Karta debetowa</label>
								<label><input type="radio" name="payment" value="3" class="mr-2"
								<?php if(isset($_SESSION['fr_payment']) && $_SESSION['fr_payment'] == 3){ 
										echo 'checked'; 
										unset($_SESSION['fr_payment']);
									}
									?> >Karta kredytowa</label>
							</div>
							<?php								
								if(isset($_SESSION['e_payment'])){
									echo '<div class="error text-center mr-4 col-12">'.$_SESSION['e_payment'].'</div>';
									unset($_SESSION['e_payment']);
								}
							?>
						</div>
						<div class="row justify-content-center mt-1">
							<div class="col-12 mb-2">Kategoria wydatku:</div>
							<div class="row col-6 col-md-8 col-lg-12 justify-content-center ml-5">
								<div class="categories">
									<div><label><input type="radio" value="1" name="category" class="mr-2"
										<?php if(isset($_SESSION['fr_category']) && $_SESSION['fr_category'] == 1){ 
											echo 'checked'; 
											unset($_SESSION['fr_category']);
										}
										?> >Transport</label></div>
									<div><label><input type="radio" value="2" name="category" class="mr-2"
										<?php if(isset($_SESSION['fr_category']) && $_SESSION['fr_category'] == 2){ 
											echo 'checked'; 
											unset($_SESSION['fr_category']);
										}
										?> >Książki</label></div>
									<div><label><input type="radio" value="3" name="category" class="mr-2"
										<?php if(isset($_SESSION['fr_category']) && $_SESSION['fr_category'] == 3){ 
											echo 'checked'; 
											unset($_SESSION['fr_category']);
										}
										?> >Jedzenie</label></div>
									<div><label><input type="radio" value="4" name="category" class="mr-2"
										<?php if(isset($_SESSION['fr_category']) && $_SESSION['fr_category'] == 4){ 
											echo 'checked'; 
											unset($_SESSION['fr_category']);
										}
										?> >Mieszkanie</label></div>
								</div>
								<div class="categories">
									<div><label><input type="radio" value="5" name="category" class="mr-2"
										<?php if(isset($_SESSION['fr_category']) && $_SESSION['fr_category'] == 5){ 
											echo 'checked'; 
											unset($_SESSION['fr_category']);
										}
										?> >Telekomunikacja</label></div>
									<div><label><input type="radio" value="6" name="category" class="mr-2"
										<?php if(isset($_SESSION['fr_category']) && $_SESSION['fr_category'] == 6){ 
											echo 'checked'; 
											unset($_SESSION['fr_category']);
										}
										?> >Opieka zdrowotna</label></div>
									<div><label><input type="radio" value="7" name="category" class="mr-2"
										<?php if(isset($_SESSION['fr_category']) && $_SESSION['fr_category'] == 7){ 
											echo 'checked'; 
											unset($_SESSION['fr_category']);
										}
										?> >Ubranie</label></div>
									<div><label><input type="radio" value="8" name="category" class="mr-2"
										<?php if(isset($_SESSION['fr_category']) && $_SESSION['fr_category'] == 8){ 
											echo 'checked'; 
											unset($_SESSION['fr_category']);
										}
										?> >Higiena</label></div>
								</div>
								<div class="categories">
									<div><label><input type="radio" value="9" name="category" class="mr-2"
										<?php if(isset($_SESSION['fr_category']) && $_SESSION['fr_category'] == 9){ 
											echo 'checked'; 
											unset($_SESSION['fr_category']);
										}
										?> >Dzieci</label></div>
									<div><label><input type="radio" value="10" name="category" class="mr-2"
										<?php if(isset($_SESSION['fr_category']) && $_SESSION['fr_category'] == 10){ 
											echo 'checked'; 
											unset($_SESSION['fr_category']);
										}
										?> >Rozrywka</label></div>
									<div><label><input type="radio" value="11" name="category" class="mr-2"
										<?php if(isset($_SESSION['fr_category']) && $_SESSION['fr_category'] == 11){ 
											echo 'checked'; 
											unset($_SESSION['fr_category']);
										}
										?> >Wycieczka</label></div>
									<div><label><input type="radio" value="12" name="category" class="mr-2"
										<?php if(isset($_SESSION['fr_category']) && $_SESSION['fr_category'] == 12){ 
											echo 'checked'; 
											unset($_SESSION['fr_category']);
										}
										?> >Oszczędności</label></div>
								</div>
								<div class="categories">	
									<div><label><input type="radio" value="13" name="category" class="mr-2"
										<?php if(isset($_SESSION['fr_category']) && $_SESSION['fr_category'] == 13){ 
											echo 'checked'; 
											unset($_SESSION['fr_category']);
										}
										?> >Emerytura</label></div>
									<div><label><input type="radio" value="14" name="category" class="mr-2"
										<?php if(isset($_SESSION['fr_category']) && $_SESSION['fr_category'] == 14){ 
											echo 'checked'; 
											unset($_SESSION['fr_category']);
										}
										?> >Spłata długów</label></div>
									<div><label><input type="radio" value="15" name="category" class="mr-2"
										<?php if(isset($_SESSION['fr_category']) && $_SESSION['fr_category'] == 15){ 
											echo 'checked'; 
											unset($_SESSION['fr_category']);
										}
										?> >Darowizna</label></div>
									<div><label><input type="radio" value="16" name="category" class="mr-2"
										<?php if(isset($_SESSION['fr_category']) && $_SESSION['fr_category'] == 16){ 
											echo 'checked'; 
											unset($_SESSION['fr_category']);
										}
										?> >Inne wydatki</label></div>
								</div>
							</div>
							<?php								
								if(isset($_SESSION['e_category'])){
									echo '<div class="error text-center mr-4 col-12">'.$_SESSION['e_category'].'</div>';
									unset($_SESSION['e_category']);
								}
							?>
						</div>
						<div class="row justify-content-center">
							<textarea name="comment" id="comment" rows="1" cols="70" placeholder="Krótki komentarz (opcjonalnie)" onfocus="this.placeholder=''" onblur="this.placeholder='Krótki komentarz (opcjonalnie)'" 
								><?php if(isset($_SESSION['fr_comment'])){ 
										echo $_SESSION['fr_comment']; 
										unset($_SESSION['fr_comment']);
									}
								?></textarea>
						</div>
						<div class="row justify-content-center mt-3">
								<input id="expense_submit" type="submit" value="Dodaj wydatek" class="mr-5"> 
								<input id="expense_button" type="button" value="Anuluj">
						</div>
						<div class="row justify-content-center mt-1">
							<?php
								if(isset($_SESSION['successful_expense'])){
									echo '<div class="success">'.$_SESSION['successful_expense']."</div>";
									unset($_SESSION['successful_expense']);
								}
							?>
						</div>					
					</form>
				</div>
			</section>
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