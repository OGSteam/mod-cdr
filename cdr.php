<SCRIPT>
    var index;
    function sort_int(p1, p2) {
        return p1[index] - p2[index];
    } //fonction pour trier les nombres
    function sort_char(p1, p2) {
        return ((p1[index] >= p2[index]) << 1) - 1;
    } //fonction pour trier les strings
    function sort_coord(p1, p2) {
        var coordPattern = '(\\d+):(\\d+):(\\d+)';
        var m1 = p1[index].match(coordPattern);
        var m2 = p2[index].match(coordPattern);
        var val1 = (m1[1] * 15000 + m1[2] * 20 + m1[3] * 1);
        var val2 = (m2[1] * 15000 + m2[2] * 20 + m2[3] * 1);
        var val = val1 - val2;
        return (val);
    }

    function TableOrder(e, Dec) { //Dec= 0:Croissant, 1:Décroissant
//---- Détermine : oCell(cellule) oTable(table) index(index cellule) -----//
        var FntSort;
        FntSort = [];
        if (!e) e = window.event;
        for (oCell = e.srcElement ? e.srcElement : e.target; oCell.tagName != "TD"; oCell = oCell.parentNode); //determine la cellule sélectionnée
        for (oTable = oCell.parentNode; oTable.tagName != "TABLE"; oTable = oTable.parentNode); //determine l'objet table parent
        for (index = 0; oTable.rows[0].cells[index] != oCell; index++); //determine l'index de la cellule

//---- Copier Tableau Html dans Table JavaScript ----//
        var table = [];
        for (r = 1; r < oTable.rows.length; r++)
            table[r - 1] = [];

        for (c = 0; c < oTable.rows[0].cells.length; c++) { //Sur toutes les cellules
            var Type;
            var objet = oTable.rows[1].cells[c].innerHTML.replace(/<\/?[^>]+>/gi, "");
            if (objet.match(/^\d\d[\/-]\d\d[\/-]\d\d\d\d\s\d\d:\d\d:\d\d$/)) { //date jj/mm/aaaa hh:mm:ss
                FntSort[c] = sort_char;
                Type = 0;
            } else if (objet.match(/^[0-9£$\.\s-]+$/)) { //nombre, numéraire
                FntSort[c] = sort_int;
                Type = 1;
            } else if (objet.match(/^\d+:\d+:\d+$/)) { //Coordonnées
                FntSort[c] = sort_coord;
                Type = 2;
            } else { //Chaine de caractère
                FntSort[c] = sort_char;
                Type = 3;
            }

            for (r = 1; r < oTable.rows.length; r++) { //De toutes les rangées
                objet = oTable.rows[r].cells[c].innerHTML.replace(/<\/?[^>]+>/gi, "");
                switch (Type) {
                    case 0:
                        table[r - 1][c] = new Date(objet.substring(6, 10), objet.substring(3, 5), objet.substring(0, 2), objet.substring(11, 13), objet.substring(14, 16), objet.substring(17, 19));
                        break; //date jj/mm/aaaa hh:mm:ss
                    case 1:
                        table[r - 1][c] = parseFloat(objet.replace(/[^0-9.-]/g, ''));
                        break; //nombre
                    case 2:
                        table[r - 1][c] = objet.toLowerCase();
                        break; //Chaine de caractère si coordonnées
                    case 3:
                        table[r - 1][c] = objet.toLowerCase();
                        break; //Chaine de caractère
                }
                table[r - 1][c + oTable.rows[0].cells.length] = oTable.rows[r].cells[c].innerHTML;
            }
        }

//--- Tri Table ---//
        table.sort(FntSort[index]);
        if (Dec) table.reverse();

//---- Copier Table JavaScript dans Tableau Html ----//
        for (c = 0; c < oTable.rows[0].cells.length; c++) //Sur toutes les cellules
            for (r = 1; r < oTable.rows.length; r++) //De toutes les rangées
                oTable.rows[r].cells[c].innerHTML = table[r - 1][c + oTable.rows[0].cells.length];
    }
</SCRIPT>

<?php
/**
 * cdr.php
 * @package Mod Cdr
 * @version 1.70
 * @author Machine
 * @co-author Capi
 * @license http: //opensource.org/licenses/gpl-license.php GNU Public License
 */

if (!defined('IN_SPYOGAME')) die("Hacking attempt");

global $db, $table_prefix, $user_data;

define("TABLE_XTENSE_CALLBACKS", $table_prefix . "xtense_callbacks");
define("T_CDR", $table_prefix . "cdr");
define("M_CDR", $table_prefix . "mod_cdr");

require_once("mod/cdr/lang/lang_fr.php");
//modif ogspy 3.0.7
//if (file_exists("mod/cdr/lang/lang_".$server_config['language'].".php")) require_once("mod/cdr/lang/lang_".$server_config['language'].".php");
//if (file_exists("mod/cdr/lang/lang_".$user_data['user_language'].".php")) require("mod/cdr/lang/lang_".$user_data['user_language'].".php");

$req = "SELECT count(id_user) FROM " . M_CDR . " WHERE id_user=" . $user_data['user_id'];
$req1 = $db->sql_query($req);
list($id_exist) = $db->sql_fetch_row($req1);

require_once("views/page_header.php");

// Barre menu
if (!isset($pub_subaction)) {
    $subaction = "cdr";
    $pub_subaction = "cdr";
} else $subaction = $pub_subaction;

if ($subaction != "cdr") {
    $bouton1 = "\t\t\t<td class='c' align='center' width='150' onclick=\"window.location = 'index.php?action=cdr&subaction=cdr';\">";
    $bouton1 .= "<a style='cursor:pointer'><font color='lime'>" . $lang['menu_cdr'] . "</font></a>";
    $bouton1 .= "</td>";
} else {
    $bouton1 = "\t\t\t<th width='150'>";
    $bouton1 .= "<a>" . $lang['menu_cdr'] . "</a>";
    $bouton1 .= "</th>";
}

if ($subaction != "option") {
    $bouton2 = "\t\t\t<td class='c' align='center' width='150' onclick=\"window.location = 'index.php?action=cdr&subaction=option';\">";
    $bouton2 .= "<a style='cursor:pointer'><font color='lime'>" . $lang['menu_option'] . "</font></a>";
    $bouton2 .= "</td>";
} else {
    $bouton2 = "\t\t\t<th width='150'>";
    $bouton2 .= "<a>" . $lang['menu_option'] . "</a>";
    $bouton2 .= "</th>";
}
echo "<br/><br/><table width=60%>\n";
echo $bouton1 . $bouton2;
echo "</table>\n";

if (isset($pub_subaction)) {
    switch ($pub_subaction) {
        case "cdr" :
            // on supprime les vieux de plus de 2jours
            $vieux = (time() - (60 * 60 * 24) * 2);
            // on fait du nettoyage au cas ou
            $query = "DELETE FROM `" . T_CDR . "` WHERE `date`<" . $vieux;
            $db->sql_query($query);

            $req = "SELECT count(id_user) FROM " . M_CDR . " WHERE id_user=" . $user_data['user_id'];
            $req1 = $db->sql_query($req);
            list($id_exist) = $db->sql_fetch_row($req1);
            if ($id_exist >= 1) $req = "SELECT * FROM " . M_CDR . " WHERE id_user=" . $user_data['user_id'];
            else $req = "SELECT * FROM " . M_CDR . " WHERE id_user=0";
            $req1 = $db->sql_query($req);
            $tc = $db->sql_fetch_row($req1);
            ?>

            <br/><br/>

            <table width='30%'>
                <tr align='center'>
                    <td class='c'><span onclick="document.location.href='index.php?action=cdr&galaxy=all'"><b>ALL</b>
                    </td>
                    <?php
                    for ($i = 1; $i <= $server_config['num_of_galaxies']; $i++)
                        echo "<td class='c'><span onclick=\"document.location.href='index.php?action=cdr&galaxy=" . $i . "'\"><b>G" . $i . "</b></span></td>";
                    ?>
                </tr>
            </table>

            <br/>

            <table width='80%' id="trier">
                <tr class=title>
                    <td class='c' align='center'><span
                            onclick=TableOrder(event,0)>&#9660;&nbsp;</span><?php echo $lang['coord']; ?><span
                            onclick=TableOrder(event,1)>&nbsp;&#9650;</span></td>
                    <td class='c' align='center'><span
                            onclick=TableOrder(event,0)>&#9660;&nbsp;</span><?php echo $lang['nb_recy']; ?><span
                            onclick=TableOrder(event,1)>&nbsp;&#9650;</span></td>
                    <td class='c' align='center'><span
                            onclick=TableOrder(event,0)>&#9660;&nbsp;</span><?php echo $lang['total']; ?><span
                            onclick=TableOrder(event,1)>&nbsp;&#9650;</span></td>
                    <td class='c' align='center'><span
                            onclick=TableOrder(event,0)>&#9660;&nbsp;</span><?php echo $lang['metal']; ?><span
                            onclick=TableOrder(event,1)>&nbsp;&#9650;</span></td>
                    <td class='c' align='center'><span
                            onclick=TableOrder(event,0)>&#9660;&nbsp;</span><?php echo $lang['crystal']; ?><span
                            onclick=TableOrder(event,1)>&nbsp;&#9650;</span></td>
                    <td class='c' align='center'><span
                            onclick=TableOrder(event,0)>&#9660;&nbsp;</span><?php echo $lang['date']; ?><span
                            onclick=TableOrder(event,1)>&nbsp;&#9650;</span></td>
                </tr>

                <?php
                if (isset($pub_galaxy) && $pub_galaxy != "") {
                    if ($pub_galaxy == "all") $galax = "";
                    else $galax = " AND gal = '" . $pub_galaxy . "'";
                } elseif (!empty($tc['galaxy'])) $galax = " AND gal = '" . $tc['galaxy'] . "'";
                else $galax = "";

                $sql = "SELECT * FROM " . T_CDR . " WHERE total > '" . $tc['taille'] . "'" . $galax . " ORDER BY " . $tc['tri1'] . " " . $tc['tri2'];
                $result = $db->sql_query($sql);
                $i = 1;

                while ($val = $db->sql_fetch_assoc($result)) {
                    $cdr_total = $val['total'];
                    $cdr_tot = number_format($val['total'], 0, '', ' ');
                    $cdr_metal = $val['metal'];
                    $cdr_met = number_format($val['metal'], 0, '', ' ');
                    $cdr_cristal = $val['cristal'];
                    $cdr_cri = number_format($val['cristal'], 0, '', ' ');
                    ?>

                    <tr>
                        <th class='c'><?php echo $val['gal'] . $val['coord']; ?></th>
                        <th class='c'><?php echo floor($val['total'] / 20000 + 1); ?></th>
                        <th class='c'><?php
                            if ($val['total'] >= $tc['big']) echo "<span STYLE='color:#" . $tc['big_color'] . "'>" . $cdr_tot . "</span>";
                            elseif ($cdr_total >= $tc['medium']) echo "<span STYLE='color:#" . $tc['medium_color'] . "'>" . $cdr_tot . "</span>";
                            elseif ($cdr_total > $tc['small']) echo "<span STYLE='color:#" . $tc['small_color'] . "'>" . $cdr_tot . "</span>";
                            else echo $cdr_tot;
                            ?></th>
                        <th class='c'><?php
                            if ($val['metal'] >= $tc['big']) echo "<span STYLE='color:#" . $tc['big_color'] . "'>" . $cdr_met . "</span>";
                            elseif ($cdr_metal >= $tc['medium']) echo "<span STYLE='color:#" . $tc['medium_color'] . "'>" . $cdr_met . "</span>";
                            elseif ($cdr_metal > $tc['small']) echo "<span STYLE='color:#" . $tc['small_color'] . "'>" . $cdr_met . "</span>";
                            else echo $cdr_met;
                            ?></th>
                        <th class='c'><?php
                            if ($val['cristal'] >= $tc['big']) echo "<span STYLE='color:#" . $tc['big_color'] . "'>" . $cdr_cri . "</span>";
                            elseif ($cdr_cristal >= $tc['medium']) echo "<span STYLE='color:#" . $tc['medium_color'] . "'>" . $cdr_cri . "</span>";
                            elseif ($cdr_cristal > $tc['small']) echo "<span STYLE='color:#" . $tc['small_color'] . "'>" . $cdr_cri . "</span>";
                            else echo $cdr_cri;
                            ?></th>
                        <th class='c'><?php echo date($lang['date_format'], $val['date']); ?></th>
                    </tr>
                    <?php
                }
                ?>
            </table>

            <br/>

            <table width='40%'>
                <tr>
                    <td BGCOLOR="#<?php echo $tc['small_color']; ?>" width='2%'></td>
                    <td width='10%'><?php echo $lang['more_than'] . number_format($tc['small'], 0, '', ' '); ?></td>
                    <td BGCOLOR="#<?php echo $tc['medium_color']; ?>" width='2%'></td>
                    <td width='10%'><?php echo $lang['more_than'] . number_format($tc['medium'], 0, '', ' '); ?></td>
                    <td BGCOLOR="#<?php echo $tc['big_color']; ?>" width='2%'></td>
                    <td width='10%'><?php echo $lang['more_than'] . number_format($tc['big'], 0, '', ' '); ?></td>
                </tr>
            </table>

            <?php
            break;
        case "option" :

            if ($id_exist >= 1) $req = "SELECT * FROM " . M_CDR . " WHERE id_user=" . $user_data['user_id'];
            else $req = "SELECT * FROM " . M_CDR . " WHERE id_user = 0";

            $req1 = $db->sql_query($req);
            $tc = $db->sql_fetch_row($req1);

            $Txt = (isset($pub_taille0)) ? $pub_taille0 : "";
            $TS = (isset($pub_T_Small)) ? $pub_T_Small : "";
            $CS = (isset($pub_C_Small)) ? $pub_C_Small : "";
            $TM = (isset($pub_t_med)) ? $pub_t_med : "";
            $CM = (isset($pub_C_med)) ? $pub_C_med : "";
            $TB = (isset($pub_t_big)) ? $pub_t_big : "";
            $CB = (isset($pub_C_big)) ? $pub_C_big : "";
            $tr1 = (isset($pub_tri1)) ? $pub_tri1 : "";
            $tr2 = (isset($pub_tri2)) ? $pub_tri2 : "";
            $gal = (isset($pub_gal)) ? $pub_gal : "";

            if (isset($pub_add) == 1) {
                if ($user_data['user_id'] == $tc['id_user']) {
                    $db->sql_query("UPDATE " . M_CDR
                        . " SET taille='$Txt', small='$TS', small_color='$CS', medium='$TM', medium_color='$CM', big='$TB', big_color='$CB', tri1='$tr1', tri2='$tr2', galaxy='$gal'"
                        . " WHERE id_user=" . $user_data['user_id']);
                } else {
                    $reg = "INSERT INTO " . M_CDR . " (id_user,taille,small,small_color,medium,medium_color,big,big_color,tri1,tri2,galaxy) VALUES (" . $user_data['user_id'] . ",'5000','10000','FFFF00','20000','FFA500','50000','FF0000','total','desc','0')";
                    $req = $db->sql_query($reg);
                }
                $req = "SELECT * FROM " . M_CDR . " WHERE id_user=" . $user_data['user_id'];
                $req1 = $db->sql_query($req);
                $tc = $db->sql_fetch_row($req1);
            }
            ?>

            <br/>

            <form action="index.php?action=cdr&subaction=option" name="form1" method="POST">
                <input type="hidden" name="add" value="1"/>
                <table width='80%'>
                    <tr>
                        <td class='c' colspan='6'
                            style="text-align: center;"><?php echo $user_data['user_name']; ?></td>
                    </tr>
                </table>
                <br/>
                <table width='20%'>
                    <td class='c' colspan='2' style="text-align: center;">Xtense</td>
                    <tr>
                        <th class='c' width='50%'><?php echo $lang['tcdt']; ?></th>
                        <th class='c' width='25%'>
                            <input style="text-align:center" type="text" name="taille0" size="10" maxlength="10"
                                   value="<?php echo $tc['taille']; ?>"/>
                        </th>
                    </tr>
                    <td class='c' colspan='2' style="text-align: center;"><?php echo $lang['colorcdr']; ?></td>
                    <tr>
                        <th class='c' width='20%'><?php echo $lang['more_than']; ?><input style="text-align:center"
                                                                                          type="text" size="10"
                                                                                          name="T_Small"
                                                                                          value="<?php echo $tc['small']; ?>"/>
                        </th>
                        <td align='center' BGCOLOR="#<?php echo $tc['small_color']; ?>">
                            <input style="text-align:center" size="10" type="text" name="C_Small" maxlength="6"
                                   value="<?php echo $tc['small_color']; ?>"/></td>
                    </tr>
                    <tr>
                        <th class='c' width='20%'><?php echo $lang['more_than']; ?> <input style="text-align:center"
                                                                                           type="text" size="10"
                                                                                           name="t_med"
                                                                                           value="<?php echo $tc['medium']; ?>"/>
                        </th>
                        <td align='center' BGCOLOR="#<?php echo $tc['medium_color']; ?>">
                            <input style="text-align:center" size="10" type="text" name="C_med" maxlength="6"
                                   value="<?php echo $tc['medium_color']; ?>"/></td>
                    </tr>
                    <tr>
                        <th class='c' width='20%'><?php echo $lang['more_than']; ?> <input style="text-align:center"
                                                                                           type="text" size="10"
                                                                                           name="t_big"
                                                                                           value="<?php echo $tc['big']; ?>"/>
                        </th>
                        <td align='center' BGCOLOR="#<?php echo $tc['big_color']; ?>">
                            <input style="text-align:center" size="10" type="text" name="C_big" maxlength="6"
                                   value="<?php echo $tc['big_color']; ?>"/></td>
                    </tr>
                    <td class='c' colspan='2' style="text-align: center;"><?php echo $lang['tri']; ?></td>
                    <tr>
                        <th>
                            <select name="tri1">
                                <option
                                    value="coord"<?php echo($tc['tri1'] == "coord" ? " selected" : ""); ?>><?php echo $lang['coord']; ?></option>
                                <option
                                    value="total"<?php echo($tc['tri1'] == "total" ? " selected" : ""); ?>><?php echo $lang['total']; ?></option>
                                <option
                                    value="metal"<?php echo($tc['tri1'] == "metal" ? " selected" : ""); ?>><?php echo $lang['metal']; ?></option>
                                <option
                                    value="cristal"<?php echo($tc['tri1'] == "cristal" ? " selected" : ""); ?>><?php echo $lang['crystal']; ?></option>
                                <option
                                    value="date"<?php echo($tc['tri1'] == "date" ? " selected" : ""); ?>><?php echo $lang['date']; ?></option>
                            </select>
                        </th>
                        <th>
                            <select name="tri2">
                                <option
                                    value="asc"<?php echo($tc['tri2'] == "asc" ? " selected" : ""); ?>><?php echo $lang['ascending']; ?></option>
                                <option
                                    value="desc"<?php echo($tc['tri2'] == "desc" ? " selected" : ""); ?>><?php echo $lang['descending']; ?></option>
                            </select>
                        </th>
                    </tr>
                    <tr>
                        <th class='c' width='50%'><?php echo $lang['galaxy']; ?></th>
                        <th class='c' width='25%'>
                            <input style="text-align:center" type="text" name="gal" size="10" maxlength="10"
                                   value="<?php echo $tc['galaxy']; ?>"/>
                        </th>
                    </tr>
                </table>
                <br/>
                <input type="submit" name="add_taille" value="<?php echo $lang['update']; ?>"/>
            </form>
            <?php
    }
}
require_once("./views/page_tail.php");
?>
