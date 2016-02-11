<?php

if (posix_isatty(0) and posix_isatty(1) and posix_isatty(2))
	define('tty', true);
else
	define('tty', false);

// 0 for success, 1 for error, 2 for warn, 3 for info
function debug($message, $level=3)
{
	if (! tty)
		return true;

	switch ($level) {
		case 0:
			$colour = 34;
			$text = '[$]';
			break;
		
		case 1:
			$colour = 124;
			$text = '[!]';
			break;
		
		case 2:
			$colour = 214;
			$text = '[!]';
			break;
		
		default:
			$colour = 39;
			$text = '[+]';
			break;
	}

	printf("\002\033[38;5;%dm\003%s\002\033[m\003 %s\n", $colour, $text, $message);

	return true;
}
