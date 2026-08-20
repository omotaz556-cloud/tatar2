#!/usr/bin/env python3
"""
Rebrand script: replaces TravianZ / Travian brand references with a
placeholder brand name across all text-based source files.

Placeholder brand: Novaterra / novaterra.example
(Client should do a final find/replace of "Novaterra" -> their chosen name
once decided, and "novaterra.example" -> their real domain.)

This script ONLY touches text content. Trademarked binary image/logo assets
were already physically removed in a separate step before this script runs.
"""
import os
import re
import sys

ROOT = "/home/claude/project/game"

NEW_NAME = "Novaterra"
NEW_NAME_LOWER = "novaterra"
NEW_DOMAIN = "novaterra.example"

TEXT_EXTENSIONS = {
    ".php", ".tpl", ".js", ".css", ".sql", ".md", ".ini",
    ".yml", ".yaml", ".json", ".txt", ".env", ".htaccess", ".sh",
    ".html", ".htm"
}

TEXT_FILENAMES = {".htaccess"}

# Ordered list of (pattern, replacement) - most specific first.
REPLACEMENTS = [
    (r"https://travianz\.org", f"https://{NEW_DOMAIN}"),
    (r"https://github\.com/Shadowss/TravianZ", f"https://github.com/YOUR-ORG/{NEW_NAME}"),
    (r"github\.com/Shadowss/TravianZ", f"github.com/YOUR-ORG/{NEW_NAME}"),
    (r"@travianz\.game", f"@{NEW_DOMAIN}"),
    (r"travianz\.game", NEW_DOMAIN),
    (r"travianz\.org", NEW_DOMAIN),
    (r"www\.travianz\.org", f"www.{NEW_DOMAIN}"),
    # Brand word, case variants (order: mixed-case first, then upper, then lower)
    (r"TravianZ", NEW_NAME),
    (r"Travianz", NEW_NAME),
    (r"TRAVIANZ", NEW_NAME.upper()),
    (r"travianz", NEW_NAME_LOWER),
]

# Generic "Travian" (without Z) mentions - handled separately/more carefully
# because some occurrences are inside translated prose (e.g. "1 day Travian").
GENERIC_TRAVIAN_REPLACEMENTS = [
    (r"\bTravian Games\b", NEW_NAME),
    (r"\bTravian Plus\b", f"{NEW_NAME} Plus"),
    (r"\bTRAVIAN\b", NEW_NAME.upper()),
    (r"\bTravian\b", NEW_NAME),
    (r"\btravian\b", NEW_NAME_LOWER),
]

def iter_text_files(root):
    for dirpath, dirnames, filenames in os.walk(root):
        if ".git" in dirpath.split(os.sep):
            continue
        for fn in filenames:
            ext = os.path.splitext(fn)[1].lower()
            if ext in TEXT_EXTENSIONS or fn.startswith(".env") or fn in TEXT_FILENAMES:
                yield os.path.join(dirpath, fn)

def process_file(path, stats):
    try:
        with open(path, "r", encoding="utf-8", errors="strict") as f:
            content = f.read()
    except (UnicodeDecodeError, PermissionError):
        stats["skipped_binary_or_unreadable"] += 1
        return

    original = content

    for pattern, repl in REPLACEMENTS:
        content = re.sub(pattern, repl, content)

    for pattern, repl in GENERIC_TRAVIAN_REPLACEMENTS:
        content = re.sub(pattern, repl, content)

    if content != original:
        with open(path, "w", encoding="utf-8") as f:
            f.write(content)
        stats["modified"] += 1
    else:
        stats["unchanged"] += 1

def main():
    stats = {"modified": 0, "unchanged": 0, "skipped_binary_or_unreadable": 0}
    files = list(iter_text_files(ROOT))
    print(f"Scanning {len(files)} text files under {ROOT}...")
    for path in files:
        process_file(path, stats)

    print("Done.")
    print(f"  Modified:  {stats['modified']}")
    print(f"  Unchanged: {stats['unchanged']}")
    print(f"  Skipped (binary/unreadable): {stats['skipped_binary_or_unreadable']}")

if __name__ == "__main__":
    main()
