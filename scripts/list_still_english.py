#!/usr/bin/env python3
"""List tz_def keys where ar.php simple string equals en.php (still English)."""
import re
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
EN = ROOT / "GameEngine" / "Lang" / "en.php"
AR = ROOT / "GameEngine" / "Lang" / "ar.php"

SIMPLE = re.compile(r"tz_def\s*\(\s*'([^']+)'\s*,\s*'((?:\\'|[^'])*)'\s*\)")

en = {m.group(1): m.group(2) for m in SIMPLE.finditer(EN.read_text(encoding="utf-8"))}
ar = {m.group(1): m.group(2) for m in SIMPLE.finditer(AR.read_text(encoding="utf-8"))}

still = [(k, en[k]) for k in sorted(en) if k in ar and ar[k] == en[k]]
for k, v in still:
    print(f"{k}|{v}")
