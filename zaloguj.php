<?php
	session_start();

	require_once 'database.php';

	if(!isset($_SESSION['logged_id'])){
		if(isset($_POST['email'])){
			//walidacja adresu email
			$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
			if(empty($email)){
				//walidacja nie powiodła się
				$_SESSION['given_email'] = $_POST['email'];
				header('Location: logowanie.php');
				exit();
			} else {
				//walidacja powiodła się, sprawdzanie czy użytkownik istnieje w bazie
				$password = filter_input(INPUT_POST, 'pass');

				$userQuery = $db->prepare('SELECT id, password, username FROM users WHERE email = :email');
				$userQuery->bindValue(':email', $email, PDO::PARAM_STR);
				$userQuery->execute();

				//dostajemy dane id, password w szufladkach tablicy asjocjacyjnej o nazwie tablic takich jak w bazie danych
				$user = $userQuery->fetch();

				//password_verify($password, $user['password']) - sprawdzanie zahashowanego hasła
				if($user && password_verify($password, $user['password'])){
					$_SESSION['username'] = $user['username'];
					$_SESSION['logged_id'] = $user['id'];
					unset($_SESSION['bad_attempt']);
					header('Location: menu.php');
					exit();
				} else {
					//nie ma takiego użytkownika w bazie
					$_SESSION['bad_attempt'] = $_POST['email'];
					header('Location: logowanie.php');
					exit();
				}
			}
		} else {
			header('Location: logowanie.php');
			exit();
		}
	}
?>