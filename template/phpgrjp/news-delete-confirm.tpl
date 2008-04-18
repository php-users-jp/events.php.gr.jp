{include file=header.tpl}

<h3>Delete News</h3>

<p>以下のニュースを削除します。よろしければsubmitボタンを押してください</p>

{assoc2dl value=$app.news}

{form method="post" action="$BASE_URL/news_delete/`$app.news.id`"}
  {form_name name="submit"}{form_input name="submit"}
{/form}

{include file=footer.tpl}
