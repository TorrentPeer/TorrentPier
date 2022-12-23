<!-- IF TPL_NOTICES_EDIT -->
<!--========================================================================-->

<h1>{L_NOTICES_MANAGEMENT}</h1>

<p>{L_NOTICES_EXPLAIN}</p>
<br/>

<form action="{S_NOTICES_ACTION}" method="post">
    {S_HIDDEN_FIELDS}

  <table class="forumline wAuto">
    <col class="row1">
    <col class="row2">
    <tr>
      <th colspan="2">{L_NOTICES_MANAGEMENT}</th>
    </tr>
    <tr>
      <td><h4>{L_STATUS}</h4>
      </td>
      <td>
        <label><input type="radio" name="active" value="1" <!-- IF ACTIVE -->checked="checked"<!-- ENDIF --> />{L_ENABLED}</label>&nbsp;&nbsp;
        <label><input type="radio" name="active" value="0" <!-- IF not ACTIVE -->checked="checked"<!-- ENDIF --> />{L_DISABLED}</label>
      </td>
    </tr>
    <tr>
      <td><h4>{L_POST_ANNOUNCEMENT}</h4></td>
      <td>
        <textarea style="white-space: pre-wrap;" rows="10" cols="100" name="text">{TEXT}</textarea>
      </td>
    </tr>
    <tr>
      <td class="catBottom" colspan="2">
        <input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption"/>&nbsp;&nbsp;
        <input type="reset" value="{L_RESET}" class="liteoption"/>
      </td>
    </tr>
  </table>

</form>

<!--========================================================================-->
<!-- ENDIF / TPL_NOTICES_EDIT -->

<!-- IF TPL_NOTICES_LIST -->
<!--========================================================================-->

<h1>{L_NOTICES_MANAGEMENT}</h1>

<p>{L_NOTICES_EXPLAIN}</p>
<br/>

<form method="post" action="{S_NOTICES_ACTION}">

  <table class="forumline w80">
    <tr>
      <th>{L_POST_ANNOUNCEMENT}</th>
      <th>{L_STATUS}</th>
      <th>{L_EDIT}</th>
      <th>{L_DELETE}</th>
    </tr>
    <!-- BEGIN notices -->
    <tr class="{notices.ROW_CLASS} tCenter">
      <td class="pad_8" width="50%">{notices.TEXT}</td>
      <td>{notices.ACTIVE}</td>
      <td><a href="{notices.U_NOTICE_EDIT}">{L_EDIT}</a></td>
      <td><a href="{notices.U_NOTICE_DELETE}">{L_DELETE}</a></td>
    </tr>
    <!-- END notices -->
    <tr>
      <td class="catBottom" colspan="4">
        <input type="submit" class="mainoption" name="add" value="{L_ADD_NEW_NOTICE}"/>
      </td>
    </tr>
  </table>

</form>

<!--========================================================================-->
<!-- ENDIF / TPL_NOTICES_LIST -->

<br clear="all"/>
