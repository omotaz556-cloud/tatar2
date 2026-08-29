<?php
include_once("GameEngine/Generator.php");
$start_timer = $generator->pageLoadTimeStart();

#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       : support.php                      	                       ##
##  Type           : In Game Support Page                                      ##
## --------------------------------------------------------------------------- ##
##  Developed by   : Dzoki 						                               ##
##  Refactored by  : Shadow                                                    ##
##  Redesign by    : Shadow                                                    ##
## --------------------------------------------------------------------------- ##
##  Contact        : (see project maintainer)                                 ##
##  Project        : Novaterra                                                  ##
##  URLs:          : https://novaterra.example                                      ##
##  GitHub         : https://github.com/omotaz556-cloud/tatar                   ##
## --------------------------------------------------------------------------- ##
##  License        : GPLv3 (derived from TravianZ; see project LICENSE)       ##
##  Copyright      : Novaterra mods (c) 2010-2026; base engine (c) TravianZ authors (GPLv3). ##
## --------------------------------------------------------------------------- ##
#################################################################################

use App\Utils\AccessLogger;

include_once("GameEngine/Village.php");
AccessLogger::logRequest();

$amount = $_SESSION['amount'];
if(isset($_GET['newdid'])) {
	$_SESSION['wid'] = $_GET['newdid'];
	header("Location: ".$_SERVER['PHP_SELF']);
	exit;
}
else $building->procBuild($_GET);
$gkShell = true;
$gkPageTitle = SERVER_NAME . ' - Support Request';
$gkSupportFormScript = '
function chkFormular() {
  if (document.Formular.Username.value == "") {
    alert("Enter your username!");
    document.Formular.Username.focus();
    return false;
  }
  if (document.Formular.Emailadress.value == "") {
    alert("Enter an emailadress!");
    document.Formular.Emailadress.focus();
    return false;
  }
  if (document.Formular.Emailadress.value.indexOf("@") == -1) {
    alert("Thats not a valid emailadress!");
    document.Formular.Emailadress.focus();
    return false;
  }
  if (document.Formular.Subject.value == "please select") {
    alert("Please select an subject!");
    document.Formular.Subject.focus();
    return false;
  }
  if (document.Formular.Message.value == "") {
    alert("Please enter a message!");
    document.Formular.Message.focus();
    return false;
  }
}';
tz_greek_shell_head($gkPageTitle, 'pg-support', array('includeNew2Js' => false));
tz_greek_shell_open('', array('contentWrap' => false));
include("Templates/support.tpl");
?>
<h1>Support</h1>
<p>You can use the following form to submit your request to the Support.<br />Please take a bit of time to answer the form questions in as much detail as possible, so that we can answer your request quickly and in length. <br />Please note that without a valid email address, your request will not get processed.
<br><br><b>Bug reports, login errors, general questions and feedback</b></p>

<form name="Formular" class=""  method="post" action="mailme.php" onsubmit="return chkFormular()">
		
		<div id="group_support_username">
        <table class="form_table form_tablel_support" width="100%">
        <tr>
        <td class="form_table_label form_table_label_support_username"><label class="form_label" for="element_2">Username </label></td>
		<td class="form_table_element form_table_element_support_username"><input id="element_2_1" name= "Username" class="text" type="text"  maxlength="255" value=""/></td>
        </tr>
        </table>
        </div>
		<div id="group_support_email">
        <table class="form_table form_tablel_support" width="100%">
        <tr>
        <td class="form_table_label form_table_label_support_supportType"><label class="form_label" for="element_3">Email </label></td>
		<td class="form_table_element form_table_element_support_email"><input id="element_3" name="Emailadress" class="text" type="text" maxlength="255" value=""/></td>
        </tr>
        </table>
		</div>
		<div id="group_support_supportType">
		<table class="form_table form_tablel_support" width="100%">
        <tr>
        <td class="form_table_label form_table_label_support_supportType"><label class="form_label" for="element_7">Category </label></td>
		<td class="form_table_element form_table_element_support_supportType"><select id="element_7" name="Subject"> 
			<option value="please select" selected="selected">please select...</option>
			<option value="Bugreport" >Bugreport</option>
			<option value="General question" >General question</option>
			<option value="I cannot login" >I cannot login</option>
			<option value="I cannot register an account" >I cannot register an account</option>
            <option value="Feedback" >Feedback</option>
        </select></td>
        </tr>
        </table>
		</div>
		<div id="group_support_message">
		<table class="form_table form_tablel_support" width="100%">
        <tr>
        <td class="form_table_label form_table_label_support_message"><label class="form_label" for="element_6">Message </label></td>
		<td class="form_table_element form_table_element_support_message"><textarea id="element_6" name="Message" cols="43" rows="7" label="Message"></textarea></td>
        </tr>
        </table>
		</div>
        <br />
        <div id="group_support_message">
        <table class="form_table form_tablel_support" width="100%">
        <tr>
		<td><input type="hidden" name="" value="" /><input id="saveForm" class="button_text" type="submit" name="" value="Send form" /></td>
        <td><input type="hidden" name="" value="" /><input id="saveForm" class="button_text" type="reset" name="" value="Clear form" /></td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        </tr>
        </table>
        </div>		
		</form>	
 
<?php
tz_greek_shell_close(array('buildPopup' => false, 'timer' => $start_timer, 'extraScripts' => $gkSupportFormScript));
