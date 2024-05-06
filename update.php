<?php
/**
* update.php
* @package Mod Cdr
* @author Machine
* @co-author Capi
* @version 1.62
* @license https://opensource.org/licenses/gpl-license.php GNU Public License
* @description Fichier de mise à jour du mod Cdr
*/

if (!defined('IN_SPYOGAME')) die("Hacking attempt");

global $db, $table_prefix;
define("TABLE_MOD_CDR", $table_prefix."mod_cdr");
define("TABLE_CDR", $table_prefix."cdr");

$mod_folder = "cdr";
$mod_name = "Champs de ruines";

$query = "SELECT `version` FROM ".TABLE_MOD." WHERE action='cdr'";
$req = $db->sql_query($query);
$ver = $db->sql_fetch_row($req);

if (version_compare($ver[0], '1.9.0', '<'))  {
    $query = "ALTER TABLE " . TABLE_MOD_CDR . " ADD `retention` varchar(3) default '2' not null";
    $req = $db->sql_query($query);
}

update_mod($mod_folder, $mod_name);

