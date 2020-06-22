<?php

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
$retention = (isset($pub_retention)) ? $pub_retention : "";

if (isset($pub_add) == 1) {
    if ($user_data['user_id'] == $tc['id_user']) {
        $db->sql_query("UPDATE " . M_CDR
            . " SET taille='$Txt', small='$TS', small_color='$CS', medium='$TM', medium_color='$CM', big='$TB', big_color='$CB', tri1='$tr1', tri2='$tr2', galaxy='$gal', retention='$retention'"
            . " WHERE id_user=" . $user_data['user_id']);
    } else {
        $reg = "INSERT INTO " . M_CDR . " (`id_user`,`taille`,`small`,`small_color`,`medium`,`medium_color`,`big`,`big_color`,`tri1`,`tri2`,`galaxy`, `retention`) VALUES (" . $user_data['user_id'] . ",'5000','10000','FFFF00','20000','FFA500','50000','FF0000','total','desc','0','2')";
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
        </tr>
        <td class='c' colspan='2' style="text-align: center;"><?php echo $lang['retention']; ?></td>
        <tr>
            <th class='c' width='50%'><?php echo $lang['retention_days']; ?></th>
            <th class='c' width='25%'>
                <input style="text-align:center" type="text" name="retention" size="10" maxlength="10" value="<?php echo $tc['retention']; ?>"/>
            </th>
    </table>
    <br/>
    <input type="submit" name="add_taille" value="<?php echo $lang['update']; ?>"/>
</form>