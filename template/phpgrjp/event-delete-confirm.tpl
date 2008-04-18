{include file=header.tpl}

<h3>Delete Event</h3>

<p>以下のイベントを削除します。よろしければsubmitボタンを押してください</p>

{assoc2dl value=$app.event}

{form method="post" action="$BASE_URL/event_delete/`$app.event.id`"}
  {form_name name="submit"}{form_input name="submit"}
{/form}

{include file=footer.tpl}
