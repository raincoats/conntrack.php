<?php

require_once 'butts.php';

$min =       true;
$max =       true;
$avg =       0;
$count =     0;
$total     = 0;
$total_avg = 0;


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
		return rand(1989, 1998);
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

	for ($i=1; $i<$n; $i++){
		$conns = get_connections();
		$sample[] = $conns;
		debug("({$i}) {$conns} connections", 3);
		$count++;
		usleep($interval);
	}
	array_map('get_averages', $sample);
}



sample(60, 1);

$out = json_encode(array(
	'date'  => date("d-m-Y-h:m"),
	'epoch' => date("U"),
	'avg'   => $avg,
	'count' => $count,
	'total' => $total,
	'min'   => $min,
	'max'   => $max,
));

printf($out);
/*

printf("%-10s %d\n", "average:", $avg);
printf("%-10s %d\n", "count:", $count);
printf("%-10s %d\n", "total:", $total);
printf("%-10s %d/%d\n", "min/max:", $min, $max);

*/