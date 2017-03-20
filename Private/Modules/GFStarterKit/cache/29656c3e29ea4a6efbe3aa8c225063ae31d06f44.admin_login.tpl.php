<?php
/* Smarty version 3.1.31, created on 2017-03-20 13:25:07
  from "C:\xampp\htdocs\GolgoFramework\Private\Modules\GFStarterKit\Views\tpls\public\admin_login.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_58cfca231bb0e4_58550133',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5ea1cd5a7f4d857c190a6343e591e550a490d680' => 
    array (
      0 => 'C:\\xampp\\htdocs\\GolgoFramework\\Private\\Modules\\GFStarterKit\\Views\\tpls\\public\\admin_login.tpl',
      1 => 1489140962,
      2 => 'file',
    ),
    'f4644a9c5336dcf5c4b603bf250314ba1fee35a1' => 
    array (
      0 => 'C:\\xampp\\htdocs\\GolgoFramework\\Private\\Modules\\GFStarterKit\\Views\\tpls\\public\\commons\\header.tpl',
      1 => 1489140880,
      2 => 'file',
    ),
    'f8c381e4827d8e427b936c4af8f8282303d0c025' => 
    array (
      0 => 'C:\\xampp\\htdocs\\GolgoFramework\\Private\\Modules\\GFStarterKit\\Views\\tpls\\public\\commons\\commonjs.tpl',
      1 => 1488978349,
      2 => 'file',
    ),
    'fbc3875d6e7a5cc66414b3a3edeca2892e357b7f' => 
    array (
      0 => 'C:\\xampp\\htdocs\\GolgoFramework\\Private\\Modules\\GFStarterKit\\Views\\tpls\\public\\commons\\footer.tpl',
      1 => 1488875008,
      2 => 'file',
    ),
  ),
  'cache_lifetime' => 3600,
),true)) {
function content_58cfca231bb0e4_58550133 (Smarty_Internal_Template $_smarty_tpl) {
?>
 <!DOCTYPE html>
    <!--[if IE 9 ]><html class="ie9"><![endif]-->
    <head>
        <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <title>GolgoFramework StarterKit</title>
	
	
	
	<link rel="stylesheet" href="modules/GFStarterKit/components/sweetalert2/dist/sweetalert2.min.css">
 	<link rel="stylesheet" href="modules/GFStarterKit/components/material-design-lite/material.min.css">
    <link rel="stylesheet" href="modules/GFStarterKit/css/loading.css">
    <link rel="stylesheet" href="modules/GFStarterKit/css/loading-chunk.css">
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    
    <link rel="stylesheet" href="modules/GFStarterKit/css/admin-mdl-template.css">
    <link rel="stylesheet" href="modules/GFStarterKit/css/login.css">

    </head>
    <body class="login-content">
    <input id="csrf" type="hidden" name="C541lvK566" value="f19fc7630070409f303a64f9546c17f70e5acb2d17a9946dbb3f8df44defe120" />
	    <div id="loading">
			<div class="loader">
		 		<svg class="circular">
		    		<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
	 			</svg>
			</div>
		</div> 


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
									<label class="mdl-textfield__label" for="username">Usuario</label>
								</div>
								<div class="mdl-textfield mdl-js-textfield">
									<input class="mdl-textfield__input" type="password" name="pass" id="userpass" />
									<label class="mdl-textfield__label" for="userpass">Contraseña</label>
								</div>
							</form>
						</div>
						<div class="mdl-card__actions mdl-card--border">
							<button class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect js-login-button">Entrar</button>
						</div>
					</div>
				</form>
			</div>
		</div>

	</main>
	</div>



<!-- for IE support -->
<script src="modules/GFStarterKit/components/es6-promise/es6-promise.auto.min.js"></script>

<script src="modules/GFStarterKit/components/sweetalert2/dist/sweetalert2.min.js"></script>
<script src="modules/GFStarterKit/components/material-design-lite/material.min.js"></script>
<script src="modules/GFStarterKit/components/jquery/dist/jquery.min.js"></script>

<script type="text/javascript">
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
		  	var url = "/tivoli/api/BaseUser";
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
		        			window.location.href = "/tivoli/administracion";
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
</script>

<footer>
</footer>
</body>
</html><?php }
}
