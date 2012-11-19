<?php
/**
* uninstall.php
* @package Mod Cdr
* @author Machine
* @co-author Capi
* @version 1.60
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @description Fichier de désinstallation du mod Cdr
*/

if (!defined('IN_SPYOGAME')) die("Hacking attempt");

global $db, $table_prefix;
define("TABLE_CDR", $table_prefix."cdr");
define("TABLE_XTENSE_CALLBACKS", $table_prefix."xtense_callbacks");
define("TABLE_MOD_CDR", $table_prefix."mod_cdr");

require_once("mod/cdr/lang/lang_fr.php");
//modif 3.0.7
//if (file_exists("mod/cdr/lang/lang_".$server_config['language'].".php")) require_once("mod/cdr/lang/lang_".$server_config['language'].".php");

// On récupère l'id du mod pour xtense...
$query = "SELECT id FROM ".TABLE_MOD." WHERE action='cdr'";
$result = $db->sql_query($query);
list($mod_id) = $db->sql_fetch_row($result);

// On regarde si la table xtense_callbacks existe :
$query = 'SHOW TABLES LIKE "'.TABLE_XTENSE_CALLBACKS.'"';
$result = $db->sql_query($query);
if ($db->sql_numrows($result) != 0) {
	//Le mod xtense 2 est installé !
	//Maintenant on regarde si cdr est dedans normalement oui mais on est jamais trop prudent...
	$query = 'SELECT * FROM '.TABLE_XTENSE_CALLBACKS.' WHERE mod_id = '.$mod_id;
	$result = $db->sql_query($query);
	if ($db->sql_numrows($result) != 0) {
		// Il est  dedans alors on l'enlève :
		$query = 'DELETE FROM '.TABLE_XTENSE_CALLBACKS.' WHERE mod_id = '.$mod_id;
		$db->sql_query($query);
		echo "<script>alert('".$lang['xtense_gone']."')</script>";
	}
}
echo "<script>alert(".$lang['xtense_gone'].")</script>";

$mod_uninstall_name = "cdr";
$mod_uninstall_table = $table_prefix."cdr".', '.$table_prefix."mod_cdr";
uninstall_mod($mod_uninstall_name, $mod_uninstall_table);

?>
