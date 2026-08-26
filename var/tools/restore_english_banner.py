# -*- coding: utf-8 -*-
from pathlib import Path
import re

classic_bak = Path(
    r"c:\Users\menam\OneDrive\Desktop\tatar\gpack\novaterra_classic\images\artwork1-ltr.jpg.bak"
)
if not classic_bak.exists():
    raise SystemExit("missing bak")

src_bytes = classic_bak.read_bytes()
for p in [
    Path(r"c:\Users\menam\OneDrive\Desktop\tatar\gpack\novaterra_classic\images\artwork1-ltr.jpg"),
    Path(r"c:\Users\menam\OneDrive\Desktop\tatar\gpack\novaterra_t4\images\artwork1-ltr.jpg"),
    Path(r"c:\Users\menam\OneDrive\Desktop\tatar\gpack\novaterra\images\artwork1-ltr.jpg"),
    Path(r"c:\Users\menam\OneDrive\Desktop\tatar\GameEngine\images\artwork1-ltr.jpg"),
]:
    p.write_bytes(src_bytes)
    print("restored", p)

nov = Path(r"c:\Users\menam\OneDrive\Desktop\tatar\gpack\novaterra\images")
for name in ["logo_background.jpg", "logo_background-ltr.jpg"]:
    bak = nov / (name + ".bak")
    if bak.exists():
        data = bak.read_bytes()
        for pack in ["novaterra", "novaterra_classic", "novaterra_t4"]:
            dst = Path(rf"c:\Users\menam\OneDrive\Desktop\tatar\gpack\{pack}\images\{name}")
            if dst.parent.exists():
                dst.write_bytes(data)
                print("logo", pack, name)

block = (
    "div#dynamic_header, \n"
    "body.mod1 div#dynamic_header, \n"
    "body.mod3 div#dynamic_header {\n"
    "\tbackground: transparent url(../images/artwork1-ltr.jpg?en1) no-repeat center top;\n"
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

    lp = Path(rf"c:\Users\menam\OneDrive\Desktop\tatar\gpack\{pack}\lang\en\lang.css")
    lt = lp.read_text(encoding="utf-8")
    lp.write_text(re.sub(r"new_images\.css\?[^\s\"']+", "new_images.css?en1", lt), encoding="utf-8")
    print("css", pack)

rtl = Path(r"c:\Users\menam\OneDrive\Desktop\tatar\css\rtl.css")
rt = rtl.read_text(encoding="utf-8")
rt2, n = re.subn(
    r"/\* Arabic header banner only[\s\S]*?background-color: transparent !important;\n\}\n\n",
    "",
    rt,
    count=1,
)
if n == 0:
    rt2, n = re.subn(
        r'html\[dir="rtl"\] #dynamic_header \{\n(?:.*\n)*?    background-color: transparent !important;\n\}\n\n',
        "",
        rt,
        count=1,
    )
rtl.write_text(rt2, encoding="utf-8")
print("rtl cleaned", n)

cfg = Path(r"c:\Users\menam\OneDrive\Desktop\tatar\GameEngine\config.php")
ct = cfg.read_text(encoding="utf-8")
cfg.write_text(re.sub(r"rtl\.css\?rtl\d+", "rtl.css?rtl51", ct), encoding="utf-8")

for name in ["login.php", "dorf1.php", "dorf2.php", "anmelden.php"]:
    p = Path(rf"c:\Users\menam\OneDrive\Desktop\tatar\{name}")
    t = p.read_text(encoding="utf-8")
    p.write_text(re.sub(r"lang\.css\?[^\s\"']+", "lang.css?en1", t), encoding="utf-8")

print("done")
