#!/usr/bin/bash

export LANG=ja_JP.UTF-8

cd CI_BASE

/usr/local/bin/php74cli spark admin:generate_popular_list
/usr/local/bin/php74cli spark admin:copy_access_counter
