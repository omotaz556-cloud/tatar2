<?php
#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : delallymedal.tpl                                          ##
##  Type           : Admin Panel Frontend                                      ##
## --------------------------------------------------------------------------- ##
##  Developed by   : Dzoki (Original)                                          ##
##  Refactored by  : Shadow                                                    ##
##  Redesign by    : Shadow                                                    ##
## --------------------------------------------------------------------------- ##
##  Contact        : (see project maintainer)                                 ##
##  Project        : Novaterra                                                  ##
##  GitHub         : https://github.com/omotaz556-cloud/tatar                   ##
## --------------------------------------------------------------------------- ##
##  License        : Novaterra Project                                          ##
##  Copyright      : Novaterra (c) 2010-2025. All rights reserved.              ##
## --------------------------------------------------------------------------- ##
#################################################################################

if($_SESSION['access'] < ADMIN) die("Access Denied: You are not Admin!");
include("../GameEngine/config.php");
$id = $_SESSION['id'];
$sql = mysqli_fetch_array(mysqli_query($GLOBALS["link"], "SELECT Count(*) as Total FROM ".TB_PREFIX."allimedal"), MYSQLI_ASSOC);
$nummedals = $sql['Total'];
?>


<style>
	.del {width:12px; height:12px; background-image: url(img/admin/icon/del.gif);}
</style>
<table id="member">
	<thead>
		<tr>
			<th><?php echo ADM_MEDAL_INFORMATION; ?></th>
		</tr>
	</thead>
</table>
<table id="profile">
	<thead>
		<tr>
			<td><?php echo ADM_WEEK; ?></td>
			<td><?php echo ADM_MEDALS_2; ?></td>
		</tr>
	</thead>
	<tbody>
		<?php
			$sql = mysqli_fetch_array(mysqli_query($GLOBALS["link"], "SELECT Count(*) as Total FROM ".TB_PREFIX."allimedal"), MYSQLI_ASSOC);
			$tot = $sql['Total'];
			$sql = mysqli_query($GLOBALS["link"], "SELECT week FROM ".TB_PREFIX."allimedal ORDER BY week DESC LIMIT 1");
			if(mysqli_num_rows($sql) > 0){
			$week = mysqli_result($sql, 0);
			echo "<tr><td><center>$week</center></td><td><center>$tot</center></td></tr>";
			}else{
			echo "<tr><td><center>0</center></td><td><center>$tot</center></td></tr>";
			}
		?>
	</tbody>
</table>
<br />
<br />



<form action="../GameEngine/Admin/Mods/delallymedalbyweek.php" method="POST">
<?php echo csrf_field(); ?>
<input type="hidden" name="admid" id="admid" value="<?php echo $_SESSION['id']; ?>">
<table id="member">
	<thead>
		<tr>
			<th><?php echo ADM_MEDAL_WEEK_BY_WEEK; ?></th>
		</tr>
	</thead>
</table>
<table id="profile">
	<thead>
		<tr>
			<td><?php echo ADM_WEEK; ?></td>
			<td><?php echo ADM_MEDALS_2; ?></td>
			<td></td>
		</tr>
	</thead>
	<tbody>
		<?php
			for($j = 0; $j<$week; $j++)
			{
				$newweek = $j+1;
				$sql = mysqli_fetch_array(mysqli_query($GLOBALS["link"], "SELECT Count(*) as Total FROM ".TB_PREFIX."allimedal WHERE week = $newweek"), MYSQLI_ASSOC);
				$tot = $sql['Total'];
				echo "<tr>
				<td>$newweek</td>
				<td>$tot</td>
				<td><input type=\"image\" name=\"deleteweek\" value=\"".$newweek."\" style=\"background-image: url('../gpack/novaterra_classic/img/a/del.gif'); height: 12px; width: 12px;\" src=\"../gpack/novaterra_classic/img/a/x.gif\"></td>";
			}
		?>
	</tbody>
</table>
</form>
<br />
<br />

<table id="member">
	<thead>
		<tr>
			<th>All Medals (<?php echo $nummedals; ?>)</th>
		</tr>
	</thead>
</table>
<table id="profile">
	<thead>
		<tr>
			<td><?php echo ADM_MEDAL; ?></td>
			<td><?php echo ADM_BB_CODE; ?></td>
			<td><?php echo ADM_TYPE; ?></td>
			<td><?php echo ADM_PLAYER_2; ?></td>
			<td><?php echo ADM_RANK; ?></td>
			<td><?php echo ADM_WEEK; ?></td>
			<td><?php echo ADM_POINTS_2; ?></td>
		</tr>
	</thead>
	<tbody>
		<?php
			$query = "SELECT * FROM ".TB_PREFIX."allimedal ORDER BY id DESC";
			$result = mysqli_query($GLOBALS["link"], $query);
			while($row = mysqli_fetch_array($result))
			{
				$i = $i + 1;
				$titel="Bonus";
				switch ($row['categorie'])
				{
					case "1": 	$titel="Attackers"; break;
					case "2": 	$titel="Defenders"; break;
					case "3":	$titel="Climbers"; break;
					case "4":	$titel="Robbers"; break;
				}
				$title = $titel;
				$rank = $row['plaats'];
				$week = $row['week'];
				$points = $row['points'];
				$bb = $row['id'];
				$allyid = $row['allyid'];

				$unq = "SELECT name FROM ".TB_PREFIX."alidata WHERE id = ".(int) $allyid."";
				$user = mysqli_result(mysqli_query($GLOBALS["link"], $unq), 0);
				$allyname = $user;

				$alliance = '<a href="admin.php?p=alliance&aid='.$allyid.'">'.$allyname.'</a>';
				echo"
				<tr>
					<td>$i</td>
					<td>[#$bb]</td>
					<td><img src=\"../../gpack/novaterra_classic/img/t/".$row['img'].".jpg\"></td>
					<td>$alliance</td>
					<td>$rank</td>
					<td>$week</td>
					<td>$points</td>
				</tr>";
			}
		?>
	</tbody>
</table>