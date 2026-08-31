<?php

require_once __DIR__ . '/GreekMedalAssets.php';

/**
 * Profile medal wall layout: same distribution (week) side-by-side, different weeks stacked.
 */
final class GreekMedalLayout
{
    private const MEDAL_TOKEN = '/(\[(?:#?[\w]+|[\w]+#)\])/i';

  /** @var string[] */
    private const EXTRA_SPECIAL_KEYS = [
        'artefact',
        'wwbuilder',
        'winnerww',
        'greatstore',
        'wallmaster',
        'hero100',
        'shadow',
        'event',
    ];

    /** @return array<string, string> medal key => group key */
    public static function weekMapFromVarmedal(array $varmedal): array
    {
        $map = [];

        foreach (GreekMedalAssets::MAP as $key => $_) {
            $map[$key] = 'special:' . $key;
        }
        foreach (self::EXTRA_SPECIAL_KEYS as $key) {
            $map[$key] = 'special:' . $key;
        }
        foreach ($varmedal as $row) {
            $map[(string) $row['id']] = 'w' . (int) ($row['week'] ?? 0);
        }

        return $map;
    }

    public static function medalKeyFromToken(string $token): string
    {
        return strtolower(preg_replace('/^\[#?|#?\]$/', '', trim($token)));
    }

    /** @param array<string, string> $weekMap */
    public static function groupKeyForMedal(string $medalKey, array $weekMap): string
    {
        $key = strtolower($medalKey);
        if (isset($weekMap[$key])) {
            return $weekMap[$key];
        }

        return 'special:' . $key;
    }

    /**
     * Group consecutive medals with the same week/distribution onto one line.
     *
     * @param array<string, string> $weekMap
     */
    public static function layoutBbByWeekRuns(string $text, array $weekMap): string
    {
        if ($text === '') {
            return '';
        }

        $parts = preg_split(self::MEDAL_TOKEN, $text, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        if (!$parts) {
            return $text;
        }

        $rows = [];
        $currentRow = '';
        $currentGroup = null;

        foreach ($parts as $part) {
            if (preg_match(self::MEDAL_TOKEN, $part)) {
                $medalKey = self::medalKeyFromToken($part);
                $group = self::groupKeyForMedal($medalKey, $weekMap);

                if ($currentGroup !== null && $group !== $currentGroup) {
                    $rows[] = $currentRow;
                    $currentRow = '';
                }

                $currentRow .= $part;
                $currentGroup = $group;
            } else {
                $trimmed = trim($part);
                if ($trimmed === '') {
                    continue;
                }
                if ($currentRow === '' && $currentGroup === null) {
                    $rows[] = $part;
                } else {
                    $currentRow .= $part;
                }
            }
        }

        if ($currentRow !== '') {
            $rows[] = $currentRow;
        }

        return implode("\n", $rows);
    }

    /** Row delimiter in BB text before medal.php (ASCII RS — survives htmlspecialchars). */
    public const ROW_MARKER = "\x1E";

    /** Convert newline-separated BB medal rows to marker-separated (call before medal.php). */
    public static function markBbRows(string $text): string
    {
        if ($text === '') {
            return '';
        }

        $rows = [];
        foreach (preg_split('/\R/', $text) as $line) {
            $line = trim($line);
            if ($line !== '') {
                $rows[] = $line;
            }
        }

        if (!$rows) {
            return '';
        }

        return implode(self::ROW_MARKER, $rows);
    }

    /** Wrap processed medal HTML rows (call after medal.php). Never split HTML on newlines. */
    public static function wallHtmlFromMarkedRows(string $html): string
    {
        if ($html === '') {
            return '';
        }

        if (strpos($html, self::ROW_MARKER) === false) {
            return '<div class="gk-medal-row">' . $html . '</div>';
        }

        $out = '';
        foreach (explode(self::ROW_MARKER, $html) as $row) {
            $row = trim($row);
            if ($row === '') {
                continue;
            }
            $out .= '<div class="gk-medal-row">' . $row . '</div>';
        }

        return $out;
    }
}
