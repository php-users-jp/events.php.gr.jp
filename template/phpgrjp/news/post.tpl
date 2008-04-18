{include file="header.tpl"}

<div>
<h2>News Post</h2>
{if count($errors)}
 <ul>
  {foreach from=$errors item=error}
   <li>{$error}</li>
  {/foreach}
 </ul>
{/if}

{form method="post"}
{form_input name="id"}<br>
{form_name name="title"}{form_input name="title"}<br>
{form_name name="date"}{form_input name="date"}<br>
{form_name name="duedate"}{form_input name="duedate"}<br>
{form_name name="description"}{form_input name="description"}<br>
{form_input name="submit"}<br>
{/form}

</div>

{include file="footer.tpl"}
