<div class="demo-graphs mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--12-col auto">
<form id="js-form-new-user" >
	<div class="demo-avatar-dropdown mdl-typography--title mdl-color-text--grey-600">{$chunkTitle}</div><br>
		<input type="hidden" id="js-model-id" value="{if isset($usuario)}{$usuario.id}{else}0{/if}" name="id">
		<div class="mdl-grid">
			<div class="mdl-cell mdl-cell--3-col">
				<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" >
					<input class="mdl-textfield__input" type="text" name="name" value="{if isset($usuario)}{$usuario.name}{/if}"/> 
					<label class="mdl-textfield__label">Nombre</label>
				</div>
			</div>
			<div class="mdl-cell mdl-cell--3-col">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" >
                    <input class="mdl-textfield__input" type="text" name="email" value="{if isset($usuario)}{$usuario.email}{/if}"/> 
                    <label class="mdl-textfield__label">Email</label>
                </div>
            </div>
			<div class="mdl-cell mdl-cell--3-col">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" >
                    <input class="mdl-textfield__input" type="text" name="telephone" value="{if isset($usuario)}{$usuario.telephone}{/if}"/> 
                    <label class="mdl-textfield__label">Telefono</label>
                </div>
            </div>
         	<div class="mdl-cell mdl-cell--3-col">
                <div class="mdl-select mdl-js-select mdl-select--floating-label">
	                <select class="mdl-select__input mdl-textfield__input" name="userType" required>
	                    {foreach from=$userTypes item=usertype}
	                        <option value="{$usertype}" {if isset($usuario) && isset($usuario.userType) && $usuario.userType eq $usertype}selected{/if}>{$usertype}</option>
	                    {/foreach}
	                </select>
	                <label class="mdl-select__label" for="zona">User type</label>
	             </div>
            </div>
		</div>
		<div class="mdl-grid">
			<div class="mdl-cell mdl-cell--3-col">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" >
                    <input class="mdl-textfield__input" type="text" name="user" value="{if isset($usuario)}{$usuario.userName}{/if}"/> 
                    <label class="mdl-textfield__label">Nombre de Usuario</label>
                </div>
            </div>
			<div class="mdl-cell mdl-cell--3-col">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" >
                    <input class="mdl-textfield__input" type="text" name="password" value=""/> 
                    <label class="mdl-textfield__label">Contraseña</label>
                </div>
            </div>
            <div class="mdl-cell mdl-cell--3-col">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" >
                    <input class="mdl-textfield__input" type="text" name="repeat_pass" value=""/> 
                    <label class="mdl-textfield__label">Repita Contraseña</label>
                </div>
            </div>
            <div class="mdl-cell mdl-cell--3-col auto">
	            <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-blocked">
				  <input type="checkbox" id="checkbox-blocked" class="mdl-checkbox__input" name="isActive"  {if isset($usuario.isActive) && $usuario.isActive eq 1}checked{/if}>
				  <span class="mdl-checkbox__label">Active</span>
				</label>
            </div>
		</div>
		<div style="clear: both; width: 100%">
			<a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-button--colored mdl-color--green-400 js-btn-save-user" style="float: right; margin: 8px;"> Guardar </a> <a
            class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-button--colored mdl-color--red-400 js-btn-cancel" style="float: right; margin: 8px;"> Cancelar </a>
		</div>
	</form>
</div>
{literal}
<script type="text/javascript">

function initChunkUsuarios() {
	
	$('#js-form-new-user').on('click','.js-btn-save-user',function(e) {
		e.preventDefault();
		 var form = $('#js-form-new-user').serialize();
		 var verb =  ($('#js-model-id').val() == 0) ? 'POST' : 'PUT';
		 $.ajax({
	 	        url: "/{$smarty.const.DOMAIN_PATH}/api/Users",
	 	        type: verb,
	 	        data: form,
	 	        beforeSend: function() {
	 	        	swal({isOnlyLoader: true ,title: "Guardando usuario",   type: "info",   showCancelButton: false,
	 	        		closeOnConfirm: false});
		        },
	 	        success: function (data) {
	 	        	tableUsers.ajax.reload();
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