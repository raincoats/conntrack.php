<?php

require_once 'vendor/autoload.php';
require_once 'butts.php';
include_once 'config.php';

$min =       true;
$max =       true;
$avg =       0;
$count =     0;
$total =     0;
$total_avg = 0;



if (isset($argv[1]) and ($argv[1] === 'sql'))
	$format = 'sql';
else
	$format = 'json';


function minmax($i)
{
	global $min, $max;

	$min = $min < $i? $min : $i;
	$max = $max > $i? $max : $i;
}


function get_connections()
{
	if (is_dir('/sys')){
		$conns = file_get_contents('/proc/sys/net/netfilter/nf_conntrack_count');
		return intval($conns);
	}
	else {
		// debug
		return rand(1, 100);
	}
}

function get_averages($int)
{
	global $total, $count;

	minmax($int);

	// averages
	$total += $int;
	$avg = $int / $count;

	return true;
}

function sample($n=10, $interval=1)
{
	global $count;

	// fripping microseconds
	$interval = $interval * 1000000;

	$samples = array();

	for ($i=0; $i<$n; $i++){
		$conns = get_connections();
		$sample[] = $conns;
		debug("({$i}) {$conns} connections", 3);
		$count++;
		usleep($interval);
	}
	array_map('get_averages', $sample);
}

function sql_insert($data)
{
	global $sql;

	try {
		$pdo = new PDO("mysql:host={$sql['host']};dbname={$sql['schema']}",
						 $sql['user'], $sql['pass']);

		$q = $pdo->prepare('INSERT INTO conntrack (avg) VALUES (:avg)');
		$q->bindParam(':avg',    $data['avg']);

		$q->execute();

		$error = $q->errorInfo();
		if (! is_null($error[2]))
			die(debug($error[3], 1));
	}
	catch(PDOException $e) {
    	debug($e->getMessage(), 1);
	}
}


sample(60, 1);

$data = array(
		'date'   => date("Y-m-d H:m:s"),
		'avg'    => round($total / $count),
);

// more debug stuff
foreach ($data as $k => $v) {
	debug(sprintf("%-15s\t%s", "{$k}:", $v));
}


if ($format === 'json'){
	echo json_encode($data);
	$ret = 0;
}
elseif ($format === 'sql'){
	$ret = sql_insert($data);
}

exit($ret);

