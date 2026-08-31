#!/usr/bin/env python3
"""
Merge TravianZ Arabic (ar.php) into Novaterra ar.php.
For every constant defined in en.php that exists in TravianZ ar.php,
apply TravianZ's Arabic text. Copy $lang array from TravianZ.
Preserve Novaterra-only keys already translated in current ar.php.
"""
import re
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
REF_AR = Path(
    r"C:\Users\menam\.cursor\projects\c-Users-menam-OneDrive-Desktop-tatar"
    r"\_travianz_ref\GameEngine\Lang\ar.php"
)
CUR_AR = ROOT / "GameEngine" / "Lang" / "ar.php"
CUR_EN = ROOT / "GameEngine" / "Lang" / "en.php"
REPORT = ROOT / "scripts" / "ar_merge_report.txt"

TZ_DEF_RE = re.compile(
    r"tz_def\s*\(\s*'([^']+)'\s*,\s*(.+?)\s*\)\s*;",
    re.MULTILINE | re.DOTALL,
)


def parse_tz_defs_raw(path: Path) -> dict[str, str]:
    """Store full RHS expression for each tz_def key."""
    content = path.read_text(encoding="utf-8", errors="replace")
    defs: dict[str, str] = {}
    for m in TZ_DEF_RE.finditer(content):
        key = m.group(1)
        rhs = m.group(2).strip()
        defs[key] = rhs
    return defs


def parse_simple_strings(path: Path) -> dict[str, str]:
    """Parse only simple quoted string values."""
    content = path.read_text(encoding="utf-8", errors="replace")
    pattern = re.compile(r"tz_def\s*\(\s*'([^']+)'\s*,\s*'((?:\\'|[^'])*)'\s*\)")
    return {m.group(1): m.group(2).replace("\\'", "'") for m in pattern.finditer(content)}


def replace_or_add_tz_defs(content: str, ref_raw: dict[str, str], en_keys: set[str]) -> tuple[str, int, int]:
    updated = 0
    added = 0
    existing_keys = set(TZ_DEF_RE.findall(content))

    def repl(m):
        nonlocal updated
        key = m.group(1)
        if key not in en_keys or key not in ref_raw:
            return m.group(0)
        new_rhs = ref_raw[key]
        if m.group(2).strip() != new_rhs:
            updated += 1
        return f"tz_def('{key}', {new_rhs});"

    content = TZ_DEF_RE.sub(repl, content)

    missing = [k for k in sorted(en_keys) if k in ref_raw and k not in existing_keys]
    if missing:
        block = "\n// === TravianZ merge: added keys ===\n"
        for k in missing:
            block += f"tz_def('{k}', {ref_raw[k]});\n"
            added += 1
        lang_idx = content.find("$lang['index']")
        if lang_idx == -1:
            lang_idx = content.find("$lang[")
        if lang_idx != -1:
            content = content[:lang_idx] + block + content[lang_idx:]
        else:
            content += block

    return content, updated, added


def merge_lang_section(content: str, ref_content: str) -> str:
    ref_idx = ref_content.find("$lang['index']")
    cur_idx = content.find("$lang['index']")
    if ref_idx == -1 or cur_idx == -1:
        return content
    return content[:cur_idx] + ref_content[ref_idx:]


def main():
    ref_content = REF_AR.read_text(encoding="utf-8", errors="replace")
    cur_content = CUR_AR.read_text(encoding="utf-8", errors="replace")
    ref_raw = parse_tz_defs_raw(REF_AR)
    en_simple = parse_simple_strings(CUR_EN)
    en_keys = set(en_simple.keys()) | set(parse_tz_defs_raw(CUR_EN).keys())

    merged, updated, added = replace_or_add_tz_defs(cur_content, ref_raw, en_keys)
    merged = merge_lang_section(merged, ref_content)

    CUR_AR.write_text(merged, encoding="utf-8")

    ref_simple = parse_simple_strings(REF_AR)
    cur_after = parse_simple_strings(CUR_AR)
    still_english = []
    for k in sorted(en_keys):
        if k in ref_simple and k in cur_after:
            if cur_after[k] == en_simple.get(k):
                still_english.append(k)
        elif k not in cur_after and k not in ref_raw:
            still_english.append(k)

    report_lines = [
        f"English keys: {len(en_keys)}",
        f"TravianZ ar raw defs: {len(ref_raw)}",
        f"Updated tz_def lines: {updated}",
        f"Added tz_def lines: {added}",
        f"Still matching English (no TravianZ ar): {len(still_english)}",
        "",
        "Still English (first 40):",
        "\n".join(f"  {k}: {en_simple.get(k, '')[:60]}" for k in still_english[:40]),
    ]
    REPORT.write_text("\n".join(report_lines), encoding="utf-8")
    print("\n".join(report_lines[:6]))


if __name__ == "__main__":
    main()
