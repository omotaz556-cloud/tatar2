<?php
#################################################################################
##                                                                             ##
##  Project:       Novaterra                                                  ##
##  Filename:      notification/index.php                                    ##
##  Purpose:       "Coming Soon" teaser page for upcoming version/features    ##
##  License:       Proprietary — original work, part of Novaterra project     ##
##                                                                             ##
#################################################################################

include("../GameEngine/config.php");
require_once dirname(__DIR__) . "/GameEngine/Lang/loader.php";
tz_load_language(LANG);

if (T4_COMING == true) {
?>
<!DOCTYPE html>
<html <?php echo tz_html_dir_attrs(); ?>>
<head>
	<meta charset="UTF-8" />
	<meta http-equiv="cache-control" content="max-age=0" />
	<meta http-equiv="pragma" content="no-cache" />
	<meta http-equiv="expires" content="0" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?php echo SERVER_NAME; ?> — Coming Soon</title>
	<style>
		:root {
			--nv-bg: #14100c;
			--nv-panel: #1f1812;
			--nv-accent: #7a1f2b;
			--nv-gold: #c8a24a;
			--nv-text: #f0e6d2;
			--nv-text-dim: #b8a988;
		}
		* { box-sizing: border-box; }
		body {
			margin: 0;
			min-height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
			background: radial-gradient(ellipse at top, #241a12 0%, var(--nv-bg) 70%);
			color: var(--nv-text);
			font-family: Georgia, 'Times New Roman', serif;
			padding: 24px;
		}
		.nv-card {
			max-width: 560px;
			width: 100%;
			background: var(--nv-panel);
			border: 1px solid rgba(200, 162, 74, 0.25);
			border-radius: 10px;
			padding: 48px 40px;
			text-align: center;
			box-shadow: 0 20px 60px rgba(0,0,0,0.5);
		}
		.nv-brand {
			font-size: 14px;
			letter-spacing: 4px;
			text-transform: uppercase;
			color: var(--nv-gold);
			margin-bottom: 8px;
		}
		h1 {
			font-size: 30px;
			margin: 0 0 16px;
			color: var(--nv-text);
		}
		.nv-sub {
			color: var(--nv-text-dim);
			font-size: 15px;
			line-height: 1.6;
			margin-bottom: 32px;
		}
		form {
			display: flex;
			gap: 10px;
			flex-wrap: wrap;
			justify-content: center;
		}
		input[type="email"] {
			flex: 1 1 240px;
			padding: 12px 14px;
			border-radius: 6px;
			border: 1px solid rgba(200, 162, 74, 0.35);
			background: #14100c;
			color: var(--nv-text);
			font-size: 14px;
		}
		button {
			padding: 12px 22px;
			border-radius: 6px;
			border: none;
			background: linear-gradient(180deg, var(--nv-accent), #5c1620);
			color: var(--nv-text);
			font-weight: bold;
			letter-spacing: 1px;
			cursor: pointer;
		}
		button:hover { filter: brightness(1.1); }
		.nv-note {
			margin-top: 18px;
			font-size: 12px;
			color: var(--nv-text-dim);
		}
		.nv-error {
			color: #d98c8c;
			font-size: 13px;
			margin-top: 10px;
			min-height: 18px;
		}
		.nv-back {
			display: inline-block;
			margin-top: 28px;
			font-size: 13px;
			color: var(--nv-gold);
			text-decoration: none;
		}
		.nv-back:hover { text-decoration: underline; }
	</style>
	<?php echo tz_rtl_stylesheet_tag(null, '../'); ?>
</head>
<body>
	<div class="nv-card">
		<div class="nv-brand">Novaterra</div>
		<h1><?php echo isset($lang['notification']['join_now']) ? $lang['notification']['join_now'] : 'A new chapter is coming'; ?></h1>
		<p class="nv-sub"><?php echo isset($lang['notification']['new_challenges']) ? $lang['notification']['new_challenges'] : 'We\'re preparing the next update. Leave your email and we\'ll let you know the moment it\'s ready.'; ?></p>

		<form method="post" action="index.php?email=error">
			<input type="email" name="registerMail" maxlength="50" placeholder="you@example.com" required />
			<button type="submit" name="sendRegisterMail" value="send">Notify Me</button>
		</form>
		<div class="nv-error">
			<?php
			if (isset($_GET['email']) && $_GET['email'] === 'error') {
				echo isset($lang['notification']['error']) ? $lang['notification']['error'] : 'Please enter a valid email address.';
			}
			?>
		</div>
		<div class="nv-note">© <?php echo date('Y'); ?> Novaterra. All rights reserved.</div>

		<a class="nv-back" href="../index.php">&larr; Back to <?php echo SERVER_NAME; ?></a>
	</div>
</body>
</html>
<?php
} else {
	header("Location: ../index.php");
	exit;
}
