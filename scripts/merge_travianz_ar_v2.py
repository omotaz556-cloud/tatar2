#!/usr/bin/env python3
"""
Safe TravianZ Arabic merge: walk en.php line-by-line, apply TravianZ ar values.
Preserves file structure (tz_def / $lang interleaving). Never drops lines.
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
OUT_AR = CUR_AR
REPORT = ROOT / "scripts" / "ar_merge_report.txt"

TZ_DEF_RE = re.compile(
    r"^(?P<pre>\s*)tz_def\s*\(\s*'(?P<key>[^']+)'\s*,\s*(?P<rhs>.*?)\s*\)\s*;\s*$"
)
LANG_RE = re.compile(
    r"^(?P<pre>\s*)\$lang(?P<path>(?:\[[^\]]+\])+)\s*=\s*(?P<rhs>.*?);\s*$"
)


def parse_tz_defs(path: Path) -> dict[str, str]:
    defs: dict[str, str] = {}
    for line in path.read_text(encoding="utf-8", errors="replace").splitlines():
        m = TZ_DEF_RE.match(line)
        if m:
            defs[m.group("key")] = m.group("rhs").strip()
    return defs


def parse_lang_lines(path: Path) -> dict[str, str]:
    langs: dict[str, str] = {}
    for line in path.read_text(encoding="utf-8", errors="replace").splitlines():
        m = LANG_RE.match(line)
        if m:
            langs[m.group("path")] = m.group("rhs").strip()
    return langs


def main():
    ref_tz = parse_tz_defs(REF_AR)
    cur_tz = parse_tz_defs(CUR_AR)
    ref_lang = parse_lang_lines(REF_AR)
    cur_lang = parse_lang_lines(CUR_AR)

    en_lines = CUR_EN.read_text(encoding="utf-8", errors="replace").splitlines()
    out: list[str] = []
    tz_updated = 0
    tz_from_ref = 0
    tz_from_cur = 0
    lang_updated = 0
    lang_from_ref = 0
    still_english_tz: list[str] = []

    en_tz_simple = {}
    for line in en_lines:
        m = TZ_DEF_RE.match(line)
        if m and m.group("rhs").strip().startswith("'") and m.group("rhs").strip().endswith("'"):
            en_tz_simple[m.group("key")] = m.group("rhs").strip()[1:-1].replace("\\'", "'")

    for line in en_lines:
        m = TZ_DEF_RE.match(line)
        if m:
            key = m.group("key")
            pre = m.group("pre")
            en_rhs = m.group("rhs").strip()
            if key in ref_tz:
                rhs = ref_tz[key]
                tz_from_ref += 1
            elif key in cur_tz:
                rhs = cur_tz[key]
                tz_from_cur += 1
            else:
                rhs = en_rhs
            if rhs != en_rhs:
                tz_updated += 1
            out.append(f"{pre}tz_def('{key}', {rhs});")
            if key in en_tz_simple and rhs.strip().startswith("'"):
                ar_val = rhs.strip()[1:-1].replace("\\'", "'")
                if ar_val == en_tz_simple.get(key):
                    still_english_tz.append(key)
            continue

        m = LANG_RE.match(line)
        if m:
            path = m.group("path")
            pre = m.group("pre")
            en_rhs = m.group("rhs").strip()
            if path in ref_lang:
                rhs = ref_lang[path]
                lang_from_ref += 1
            elif path in cur_lang:
                rhs = cur_lang[path]
            else:
                rhs = en_rhs
            if rhs != en_rhs:
                lang_updated += 1
            out.append(f"{pre}$lang{path} = {rhs};")
            continue

        out.append(line)

    OUT_AR.write_text("\n".join(out) + "\n", encoding="utf-8")

    report = [
        f"Output lines: {len(out)}",
        f"tz_def from TravianZ: {tz_from_ref}",
        f"tz_def from current ar: {tz_from_cur}",
        f"tz_def value changes vs en: {tz_updated}",
        f"$lang from TravianZ: {lang_from_ref}",
        f"$lang value changes vs en: {lang_updated}",
        f"Simple tz_def still English: {len(still_english_tz)}",
        "",
        "Still English (first 50):",
        "\n".join(f"  {k}" for k in still_english_tz[:50]),
    ]
    REPORT.write_text("\n".join(report), encoding="utf-8")
    print("\n".join(report[:8]))


if __name__ == "__main__":
    main()
