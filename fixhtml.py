from BeautifulSoup import *

filename="rise.html"

fhand = open(filename)
html = fhand.read()
fhand.close()
html = html.replace('``','"')
html = html.replace("''",'"')
soup = BeautifulSoup(html)
html = str(soup)
open(filename,"w").write(html)
print filename, len(html)
