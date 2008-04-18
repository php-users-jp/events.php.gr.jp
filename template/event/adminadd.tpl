{include file=header.tpl}
<h2>Login</h2>
<ul>
{foreach from=$app.admin_list item=item}
<li>{$item.value}</li>
{/foreach}
</ul>
<p>
<form method="post">
{form_input name="name"}{form_input name="submit"}
</form>
</p>
{include file=footer.tpl}
