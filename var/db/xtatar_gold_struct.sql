-- #############################################################################
-- ##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-              ##
-- ## ------------------------------------------------------------------------ ##
-- ##  Filename       : xtatar_gold_struct.sql                                 ##
-- ##  Purpose        : Schema for the X-Tatar activity-based FREE gold        ##
-- ##                    system. Lives in EACH world's own database (unlike    ##
-- ##                    central_gold_struct.sql, which is one shared DB) —    ##
-- ##                    free gold is earned per-world and is intentionally    ##
-- ##                    NOT portable across worlds. See                      ##
-- ##                    GameEngine/CentralGold.php's file header for why.     ##
-- ##  Developed by   : Shadow                                                 ##
-- ##  Project        : Novaterra                                              ##
-- ##  License        : Novaterra Project                                     ##
-- ##  Copyright      : Novaterra (c) 2010-2026. All rights reserved.          ##
-- ## ------------------------------------------------------------------------ ##
-- ##  Run this ONCE against each world's own database, the same way you'd    ##
-- ##  run any other Novaterra migration file.                                ##
-- #############################################################################

-- Master settings row, controlled from the admin panel (?p=xtatarGold):
--   enabled              -> master on/off switch for the whole feature.
--   points_per_gold      -> how many activity points convert into 1 gold.
--   daily_login_points   -> points granted for a player's first login of the
--                           day (0 disables this specific source).
--   daily_cap_points     -> maximum points a single player can earn per day
--                           across ALL sources (0 = uncapped).
--   webhook_secret       -> shared secret X-Tatar.com sends when it posts
--                           activity points in from outside the game (see
--                           xtatar_gold_webhook.php). Blank = webhook path
--                           rejects everything, in-game sources still work.
CREATE TABLE IF NOT EXISTS `xtatar_gold_settings` (
  `id`                 tinyint(1) NOT NULL DEFAULT 1,
  `enabled`            tinyint(1) NOT NULL DEFAULT 1,
  `points_per_gold`    int(11) NOT NULL DEFAULT 100,
  `daily_login_points` int(11) NOT NULL DEFAULT 5,
  `daily_cap_points`   int(11) NOT NULL DEFAULT 0,
  `webhook_secret`     varchar(128) NOT NULL DEFAULT '',
  `updated`            int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `xtatar_gold_settings`
  (`id`, `enabled`, `points_per_gold`, `daily_login_points`, `daily_cap_points`, `webhook_secret`, `updated`)
VALUES (1, 1, 100, 5, 500, '', UNIX_TIMESTAMP());

-- Running point balance per player. Points accumulate here and are converted
-- to gold (credited into users.gold) in whole-gold chunks as soon as enough
-- points have built up; the leftover remainder stays here so no fractional
-- gold is ever lost between conversions.
CREATE TABLE IF NOT EXISTS `xtatar_gold_points` (
  `uid`            int(11) NOT NULL,
  `points`         int(11) NOT NULL DEFAULT 0,
  `total_earned`   int(11) NOT NULL DEFAULT 0,
  `total_converted_gold` int(11) NOT NULL DEFAULT 0,
  `last_login_award_date` date DEFAULT NULL,
  `points_today`   int(11) NOT NULL DEFAULT 0,
  `points_today_date` date DEFAULT NULL,
  `updated`        int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Full audit trail: every point award and every points->gold conversion.
-- `source` distinguishes in-game sources (daily_login, ...) from the
-- external X-Tatar.com webhook (source = 'xtatar_web:<event-name>').
CREATE TABLE IF NOT EXISTS `xtatar_gold_log` (
  `id`         int(11) NOT NULL AUTO_INCREMENT,
  `uid`        int(11) NOT NULL,
  `type`       enum('points_awarded','gold_converted','admin_adjust') NOT NULL,
  `points`     int(11) NOT NULL DEFAULT 0,
  `gold`       int(11) NOT NULL DEFAULT 0,
  `source`     varchar(64) NOT NULL DEFAULT '',
  `note`       varchar(255) NOT NULL DEFAULT '',
  `admin_id`   int(11) NOT NULL DEFAULT 0,
  `time`       int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `time` (`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
