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
				//testy zaliczone, dodajemy przychód do bazy
				$query = $db->prepare('INSERT INTO incomes VALUES (NULL, :user_id, :income_category_assigned_to_user_id, :amount, :date_of_income, :income_comment)');
				$query->bindValue(':user_id', $_SESSION['logged_id'], PDO::PARAM_INT);
				$query->bindValue(':income_category_assigned_to_user_id', $category, PDO::PARAM_INT);
				$query->bindValue(':amount', $amount, PDO::PARAM_STR);
				$query->bindValue(':date_of_income', $date, PDO::PARAM_STR);
				$query->bindValue(':income_comment', $comment, PDO::PARAM_STR);
				$query->execute();
				$_SESSION['successful_income'] = 'Zaksięgowano nowy przychód!';
			
				//Usuwanie zmiennych, które pamiętały wartości wprowadzone do formularza
				if(isset($_SESSION['fr_date'])) unset($_SESSION['fr_date']);
				if(isset($_SESSION['fr_amount'])) unset($_SESSION['fr_amount']);
				if(isset($_SESSION['fr_category'])) unset($_SESSION['fr_category']);
				if(isset($_SESSION['fr_comment'])) unset($_SESSION['fr_comment']);
	
				//Usuwanie błędów
				if(isset($_SESSION['e_data'])) unset($_SESSION['e_data']);
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
			<div id="content_expense">
				<div class="container">
					<div id="title">Dodawanie przychodu</div>
					<div id="sentence_expense">Uzupełnij poniższe dane dotyczące nowego przychodu</div>
					<form method="post">
						<div class="row justify-content-center mt-4">
							<div class="mr-4">
								<label>Kwota : <input type="number" step="0.01" name="amount" id="amount" value = "<?php 
									if(isset($_SESSION['fr_amount'])){
										echo $_SESSION['fr_amount'];
										unset($_SESSION['fr_amount']);
									}
								?>"></label>
							</div>
							<div class="ml-4">
								<label>Data : <input type="date" name="date" id="date1" <?php
									if(isset($_SESSION['fr_date'])){
										echo "value = ".$_SESSION['fr_date']; 
										unset($_SESSION['fr_date']);
									}
								?>></label>
							</div>
							<?php								
								if(isset($_SESSION['e_data'])){
									echo '<div class="error text-center mr-4 col-12">'.$_SESSION['e_data'].'</div>';
									unset($_SESSION['e_data']);
								}
							?>
						</div>
						<div class="row justify-content-center mt-2">
							<div class="col-12 mb-2">Kategoria :</div>
							<div class="categories">
								<div><label><input type="radio" value="1" name="category" class="mr-2"
										<?php if(isset($_SESSION['fr_category']) && $_SESSION['fr_category'] == 1){ 
											echo 'checked'; 
											unset($_SESSION['fr_category']);
										}
										?> >Wynagrodzenie</label></div>
								<div><label><input type="radio" value="2" name="category" class="mr-2"
										<?php if(isset($_SESSION['fr_category']) && $_SESSION['fr_category'] == 2){ 
											echo 'checked'; 
											unset($_SESSION['fr_category']);
										}
										?> >Odsetki bankowe</label></div>
								<div><label><input type="radio" value="3" name="category" class="mr-2"
										<?php if(isset($_SESSION['fr_category']) && $_SESSION['fr_category'] == 3){ 
											echo 'checked'; 
											unset($_SESSION['fr_category']);
										}
										?> >Sprzedaż na allegro</label></div>
								<div><label><input type="radio" value="4" name="category" class="mr-2"
										<?php if(isset($_SESSION['fr_category']) && $_SESSION['fr_category'] == 4){ 
											echo 'checked'; 
											unset($_SESSION['fr_category']);
										}
										?> >Inne</label></div>
							</div>
							<?php								
								if(isset($_SESSION['e_category'])){
									echo '<div class="error text-center mr-4 col-12">'.$_SESSION['e_category'].'</div>';
									unset($_SESSION['e_category']);
								}
							?>
						</div>
						<div class="row justify-content-center">
							<textarea name="comment" id="comment" rows="1" cols="55" placeholder="Krótki komentarz (opcjonalnie)" onfocus="this.placeholder=''" onblur="this.placeholder='Krótki komentarz (opcjonalnie)'"
							><?php if(isset($_SESSION['fr_comment'])){ 
										echo $_SESSION['fr_comment']; 
										unset($_SESSION['fr_comment']);
									}
								?></textarea>
						</div>
						<div class="row justify-content-center mt-4 mb-2">
								<input id="expense_submit" type="submit" value="Dodaj przychód" class="mr-5"> 
								<input id="expense_button" type="reset" value="Anuluj"> 
						</div>	
						<div class="row justify-content-center mt-1">
							<?php
								if(isset($_SESSION['successful_income'])){
									echo '<div class="success mt-4 mb-3">'.$_SESSION['successful_income']."</div>";
									unset($_SESSION['successful_income']);
								}
							?>
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