-- #############################################################################
-- ##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-              ##
-- ## ------------------------------------------------------------------------ ##
-- ##  Filename       : feeding_system_struct.sql                              ##
-- ##  Purpose        : Schema for the "linked accounts" (feeding) system.     ##
-- ##                    Local to each world's own database (unlike           ##
-- ##                    central_gold_struct.sql, which is one shared DB).    ##
-- ##  Developed by   : Shadow                                                 ##
-- ##  Project        : Novaterra                                              ##
-- ## ------------------------------------------------------------------------ ##
-- ##  Reference only: FeedingSystem.php creates these tables itself on first ##
-- ##  use (CREATE TABLE IF NOT EXISTS), the same lazy-migration pattern used ##
-- ##  by MultiAccount.php's mad_session table. You do NOT need to run this   ##
-- ##  file by hand — it exists so the schema is easy to read/audit and so    ##
-- ##  it can be included in a fresh install's struct.sql if wanted.          ##
-- #############################################################################

-- One row per declared "main -> linked account" relationship. A linked
-- (fed) account can be attacked/raided by its owner without cranny/warehouse
-- protection reducing the loot (see FeedingSystem::isLinkedPair(), called
-- from AutomationBattleResolution::resolveResourcesAfterBattle()).
--
-- Direction matters: `owner_uid` is the main/feeding account, `linked_uid`
-- is the account being fed. The relationship is intentionally NOT symmetric
-- in the loot-cap bypass (only the declared owner gets unlimited loot from
-- the declared linked account), even though in practice server admins will
-- usually see the same two players on both ends of a legitimate pair.
CREATE TABLE IF NOT EXISTS `%PREFIX%linked_accounts` (
  `id`         int(11) NOT NULL AUTO_INCREMENT,
  `owner_uid`  int(11) NOT NULL,
  `linked_uid` int(11) NOT NULL,
  `added`      int(11) NOT NULL DEFAULT 0,
  `added_by`   int(11) NOT NULL DEFAULT 0,  -- 0 = player self-service, admin uid = added from Admin panel
  PRIMARY KEY (`id`),
  UNIQUE KEY `owner_linked` (`owner_uid`, `linked_uid`),
  KEY `owner_uid` (`owner_uid`),
  KEY `linked_uid` (`linked_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Single-row settings table (same one-row pattern as other Novaterra admin
-- toggles). `max_linked_per_player` is the admin-configurable cap the client
-- asked for ("1 or 2 or 3, up to whatever number we set"). `enabled` is the
-- master on/off switch. `announced_in_rules` is a soft flag only (Section
-- 5.1 in the brief recommends declaring this in the server rules; this flag
-- does not change any game logic, it is only surfaced back to the admin as
-- a reminder in the panel).
CREATE TABLE IF NOT EXISTS `%PREFIX%feeding_settings` (
  `id`                     int(11) NOT NULL DEFAULT 1,
  `enabled`                tinyint(1) NOT NULL DEFAULT 0,
  `max_linked_per_player`  int(11) NOT NULL DEFAULT 1,
  `announced_in_rules`     tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `%PREFIX%feeding_settings` (`id`, `enabled`, `max_linked_per_player`, `announced_in_rules`)
VALUES (1, 0, 1, 0);
