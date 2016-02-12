<?php

require_once 'connquery.php';

// $data = json_decode(file_get_contents('data.json'));
// (int) $item->conns / 1000
$data = get_recent(60);
$rows = '';
$row_format = "<div style='height: %dpx !important; order: %d'></div>";
$max_format = "<div class='max' style='height: %dpx !important; order: %d'>%s</div>";
$max = false;
$order = 0;

foreach ($data as $item) {
	if ($item['conns'] > $max) {
		$max = $item['conns'];
	}
}

foreach ($data as $item) {
	$order++;
	$conns = $item['conns'];
	if ($item['conns'] === $max){
		$rows .= sprintf($max_format, (int)$conns/1000, $order, false?$conns:'');
	}
	else{
		$rows .= sprintf($row_format, (int)$conns/1000, $order);
	}
}

?>
<!doctype html>
<html>
	<head>
		<link href="css/phpgraph.css" rel="stylesheet" type="text/css"/>
		<style>
		.max:after {
			/*content: "<?php echo $max; ?>";*/
			color: #000000;
		}
		</style>
	</head>
	<body>
		<div class="chart">
			<?php echo $rows; ?>
		</div>
	</body>
</html>