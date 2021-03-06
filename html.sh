# This needs netpbm and hevea from Darwin Ports

rm -rf html/*
rm html-rise*
rm html-rise.tex
sed 's"\\slash "/"g' < rise.tex | sed 's"\\- "-"g' | sed "s/\`\`/\"/g" | sed "s/\'\'/\"/g" | sed s/---/--/g > html-rise.tex

# run twice to get references
hevea -O -e latexonly htmlonly png.hva html-rise.tex
hevea -O -e latexonly htmlonly png.hva html-rise.tex

imagen -png html-rise

# Tweak output HTML for glitches
sed 's/<DT><FONT SIZE=5>/<dt>/g' < html-rise.html | sed 's"</A></FONT><DD>"</a><dd>"g' | sed 's/^3$//' | sed 's/COLOR=purple/face="sans-serif"/g' | sed '/^<IMG/s/$/<br>/' > rise.html

echo " "
echo "Running BeautifulSoup on the HTML ..."
python fixhtml.py

mv html-rise*png html
mv rise.html html

rm html-rise*
rm html.zip
zip -r html.zip html

echo This is non-fatal apparently: Command not found: endsf

