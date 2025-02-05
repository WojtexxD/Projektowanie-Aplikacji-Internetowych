<?php
	include "../cfg.php";
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		global $link;

		$id = intval($_POST['id']);
		$page_title = mysqli_real_escape_string($link, $_POST['page_title']);
		$page_content = mysqli_real_escape_string($link, $_POST['page_content']);
		$status = isset($_POST['status']) ? 1 : 0;

		$query = "UPDATE page_list SET page_title = ?, page_content = ?, status = ? WHERE id = ?";
		$stmt = mysqli_prepare($link, $query);

		if (!$stmt) {
			die("Błąd SQL: " . mysqli_error($link));
		}

		mysqli_stmt_bind_param($stmt, "ssii", $page_title, $page_content, $status, $id);

		if (mysqli_stmt_execute($stmt)) {
			echo "Podstrona została zaktualizowana pomyślnie.";
		} else {
			echo "Błąd SQL: " . mysqli_error($link);
		}

		mysqli_stmt_close($stmt);
	}
?>
