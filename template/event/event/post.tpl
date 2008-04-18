{include file="header.tpl"}
<div>
<h2>Event Post</h2>
{if count($errors)}
 <ul>
  {foreach from=$errors item=error}
   <li>{$error}</li>
  {/foreach}
 </ul>
{/if}
<form method="post" class="actionform">
{form_input name="id"}<br>
<dl>
<dt>{form_name name="name"}</dt><dd>{form_input name="name"}</dd>
<dt>{form_name name="date"}</dt><dd>{form_input name="date"}</dd>
<dt>{form_name name="duedate"}</dt><dd>{form_input name="duedate"}</dd>
<dt>{form_name name="max_register"}</dt><dd>{form_input name="max_register"}</dd>
<dt>{form_name name="description"}</dt><dd>{form_input name="description"}</dd>
<dt>{form_name name="map"}</dt><dd>{form_input name="map"}</dd>
</dl>
{form_input name="submit"}
</form>
</div>

<div class="info">
<h4>Mapについて</h4>
<p>
Mapとは地図を貼り付ける機能の事でフォームの中にALPS Labsで提供されているALPSLAB Slideのパラメータ(ex.35/9/23,136/58/34)を
入力することで地図を表示させる事ができるようになります。
</p>
<p>
地図の座標は<a href="http://base.alpslab.jp/">ALPSLAB Base</a>から取得することができます。さらに具体的な説明は<a href="http://www.alpslab.jp/slide_howto.html">ALPSLAB Slide</a>を参照してください。
</p>
</div>
{include file="footer.tpl"}
