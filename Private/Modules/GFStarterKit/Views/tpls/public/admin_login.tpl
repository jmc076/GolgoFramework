{include file="./commons/general/header.tpl"}
<div class="wrapper wrapper-full-page">
	<div class="main-panel login-page">
		{include file="./commons/top-navbar.tpl"}
		<!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->
	  <div class="content">
	            <div class="container-fluid">
	            {if isset($alreaydLogged) && $alreaydLogged == true}
	           	 	{include file="../blocks/login-form-logged.tpl"}
	            {else}
					{include file="../blocks/login-form.tpl"}
				{/if}
			</div>
		</div>
	</div>
		{include file="./commons/general/footer.tpl"}
</div>
</body>
{include file="./commons/general/commonjs.tpl"}
<script type="text/javascript" src="modules/GFStarterKit/js/admin-login/admin-login.js"></script>

</html>

