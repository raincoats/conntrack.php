<?php

require_once 'butts.php';
require_once 'config.php';

/* retrieve $limit recent entries from the db */
function get_recent($limit)
{
	global $sql, $query;

	$pdo = new PDO("mysql:host={$sql['host']};dbname={$sql['schema']}",
	               $sql['user'], $sql['pass']);
	$q = $pdo->prepare($query);
	$q->execute();

	if (! is_null($error = $q->errorInfo()[2])){
		die(debug($error, 1));
	}

	$output = array();

	for ($i=0; $i<$limit; $i++){
		$output[] = $q->fetch(PDO::FETCH_ASSOC);
	}

	return array_reverse($output);
}

$query = <<< 'EOF'
SELECT    ctime AS time,
          TIME_FORMAT(ctime, '%H') AS h,
          TIME_FORMAT(ctime, '%i') AS m,
          avg
FROM      conntrack
ORDER BY  ctime DESC
EOF;


$entries = get_recent(50);

foreach ($entries as $entry) {
	debug(sprintf("time: %-15s\th: %d\tm: %d\tavg: %-15s", 
		          $entry['time'], $entry['hour'], $entry['minute'], $entry['avg']));
}

echo json_encode($entries);
