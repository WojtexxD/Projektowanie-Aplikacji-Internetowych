<?php
	function PokazPodstrone($id)
	{
		include 'cfg.php';
		$id_clear=htmlspecialchars($id);
		
		$query="SELECT * FROM page_list WHERE id='$id_clear'";
		$result=mysqli_query($link,$query);
		$row=mysqli_fetch_array($result);
		
		if(empty($row['id']))
		{
			$web='[nie_znaleziono_strony]';
		}
		else
		{
			$web=$row['page_content'];
		}
		echo $row['page_content'];
		return $web;
	}
	PokazPodstrone(5);
?>