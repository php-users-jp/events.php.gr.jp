<hr />
{if $smarty.session.is_admin}
<ul class="menu">
<li><a href="{$BASE_URL}/news_admin">NewsAdmin</a></li>
<li><a href="{$BASE_URL}/event_admin">EventAdmin</a></li>
<li><a href="{$BASE_URL}/admin">Setting</a></li>
</ul>
{/if}
<ul class="menu">
<li><a href="{$BASE_URL}">Top</a></li>
{if isset($smarty.session.name)}
<li><a href="{$BASE_URL}/logout">Logout</a></li>
<li>USER:{$smarty.session.name}</li>
<li>Role:
{if isset($smarty.session.is_admin}
Administrator
{else}
Power User
{/if}
</li>
{else}
<li><a href="{$BASE_URL}/login">Login</a></li>
{/if}
</ul>
</div>
<div id="footer">
&copy;&nbsp;Event&nbsp;Server&nbsp;@&nbsp;Ethna Version {$smarty.const.ETHNA_VERSION}
</div>
</div>
</body>
</html>
