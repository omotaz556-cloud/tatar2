# -*- coding: utf-8 -*-
import zipfile, re, json
from pathlib import Path
z = zipfile.ZipFile(r"c:\Users\menam\OneDrive\Desktop\tatar\greek-sa-and-x-tatar.zip")
out = Path(r"c:\Users\menam\OneDrive\Desktop\tatar\_ref_village")
out.mkdir(exist_ok=True)
# extract field + building pages fully (html + _files assets for css)
want_prefixes = []
for n in z.namelist():
    if any(x in n for x in [
        "صفحة القريه ( الحقول )",
        "صفحة القريه ( المباني )",
        "صفحة القريه - الحقول",
        "صفحة القريه - المباني",
    ]):
        want_prefixes.append(n)
# also get unique top folders
folders = sorted({"/".join(n.split("/")[:2]) for n in want_prefixes if n.count("/")>=1})
print("folders:")
for f in folders: print(" ", f)
# extract all files under those folder paths
count = 0
for n in z.namelist():
    ok = False
    for f in folders:
        if n.startswith(f + "/") or n == f:
            ok = True
            break
    if not ok:
        continue
    # skip huge .download js if any - keep html css png gif jpg
    if n.endswith(".download"):
        continue
    dest = out / n
    dest.parent.mkdir(parents=True, exist_ok=True)
    if not n.endswith("/"):
        dest.write_bytes(z.read(n))
        count += 1
print("extracted", count)

# Summarize structure of each main html
summaries = []
for html in out.rglob("*.html"):
    if "saved_resource" in html.name:
        continue
    text = html.read_text(encoding="utf-8", errors="ignore")
    # strip scripts for readability sample of content area
    body = re.search(r"<body[^>]*>(.*)</body>", text, re.I|re.S)
    body_html = body.group(1) if body else text
    # find production / vlist / village_map snippets
    snips = {}
    for key in ["production", "vlist", "village_map", "map_details", "troops", "levels"]:
        m = re.search(rf'id=["\']{key}["\'][^>]*>', text, re.I)
        snips[key] = bool(m)
    # class on content
    cm = re.search(r'id=["\']content["\'][^>]*class=["\']([^"\']+)', text, re.I)
    # css links
    css = re.findall(r'href=["\']([^"\']+\.css[^"\']*)["\']', text)[:8]
    # sample village list markup if any
    vl = re.search(r'id=["\']vlist["\'].{0,800}', text, re.I|re.S)
    prod = re.search(r'id=["\']production["\'].{0,1200}', text, re.I|re.S)
    summaries.append({
        "rel": str(html.relative_to(out)),
        "title": (re.search(r"<title[^>]*>(.*?)</title>", text, re.I|re.S) or [None, ""])[1][:80] if False else re.sub(r"\s+"," ", (re.search(r"<title[^>]*>(.*?)</title>", text, re.I|re.S).group(1) if re.search(r"<title[^>]*>(.*?)</title>", text, re.I|re.S) else "")),
        "content_class": cm.group(1) if cm else None,
        "ids": snips,
        "css": css,
        "vlist_snip": re.sub(r"\s+", " ", vl.group(0)[:500]) if vl else None,
        "prod_snip": re.sub(r"\s+", " ", prod.group(0)[:700]) if prod else None,
        "len": len(text),
    })
(out / "summary.json").write_text(json.dumps(summaries, ensure_ascii=False, indent=2), encoding="utf-8")
print("summaries", len(summaries))