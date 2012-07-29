#!/bin/sh
#$1 exercise seri path
#$2 student number
#$3 exercise number
#$4 file extension
#$5 stats log file path
#$6 error log file path

#exit stats:
#2:	bad invoking script
#4:	source not exists
#5:	stats file not exists
#6:	compile error

#SRC[student][exercise]
# 0 compiled
# 1 compile error
# 2 not found

if ! [ -e "$6" ]; then
	echo "error in `basename $0`: error log not found!"
	exit 2
fi

if ! [ -e "$5" ]; then
	echo "error in `basename $0`: stats log not found!" >> $6
	exit 5
fi
source="$1/$2/$3"

      #	first check case insensitive valid files
extensions=`ls -1 "$source".* 2>/dev/null | sed -n -e "s:$source\.\($4\)$:\1:ip"`

for ext in $extensions; do

  #IMPORTANT: BAD! MOVE IT TO SOME FUNCTION
  #DUPLICATED CODE
  if [ -e "$source.$ext" ]; then
    mv "$source.$ext" "$source.$4" &>/dev/null
    echo -n "searching student $2 for exercise $3: selecting source "`basename $source.$ext`" ... "
    case $4 in
      Pascal) compile="/usr/bin/fpc -So -v0 -Fe/dev/null $source.$4";;
      C) compile="/usr/bin/gcc -x c -std=c99 -lm $source.$4 -o $source";;
      CPP) compile="/usr/bin/g++ -x c++ -lm $source.$4 -o $source";;
      *) echo "ERROR: unknown language format $4" >> $6
    esac

    echo "$source.$4" > "$1/$2/out/$3.filename"
    ( exec $compile )2>"$1/$2/out/$3.error"

    if [ $? -eq 0 ]; then
	  echo "\$SRC[$2][$3]=0;" >> "$5"
	  echo "Compiled Successfully."
	  rm -f `dirname $source`/*.o "$1/$2/out/$3.error"
	  exit 0
    else
	  echo "\$SRC[$2][$3]=1;" >> "$5"
	  echo "Compile error."
	  exit 6
    fi
  fi 	  #else nothing. try another file
done



echo -n "searching student $2 for exercise $3 "

#-I baraye vaghtie ke dobare barname ro ba script run mikonim.
fileName=`ls -1 "$source".* 2>/dev/null`
if [ ! -s "$fileName" ]; then
  #realy not found any valid file. say goodbye
  echo "... Not Found!"
  echo "\$SRC[$2][$3]=2;" >> "$5"
  echo "error in `basename $0`: source file not exists. [$source.?]" >> $6
  exit 4
fi
echo -n ": "`basename $fileName`" ...";

#move to standard name for cheats and other benefits
mv "$fileName" "$source.$4" &>/dev/null

case $4 in
  Pascal) compile="/usr/bin/fpc -So -v0 -Fe/dev/null $source.$4";;
  C) compile="/usr/bin/gcc -x c -std=c99 -lm $source.$4 -o $source";;
  CPP) compile="/usr/bin/g++ -x c++ -lm $source.$4 -o $source";;
  *) echo "ERROR: unknown language format $4" >> $6
esac

echo "$source.$4" > "$1/$2/out/$3.filename"
( exec $compile )2>"$1/$2/out/$3.error"

if [ $? -eq 0 ]; then
  echo "\$SRC[$2][$3]=0;" >> "$5"
  echo "Compiled Successfully."
  rm -f `dirname $source`/*.o "$1/$2/out/$3.error"
  exit 0
else
  echo "\$SRC[$2][$3]=1;" >> "$5"
  echo "Compile error"
  exit 6
fi
