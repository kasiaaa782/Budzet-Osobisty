<?php
	session_start();

	require_once 'database.php';

	if(isset($_POST['email'])){
		//walidacja adresu email
		$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
		if(empty($email)){
			//walidacja nie powiodła się
			$_SESSION['given_email'] = $_POST['email'];
			header('Location: logowanie.php');
		} else {
			//walidacja powiodła się, sprawdzanie czy użytkownik istnieje w bazie
			
		}



	} else {
		header('Location: logowanie.php');
		exit();
	}

?>