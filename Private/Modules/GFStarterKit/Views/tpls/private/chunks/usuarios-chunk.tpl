<form id="js-form-new-user" style="width: 100%;">
<div class="demo-graphs mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--12-col">
	<div class="demo-avatar-dropdown mdl-typography--title mdl-color-text--grey-600">{$chunkTitle}</div><br>
		<input type="hidden" id="js-model-id" value="{if isset($usuario)}{$usuario.id}{else}0{/if}" name="id">
		<input type="hidden" name="op" value="{if isset($usuario)}update{else}create{/if}">
		<div class="mdl-grid">
			<div class="mdl-cell mdl-cell--4-col">
				<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" >
					<input class="mdl-textfield__input" type="text" name="nombre" value="{if isset($usuario)}{$usuario.nombre}{/if}"/> 
					<label class="mdl-textfield__label">Nombre</label>
				</div>
			</div>
			<div class="mdl-cell mdl-cell--4-col">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" >
                    <input class="mdl-textfield__input" type="text" name="email" value="{if isset($usuario)}{$usuario.email}{/if}"/> 
                    <label class="mdl-textfield__label">Email</label>
                </div>
            </div>
			<div class="mdl-cell mdl-cell--4-col">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" >
                    <input class="mdl-textfield__input" type="text" name="telefono" value="{if isset($usuario)}{$usuario.telefono}{/if}"/> 
                    <label class="mdl-textfield__label">Telefono</label>
                </div>
            </div>
		</div>
		<div class="mdl-grid">
			<div class="mdl-cell mdl-cell--4-col">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" >
                    <input class="mdl-textfield__input" type="text" name="user" value="{if isset($usuario)}{$usuario.user}{/if}"/> 
                    <label class="mdl-textfield__label">Nombre de Usuario</label>
                </div>
            </div>
			<div class="mdl-cell mdl-cell--4-col">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" >
                    <input class="mdl-textfield__input" type="text" name="pass" value=""/> 
                    <label class="mdl-textfield__label">Contraseña</label>
                </div>
            </div>
            <div class="mdl-cell mdl-cell--4-col">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" >
                    <input class="mdl-textfield__input" type="text" name="repeat_pass" value=""/> 
                    <label class="mdl-textfield__label">Repita Contraseña</label>
                </div>
            </div>
            <input type="hidden" value="0" name="isAdmin">
		</div>
		<div style="clear: both; width: 100%">
			<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-button--colored mdl-color--green-400 js-btn-save-user" style="float: right; margin: 8px;"> Guardar </a> <a
            class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-button--colored mdl-color--red-400 js-btn-cancel" style="float: right; margin: 8px;"> Cancelar </a>
		</div>
</div>
</form>
{literal}
<script type="text/javascript">

function initChunkUsuarios(isAdmin) {
	if(isAdmin == true) {
		$('#js-form-new-user').find('input[name="isAdmin"]').val(1);
	} else {
		$('#js-form-new-user').find('input[name="isAdmin"]').val(0);
	}
	$('#js-form-new-user').on('click','.js-btn-save-user',function(e) {
		e.preventDefault();
		 var form = $('#js-form-new-user').serialize();
		 $.ajax({
	 	        url: "/tivoli/api/BaseUser",
	 	        type: 'POST',
	 	        data: form,
	 	        beforeSend: function() {
	 	        	swal({isOnlyLoader: true ,title: "Guardando usuario",   type: "info",   showCancelButton: false,
	 	        		closeOnConfirm: false});
		        },
	 	        success: function (data) {
	 	        	if(isAdmin == false)
	 	        		tableUsers.ajax.reload();
	 	        	else
	 	        		tableAdmin.ajax.reload();
	 	        	
	 	          	swal("¡Listo!", "Usuario guardado correctamente", "success");
	 	        },
	 	       error: function(xhr) {
	 	    	  handleAjaxError(xhr);
	 	        }
	 	    });
		
		
	});
	$('#js-form-new-user').on('click','.js-btn-cancel',function(e) {
        e.preventDefault();
        $('#js-usuarios-chunk-holder').html("");
    });
	
}
</script>
{/literal}