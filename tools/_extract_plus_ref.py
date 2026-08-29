#!/usr/bin/env python3
import re
from pathlib import Path

src = Path(r"C:\Users\menam\.cursor\projects\c-Users-menam-OneDrive-Desktop-tatar\agent-tools\380d9814-ea22-43da-884c-118f9601f8f2.txt")
out = Path(__file__).resolve().parent.parent / "_design_greek_sa" / "_analysis" / "plus_body_extract.html"
text = src.read_text(encoding="utf-8", errors="replace")
m = re.search(r'<div class="Bod"><div class="PaNa">[^<]*</div><span class="BAR5">.*?plus\?S=3.*?</table>', text, re.S)
if not m:
    for line in text.splitlines():
        if "plus?S=3" in line and "table class" in line:
            start = line.find('<div class="Bod">')
            end = line.find("</table>", start)
            if start >= 0 and end >= 0:
                chunk = line[start : end + 8]
                out.write_text(chunk, encoding="utf-8")
                print("wrote", out, "len", len(chunk))
                break
    else:
        print("not found")
else:
    out.write_text(m.group(0), encoding="utf-8")
    print("wrote", out, "len", len(m.group(0)))
