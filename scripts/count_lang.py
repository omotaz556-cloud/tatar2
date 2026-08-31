import re
from pathlib import Path
LANG_RE = re.compile(r"^\s*\$lang(?:\[[^\]]+\])+\s*=")
for name, p in [
    ("en", Path(r"c:\Users\menam\OneDrive\Desktop\tatar\GameEngine\Lang\en.php")),
    ("ref", Path(r"C:\Users\menam\.cursor\projects\c-Users-menam-OneDrive-Desktop-tatar\_travianz_ref\GameEngine\Lang\ar.php")),
    ("out", Path(r"c:\Users\menam\OneDrive\Desktop\tatar\GameEngine\Lang\ar.php")),
]:
    n = sum(1 for l in p.read_text(encoding="utf-8").splitlines() if LANG_RE.match(l))
    print(name, n)
