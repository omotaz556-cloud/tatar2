# -*- coding: utf-8 -*-
from pathlib import Path
import re

block = (
    "div#dynamic_header, \n"
    "body.mod1 div#dynamic_header, \n"
    "body.mod3 div#dynamic_header {\n"
    "\tbackground: transparent url(../images/artwork1-ar.jpg?v9) no-repeat center top;\n"
    "\tbackground-size: 1099px 100px;\n"
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
    print("css", pack, t != t2)

    lp = Path(rf"c:\Users\menam\OneDrive\Desktop\tatar\gpack\{pack}\lang\en\lang.css")
    lt = lp.read_text(encoding="utf-8")
    lp.write_text(re.sub(r"new_images\.css\?v\d+", "new_images.css?v9", lt), encoding="utf-8")

cfg = Path(r"c:\Users\menam\OneDrive\Desktop\tatar\GameEngine\config.php")
ct = cfg.read_text(encoding="utf-8")
cfg.write_text(re.sub(r"rtl\.css\?rtl\d+", "rtl.css?rtl50", ct), encoding="utf-8")
print("rtl50", "rtl50" in cfg.read_text(encoding="utf-8"))

for name in ["login.php", "dorf1.php", "dorf2.php", "anmelden.php"]:
    p = Path(rf"c:\Users\menam\OneDrive\Desktop\tatar\{name}")
    t = p.read_text(encoding="utf-8")
    t2 = re.sub(r"lang\.css\?[^\s\"']+", "lang.css?v9banner", t)
    p.write_text(t2, encoding="utf-8")
    print(name, "ok")
