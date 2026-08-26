# -*- coding: utf-8 -*-
from pathlib import Path

rtl = Path(r"c:\Users\menam\OneDrive\Desktop\tatar\css\rtl.css")
text = rtl.read_text(encoding="utf-8")
text2 = text.replace("artwork1-ar.jpg?v3", "artwork1-ar.jpg?v4")
# Only replace the dynamic_header cover rule near artwork1-ar
needle = (
    'url("../gpack/novaterra_classic/images/artwork1-ar.jpg?v4") '
    "no-repeat center top !important;\n"
    "    background-size: cover !important;"
)
repl = (
    'url("../gpack/novaterra_classic/images/artwork1-ar.jpg?v4") '
    "no-repeat center top !important;\n"
    "    background-size: 100% 100% !important;"
)
if needle in text2:
    text2 = text2.replace(needle, repl, 1)
    print("replaced cover->100%")
else:
    print("needle missing")
    idx = text2.find("artwork1-ar.jpg?v4")
    print(repr(text2[idx : idx + 180]))
rtl.write_text(text2, encoding="utf-8")

lang = Path(
    r"c:\Users\menam\OneDrive\Desktop\tatar\gpack\novaterra_classic\lang\en\lang.css"
)
lt = lang.read_text(encoding="utf-8")
lang.write_text(lt.replace("new_images.css?v3", "new_images.css?v4"), encoding="utf-8")
print("done")
