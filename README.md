# conntrack.php

A really simple log thing. Tracks how many active connections a server currently has, and averages them, calculates min/max, etc.

It gets the number of current connections from `/proc/sys/net/netfilter/nf_conntrack_count`.
