{include file=header.tpl}
<h3>Delete Event</h3>
<p>以下のイベントを削除します。よろしければsubmitボタンを押してください</p>
<dl>
{foreach from=$app.event item=item key=key}
<dt>{$key}</dt><dd>{$item}</dd>
{/foreach}
</dl>
<form method="post" action="{$BASE_URL}/event_delete/{$app.event.id}">
{form_name name="submit"}{form_input name="submit"}
</form>
{include file=footer.tpl}
