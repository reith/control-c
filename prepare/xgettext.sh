cd `dirname $0`
xgettext -k_ --msgid-bugs-address="Ameretat.Reith@gmail.com" --package-name="^C" --package-version=1.0 -f locale_files.lst -j --from-code utf-8 -d cc -o cc.pot -L php --no-wrap
