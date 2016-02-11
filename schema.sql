 -- example schema to store results from conntrack.php
 -- the column `ctime` requires mysql 5.6.5

DROP TABLE IF EXISTS `conntrack`;

CREATE TABLE IF NOT EXISTS `conntrack` (
	`ctime`		TIMESTAMP			 NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`avg`		MEDIUMINT UNSIGNED	 NOT NULL,
);
