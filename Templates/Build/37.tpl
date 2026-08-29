<?php

#################################################################################
##  HEROSMANSION — Greek.sa layout (train + oasis summary + classic upgrade)   ##
#################################################################################

        $hero_info = $units->Hero($session->uid);
        $heroes = $units->Hero($session->uid, 1);
        $define['reset_level'] = 3;

        $heroUnitNames = [
            1  => U1, 2 => U2, 3 => U3, 5 => U5, 6 => U6,
            11 => U11, 12 => U12, 13 => U13, 15 => U15, 16 => U16,
            21 => U21, 22 => U22, 24 => U24, 25 => U25, 26 => U26,
            51 => U51, 53 => U53, 54 => U54, 55 => U55, 56 => U56,
            61 => U61, 62 => U62, 63 => U63, 65 => U65, 66 => U66,
            71 => U71, 72 => U72, 73 => U73, 75 => U75, 76 => U76,
            81 => U81, 83 => U83, 84 => U84, 85 => U85, 86 => U86,
        ];

        $lvlLabel = defined('BUILD_LEVEL_SHORT') ? BUILD_LEVEL_SHORT : LEVEL;
        $hmLevel = (int) $village->resarray['f' . $id];
        // max oases = floor((level - 5) / 5) — same formula as DatabaseVillageQueries
        $maxOasisNow = max(0, (int) floor(($hmLevel - 5) / 5));
        $maxOasisNext = max(0, (int) floor((($hmLevel + 1) - 5) / 5));
        $ownedOasis = (int) $database->VillageOasisCount($village->wid);
?>


 <div id="build" class="gid37">
        <a href="#" onclick="return Popup(37,4, 'gid');" class="build_logo"><img class="building g37" src="img/x.gif" alt="<?php echo HEROSMANSION; ?>" title="<?php echo HEROSMANSION; ?>"></a>

        <h1><?php echo HEROSMANSION; ?> <span class="level"><?php echo $lvlLabel; ?> <?php echo $hmLevel; ?></span></h1>

        <p class="build_desc"><?php echo HEROSMANSION_DESC; ?></p>


        <?php
        if ($hero_info) {
            $name  = $heroUnitNames[$hero_info['unit']] ?? null;
            $name1 = $hero_info['name'];
        } else {
            $name = 'Mr. Nobody';
            $name1 = 'unknown';
        }

		if(isset($_GET['land']) && $hmLevel >= 1) {
            include_once("37_land.tpl");
		} else if (defined('NEW_FUNCTIONS_HERO_T4') && NEW_FUNCTIONS_HERO_T4
            && $hmLevel >= 1
            && isset($_GET['t4tab'])
            && in_array($_GET['t4tab'], ['items', 'adventures', 'auction'], true)) {

            $t4tab = $_GET['t4tab'];
            include_once("37_" . $t4tab . ".tpl");

		} else if ($hmLevel >= 1) {
            $include_training = true;
            $include_revive = false;
            if (isset($heroes) && is_array($heroes) && count($heroes)) {
                foreach ( $heroes as $hdata ) {
                    if ( $hdata['dead'] == 1 ) {
                        $include_revive = true;
                    }

                    if ( $hdata['inrevive'] == 1 ) {
                        $name1            = $hdata['name'];
                        $include_training = false;
                    }
                }
            }

            if($hero_info === false && $include_revive){
                include_once("37_revive.tpl");
            }

            if ($hero_info === false && $include_training) {
                include_once("37_train.tpl");
            } else if(is_array($hero_info) && $hero_info['intraining'] == 1) {
		    $timeleft = $generator->getTimeFormat($hero_info['trainingtime'] - time());
		?>
	<table id="distribution" cellpadding="1" cellspacing="1">
        <thead>
            <tr class="next">
                <th><?php echo HERO_READY; ?><span id="timer<?php echo ++$session->timer; ?>"><?php echo $timeleft; ?></span></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="desc">
					<div class="tit">
						<img class="unit u<?php echo (int) $hero_info['unit']; ?>" src="img/x.gif" alt="<?php echo htmlspecialchars((string) $name, ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars((string) $name, ENT_QUOTES, 'UTF-8'); ?>" />
						<?php echo htmlspecialchars((string) $name, ENT_QUOTES, 'UTF-8'); ?> (<?php echo htmlspecialchars((string) $name1, ENT_QUOTES, 'UTF-8'); ?>)
					</div>
                </td>
            </tr>
        </tbody>
    </table>
		<?php
		}

        if($hero_info !== false AND $hero_info['dead'] == 0 AND $hero_info['trainingtime'] <= time() AND $hero_info['inrevive'] == 0 AND $hero_info['intraining'] == 0){
            include("37_hero.tpl");
        }

        // Oasis summary — always on main mansion view (Greek.sa layout)
        $oasisOwnedTxt = defined('OASIS_OWNED_OF')
            ? sprintf(OASIS_OWNED_OF, $ownedOasis, $maxOasisNow)
            : ('لقد استوليت على ' . $ownedOasis . ' من ' . $maxOasisNow . ' واحات وللمزيد طوّر المبنى');
        $oasisExpandPop = defined('OASIS_EXPAND_POP') ? (int) OASIS_EXPAND_POP : 1020;
        $oasisExpandPopFmt = number_format($oasisExpandPop);
        $oasisRangeHint = defined('OASIS_RANGE_HINT')
            ? sprintf(OASIS_RANGE_HINT, $oasisExpandPopFmt)
            : ('لايمكن إحتلال واحات خارج المربع 7 x 7 وزيادة السكان إلى ' . $oasisExpandPopFmt . ' توسع المربع');
        $oasisViewLink = defined('OASIS_VIEW_NEARBY') ? OASIS_VIEW_NEARBY : 'عرض الواحات القريبة منك التي يمكنك احتلالها';
        $oasisMaxNow = defined('OASIS_MAX_NOW') ? OASIS_MAX_NOW : 'أكبر عدد الآن';
        $oasisMaxNext = defined('OASIS_MAX_NEXT') ? OASIS_MAX_NEXT : 'أكبر عدد في المستوى';
        $oasisUnit = defined('OASIS_UNIT_SING') ? OASIS_UNIT_SING : 'واحة';
        ?>
        <div class="gk-oasis-summary">
            <p><?php echo htmlspecialchars($oasisOwnedTxt, ENT_QUOTES, 'UTF-8'); ?></p>
            <p class="gk-oasis-hint"><?php echo htmlspecialchars($oasisRangeHint, ENT_QUOTES, 'UTF-8'); ?></p>
            <p><a href="#gk-nearby-wrap" id="gk-nearby-toggle"><?php echo htmlspecialchars($oasisViewLink, ENT_QUOTES, 'UTF-8'); ?></a></p>
            <div id="gk-nearby-wrap" class="gk-nearby-wrap" style="display:none;">
                <?php include_once('37_nearby.tpl'); ?>
            </div>
            <p class="gk-oasis-max"><?php echo htmlspecialchars($oasisMaxNow, ENT_QUOTES, 'UTF-8'); ?>: <b><?php echo (int) $maxOasisNow; ?></b> <?php echo htmlspecialchars($oasisUnit, ENT_QUOTES, 'UTF-8'); ?></p>
            <p class="gk-oasis-max"><?php echo htmlspecialchars($oasisMaxNext, ENT_QUOTES, 'UTF-8'); ?> <?php echo (int) ($hmLevel + 1); ?>: <b><?php echo (int) $maxOasisNext; ?></b> <?php echo htmlspecialchars($oasisUnit, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
        <script type="text/javascript">
        (function () {
            var btn = document.getElementById('gk-nearby-toggle');
            var box = document.getElementById('gk-nearby-wrap');
            if (!btn || !box) { return; }
            btn.onclick = function (e) {
                if (e && e.preventDefault) { e.preventDefault(); }
                box.style.display = (box.style.display === 'none') ? 'block' : 'none';
                return false;
            };
        })();
        </script>
        <?php
        }
        include ("upgrade.tpl"); ?>

    </div>
