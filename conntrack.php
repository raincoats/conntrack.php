<?php

require_once 'butts.php';
include_once 'config.php';

if (posix_uname()['sysname'] === 'Darwin')
	define('DEV_MODE', true);


function get_connections()
{
	if (is_dir('/sys')){
		// if this is linux (ie. not dev)
		$conns = file_get_contents('/proc/sys/net/netfilter/nf_conntrack_count');
		$conns = intval($conns);
	}
	else {
		// dev
		$conns = rand(1, 100);
	}
	debug(sprintf("%7d connections", $conns), 3);

	return $conns;
}


function sample($count=60, $interval=1)
{
	$avg =   0;
	$total = 0;

	// fripping microseconds
	$interval = $interval * 1000000;

	$samples = array();

	for ($i=0; $i<$count; $i++){
		$total += get_connections();
		usleep($interval);
	}

	$avg = round($total / $count);
	debug(sprintf("%s   average: %d", date("H:M"), $avg), 3); 

	return $avg;
}


function sql_insert($avg)
{
	global $sql, $pdo;

	try {
		$q = $pdo->prepare('INSERT INTO conntrack (avg) VALUES (:avg)');
		$q->bindParam(':avg', $avg);

		$q->execute();

		if (! is_null($error = $q->errorInfo()[2]))
			die(debug($error, 1));
	}
	catch(PDOException $e) {
    	debug($e->getMessage(), 1);
	}
}





$pdo = new PDO(
	"mysql:host={$sql['host']};dbname={$sql['schema']}",
	$sql['user'],
	$sql['pass']
);

while (true) {
	if (defined('DEV_MODE')) {
		sql_insert(sample(60, 0.000001));
		sleep(1);
	}
	else {
		sql_insert(sample(60, 1));
	}
}


