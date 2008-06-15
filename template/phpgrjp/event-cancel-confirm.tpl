{include file=header.tpl}

<h3>キャンセルの確認</h3>
<p>イベントをキャンセルします。<strong>原則的にキャンセルの取り消しはできません</strong>。キャンセルする場合、以下の内容を確認して、submitボタンを押してください</p>

<dl>
  {foreach from=$app.record item=item key=key}
  <dt>{$key}</dt><dd>{$item}</dd>
  {/foreach}
</dl>

{form method="post" action="$BASE_URL/eventcancel/`$app.record.id`"}
  {form_input name="id"}
  {form_name name="submit"}{form_input name="submit"}
{/form}

{include file=footer.tpl}
