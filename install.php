<?php
/**
* install.php
* @package Mod Cdr
* @author Machine
* @co-author Capi
* @version 1.62
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* @description Fichier d'installation du mod Cdr
*/

if (!defined('IN_SPYOGAME')) die("Hacking attempt");

global $db, $table_prefix;

$security = false;
$mod_folder = "cdr";
$security = install_mod($mod_folder);
if ($security == true)
  {
    
define("TABLE_XTENSE_CALLBACKS", $table_prefix."xtense_callbacks");
define("TABLE_CDR", $table_prefix."cdr");
define("TABLE_MOD_CDR", $table_prefix."mod_cdr");

require_once("mod/cdr/lang/lang_fr.php");
//modif 3.0.7
//if (file_exists("mod/cdr/lang/lang_".$server_config['language'].".php")) require_once("mod/cdr/lang/lang_".$server_config['language'].".php");

// Creation table qui recevra les infos de Xtense
$query = "CREATE TABLE IF NOT EXISTS ".TABLE_CDR." ("
	." id INT NOT NULL AUTO_INCREMENT,"
	." date INT(11) NOT NULL,"
	." total INT(11) NOT NULL,"
	." metal INT(11) NOT NULL,"
	." cristal INT(11) NOT NULL,"
	." gal INT(1) NOT NULL,"
	." coord TEXT NOT NULL,"
	." PRIMARY KEY (id)"
	.")";
$db->sql_query($query);

// Creation de la table qui recevra les option des membres
$query = "CREATE TABLE IF NOT EXISTS ".TABLE_MOD_CDR." ("
	." id_user INT(11) unsigned NOT NULL,"
	." taille INT(11) NOT NULL,"
	." small INT(11) NOT NULL,"
	." small_color varchar(6) NOT NULL,"
	." medium INT(11) NOT NULL,"
	." medium_color varchar(6) NOT NULL,"
	." big INT(11) NOT NULL,"
	." big_color varchar(6) NOT NULL,"
	." tri1 varchar(50) NOT NULL,"
	." tri2 varchar(5) NOT NULL,"
	." galaxy varchar(3) NOT NULL,"
	." PRIMARY KEY (id_user)"
	.")";
$db->sql_query($query);

$query = "INSERT INTO ".TABLE_MOD_CDR
	." (id_user,taille,small,small_color,medium,medium_color,big,big_color,tri1,tri2,galaxy)"
	." VALUES ('0','5000','10000','FFFF00','20000','FFA500','50000','FF0000','total','desc','0')";
$db->sql_query($query);

$filename = 'mod/cdr/version.txt';
if (file_exists($filename)) $file = file($filename);

// modif pour la 3.0.7
//$query = "INSERT INTO ".TABLE_MOD
//	." (title, menu, action, root, link, version, active, admin_only)"
//	." VALUES ('Cdr', 'Cdr', 'cdr', 'cdr', 'cdr.php', '".trim($file[1])."', '1', '0')";
//$db->sql_query($query);

// Insertion de la liaison entre Xtense v2 et cdr
// Quelle est l'ID du mod ?
		// On récupère le n° d'id du mod
		$query = "SELECT `id` FROM `".TABLE_MOD."` WHERE `action`='cdr' AND `active`='1' LIMIT 1";
		$result = $db->sql_query($query);
		$mod_id = $db->sql_fetch_row($result);
		$mod_id = $mod_id[0];

// On regarde si la table xtense_callbacks existe :
$result = $db->sql_query('SHOW tables LIKE "'.TABLE_XTENSE_CALLBACKS.'"');
if ($db->sql_numrows($result) != 0) {
	// Maintenant on regarde si cdr est dedans
	$result = $db->sql_query("SELECT * FROM ".TABLE_XTENSE_CALLBACKS." WHERE mod_id = '$mod_id'");
	$nresult = $db->sql_numrows($result);

	// S'il n'y est pas : alors on l'ajoute !
	if ($nresult == 0) $db->sql_query("INSERT INTO ".TABLE_XTENSE_CALLBACKS." (mod_id, function, type, active) VALUES ('".$mod_id."', 'cdr', 'system', 1)");
	echo "<script>alert('".$lang['xtense_ok']."')</script>";
} else // On averti qu'Xtense 2 n'est pas installé :
	echo "<script>alert('".$lang['no_xtense']."')</script>";
}
?>
