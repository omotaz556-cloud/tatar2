<?php
/**
 * Shared top navigation icons (same markup as Templates/header.tpl / dorf2.php).
 */
if (!function_exists('safeHTML')) {
	function safeHTML($string)
	{
		return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
	}
}

$isRestrictedUser = (
	isset($_SESSION['id_user']) &&
	(int) $_SESSION['id_user'] === 1
);

$dorf1Link = $isRestrictedUser ? '#' : 'dorf1.php';
$dorf2Link = $isRestrictedUser ? '#' : 'dorf2.php';
$reportsLink = $isRestrictedUser ? '#' : 'berichte.php';

$class = 'i4';
if (isset($message)) {
	if ($message->unread && !$message->nunread) {
		$class = 'i2';
	} elseif (!$message->unread && $message->nunread) {
		$class = 'i3';
	} elseif ($message->unread && $message->nunread) {
		$class = 'i1';
	}
}
?>
<div id="topNavIcons">
	<a href="<?php echo $dorf1Link; ?>" id="n1" accesskey="1">
		<img src="img/x.gif" title="<?php echo TZ_VILLAGE_OVERVIEW; ?>" alt="<?php echo TZ_VILLAGE_OVERVIEW; ?>" />
	</a>
	<a href="<?php echo $dorf2Link; ?>" id="n2" accesskey="2">
		<img src="img/x.gif" title="<?php echo VILLAGE_CENTER; ?>" alt="<?php echo VILLAGE_CENTER; ?>" />
	</a>
	<a href="karte.php" id="n3" accesskey="3">
		<img src="img/x.gif" title="<?php echo MAP; ?>" alt="<?php echo MAP; ?>" />
	</a>
	<a href="statistiken.php" id="n4" accesskey="4">
		<img src="img/x.gif" title="<?php echo STATISTICS; ?>" alt="<?php echo STATISTICS; ?>" />
	</a>
	<div id="n5" class="<?php echo safeHTML($class); ?>">
		<a href="<?php echo $reportsLink; ?>" accesskey="5">
			<img src="img/x.gif" class="l" title="<?php echo REPORTS; ?>" alt="<?php echo REPORTS; ?>" />
		</a>
		<a href="nachrichten.php" accesskey="6">
			<img src="img/x.gif" class="r" title="<?php echo MESSAGES; ?>" alt="<?php echo MESSAGES; ?>" />
		</a>
	</div>
</div>
