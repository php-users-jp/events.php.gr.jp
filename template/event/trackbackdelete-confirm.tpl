{include file=header.tpl}

<h3>トラックバックの削除</h3>

<p>以下の内容のトラックバックを削除します．内容を確認し，削除する場合は削除ボタンを押してください．</p>

{assoc2dl value=$app.trackback}

<form method="post" action="{$BASE_URL}/trackbackdelete/{$app.trackback.id}">
{form_name name="post"}{form_input name="post"}
</form>

{include file=footer.tpl}
