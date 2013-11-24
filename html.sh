# This needs netpbm and hevea from Darwin Ports

rm -rf html
mkdir html
rm html-rise.tex
sed 's"\\slash "/"g' < rise.tex | sed 's"\\- "-"g' | sed "s/\`\`/\"/g" | sed "s/\'\'/\"/g" | sed s/---/--/g > html-rise.tex
hevea -O -e htmlonly png.hva html-rise.tex
hevea -O -e htmlonly png.hva html-rise.tex
# the following line is a kludge to prevent imagen from seeing
# the definitions in latexonly
# grep -v latexonly book.image.tex > a; mv a book.image.tex
# imagen -png book
# hacha book.html
mv html-rise.html rise.html
rm html-rise*

echo " "
echo "Patching the HTML ..."
python fixhtml.py
