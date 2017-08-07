<div class="row">
					<div class="col-md-4 col-sm-6 col-md-offset-4 col-sm-offset-3">
						<form id="js-login-form" class="login-form">
							<input type="hidden" value="read" name="op">
							<input type="hidden" value="doLogin" name="sop">
							<div class="card card-login">
								<div class="card-header text-center header-card-color" >
									<h4 class="card-title">{$i18n["admin-login"].alreadyLogged}</h4>
								</div>
								<div class="card-content">
									<div class="footer text-center">
									<a href="/{$smarty.const.DOMAIN_PATH}/dashboard" type="submit" class="btn btn-primary main-button-color">
									<i class="material-icons">exit_to_app</i>
									{$i18n["admin-login"].continue}<div class="ripple-container"></div></a>
								</div>
									<div class="footer text-center">
									<a href="/{$smarty.const.DOMAIN_PATH}/exit" type="submit" class="btn btn-primary main-button-color">
									<i class="material-icons">power_settings_new</i>
									{$i18n["admin-login"].exit}<div class="ripple-container"></div></a>
								</div>
								</div>
								
							</div>
						</form>
					</div>
				</div>