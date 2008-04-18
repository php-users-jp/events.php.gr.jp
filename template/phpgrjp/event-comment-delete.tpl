{include file=header.tpl}
<h3>Delete Comment From Event</h3>
  <p>以下のCommentを削除します。よろしければsubmitボタンを押してください</p>

<dl>
  {foreach from=$app.comment item=item key=key}
  <dt>{$key}</dt><dd>{$item}</dd>
  {/foreach}
</dl>

{form method="post" action="$BASE_URL/event_commentdelete/`$app.comment.id`"}
  {form_name name="submit"}{form_input name="submit"}
{/form}

{include file=footer.tpl}
