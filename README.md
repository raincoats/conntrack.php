# conntrack.php

A really simple log thing. Tracks how many active connections a server currently has, and averages them, calculates min/max, etc. It gets the number of current connections from `/proc/sys/net/netfilter/nf_conntrack_count`, if you’re interested.

By default, it samples once each second for a minute. At the end of that minute, it gives you a json object. Additionally, if the program detects a tty, it will tell you how many connections there are, once a second.

### Example

![Output](http://i.imgur.com/Lcpe2Jp.png)
