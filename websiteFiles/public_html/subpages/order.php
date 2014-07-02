<?php
	$orders = explode("-",$_GET['array']);
	for ($i = 0; $i < sizeof($orders); $i++)
		{
		echo $orders[$i];
	}
?>