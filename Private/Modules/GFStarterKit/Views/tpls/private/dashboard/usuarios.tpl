 {include file="../commons/header.tpl"}
 {include file="../commons/nav-bar.tpl"}
 {include file="../commons/menu-drawer.tpl"}
	<main class="mdl-layout__content mdl-color--grey-100">
		<div class="mdl-grid fullwidth">
			<div class="demo-graphs mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--12-col">
				<div class="demo-avatar-dropdown mdl-typography--title mdl-color-text--grey-600">
					<a id="js-new-user" class="mdl-button-mini mdl-js-button mdl-button--raised  mdl-js-ripple-effect">
						<i class="md material-icons icon-mini">add</i>
					</a> Listado de Usuarios
				</div>
				<br>
				<table id="data-table-users" class="mdl-data-table mdl-js-data-table mdl-shadow--2dp fullwidth table-bordered">
					<thead>
						<tr>
							<th class="mdl-data-table__cell--non-numeric ">Nombre</th>
							<th class="mdl-data-table__cell--non-numeric ">Email</th>
							<th class="mdl-data-table__cell--non-numeric ">Teléfono</th>
							<th class="mdl-data-table__cell--non-numeric ">Nombre de Usuario</th>
							<th class="mdl-data-table__cell--non-numeric ">Fecha creación</th>
							<th class="mdl-data-table__cell--non-numeric ">Acciones</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
		<div class="mdl-grid" id="js-usuarios-chunk-holder">
	        
        </div>
	</main>
{include file="../commons/footer.tpl"}
{include file="../commons/commonjs.tpl"}
<script src="modules/GFStarterKit/js/libraries/datatables.js"></script>
<script src="modules/GFStarterKit/js/dashboard/usuarios.js"></script>
</html>