-- #############################################################################
-- ##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-              ##
-- ## ------------------------------------------------------------------------ ##
-- ##  Filename       : central_gold_struct.sql                                ##
-- ##  Purpose        : Schema for the CENTRAL gold database, shared by all    ##
-- ##                    Novaterra worlds (one physical DB, separate from      ##
-- ##                    every world's own database).                         ##
-- ##  Developed by   : Shadow                                                 ##
-- ##  Project        : Novaterra                                              ##
-- ##  License        : Novaterra Project                                     ##
-- ##  Copyright      : Novaterra (c) 2010-2026. All rights reserved.          ##
-- ## ------------------------------------------------------------------------ ##
-- ##  Run this ONCE against the dedicated central-gold database (see          ##
-- ##  CENTRAL_GOLD_* constants in config.php). Every world connects to the    ##
-- ##  SAME database — do not create one of these per world.                  ##
-- #############################################################################

-- One row per real-world player, identified by e-mail (case-insensitively).
-- `paid_gold` is the single, cross-world balance described in the client
-- brief: "gold bought stays with the player if they register with the same
-- name + email on any other world". Free gold is NOT stored here — it is
-- credited directly into each world's own `users.gold`, since it is granted
-- per-world based on X-Tatar activity and is explicitly NOT meant to follow
-- the player across worlds (see central_gold_settings.free_gold_enabled).
CREATE TABLE IF NOT EXISTS `central_gold_accounts` (
  `id`         int(11) NOT NULL AUTO_INCREMENT,
  `email`      varchar(255) NOT NULL,
  `username`   varchar(100) NOT NULL DEFAULT '',
  `paid_gold`  int(11) NOT NULL DEFAULT 0,
  `created`    int(11) NOT NULL DEFAULT 0,
  `updated`    int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Every world a given central account has logged into / registered on.
-- Lets the admin panel show "this player exists on worlds X, Y, Z" and lets
-- CentralGold know which worlds to notify of a balance change (see
-- CentralGold::pushToWorld in GameEngine/CentralGold.php).
CREATE TABLE IF NOT EXISTS `central_gold_world_links` (
  `id`               int(11) NOT NULL AUTO_INCREMENT,
  `account_id`       int(11) NOT NULL,
  `world_key`        varchar(64) NOT NULL,
  `world_user_id`    int(11) NOT NULL DEFAULT 0,
  `first_seen`       int(11) NOT NULL DEFAULT 0,
  `last_seen`        int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `account_world` (`account_id`, `world_key`),
  KEY `world_key` (`world_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Full audit trail of every balance change: purchases, spends, admin grants,
-- and transfers. `balance_after` makes it possible to reconstruct/verify the
-- running balance at any point without recomputing the whole history.
CREATE TABLE IF NOT EXISTS `central_gold_ledger` (
  `id`             int(11) NOT NULL AUTO_INCREMENT,
  `account_id`     int(11) NOT NULL,
  `world_key`      varchar(64) NOT NULL DEFAULT '',
  `delta`          int(11) NOT NULL,
  `balance_after`  int(11) NOT NULL,
  `reason`         varchar(32) NOT NULL,
  `note`           varchar(255) NOT NULL DEFAULT '',
  `related_account_id` int(11) NOT NULL DEFAULT 0,
  `admin_id`       int(11) NOT NULL DEFAULT 0,
  `time`           int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`),
  KEY `time` (`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Single-row settings table the admin panel controls:
--   free_gold_enabled  -> master on/off switch for the X-Tatar free-gold
--                         feature, per client brief ("خاصية أدمن panel:
--                         تفعيل/إخفاء الذهب المجاني").
--   max_feeders_default -> fallback used by the Feeding System (separate
--                          feature) when a client-specific override isn't set.
CREATE TABLE IF NOT EXISTS `central_gold_settings` (
  `id`                  tinyint(1) NOT NULL DEFAULT 1,
  `free_gold_enabled`   tinyint(1) NOT NULL DEFAULT 1,
  `updated`             int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `central_gold_settings` (`id`, `free_gold_enabled`, `updated`)
VALUES (1, 1, UNIX_TIMESTAMP());
