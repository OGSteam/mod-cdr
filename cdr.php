
<script src='./mod/cdr/js/tablesort.min.js'></script>

<!-- Include sort types you need -->
<script src='./mod/cdr/js/sorts/tablesort.number.min.js'></script>
<script src='./mod/cdr/js/sorts/tablesort.date.min.js'></script>

<?php
/**
 * index.php
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


$req = "SELECT COUNT(`id_user`) FROM " . M_CDR . " WHERE `id_user` =" . $user_data['user_id'];
$req1 = $db->sql_query($req);
list($id_exist) = $db->sql_fetch_row($req1);

require_once("views/page_header.php");

// Barre menu
if (!isset($pub_subaction)) {
    $subaction = "cdr";
    $pub_subaction = "cdr";
} else $subaction = $pub_subaction;

if ($subaction != "cdr") {
    $bouton1 = "\t\t\t<td class='c' style='text-align: center; width: 150pt' onclick=\"window.location = 'index.php?action=cdr&subaction=cdr';\">";
    $bouton1 .= "<a style='cursor:pointer'><span style=\"color: lime; \">" . $lang['menu_cdr'] . "</span></a>";
    $bouton1 .= "</td>";
} else {
    $bouton1 = "\t\t\t<th width='150'>";
    $bouton1 .= "<a>" . $lang['menu_cdr'] . "</a>";
    $bouton1 .= "</th>";
}

if ($subaction != "option") {
    $bouton2 = "\t\t\t<td class='c' style='text-align: center; width: 150pt' onclick=\"window.location = 'index.php?action=cdr&subaction=option';\">";
    $bouton2 .= "<a style='cursor:pointer'><span style=\"color: lime; \">" . $lang['menu_option'] . "</span></a>";
    $bouton2 .= "</td>";
} else {
    $bouton2 = "\t\t\t<th style='width:150pt'>";
    $bouton2 .= "<a>" . $lang['menu_option'] . "</a>";
    $bouton2 .= "</th>";
}
echo "<br><br><table>\n";
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

            $req = "SELECT count(`id_user`) FROM " . M_CDR . " WHERE `id_user` = " . $user_data['user_id'];
            $req1 = $db->sql_query($req);
            list($id_exist) = $db->sql_fetch_row($req1);

            if ($id_exist >= 1) {
                $req = "SELECT * FROM " . M_CDR . " WHERE `id_user`=" . $user_data['user_id'];
            }
            else {
                $req = "SELECT * FROM " . M_CDR . " WHERE `id_user` = 0";
            }
            $req1 = $db->sql_query($req);
            $tc = $db->sql_fetch_row($req1);
            ?>

            <br><br>

            <table>
                <tr align='center'>
                    <td class='c'><span onclick="document.location.href='index.php?action=cdr&galaxy=all'"><b>ALL</b></td>
                    <?php
                    for ($i = 1; $i <= $server_config['num_of_galaxies']; $i++) {
                        echo "<td class='c'><span onclick=\"document.location.href='index.php?action=cdr&galaxy=" . $i . "'\"><b>G" . $i . "</b></span></td>";
                    }
                    ?>
                </tr>
            </table>
            <br>

            <table id="trier">
                <thead>
                <tr class=title>
                    <th class='' data-sort-method='' style="text-align:center"><?php echo $lang['coord']; ?></th>
                    <th class='' data-sort-method='' style="text-align:center"><?php echo $lang['nb_recy']; ?></th>
                    <th class='' data-sort-method='' style="text-align:center"><?php echo $lang['total']; ?></th>
                    <th class='' data-sort-method='' style="text-align:center"><?php echo $lang['metal']; ?></th>
                    <th class='' data-sort-method='' style="text-align:center"><?php echo $lang['crystal']; ?></th>
                    <th class='' data-sort-method='' style="text-align:center"><?php echo $lang['date']; ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (isset($pub_galaxy) && $pub_galaxy != "") {
                    if ($pub_galaxy == "all") $galax = "";
                    else $galax = " AND gal = '" . $pub_galaxy . "'";
                } elseif (!empty($tc['galaxy'])) $galax = " AND gal = '" . $tc['galaxy'] . "'";
                else $galax = "";

                $sql = "SELECT * FROM " . T_CDR . " WHERE `total` > '" . $tc['taille'] . "'" . $galax . " ORDER BY " . $tc['tri1'] . " " . $tc['tri2'];
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
                        <td class='' ><?php echo $val['gal'] . $val['coord']; ?></td>
                        <td class='' ><?php echo floor($val['total'] / 20000 + 1); ?></td>
                        <td class='' ><?php
                            if ($val['total'] >= $tc['big']) {
                                echo "<span style='color:#" . $tc['big_color'] . "'>" . $cdr_tot . "</span>";
                            }
                            elseif ($cdr_total >= $tc['medium']) {
                                echo "<span style='color:#" . $tc['medium_color'] . "'>" . $cdr_tot . "</span>";
                            }
                            elseif ($cdr_total > $tc['small']) {
                                echo "<span style='color:#" . $tc['small_color'] . "'>" . $cdr_tot . "</span>";
                            }
                            else {
                                echo $cdr_tot;
                            }
                            ?></td>
                        <td class='' ><?php
                            if ($val['metal'] >= $tc['big']) {
                                echo "<span style='color:#" . $tc['big_color'] . "'>" . $cdr_met . "</span>";
                            }
                            elseif ($cdr_metal >= $tc['medium']) {
                                echo "<span style='color:#" . $tc['medium_color'] . "'>" . $cdr_met . "</span>";
                            }
                            elseif ($cdr_metal > $tc['small']) {
                                echo "<span style='color:#" . $tc['small_color'] . "'>" . $cdr_met . "</span>";
                            }
                            else {
                                echo $cdr_met;
                            }
                            ?></td>
                        <td class='' ><?php
                            if ($val['cristal'] >= $tc['big']) {
                                echo "<span style='color:#" . $tc['big_color'] . "'>" . $cdr_cri . "</span>";
                            }
                            elseif ($cdr_cristal >= $tc['medium']) {
                                echo "<span style='color:#" . $tc['medium_color'] . "'>" . $cdr_cri . "</span>";
                            }
                            elseif ($cdr_cristal > $tc['small']){
                                echo "<span style='color:#" . $tc['small_color'] . "'>" . $cdr_cri . "</span>";
                            }
                            else{
                                echo $cdr_cri;
                            }

                            ?></td>
                        <td class='' ><?php echo date($lang['date_format'], $val['date']); ?></td>
                    </tr>

                    <?php
                }
                ?>
                </tbody>
            </table>
            <script>
                new Tablesort(document.getElementById('trier'));
            </script>

            <br>

            <table>
                <tr>
                    <td BGCOLOR="#<?php echo $tc['small_color']; ?>" width='2%'></td>
                    <td width='10%'><?php echo $lang['more_than'] . " ". number_format($tc['small'], 0, '', ' '); ?></td>
                    <td BGCOLOR="#<?php echo $tc['medium_color']; ?>" width='2%'></td>
                    <td width='10%'><?php echo $lang['more_than'] . " ". number_format($tc['medium'], 0, '', ' '); ?></td>
                    <td BGCOLOR="#<?php echo $tc['big_color']; ?>" width='2%'></td>
                    <td width='10%'><?php echo $lang['more_than'] . " ". number_format($tc['big'], 0, '', ' '); ?></td>
                </tr>
            </table>

            <?php
            break;
        case "option" :

            if ($id_exist >= 1) $req = "SELECT * FROM " . M_CDR . " WHERE `id_user`=" . $user_data['user_id'];
            else $req = "SELECT * FROM " . M_CDR . " WHERE `id_user` = 0";

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
                    $reg = "INSERT INTO " . M_CDR . " (`id_user`,`taille`,`small`,`small_color`,`medium`,`medium_color`,`big`,`big_color`,`tri1`,`tri2`,`galaxy`) VALUES (" . $user_data['user_id'] . ",'5000','10000','FFFF00','20000','FFA500','50000','FF0000','total','desc','0')";
                    $req = $db->sql_query($reg);
                }
                $req = "SELECT * FROM " . M_CDR . " WHERE `id_user` =" . $user_data['user_id'];
                $req1 = $db->sql_query($req);
                $tc = $db->sql_fetch_row($req1);
            }
            ?>

            <br/>

            <form action="index.php?action=cdr&subaction=option" name="form1" method="POST">
                <input type="hidden" name="add" value="1"/>
                <table width='80%'>
                    <tr>
                        <td class='c' colspan='6' style="text-align: center;"><?php echo $user_data['user_name']; ?></td>
                    </tr>
                </table>
                <br/>
                <table width='20%'>
                    <td class='c' colspan='2' style="text-align: center;">Xtense</td>
                    <tr>
                        <th class='c' width='50%'><?php echo $lang['tcdt']; ?></th>
                        <th class='c' width='25%'>
                            <input style="text-align:center" type="text" name="taille0" size="10" maxlength="10" value="<?php echo $tc['taille']; ?>"/>
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
                            <input style="text-align:center" size="10" type="text" name="C_Small" maxlength="6" value="<?php echo $tc['small_color']; ?>"/></td>
                    </tr>
                    <tr>
                        <th class='c' width='20%'><?php echo $lang['more_than']; ?> <input style="text-align:center"
                                                                                           type="text" size="10"
                                                                                           name="t_med"
                                                                                           value="<?php echo $tc['medium']; ?>"/>
                        </th>
                        <td align='center' BGCOLOR="#<?php echo $tc['medium_color']; ?>">
                            <input style="text-align:center" size="10" type="text" name="C_med" maxlength="6" value="<?php echo $tc['medium_color']; ?>"/></td>
                    </tr>
                    <tr>
                        <th class='c' width='20%'><?php echo $lang['more_than']; ?> <input style="text-align:center"
                                                                                           type="text" size="10"
                                                                                           name="t_big"
                                                                                           value="<?php echo $tc['big']; ?>"/>
                        </th>
                        <td align='center' BGCOLOR="#<?php echo $tc['big_color']; ?>">
                            <input style="text-align:center" size="10" type="text" name="C_big" maxlength="6" value="<?php echo $tc['big_color']; ?>"/></td>
                    </tr>
                    <td class='c' colspan='2' style="text-align: center;"><?php echo $lang['tri']; ?></td>
                    <tr>
                        <th>
                            <select name="tri1">
                                <option value="coord"<?php echo($tc['tri1'] == "coord" ? " selected" : ""); ?>><?php echo $lang['coord']; ?></option>
                                <option value="total"<?php echo($tc['tri1'] == "total" ? " selected" : ""); ?>><?php echo $lang['total']; ?></option>
                                <option value="metal"<?php echo($tc['tri1'] == "metal" ? " selected" : ""); ?>><?php echo $lang['metal']; ?></option>
                                <option value="cristal"<?php echo($tc['tri1'] == "cristal" ? " selected" : ""); ?>><?php echo $lang['crystal']; ?></option>
                                <option value="date"<?php echo($tc['tri1'] == "date" ? " selected" : ""); ?>><?php echo $lang['date']; ?></option>
                            </select>
                        </th>
                        <th>
                            <select name="tri2">
                                <option value="asc"<?php echo($tc['tri2'] == "asc" ? " selected" : ""); ?>><?php echo $lang['ascending']; ?></option>
                                <option value="desc"<?php echo($tc['tri2'] == "desc" ? " selected" : ""); ?>><?php echo $lang['descending']; ?></option>
                            </select>
                        </th>
                    </tr>
                    <tr>
                        <th class='c' width='50%'><?php echo $lang['galaxy']; ?></th>
                        <th class='c' width='25%'>
                            <input style="text-align:center" type="text" name="gal" size="10" maxlength="10" value="<?php echo $tc['galaxy']; ?>"/>
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

