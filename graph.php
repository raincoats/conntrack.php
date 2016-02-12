<?php

require_once 'connquery.php';

// $data = json_decode(file_get_contents('data.json'));
// (int) $item->conns / 1000
$data = get_recent(30);
$rows = '';
$row_format = "<div style='height: %dpx !important; order: %d'>%s</div>";
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
		
		$rows .= sprintf(
			"<div class='%s' style='height: %dpx; order: %d'>%dk</div>",
			($conns === $max)? 'max':'row',
			(int) $conns / 250,
			$order,
			(int) $conns / 1000
		);
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
		<header>
			<h1>Open connections over the last half hour</h1>
			<h3>x axis: 1 bar = 1 minute</h3>
			<h3>y axis: connections</h3>
		</header>
		<div class="chart">
			<?php echo $rows; ?>
		</div>
	</body>
</html>