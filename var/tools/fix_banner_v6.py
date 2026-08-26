# -*- coding: utf-8 -*-
from pathlib import Path
from PIL import Image
import re

src = Image.open(
    r"C:\Users\menam\.cursor\projects\c-Users-menam-OneDrive-Desktop-tatar\assets"
    r"\c__Users_menam_AppData_Roaming_Cursor_User_workspaceStorage_"
    r"65e9861a8b4cb2bb0c0f93a944d00731_images_image-e7f237c3-08d9-4b39-bc16-fddd6ebf25c6.png"
).convert("RGB")
w, h = src.size


def row_white(y, thr=250):
    return all(all(c >= thr for c in src.getpixel((x, y))) for x in range(0, w, 8))


top = 0
while top < h - 10 and row_white(top):
    top += 1
core = src.crop((0, top, w, h)).resize((1099, 99), Image.Resampling.LANCZOS)

for d in [
    Path(r"c:\Users\menam\OneDrive\Desktop\tatar\gpack\novaterra_classic\images"),
    Path(r"c:\Users\menam\OneDrive\Desktop\tatar\gpack\novaterra_t4\images"),
    Path(r"c:\Users\menam\OneDrive\Desktop\tatar\gpack\novaterra\images"),
    Path(r"c:\Users\menam\OneDrive\Desktop\tatar\GameEngine\images"),
]:
    core.save(d / "artwork1-ltr.jpg", format="JPEG", quality=95, optimize=True)
    core.save(d / "artwork1-ar.jpg", format="JPEG", quality=95, optimize=True)
print("core", core.size)

block = """div#dynamic_header, 
body.mod1 div#dynamic_header, 
body.mod3 div#dynamic_header {
	background: transparent url(../images/artwork1-ar.jpg?v6) no-repeat center top;
	background-size: 1099px 100px;
}"""

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
    print("css", pack, t2 != t)

rtl = Path(r"c:\Users\menam\OneDrive\Desktop\tatar\css\rtl.css")
rt = rtl.read_text(encoding="utf-8")
rtl_block = """/* Arabic banner: classic Travian 1099x100 centered on grass strip. */
html[dir="rtl"] #dynamic_header {
    background: transparent url("../gpack/novaterra_classic/images/artwork1-ar.jpg?v6") no-repeat center top !important;
    background-size: 1099px 100px !important;
    height: 100px !important;
    background-color: transparent !important;
}
html[dir="rtl"] .wrapper {
    background: transparent url("../gpack/novaterra_classic/images/header_background.jpg") repeat-x left top !important;
}
"""
rt2, n = re.subn(
    r"/\* (?:Edge-to-edge|Arabic) banner[\s\S]*?html\[dir=\"rtl\"\] \.wrapper \{[\s\S]*?\n\}\n",
    rtl_block,
    rt,
    count=1,
)
if n == 0:
    # fallback: replace from comment through wrapper block starting at artwork1-ar
    start = rt.find("/* Edge-to-edge")
    if start < 0:
        start = rt.find("html[dir=\"rtl\"] #dynamic_header")
    end = rt.find("html[dir=\"rtl\"] #header,", start)
    if start >= 0 and end > start:
        rt2 = rt[:start] + rtl_block + "\n" + rt[end:]
        n = 1
print("rtl replacements", n)
rtl.write_text(rt2, encoding="utf-8")

for pack in ["novaterra_classic", "novaterra", "novaterra_t4"]:
    p = Path(rf"c:\Users\menam\OneDrive\Desktop\tatar\gpack\{pack}\lang\en\lang.css")
    t = p.read_text(encoding="utf-8")
    t = re.sub(r"new_images\.css\?v\d+", "new_images.css?v6", t)
    p.write_text(t, encoding="utf-8")

cfg = Path(r"c:\Users\menam\OneDrive\Desktop\tatar\GameEngine\config.php")
ct = cfg.read_text(encoding="utf-8")
ct = re.sub(r"rtl\.css\?rtl\d+", "rtl.css?rtl46", ct)
cfg.write_text(ct, encoding="utf-8")
print("done v6")
