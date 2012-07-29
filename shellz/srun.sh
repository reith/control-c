#!/bin/bash
#securely run programms
(
sleep $6
echo "\$ERR[$3][$4]|=(1<<$5-1);" >> "$7"
killall -9 "$1"
killall -9 "sandbox"
rm -r "$2/$3/out/$4.$5.out" #avoid of SED confusing...
eval "pkill -P $$"
)&

exdir=`dirname $1`
export SANDBOX_READ="/usr/share/sandbox:/bin/:$exdir"
export SANDBOX_WRITE="$exdir"
/usr/bin/sandbox "$1"<"$2/data/$4.$5.in"&>"$2/$3/out/$4.$5.out"
eval "pkill -P $$"
