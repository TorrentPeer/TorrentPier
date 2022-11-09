
<!-- IF TPL_INVITES_RULES -->
<!--========================================================================-->
<h1>{L_INVITE_RULES}</h1>
<!-- IF $bb_cfg['new_user_reg_only_by_invite'] -->
<form method="post" action="{S_RULES_ACTION}">
<table class="forumline">
	<tr>
	  <td class="catTitle" colspan="6">{L_INVITE_RULES}</td>
	</tr>
	<tr>
	  <th>&nbsp;{L_INVITE_MIN_RATIO}&nbsp;</th>
	  <th>&nbsp;{L_INVITE_MIN_EXP}&nbsp;</th>
	  <th>&nbsp;{L_INVITE_ALLOWED_GROUP}&nbsp;</th>
	  <th colspan="3">&nbsp;{L_INVITE_NUMBERS_IN_WEEK}&nbsp;</th>
	</tr>
	<tr>
	  <td class="row1" align="center"><input type="text" size="10" name="add_rule_user_rating" class="post" /></td>
	  <td class="row2" align="center"><input type="text" size="10" name="add_rule_user_age" class="post" /></td>
	  <td class="row1" align="center">{S_ADD_GROUP_SELECT}</td>
	  <td class="row2" colspan="3" align="center"><input type="text" size="10" name="add_rule_invites_count" class="post" /></td>
	</tr>
	<tr align="right">
	  <td class="catBottom" colspan="6">
      <input type="submit" name="add_rule" class="liteoption" value="{L_INVITE_ADD_RULE}" />&nbsp;&nbsp;
      <input type="reset" value="{L_RESET}" class="liteoption" />
    </td>
  </tr>
</table>
<br>
<table class="forumline">
	<tr>
	  <td class="catTitle" colspan="5">{L_EDIT_INVITE_RULES}</td>
	</tr>
  <tr>
	  <th>&nbsp;{L_INVITE_MIN_RATIO}&nbsp;</th>
	  <th>&nbsp;{L_INVITE_MIN_EXP}&nbsp;</th>
	  <th>&nbsp;{L_INVITE_ALLOWED_GROUP}&nbsp;</th>
	  <th>&nbsp;{L_INVITE_NUMBERS_IN_WEEK}&nbsp;</th>
	  <th colspan="1">&nbsp;{L_DELETE}&nbsp;</th>
	</tr>
<!-- BEGIN rule_row -->
	<tr>
	  <input type="hidden" name="rule_change_list[]" value="{rule_row.RULE_ID}" />
	  <td class="row1" align="center"><input type="text" size="10" name="rule_user_rating_list[]" class="post" value="{rule_row.USER_RATING}" /></td>
	  <td class="row2" align="center"><input type="text" size="10" name="rule_user_age_list[]" class="post" value="{rule_row.USER_AGE}" /></td>

	  <td class="row1" align="center">
	    {rule_row.S_GROUP_SELECT}
	  </td>

	  <td class="row1" align="center"><input type="text" size="10" name="rule_invites_count_list[]" class="post" value="{rule_row.INVITES_COUNT}" /></td>
	  <td class="row2" colspan="1" align="center"><input type="checkbox" name="rule_id_list[]" value="{rule_row.RULE_ID}" /></td>
	</tr>
<!-- END rule_row -->
	<tr align="right">
	  <td class="catBottom" colspan="5">
	  <input type="submit" name="change_rule" class="liteoption" value="{L_SAVE}" /></td>
	</tr>
</table>
</form>
<!-- ELSE -->
<div class='cat_title'>
  <center>{L_REG_INVITES_DISABLE}</center>
</div>
<table class="forumline">
	<tr>
	  <td colsapn="6" class="row1" align="center">{L_REG_INVITES_DISABLE_MSG}</td>
	</tr>
<table>

<!-- ENDIF -->
<!--========================================================================-->
<!-- ENDIF / TPL_INVITES_RULES -->

<!-- IF TPL_INVITES_HISTORY -->
<!--========================================================================-->

<h1>{L_INVITE_HISTORY}</h1>

<table class="forumline">
<tr>
	<th colspan="6" class="thHead">{L_INVITE_HISTORY}</th>
</tr>
<tr>
	<td class="catTitle">{L_INVITE_GETED_USER}</td>
	<td class="catTitle">{L_INVITE_GET_DATE}</td>
	<td class="catTitle">{L_INVITE_CODE}</td>
	<td class="catTitle">{L_INVITE_ACTIVE}</td>
	<td class="catTitle">{L_INVITE_INVITED_USER}</td>
	<td class="catTitle">{L_INVITE_ACTIVATION_DATE}</td>
</tr>
<!-- BEGIN invite_row -->
	<tr>
		<td class="row1" align="center">{invite_row.USER}</td>
	  <td class="row1" align="center">{invite_row.GENERATION_DATE}</td>
	  <td class="row1" align="center">{invite_row.INVITE_CODE}</td>
	  <td class="row1" align="center">{invite_row.ACTIVE}</td>
	  <td class="row1" align="center">{invite_row.NEW_USER}</td>
	  <td class="row1" align="center">{invite_row.ACTIVATION_DATE}</td>
	</tr>
<!-- END invite_row -->
</table>
<!--========================================================================-->
<!-- ENDIF / TPL_INVITES_HISTORY -->

<br clear="all" />
