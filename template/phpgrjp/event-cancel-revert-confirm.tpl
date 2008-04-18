{include file=header.tpl}

<h3>キャンセル解除の確認</h3>
<p>イベントをキャンセルを解除します。以下の内容を確認して、submitボタンを押してください</p>

<dl>
  {foreach from=$app.record item=item key=key}
  <dt>{$key}</dt><dd>{$item}</dd>
  {/foreach}
</dl>

{form method="post" action="$BASE_URL/eventcancelrevert/$app.record.id"}
  {form_input name="id"}
  {form_name name="submit"}{form_input name="submit"}
{/form}

{include file=footer.tpl}
