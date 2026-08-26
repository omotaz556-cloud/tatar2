<?php

#################################################################################
##  Natars spawn schedule helpers (admin reset can restart the countdown).     ##
#################################################################################

if (!function_exists('tz_natars_timer_base_file')) {
	/**
	 * Absolute path to the optional Natars countdown base timestamp file.
	 */
	function tz_natars_timer_base_file(): string
	{
		return dirname(__DIR__) . '/var/natars_timer_base';
	}

	/**
	 * Unix timestamp from which Natars/WW/plan spawn delays are measured.
	 * Defaults to START_DATE when no admin reset has been stored.
	 */
	function tz_natars_timer_base(): int
	{
		$file = tz_natars_timer_base_file();
		if (is_readable($file)) {
			$raw = trim((string) file_get_contents($file));
			if ($raw !== '' && ctype_digit($raw)) {
				return (int) $raw;
			}
		}

		return defined('START_DATE') ? (int) strtotime(START_DATE) : time();
	}

	/**
	 * Persist a new Natars countdown base (used by admin "reset Natars").
	 */
	function tz_natars_set_timer_base(int $timestamp): bool
	{
		$dir = dirname(tz_natars_timer_base_file());
		if (!is_dir($dir)) {
			@mkdir($dir, 0755, true);
		}

		return @file_put_contents(tz_natars_timer_base_file(), (string) $timestamp, LOCK_EX) !== false;
	}

	/**
	 * Absolute spawn unix time for a Natars-related delay (days config value).
	 * Matches AutomationNatarsWW: base + days * 86400 / SPEED.
	 */
	function tz_natars_spawn_at(int $days): int
	{
		$speed = (defined('SPEED') && (float) SPEED > 0) ? (float) SPEED : 1.0;

		return tz_natars_timer_base() + (int) ($days * 86400 / $speed);
	}
}
