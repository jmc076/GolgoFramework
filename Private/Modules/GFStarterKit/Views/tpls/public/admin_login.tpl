 {include file="./commons/header.tpl"}

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
									<label class="mdl-textfield__label" for="username">{$i18n["admin-login"].usuario}</label>
								</div>
								<div class="mdl-textfield mdl-js-textfield">
									<input class="mdl-textfield__input" type="password" name="pass" id="userpass" />
									<label class="mdl-textfield__label" for="userpass">{$i18n["admin-login"].contrasena}</label>
								</div>
							</form>
						</div>
						<div class="mdl-card__actions mdl-card--border">
							<button class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect js-login-button">{$i18n["admin-login"].login}</button>
						</div>
					</div>
				</form>
			</div>
		</div>

	</main>
	</div>


{include file="./commons/commonjs.tpl"}

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
{include file="./commons/footer.tpl"}