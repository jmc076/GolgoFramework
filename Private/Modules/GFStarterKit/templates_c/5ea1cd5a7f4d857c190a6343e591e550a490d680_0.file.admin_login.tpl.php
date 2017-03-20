<?php
/* Smarty version 3.1.31, created on 2017-03-20 15:51:27
  from "C:\xampp\htdocs\GolgoFramework\Private\Modules\GFStarterKit\Views\tpls\public\admin_login.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_58cfec6f169067_83902595',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5ea1cd5a7f4d857c190a6343e591e550a490d680' => 
    array (
      0 => 'C:\\xampp\\htdocs\\GolgoFramework\\Private\\Modules\\GFStarterKit\\Views\\tpls\\public\\admin_login.tpl',
      1 => 1490021483,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:./commons/header.tpl' => 1,
    'file:./commons/commonjs.tpl' => 1,
    'file:./commons/footer.tpl' => 1,
  ),
),false)) {
function content_58cfec6f169067_83902595 (Smarty_Internal_Template $_smarty_tpl) {
?>
 <?php $_smarty_tpl->_subTemplateRender("file:./commons/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<div
		class="demo-layout mdl-layout mdl-layout--fixed-header mdl-js-layout mdl-color--grey-100">
		<div class="demo-ribbon"></div>
		<main class="demo-main mdl-layout__content">

		<div class="demo-container mdl-grid">
			<div class="mdl-cell mdl-cell--2-col mdl-cell--hide-tablet mdl-cell--hide-phone"></div>
			<div class="demo-content mdl-color--white mdl-shadow--4dp content mdl-color-text--grey-800 mdl-cell mdl-cell--8-col"
				style="margin-bottom: 0px;">
				<!-- Two cards with a title card and small content and/or youtube video 
				<h3>Main Title</h3>
				<h4>Subtitle</h4>-->
				<form id="js-login-form">
					<input type="hidden" value="read" name="op">
					<input type="hidden" value="doLogin" name="sop">
			        <div class="mdl-card mdl-shadow--6dp">
						<div class="mdl-card__title mdl-color--primary mdl-color-text--white">
							<h2 class="mdl-card__title-text">GolgoFramework StarterKit</h2>
						</div>
						<div class="mdl-card__supporting-text">
							<form action="#">
								<div class="mdl-textfield mdl-js-textfield">
									<input class="mdl-textfield__input" type="text" id="user" name="user" />
									<label class="mdl-textfield__label" for="username"><?php echo $_smarty_tpl->tpl_vars['i18n']->value["admin-login"]['usuario'];?>
</label>
								</div>
								<div class="mdl-textfield mdl-js-textfield">
									<input class="mdl-textfield__input" type="password" name="pass" id="userpass" />
									<label class="mdl-textfield__label" for="userpass"><?php echo $_smarty_tpl->tpl_vars['i18n']->value["admin-login"]['contrasena'];?>
</label>
								</div>
							</form>
						</div>
						<div class="mdl-card__actions mdl-card--border">
							<button class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect js-login-button"><?php echo $_smarty_tpl->tpl_vars['i18n']->value["admin-login"]['login'];?>
</button>
						</div>
					</div>
				</form>
			</div>
		</div>

	</main>
	</div>


<?php $_smarty_tpl->_subTemplateRender("file:./commons/commonjs.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php echo '<script'; ?>
 type="text/javascript">
$(window, document, undefined).ready(function() {
	
	 $.ajaxPrefilter(function(options, originalOptions, jqXHR) {
		    if (originalOptions.type !== 'POST' || options.type !== 'POST') {
		        return;
		    }
		    postData = {};
		    postData[$('#csrf').attr('name')] = $('#csrf').val();
			options.data = typeof options.data === "string" ? $('#csrf').attr('name')+"="+ $('#csrf').val()+"&"+options.data : $.extend(options.data, postData);
		});

	  
	  $('.js-login-button').on('click',function(e) {
		  	e.preventDefault();
		  	var url = "<?php echo $_smarty_tpl->tpl_vars['basePath']->value;?>
/api/BaseUser";
			$.ajax({
		        url:   url,
		        type:  'POST',
		        data: $('#js-login-form').serialize(),
		        beforeSend: function() {
		        	$('#loading').show();
		        },
		        complete: function() {
		        	$('#loading').hide();
		        },
		        success:  function (response) {
		        	console.log(response);
		        	if(response && response.resources != null) {
		        		if(response.resources === true) {
		        			window.location.href = "<?php echo $_smarty_tpl->tpl_vars['basePath']->value;?>
/administracion";
		        		} else {
		        			if(response.resources.error)
		        				alert(response.resources.error);
		        			else
		        				alert("Usuario o contraseña erroneos.");
		        		}
		        	}
		        },
		        error: function(xhr) {
		        	$('#loading').hide();
		        	 handleAjaxError(xhr); 
		        }
			 });
	  });

	});
function handleAjaxError(xhr) {
	  var message = "No se pudo procesar su solicitud en estos instantes.";
	  if(xhr.responseText != undefined && xhr.responseText != "") {
		  message = JSON.parse(xhr.responseText);
		  message = message.msg;
	  }
	  swal("¡Error!", message, "error");   
	
}
<?php echo '</script'; ?>
>
<?php $_smarty_tpl->_subTemplateRender("file:./commons/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
