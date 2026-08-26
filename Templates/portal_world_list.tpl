<?php
/**
 * Renders enabled portal worlds into login/signup overlays.
 * Expects $portalMode = 'login' | 'register'
 */
if (!class_exists('PortalWorlds', false)) {
    require_once __DIR__ . '/../GameEngine/PortalWorlds.php';
}

$portalMode = isset($portalMode) ? (string) $portalMode : 'login';
$portalLink = $GLOBALS['link'] ?? null;
$portalWorlds = PortalWorlds::enabled();

if (!$portalWorlds) {
    $portalWorlds = PortalWorlds::defaults();
    $portalWorlds = array_values(array_filter($portalWorlds, static function ($w) {
        return !empty($w['local']);
    }));
}

foreach ($portalWorlds as $rawWorld) {
    $w = PortalWorlds::prepareForDisplay($rawWorld, $portalLink);
    $href = $portalMode === 'register' ? $w['register_href'] : $w['login_href'];
    $canEnter = !empty($w['started']) || $portalMode === 'login';
    if ($portalMode === 'register' && empty($w['started'])) {
        $canEnter = false;
    }

    $bg = htmlspecialchars((string) $w['bg_image'], ENT_QUOTES, 'UTF-8');
    $hrefEsc = htmlspecialchars((string) $href, ENT_QUOTES, 'UTF-8');
    $nameEsc = htmlspecialchars((string) $w['name'], ENT_QUOTES, 'UTF-8');
    $badgeEsc = htmlspecialchars((string) $w['badge_label'], ENT_QUOTES, 'UTF-8');
    $badgeClass = htmlspecialchars((string) $w['badge'], ENT_QUOTES, 'UTF-8');
    $playersLabel = defined('PORTAL_PLAYERS_LABEL') ? PORTAL_PLAYERS_LABEL : PLAYERS;
    $ageEsc = htmlspecialchars((string) $w['age_label'], ENT_QUOTES, 'UTF-8');
    $countdownEsc = htmlspecialchars((string) $w['countdown_label'], ENT_QUOTES, 'UTF-8');
    $idEsc = htmlspecialchars((string) $w['id'], ENT_QUOTES, 'UTF-8');
    $pendingClass = empty($w['started']) ? ' is-pending' : '';
    $tag = $canEnter ? 'a' : 'div';
    $hrefAttr = $canEnter ? ' href="' . $hrefEsc . '"' : '';
    ?>
				<li class="tz-world-card<?php echo $pendingClass; ?>" data-world="<?php echo $idEsc; ?>">
					<<?php echo $tag; ?> class="tz-world-link"<?php echo $hrefAttr; ?> title="<?php echo $nameEsc; ?>">
						<img class="tz-world-banner" src="<?php echo $bg; ?>" width="361" height="64" alt="<?php echo $nameEsc; ?>" />
						<?php if ($badgeEsc !== '') { ?>
						<span class="tz-world-badge badge-<?php echo $badgeClass; ?>"><?php echo $badgeEsc; ?></span>
						<?php } ?>
						<span class="tz-world-info">
						<?php if (!empty($w['started'])) { ?>
							<span class="tz-world-players"><b><?php echo (int) $w['players']; ?></b> <?php echo htmlspecialchars($playersLabel, ENT_QUOTES, 'UTF-8'); ?></span>
							<span class="tz-world-age"><?php echo $ageEsc; ?></span>
						<?php } else { ?>
							<span class="tz-world-players"><?php
								echo defined('PORTAL_WORLD_STARTS') ? PORTAL_WORLD_STARTS : 'يبدأ العالم';
							?></span>
							<span class="tz-world-timer" data-remain="<?php echo (int) $w['seconds_to_start']; ?>"><?php echo $countdownEsc; ?></span>
						<?php } ?>
						</span>
					</<?php echo $tag; ?>>
				</li>
<?php
}
?>
