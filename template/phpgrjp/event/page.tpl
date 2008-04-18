{include file="header.tpl"}
<h2>Event Wiki Page</h2>
<div>
{$app_ne.content|parse_pukiwiki}
</div>

<div>
{if count($errors)}
<ul>
{foreach from=$errors item=error}
<li>{$error}</li>
{/foreach}
</ul>
{/if}
{form method="post"}
{form_input name="event_id"}
{form_input name="content" cols="50" rows="9"}<br>
{form_input name="preview"}
{form_input name="submit"}
{/form}
</div>

<div>
{assign var="help" value="
**見出し
 *h3
 **h4
 ***h5
 ****h6
*h3
**h4
***h5
****h6

**リスト
 -list
 -list2
 --list2-1
 --list2-2
-list
-list2
--list2-1
--list2-2

**番号付きリスト
 +olist
 +olist2
 ++olist2-1
 ++olist2-2
+olist
+olist2
++olist2-1
++olist2-2

**引用
 >引用文
 >example
>引用文
>example

**pre
頭にスペースを入れる
 def takahashi
   'takahashi'
 end

**定義語
 :apple|リンゴ
 :orange|オランゲ
:apple|リンゴ
:orange|オランゲ

**リンク
 [[example:http://example.com]]
[[example:http://example.com]]
"}
{$help|parse_pukiwiki}
</div>

{include file="footer.tpl"}
