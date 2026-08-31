<?php

/**
 * Travian ribbon medal assets for Greek profile (35×48 webp from gpack img/t).
 * Banner files (t10_2 scene art, Veteran_Medal.jpg, MH.png, …) must not be used here.
 */
final class GreekMedalAssets
{
    /** Logical / BB-code key => img/t basename without extension */
    public const MAP = [
        '0'           => 'tn',
        'g2300'       => 't10_1',
        'g2301'       => 't200_1',
        'g2302'       => 't210_1',
        'roman'       => 't224_1',
        'teuton'      => 't223_1',
        'gaul'        => 't222_1',
        'huns'        => 't222_1',
        'egyptians'   => 't223_1',
        'spartans'    => 't224_1',
        'vikings'     => 't222_1',
        'multihunter' => 't6_1',
        'mh'          => 't6_2',
        'team'        => 't6_3',
    ];

    /** img/t files that are wide banners — never use in profile medal wall/table */
    public const BANNERS = [
        't10_2',
        't10_3',
    ];

    public static function ext(string $base): string
    {
        if ($base === 'tn' || $base === 'tnd') {
            return 'gif';
        }
        return 'webp';
    }

    public static function basename(string $key): ?string
    {
        $key = strtolower(trim($key));
        return self::MAP[$key] ?? null;
    }

    public static function url(string $gpack, string $keyOrBase, bool $allowBanner = false): string
    {
        $base = self::MAP[strtolower($keyOrBase)] ?? preg_replace('/[^a-zA-Z0-9_.-]/', '', $keyOrBase);
        if ($base === '') {
            return '';
        }
        if (!$allowBanner && in_array($base, self::BANNERS, true)) {
            return '';
        }
        return $gpack . 'img/t/' . $base . '.' . self::ext($base);
    }

    public static function tribeBasename(int $tribe): ?string
    {
        $map = [
            1 => 't224_1',
            2 => 't223_1',
            3 => 't222_1',
            6 => 't222_1',
            7 => 't223_1',
            8 => 't224_1',
            9 => 't222_1',
        ];
        return $map[$tribe] ?? null;
    }

    public static function extraClass(string $keyOrBase): string
    {
        $key = strtolower($keyOrBase);
        if ($key === '0') {
            return 'gk-medal-bird';
        }
        if (in_array($key, ['roman', 'teuton', 'gaul', 'huns', 'egyptians', 'spartans', 'vikings'], true)) {
            return 'gk-medal-tribe';
        }
        if (in_array($key, ['g2300', 'g2301', 'g2302', 'multihunter', 'mh', 'team', 'artefact', 'wwbuilder', 'winnerww', 'greatstore', 'wallmaster', 'hero100'], true)) {
            return 'gk-medal-special';
        }
        return 'gk-inline-medal';
    }
}
