<?php

require_once 'connquery.php';

// $data = json_decode(file_get_contents('data.json'));
// (int) $item->conns / 1000
$data = get_recent(24 * 60);
$rows = '';
$row_format = "<div style='height: %dpx !important; order: %d'>%s</div>";
$max_format = "<div class='max' style='height: %dpx !important; order: %d'>%s</div>";
$max = false;
$order = 0;

$hourly = array();
// average them by hour
for ($i=0; $i<24; $i++)
{
	$slice = array_slice($data, ($i*60), 60);

	$total = 0;
	$time = null;
	foreach ($slice as $s) {
		if (is_null($time)){
			$time = preg_replace('/.* (\d+).*/', "$1", $s['time']);
			$time = sprintf("%2d", $time);
			$time = preg_replace('/ /', '0', $time);
			$time .= ':00';
			//$time = $s['time'];
		}
		$total += $s['conns'];
	}

	$avg = ($total/60);
	$avg = sprintf("%d", $avg);
	$avg = round($avg, -3);

	$hourly[] = array('conns' =>  $avg, 'time' => $time);
}
//var_dump($hourly);die();
$data = $hourly;

foreach ($data as $item) {
	if ($item['conns'] > $max) {
		$max = $item['conns'];
	}
}

foreach ($data as $item) {
	$order++;
	$conns = $item['conns'];

		$rows .= sprintf(
			"\n<div class='%s' style='height: %dpx; order: %d'>".
				"<div class='row-value'>%dk</div>".
				"<div class='row-time' >%s</div>".
			"</div>\n",
			($conns === $max)? 'row max' : 'row',
			(int) $conns / 200,
			$order,
			(int) $conns / 1000,
			$item['time']
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
#wrap {
	min-height: 85vh;
}



div.row-value, div.row-time {
  background-color: unset !important;
}

		</style>
	</head>
	<body>
	<div id="wrap">
		<header style="margin-left: 4rem;">
			<h1>Connections to time.apricot.pictures<br/>over the last day</h1>
			<h3>x axis: 1 bar = 1 hour<br/>y axis: average active UDP connections</h3>
		</header>
		<div class="chart">
			<?php echo $rows; ?>
		</div>
	</div>
		<footer style="margin-left: 4rem;">
		<h4><?php echo "at ".date("H:i e")." time<br/>".
                       "at ".date("U")." seconds since Jan 1, 1970"; ?></h4>
		</footer>
	</body>
</html>