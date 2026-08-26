# -*- coding: utf-8 -*-
from pathlib import Path
import re

block = (
    "div#dynamic_header, \n"
    "body.mod1 div#dynamic_header, \n"
    "body.mod3 div#dynamic_header {\n"
    "\tbackground: transparent url(../images/artwork1-ar.jpg?v7) no-repeat center top;\n"
    "\tbackground-size: 100% 100%;\n"
    "}"
)

for pack in ["novaterra_classic", "novaterra", "novaterra_t4"]:
    p = Path(rf"c:\Users\menam\OneDrive\Desktop\tatar\gpack\{pack}\modules\new_images.css")
    t = p.read_text(encoding="utf-8")
    t2 = re.sub(
        r"div#dynamic_header,\s*body\.mod1 div#dynamic_header,\s*body\.mod3 div#dynamic_header \{[^}]*\}",
        block,
        t,
        count=1,
        flags=re.S,
    )
    p.write_text(t2, encoding="utf-8")

rtl_block = (
    "/* Seamless full-width Arabic banner (art composited onto grass strip). */\n"
    'html[dir="rtl"] #dynamic_header {\n'
    '    background: transparent url("../gpack/novaterra_classic/images/artwork1-ar.jpg?v7")'
    " no-repeat center top !important;\n"
    "    background-size: 100% 100% !important;\n"
    "    height: 100px !important;\n"
    "    background-color: transparent !important;\n"
    "}\n"
    'html[dir="rtl"] .wrapper {\n'
    "    background-image: none !important;\n"
    "    background-color: #eaf4fb !important;\n"
    "}\n"
)

rtl = Path(r"c:\Users\menam\OneDrive\Desktop\tatar\css\rtl.css")
rt = rtl.read_text(encoding="utf-8")
start = rt.find("/* Arabic banner:")
if start < 0:
    start = rt.find('html[dir="rtl"] #dynamic_header')
end = rt.find('html[dir="rtl"] #header,', start)
rtl.write_text(rt[:start] + rtl_block + "\n" + rt[end:], encoding="utf-8")

for pack in ["novaterra_classic", "novaterra", "novaterra_t4"]:
    p = Path(rf"c:\Users\menam\OneDrive\Desktop\tatar\gpack\{pack}\lang\en\lang.css")
    t = p.read_text(encoding="utf-8")
    p.write_text(re.sub(r"new_images\.css\?v\d+", "new_images.css?v7", t), encoding="utf-8")

cfg = Path(r"c:\Users\menam\OneDrive\Desktop\tatar\GameEngine\config.php")
ct = cfg.read_text(encoding="utf-8")
cfg.write_text(ct.replace("rtl47", "rtl48"), encoding="utf-8")

for name in ["login.php", "dorf1.php", "dorf2.php", "anmelden.php"]:
    p = Path(rf"c:\Users\menam\OneDrive\Desktop\tatar\{name}")
    t = p.read_text(encoding="utf-8")
    p.write_text(t.replace("lang.css?v6banner", "lang.css?v7banner"), encoding="utf-8")

print("v7 css done")
