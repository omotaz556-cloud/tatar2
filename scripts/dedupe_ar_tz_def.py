#!/usr/bin/env python3
"""Remove duplicate tz_def() entries from ar.php — keep first occurrence."""
import re
from pathlib import Path

AR = Path(__file__).resolve().parents[1] / "GameEngine" / "Lang" / "ar.php"
TZ_LINE = re.compile(r"^\s*tz_def\s*\(\s*'([^']+)'\s*,")

content = AR.read_text(encoding="utf-8")
lines = content.splitlines(keepends=True)
seen: set[str] = set()
out: list[str] = []
removed = 0
in_merge_block = False
skip_merge_block = False

for line in lines:
    if "// === TravianZ merge: added keys ===" in line:
        in_merge_block = True
        if skip_merge_block:
            continue
        out.append(line)
        continue
    if in_merge_block and line.startswith("$lang"):
        in_merge_block = False

    m = TZ_LINE.match(line)
    if m:
        key = m.group(1)
        if key in seen:
            removed += 1
            continue
        seen.add(key)

out_text = "".join(out)
AR.write_text(out_text, encoding="utf-8")
print(f"Removed {removed} duplicate tz_def lines. Unique keys: {len(seen)}")
