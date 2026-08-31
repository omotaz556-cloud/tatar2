#!/usr/bin/env python3
import re
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
AR = ROOT / "GameEngine" / "Lang" / "ar.php"
EN = ROOT / "GameEngine" / "Lang" / "en.php"

def parse_simple(path):
    c = path.read_text(encoding="utf-8", errors="replace")
    pat = re.compile(r"tz_def\s*\(\s*'([^']+)'\s*,\s*'((?:\\'|[^'])*)'\s*\)")
    return {m.group(1): m.group(2) for m in pat.finditer(c)}

en = parse_simple(EN)
ar = parse_simple(AR)
missing = sorted(set(en) - set(ar))
still_en = [k for k in en if k in ar and ar[k] == en[k]]
print(f"en keys: {len(en)}, ar keys: {len(ar)}")
print(f"missing in ar: {len(missing)}")
print(f"still english: {len(still_en)}")
if missing:
    print("missing sample:", missing[:15])
if still_en:
    print("english sample:", still_en[:15])
