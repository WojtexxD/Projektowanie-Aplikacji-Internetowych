<?php	
	function PokazKontakt()
	{
		echo '<h2>Formularz kontaktowy</h2>';
		echo '<form action="contact.php" method="post">';
		echo '<label for="email">Twój e-mail:</label><br>';
		echo '<input type="email" id="email" name="email" required><br><br>';

		echo '<label for="temat">Temat wiadomości:</label><br>';
		echo '<input type="text" id="temat" name="temat" required><br><br>';

		echo '<label for="tresc">Treść wiadomości:</label><br>';
		echo '<textarea id="tresc" name="tresc" required></textarea><br><br>';

		echo '<input type="submit" name="wyslij_kontakt" value="Wyślij">';
		echo '</form>';
	}
	
	
	
	function WyslijMailKontakt($odbiorca)
	{
		if (empty($_POST['temat']) || empty($_POST['tresc']) || empty($_POST['email']))
		{
			echo '[nie_wypelniles_pola]';
			PokazKontakt();
			return;
		}

		$mail =
		[
			'subject'    => htmlspecialchars(trim($_POST['temat'])),
			'body'       => htmlspecialchars(trim($_POST['tresc'])),
			'sender'     => filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL),
			'recipient'  => $odbiorca
		];

		if (!$mail['sender'])
		{
			echo '[nieprawidlowy_email]';
			PokazKontakt();
			return;
		}

		$headers  = "From: Formularz kontaktowy <".$mail['sender'].">\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: text/plain; charset=utf-8\n";
		$headers .= "Content-Transfer-Encoding: 8bit\n";
		$headers .= "X-Mailer: PRapWWW mail 1.2\n";
		$headers .= "X-Priority: 3\n";
		$headers .= "Return-Path: <".$mail['sender'].">\n";
	}
	
	
	
	function PrzypomnijHaslo($odbiorca, $adminPassword)
	{
		if (empty($_POST['email']))
		{
			echo '<p style="color: red;">Musisz podać adres e-mail.</p>';
			PokazPrzypomnijHasloFormularz();
			return;
		}

		$email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);

		if (!$email)
		{
			echo '<p style="color: red;">Podano nieprawidłowy adres e-mail.</p>';
			PokazPrzypomnijHasloFormularz();
			return;
		}

		$subject = "Przypomnienie hasła do panelu admina";
		$body = "Twoje hasło do panelu administracyjnego: $adminPassword\n\n";
		$body .= "Prosimy o zachowanie go w tajemnicy i zmianę, jeśli podejrzewasz, że zostało ujawnione.";
		$headers  = "From: Admin Panel <169249@student.uwm.edu.pl>\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: text/plain; charset=utf-8\n";
		$headers .= "Content-Transfer-Encoding: 8bit\n";
		$headers .= "X-Mailer: PRapWWW mail 1.2\n";
		$headers .= "X-Priority: 3\n";
		$headers .= "Return-Path: <".$mail['sender'].">\n";

		if (mail($email, $subject, $body, $headers))
		{
			echo '<p style="color: green;">Hasło zostało wysłane na podany adres e-mail.</p>';
		}
		else
		{
			echo '<p style="color: red;">Wystąpił błąd podczas wysyłania wiadomości. Spróbuj ponownie później.</p>';
		}
	}
	
	
	
	
	function PokazPrzypomnijHasloFormularz()
	{
		echo '<h2>Przypomnienie hasła</h2>';
		echo '<form action="" method="post">';
		echo '<label for="email">Podaj swój adres e-mail:</label><br>';
		echo '<input type="email" id="email" name="email" required><br><br>';
		echo '<input type="submit" name="przypomnij_haslo" value="Wyślij przypomnienie">';
		echo '</form>';
	}
?>