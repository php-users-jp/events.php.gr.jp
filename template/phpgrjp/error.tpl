{include file="header.tpl"}
<div class="section">
<h3>Error</h3>
{$app.error}
{if count($errors)}
 <ul>
  {foreach from=$errors item=error}
   <li>{$error}</li>
  {/foreach}
 </ul>
{/if}
</div>
{include file="footer.tpl"}
