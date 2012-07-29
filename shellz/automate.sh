#!/bin/sh
if [ $# -ne 1 ]; then
	echo "usage: `basename $0` <seri id>"
	exit
elif ! [ -d $seriPath ]; then
	echo "Error in `basename $0`: $seriPath is not exists."
	exit
fi

cd `dirname $0`
. ./shell.conf
. ./build.sh $1
. ./evaluate.sh $1

(
cd "$phpPath"
cat "$usrFiles/$1/data/error.log"
(echo "<?php" && cat "$usrFiles/$1/data/stats.log" "update.exercise_result.php") | /usr/bin/php) &> "$errorLogsPath.$1"
