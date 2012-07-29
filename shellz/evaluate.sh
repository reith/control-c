#!/bin/sh

#$seriPath seri id
seriPath="$usrFiles/$1"

if  [ $# -ne 1 ]; then
	echo "usage: `basename $0 ` <seri id>"
	exit
elif ! [ -d $seriPath ]; then
	echo "$seriPath is not valid seri id"
	exit
fi

list=`ls -1 -I "data" "$seriPath/"`
dataDir="$seriPath"/data
! [ -d $dataDir ] && echo "data folder not exists in $seriPath" && exit
! . "$dataDir/seri.conf"  && echo "not exists!" && exit

statlog="$dataDir/stats.log"
errlog="$dataDir/error.log"
echo '$evalTimeStart="'`eval $dateCommand`'";' >> "$statlog"
declare -i expression

for ID in $list
do
	for ((n=1; n <= $exNum; n++))
	do
		! . "$dataDir/$n.conf" && echo "exercise options not exists!" && continue

		declare -i markexercise=0
		
		for ((testcase=1; testcase <= $TCnum; testcase++)); do
			! [ -e "$seriPath/$ID/out/$n.$testcase.out" ] && continue
			diffopts="-a"
			$rmblanks && diffopts+=' -w'
			$rmmultiblanks && diffopts+=' -b'
			$rmblankln && diffopts+=' -B'
			! $casesense && diffopts+=' -i'
			expression=1

			[ -e "$seriPath/$ID/out/$n.$testcase.out" ] && diff -w $diffopts "$seriPath/$ID/out/$n.$testcase.out" "$dataDir/$n.$testcase.out" &>/dev/null && expression=$?

			if  [ $expression -eq 0 ]; then
				eval echo "\\\$TC[$ID][$n][$testcase]=\$tc${testcase}w\;" >> "$statlog"
			else
				echo "\$TC[$ID][$n][$testcase]=0;" >> "$statlog"
			fi
		done
	done
done

#check cheats
for ((n=1; n <= $exNum; n++)); do
  if [ -e "$dataDir/$n.md5sum" ]; then
    cat "$dataDir/$n.md5sum" | sort | uniq -w 33 -D | awk '{print "$CHEAT["$2"]["$3"]=true;"}' >> $statlog
  fi
done
echo '$evalTimeFinish="'`eval $dateCommand`'";' >> "$statlog"
