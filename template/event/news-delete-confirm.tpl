{include file=header.tpl}
<h3>Delete News</h3>
<p>以下のニュースを削除します。よろしければsubmitボタンを押してください</p>
<dl>
{foreach from=$app.news item=item key=key}
<dt>{$key}</dt><dd>{$item}</dd>
{/foreach}
</dl>
<form method="post" action="{$BASE_URL}/news_delete/{$app.news.id}">
{form_name name="submit"}{form_input name="submit"}
</form>
{include file=footer.tpl}
