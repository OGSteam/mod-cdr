<?php
/**
* update.php
* @package Mod Cdr
* @author Machine
* @co-author Capi
* @version 1.62
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @description Fichier de mise à jour du mod Cdr
*/

if (!defined('IN_SPYOGAME')) die("Hacking attempt");

global $db, $table_prefix;
define("TABLE_MOD_CDR", $table_prefix."mod_cdr");
define("TABLE_CDR", $table_prefix."cdr");

require_once("mod/cdr/lang/lang_fr.php");
// mise a jour 3.0.7
//if (file_exists("mod/cdr/lang/lang_".$server_config['language'].".php")) require_once("mod/cdr/lang/lang_".$server_config['language'].".php");

$filename = 'mod/cdr/version.txt';
if (file_exists($filename)) $file = file($filename);

$security = false;
$security = update_mod('cdr','cdr');
///mise a jour 3.0.7
//$query = "UPDATE ".TABLE_MOD." SET `version`='".trim($file[1])."' WHERE `action`='cdr'";
//$db->sql_query($query);

if ($security == true){
// Creation de la table qui recevra les option des membres
$query = "CREATE TABLE IF NOT EXISTS ".TABLE_MOD_CDR." ("
	." id_user INT(11) unsigned NOT NULL,"
	." taille INT(11) NOT NULL,"
	." small INT(11) NOT NULL,"
	." small_color VARCHAR(6) NOT NULL,"
	." medium INT(11) NOT NULL,"
	." medium_color VARCHAR(6) NOT NULL,"
	." big INT(11) NOT NULL,"
	." big_color VARCHAR(6) NOT NULL,"
	." tri1 VARCHAR(50) NOT NULL,"
	." tri2 VARCHAR(5) NOT NULL,"
	." galaxy VARCHAR(3) NOT NULL," 
	." PRIMARY KEY (id_user)"
	.")";
$db->sql_query($query);

$query = "SELECT version FROM ".TABLE_MOD." WHERE action='cdr'";
$req = $db->sql_query($query);
$ver = $db->sql_fetch_row($req);

if ($ver[0] < 1.60) {
	$query = "ALTER TABLE ".TABLE_MOD_CDR." ADD `tri1` VARCHAR(50) NOT NULL DEFAULT 'total', ADD `tri2` VARCHAR(50) NOT NULL DEFAULT 'desc', ADD `galaxy` VARCHAR(3) NOT NULL DEFAULT 'all'";
	$db->sql_query($query);
	$query = "ALTER TABLE ".TABLE_CDR." ADD `gal` INT(1) NOT NULL AFTER `cristal`";
	$db->sql_query($query);
	$query = "TRUNCATE TABLE ".TABLE_CDR;
	$db->sql_query($query);

	$query = "INSERT INTO ".TABLE_MOD_CDR
		." (id_user,taille,small,small_color,medium,medium_color,big,big_color,tri1,tri2,galaxy)"
		." VALUES ('0','5000','10000','FFFF00','20000','FFA500','50000','FF0000','total','desc','0')";
	$db->sql_query($query);
}
}
?>
