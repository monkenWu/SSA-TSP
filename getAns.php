<?php 
	include "SimulatedAnnealing.php";
	$data = json_decode($_POST["data"],true);
	//print_r($data);
    $map = $data["map"];
	$n = $data["info"]["N"];
	$T = $data["info"]["T"];
	$L = $data["info"]["L"];
	$l = $data["info"]["l"];
	$SA = new SimulatedAnnealing($map,$n,$T,$L,$l);
	$SA->calculate();
	echo json_encode([
		"status" => 1,
		"data" => [
			$SA->getAns()
		]
	]);
?>