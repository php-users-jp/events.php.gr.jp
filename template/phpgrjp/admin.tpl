{include file=header.tpl}
<h2>Config</h2>

<h3>System Environment</h3>
{assoc2dl value=$app.system_env}

<h3>管理権限者一覧</h3>

<table>
  <tr><th>name</th><th>edited_at</th><th>action</th></tr>
  {foreach from=$app.admin_list item=item}
  <tr>
    <td>{$item.column}</td>
    <td>{$item.edited_at}</td>
    <td>delete(no implement)</td>
  </tr>
  {/foreach}
</table>

<h3>管理者の追加</h3>
<div class="info">
  <p>追加するユーザのtypekeyアカウントを入力してsubmitを押してください</p>
</div>
<p>
{form method="post" action="$BASE_URL/adminadd"}
TypeKey Account{form_input name="name"}{form_input name="submit"}
{/form}
</p>

{include file=footer.tpl}
