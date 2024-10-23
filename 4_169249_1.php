<?php
	$nr_indeksu='169249';
	$nrGrupy='1';
	
	echo 'Wojciech Czerniak'.$nr_indeksu.'grupa'.$nrGrupy.'<br/><br/>';
	
	echo 'Zastosowanie metody include()<br/>';
	
	$a=2;
	$b=3;
	$c=1;
	
	if ($a > $b)
		if ($a > $c)
			if($b > $c)
				echo 'a,b,c <br/>';
			else
				echo 'a,c,b <br/>';
		else
			echo 'c,a,b <br/>';
	elseif ($b > $c)
		if ($a > $c)
			echo 'b,a,c <br/>';
		else
			echo 'b,c,a <br/>';
	else
		echo 'c,b,a <br/>';
	
	
	
	

	$i=2;
	switch ($i)
	{
		case 0:
			echo "i equals 0 <br/>";
		case 1:
			echo "i equals 1 <br/>";
		case 2:
			echo "i equals 2 <br/>";
	}
	
	while ($i <= 10)
	{
		echo 'i = '.$i++.'</br>';
	}
	
	echo 'Hello'.htmlspecialchars($_GET['name']).'!';
	echo 'Hello'.htmlspecialchars($_POST["name"]).'!';
?>