 -- example schema to store results from conntrack.php
 -- the column `ctime` requires mysql 5.6.5

DROP TABLE IF EXISTS `conntrack`;

CREATE TABLE IF NOT EXISTS `conntrack` (
	`ctime`		TIMESTAMP			 NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`avg`		MEDIUMINT UNSIGNED	 NOT NULL,
	`count`		MEDIUMINT UNSIGNED	 NOT NULL DEFAULT 0,
	`total`		INT UNSIGNED		 NOT NULL,
	`min`		MEDIUMINT UNSIGNED	 NOT NULL,
	`max`		MEDIUMINT UNSIGNED	 NOT NULL
);
