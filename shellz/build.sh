#!/bin/sh

#return codes
# 6 data directory is not found
# 8 seri config file is not found

#$1 seri id
seriPath="$usrFiles/$1"

if [ $# -ne 1 ]; then
	echo "usage: `basename $0` <seri id>"
	exit
elif ! [ -d $seriPath ]; then
	echo "Error in `basename $0`: $seriPath is not exists."
	exit
fi

seriPath="$seriPath"
seriList=`ls -I data $seriPath`
dataDir="$seriPath"/data
statlog="$dataDir/stats.log"
errlog="$dataDir/error.log"

declare -i t
declare -i seriWage=0

[ -e "$statlog" ] && rm "$statlog"
[ -e "$errlog" ] && rm "$errlog"
touch "$statlog" "$errlog"

! [ -d "$dataDir" ] && echo "error in `basename $0`: dataDir not found" && exit 6

! . "$dataDir/seri.conf"  && echo "seri.conf not exists!" && exit 8
#add some data to files
for (( exTracer=1; exTracer <= $exNum; exTracer++)); do
  ! . "$dataDir/$exTracer.conf" && echo "exercise options file not found!" && continue
  seriWage+=$wage
  echo -e "\$EX[$exTracer]=$exId;\n\$TCNUM[$exTracer]=$TCnum;\n\$TCSUMW[$exTracer]=$TCSUMW;\n\$WAGE[$exTracer]=$wage;" >> "$statlog"
  echo "\$WAGESUM=$seriWage;" >> "$statlog"
done

EL=`md5sum $seriPath/*/uploaded.zip | sed -ne 's#\(.*\)  .*/\(.*\)/.*$#\1 \2#p' | sort | uniq -w 33 -D`
echo "$EL" | awk '{ if ($2) print "$ZIP["$2"]=3;" }' >> "$statlog"
EL=`echo "$EL" | awk '{ print $2 }'`
for ID in $EL; do
  eval "E${ID}=1";
done

echo -e "\$EXCOUNT=$exNum;\n\$courseId=$crsId;\n\$seriId=$1;\n"'$compileTimeStart="'`eval $dateCommand`"\";" >> "$statlog"

for ID in $seriList; do
	echo "\$STUDENTS[]=$ID;" >> "$statlog"
 	test `eval echo '$'E${ID}` && continue

	# $ZIP[id]:
	# 0	fine
	# 1	error
	# 2	not exists
	# 3	duplicated
	if [ -e "$seriPath/$ID/uploaded.zip" ]; then
	  unzip -u -d "$seriPath/$ID" "$seriPath/$ID/uploaded.zip" > /dev/null
	  if  [ $? -eq 0 ]; then
	    echo "\$ZIP[$ID]=0;" >> "$statlog";
	  else
	    echo "\$ZIP[$ID]=1;" >> "$statlog";
	  fi
	else
	  echo "\$ZIP[$ID]=2;" >> "$statlog";
	fi

	! [ -e "$seriPath/$ID/out" ] && mkdir "$seriPath/$ID/out"
	for (( exTracer=1; exTracer <= $exNum; exTracer++)); do
		sh ./compile.sh "$seriPath" "$ID" "$exTracer" "$lang" "$statlog" "$errlog"
		if [ $? -eq 0 ]; then
			exeFile="$seriPath/$ID/$exTracer"
			maxtime=$defaultMaxTime
			t=0;
			! . "$dataDir/$exTracer.conf" && echo "exercise options file not found!" && continue
			for (( testcase=1; testcase <= $TCnum; testcase++ )); do
				tmp=`time (./srun.sh "$exeFile" "$seriPath" "$ID" "$exTracer" "$testcase" "$maxtime" "$statlog" "$errlog" &>/dev/null) 2>&1`
				t+=`echo $tmp | sed -e 's/[.]//' | sed -e 's/^0*//'`
				outPath="$seriPath/$ID/out/$exTracer.$testcase.out"
				[ -e "$outPath" ] && $rmfblanks && sed -i -e 's/^ *//' "$outPath"
				[ -e "$outPath" ] && $rmlblanks && sed -i -e 's/ *$//' "$outPath"
			done
			echo "\$TIME[$ID][$exTracer]=$t;" >>"$statlog"
			[ -e "$exeFile" ] && md5sum "$exeFile" | sed -ne 's#\(.*\)  .*/\(.*\)/.*$#\1 \2'" $exTracer"'#p'>>"$dataDir/$exTracer.md5sum" && rm "$exeFile"
		fi
	done
done
echo '$compileTimeFinish="'`eval $dateCommand`'";' >> "$statlog"