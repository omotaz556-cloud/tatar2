<h1><img class="point" src="img/x.gif" alt="" title="" /> شرح اللعبة</h1>
<p><?php echo MANUAL_INTRO; ?></p>
<img class="troops" src="img/x.gif" alt="<?php echo TROOPS; ?>" title="<?php echo TROOPS; ?>" />
<img class="buildings" src="img/x.gif" alt="<?php echo BUILDINGS; ?>" title="<?php echo BUILDINGS; ?>" />
<ul>
<li><?php echo TROOPS; ?></li>
<ul>
	<li><a href="manual.php?typ=2&amp;s=1"><?php echo TRIBE1; ?></a></li>
	<li><a href="manual.php?typ=2&amp;s=2"><?php echo TRIBE2; ?></a></li>
	<li><a href="manual.php?typ=2&amp;s=3"><?php echo TRIBE3; ?></a></li>
	<?php if (defined('NEW_FUNCTIONS_MANUAL_NATURENATARS') && NEW_FUNCTIONS_MANUAL_NATURENATARS) { ?>
	<li><a href="manual.php?typ=2&amp;s=4"><?php echo TRIBE4; ?></a></li>
	<li><a href="manual.php?typ=2&amp;s=5"><?php echo TRIBE5; ?></a></li>
	<?php } ?>
	<?php if (defined('NEW_FUNCTION_TRIBE_HUNS') && NEW_FUNCTION_TRIBE_HUNS) { ?>
	<li><a href="manual.php?typ=2&amp;s=6"><?php echo TRIBE6; ?></a></li>
	<?php } ?>
	<?php if (defined('NEW_FUNCTION_TRIBE_EGIPTEANS') && NEW_FUNCTION_TRIBE_EGIPTEANS) { ?>
	<li><a href="manual.php?typ=2&amp;s=7"><?php echo TRIBE7; ?></a></li>
	<?php } ?>
	<?php if (defined('NEW_FUNCTION_TRIBE_SPARTANS') && NEW_FUNCTION_TRIBE_SPARTANS) { ?>
	<li><a href="manual.php?typ=2&amp;s=8"><?php echo TRIBE8; ?></a></li>
	<?php } ?>
	<?php if (defined('NEW_FUNCTION_TRIBE_VIKINGS') && NEW_FUNCTION_TRIBE_VIKINGS) { ?>
	<li><a href="manual.php?typ=2&amp;s=9"><?php echo TRIBE9; ?></a></li>
	<?php } ?>
</ul>
<br />
<li><?php echo BUILDINGS; ?></li>
<ul>
    <li><a href="manual.php?typ=3&amp;s=1"><?php echo RESOURCES; ?></a></li>
    <li><a href="manual.php?typ=3&amp;s=2"><?php echo Q17_BUTN1; ?></a></li>
    <li><a href="manual.php?typ=3&amp;s=3"><?php echo INFRASTRUCTURE; ?></a></li>
</ul>
<br />
<li><a href="anleitung.php">دليل اللعبة</a></li>
</ul>
<img usemap="#nav" src="img/x.gif" class="navi" alt="" />
<map id="nav" name="nav">
    <area href="manual.php?typ=3&amp;s=1" title="<?php echo BACK; ?>" coords="0,0,45,18" shape="rect" alt="" />
    <area href="manual.php" title="<?php echo OVERVIEW; ?>" coords="46,0,70,18" shape="rect" alt="" />
    <area href="manual.php?typ=2&amp;s=1" title="<?php echo FORWARD; ?>" coords="71,0,116,18" shape="rect" alt="" />
</map>
