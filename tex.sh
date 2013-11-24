#! /bin/sh

latex rise
makeindex rise
latex rise
dvipdf rise.dvi rise.pdf
open rise.pdf
echo Removed temporary files
rm -f rise.aux rise.ind rise.ilg rise.log rise.dvi rise.idx rise.toc rise.haux rise.hind rise.image.tex
