<?php
	session_start();

	function selectPeriodPhp($firstDate, $secondDate){
		$firstDateFormated = date("d.m.Y", strtotime($firstDate));
		$secondDateFormated = date("d.m.Y", strtotime($secondDate));		
		$_SESSION['sentencePeriod'] = 'Za okres od '.$firstDateFormated.' do '.$secondDateFormated;
	}

	if(!isset($_SESSION['logged_id'])){
		header('Location: logowanie.php');
	} else {
		
		if(!isset($_GET['option'])&&!isset($_POST['dateBegin'])&&!isset($_POST['dateEnd'])){
			$option = 1;
		}
		if(isset($_GET['option'])){
			$option = $_GET['option'];
		}
		if(isset($_POST['dateBegin']) || isset($_POST['dateEnd'])){
			$option = 4;
		}
		
		switch($option){
			case 1: //current month
				$beginCurMonth = date("Y-m-01");
				$endCurMonth = date("Y-m-t");				
				selectPeriodPhp($beginCurMonth, $endCurMonth);			
				break;
			case 2: //previous month
				$beginPrevMonth = date("Y-m-01", strtotime ("-1 month"));
				$endPrevMonth = date("Y-m-t", strtotime ("-1 month"));				
				selectPeriodPhp($beginPrevMonth, $endPrevMonth);	
				break;
			case 3: //current year
				$beginCurYear = date("Y-01-01");
				$endCurYear = date("Y-12-t");				
				selectPeriodPhp($beginCurYear, $endCurYear);	
				break;
			case 4: //nonstandard
				if(empty($_POST['dateBegin'])){
					$beginDate = date("Y-m-d");
				} else {
					$beginDate = $_POST['dateBegin'];
				}
				if(empty($_POST['dateEnd'])){
					$endDate = date("Y-m-d");
				} else {
					$endDate = $_POST['dateEnd'];
				}
				if($beginDate > $endDate || $endDate < $beginDate){
					$_SESSION['dateError'] = 'Błędny przedział czasowy!';
				} else {
					selectPeriodPhp($beginDate, $endDate);
				}
				break;
		}
		
		require_once 'database.php';

		$i = 4;
		while($i > 0){
			$query = $db->prepare('SELECT SUM(amount) FROM incomes WHERE user_id = :user_id AND income_category_assigned_to_user_id = :i');
			$query->bindValue(':user_id', $_SESSION['logged_id'], PDO::PARAM_INT);
			$query->bindValue(':i', $i, PDO::PARAM_INT);
			$query->execute();
			//dostajemy dane amount w szufladkach tablicy asjocjacyjnej o nazwie tablic takich jak w bazie danych
			$income = $query->fetch();

			$_SESSION['in_amount'.$i]  = $income['SUM(amount)'];
		
			$i--;
		}

		$j = 16;
		while($j > 0){
			$query = $db->prepare('SELECT SUM(amount) FROM expenses WHERE user_id = :user_id AND expense_category_assigned_to_user_id = :j');
			$query->bindValue(':user_id', $_SESSION['logged_id'], PDO::PARAM_INT);
			$query->bindValue(':j', $j, PDO::PARAM_INT);
			$query->execute();

			$expense = $query->fetch();

			$_SESSION['ex_amount'.$j]  = $expense['SUM(amount)'];
		
			$j--;
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
	
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<link rel="stylesheet" href="style.css" type="text/css" />
	<link rel="stylesheet" href="css/money.css" type="text/css" />
	<link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,700&display=swap" rel="stylesheet">	


	<script type="text/javascript" src="budget.js"></script>
	<!--Wykorzystane do PieChart - wykresu-->
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>


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
						<li class="nav-item mx-auto active"><a class="nav-link" href="bilans.php">Przeglądaj bilans</a></li>
						<li class="nav-item mx-auto"><a class="nav-link" href="#">Ustawienia</a></li>
						<li class="nav-item mx-auto"><a class="nav-link" href="wylogowanie.php">Wyloguj się</a></li>
					</ol>
				</div>
			</nav>
		</header>
		<main>
			<div id="content_balance">
				<div class="container">
					<div class="row justify-content-center mt-2">
						<div class="col-12 col-lg-4 text-center ml-lg-5" id="title">Bilans finansowy</div>
						<div class="mb-3 mt-3 mt-lg-0" id="select_period_dropdown">
							<button id="dropbutton">Wybierz okres</button>
							<div id="dropdown-content">
								<a href="bilans.php?option=1">Bieżący miesiąc</a>
								<a href="bilans.php?option=2">Poprzedni miesiąc</a>
								<a href="bilans.php?option=3">Bieżący rok</a>
								<a href="#myModal" data-toggle="modal" >Niestandardowy</a>
							</div>
							<!-- <form method='post'>
								<select id="select_option" name="select_option"  >                      
									<option value="0" >--Wybierz okres--</option>
									<option value="1" onclick="selectPeriod(1);">Bieżący miesiąc</option>
									<option value="2" onclick="selectPeriod(2);">Poprzedni miesiąc</option>
									<option value="3" onclick="selectPeriod(3);">Bieżący rok</option>
									<option value="4" >Niestandardowy</option>
									
								</select>
							</form> -->
							<!--Modal-->
							<div class="modal fade text-body" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title" id="myModalLabel">Wybierz przedział czasowy</h4>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">×</span>
											</button>
										</div>
										<form method="post" action="bilans.php" >
											<div class="modal-body text-center mt-3 mb-3">
												<div id="selectPeriod">	
													od <input type="date" name="dateBegin" id="dateBegin">
													do <input type="date" name="dateEnd" id="dateEnd">
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Anuluj</button>
												<button type="submit" class="btn btn-primary" >Pokaż</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row justify-content-center">
						<div class="col-12 text-center" id="period" >
						<?php								
							if(isset($_SESSION['sentencePeriod'])){
								echo $_SESSION['sentencePeriod'];
								unset($_SESSION['sentencePeriod']);
							}
							if(isset($_SESSION['dateError'])){
								echo $_SESSION['dateError'];
								unset($_SESSION['dateError']);
							}
						?>
						</div>
					</div>
				</div>
				<div class="container">
					<div class="table_name">PRZYCHODY</div>
					<div class="row justify-content-center">
						<table>
							<tr>
								<th>Kategoria</th>
								<th>Kwota</th>
							</tr>
							<tr>
								<td>Wynagrodzenie</td>
								<td id="salary"><?php if(isset($_SESSION['in_amount1'])){
									echo $_SESSION['in_amount1'];
									unset($_SESSION['in_amount1']);
								} else {
									echo '0.00';
								}?></td>
							</tr>
							<tr>
								<td>Odsetki bankowe</td>
								<td id="bankInterest"><?php if(isset($_SESSION['in_amount2'])){
									echo $_SESSION['in_amount2'];
									unset($_SESSION['in_amount2']);
								} else {
									echo '0.00';
								}?></td>
							</tr>
							<tr>
								<td>Sprzedaż na allegro</td>
								<td id="allegro"><?php if(isset($_SESSION['in_amount3'])){
									echo $_SESSION['in_amount3'];
									unset($_SESSION['in_amount3']);
								} else {
									echo '0.00';
								}?></td>
							</tr>
							<tr>
								<td>Inne</td>
								<td id="otherIncome"><?php if(isset($_SESSION['in_amount4'])){
									echo $_SESSION['in_amount4'];
									unset($_SESSION['in_amount4']);
								} else {
									echo '0,00';
								}?></td>
							</tr>
							<tr class="sum">
								<td>Suma</td>
								<td id="sumOfIncomes">0.00</td>
							</tr>
						</table> 
					</div>
					<div class="table_name">WYDATKI</div>
					<div class="row justify-content-center">
							<table>
							<tr>
								<th>Kategoria</th>
								<th>Kwota</th>
							</tr>
							<tr>
								<td>Transport</td>
								<td id="transport"><?php if(isset($_SESSION['ex_amount1'])){
									echo $_SESSION['ex_amount1'];
									unset($_SESSION['ex_amount1']);
								} else {
									echo '0,00';
								}?></td>
							</tr>
							<tr>
								<td>Książki</td>
								<td id="books"><?php if(isset($_SESSION['ex_amount2'])){
									echo $_SESSION['ex_amount2'];
									unset($_SESSION['ex_amount2']);
								} else {
									echo '0,00';
								}?></td>
							</tr>
							<tr>
								<td>Jedzenie</td>
								<td id="eat"><?php if(isset($_SESSION['ex_amount3'])){
									echo $_SESSION['ex_amount3'];
									unset($_SESSION['ex_amount3']);
								} else {
									echo '0,00';
								}?></td>
							</tr>
							<tr>
								<td>Mieszkanie</td>
								<td id="accommodation"><?php if(isset($_SESSION['ex_amount4'])){
									echo $_SESSION['ex_amount4'];
									unset($_SESSION['ex_amount4']);
								} else {
									echo '0,00';
								}?></td>
							</tr>
							<tr>
								<td>Telekomunikacja</td>
								<td id="telecommunication"><?php if(isset($_SESSION['ex_amount'])){
									echo $_SESSION['ex_amount5'];
									unset($_SESSION['ex_amount5']);
								} else {
									echo '0,00';
								}?></td>
							</tr>
							<tr>
								<td>Opieka zdrowotna</td>
								<td id="healthcare"><?php if(isset($_SESSION['ex_amount6'])){
									echo $_SESSION['ex_amount6'];
									unset($_SESSION['ex_amount6']);
								} else {
									echo '0,00';
								}?></td>
							</tr>
							<tr>
								<td>Ubranie</td>
								<td id="clothes"><?php if(isset($_SESSION['ex_amount7'])){
									echo $_SESSION['ex_amount7'];
									unset($_SESSION['ex_amount7']);
								} else {
									echo '0,00';
								}?></td>
							</tr>
							<tr>
								<td>Higiena</td>
								<td id="hygiene"><?php if(isset($_SESSION['ex_amount8'])){
									echo $_SESSION['ex_amount8'];
									unset($_SESSION['ex_amount8']);
								} else {
									echo '0,00';
								}?></td>
							</tr>
							<tr>
								<td>Dzieci</td>
								<td id="kids"><?php if(isset($_SESSION['ex_amount9'])){
									echo $_SESSION['ex_amount9'];
									unset($_SESSION['ex_amount9']);
								} else {
									echo '0,00';
								}?></td>
							</tr>
							<tr>
								<td>Rozrywka</td>
								<td id="entertainment"><?php if(isset($_SESSION['ex_amount10'])){
									echo $_SESSION['ex_amount10'];
									unset($_SESSION['ex_amount10']);
								} else {
									echo '0,00';
								}?></td>
							</tr>
							<tr>
								<td>Wycieczka</td>
								<td id="trip"><?php if(isset($_SESSION['ex_amount11'])){
									echo $_SESSION['ex_amount11'];
									unset($_SESSION['ex_amount11']);
								} else {
									echo '0,00';
								}?></td>
							</tr>									
							<tr>
								<td>Oszczędności</td>
								<td id="savings"><?php if(isset($_SESSION['ex_amount12'])){
									echo $_SESSION['ex_amount12'];
									unset($_SESSION['ex_amount12']);
								} else {
									echo '0,00';
								}?></td>
							</tr>
							<tr>
								<td>Emerytura</td>
								<td id="pension"><?php if(isset($_SESSION['ex_amount13'])){
									echo $_SESSION['ex_amount13'];
									unset($_SESSION['ex_amount13']);
								} else {
									echo '0,00';
								}?></td>
							</tr>
							<tr>
								<td>Spłata długów</td>
								<td id="debts"><?php if(isset($_SESSION['ex_amount14'])){
									echo $_SESSION['ex_amount14'];
									unset($_SESSION['ex_amount14']);
								} else {
									echo '0,00';
								}?></td>
							</tr>
							<tr>
								<td>Darowizna</td>
								<td id="donation"><?php if(isset($_SESSION['ex_amount15'])){
									echo $_SESSION['ex_amount15'];
									unset($_SESSION['ex_amount15']);
								} else {
									echo '0,00';
								}?></td>
							</tr>
							<tr>
								<td>Inne wydatki</td>
								<td id="otherExpenses"><?php if(isset($_SESSION['ex_amount16'])){
									echo $_SESSION['ex_amount16'];
									unset($_SESSION['ex_amount16']);
								} else {
									echo '0,00';
								}?></td>
							</tr>
							<tr class="sum">
								<td>Suma</td>
								<td id="sumOfExpenses">0.00</td>
							</tr>
						</table> 
					</div>
					<div class="row justify-content-center">
						<div id="piechart"></div>
					</div>
					<div class="mx-auto" id="balance">
						<div id="balance1">BILANS</div>
						<div id="result">0.00</div>
						<div id="score">Gratulacje. Świetnie zarządzasz finansami!</div>
					</div>
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
