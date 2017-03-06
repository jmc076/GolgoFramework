 {include file="./common/header.tpl"}
<hgroup>
	<h1 class="verde">Acceso administración <br> Tombolas Tivoli</h1>
</hgroup>
<form id="js-login-form">
	<input type="hidden" value="read" name="op">
	<input type="hidden" value="doLogin" name="sop">
	<div class="mdl-layout mdl-js-layout mdl-color--grey-100">
	<main class="mdl-layout__content">
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
	</main>
</div>
</form>


{include file="./common/commonjs.tpl"}

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

	  $('input').blur(function() {
	    var $this = $(this);
	    if ($this.val())
	      $this.addClass('used');
	    else
	      $this.removeClass('used');
	  });
	  
	  $.each($('input'),function(i,item) {
		  if($(this).val() != "") $(this).addClass("used");
	  })

	  var $ripples = $('.ripples');

	  $ripples.on('click.Ripples', function(e) {

	    var $this = $(this);
	    var $offset = $this.parent().offset();
	    var $circle = $this.find('.ripplesCircle');

	    var x = e.pageX - $offset.left;
	    var y = e.pageY - $offset.top;

	    $circle.css({
	      top: y + 'px',
	      left: x + 'px'
	    });

	    $this.addClass('is-active');

	  });

	  $ripples.on('animationend webkitAnimationEnd mozAnimationEnd oanimationend MSAnimationEnd', function(e) {
	  	$(this).removeClass('is-active');
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
{include file="./common/footer.tpl"}