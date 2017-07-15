<div class="row">
					<div class="col-md-4 col-sm-6 col-md-offset-4 col-sm-offset-3">
						<form id="js-login-form" class="login-form">
							<input type="hidden" value="read" name="op">
							<input type="hidden" value="doLogin" name="sop">
							<div class="card card-login">
								<div class="card-header text-center header-card-color" >
									<h4 class="card-title">Login</h4>
								</div>
								<div class="card-content">
									<div class="input-group">
										<span class="input-group-addon"> <i class="material-icons">face</i>
										</span>
										<div class="form-group label-floating is-empty">
											<label class="control-label">{$i18n["admin-login"].usuario}</label>
											<input id="user" type="text" class="form-control" name="user"> 
											<span class="material-input"></span>
										</div>
									</div>
									<div class="input-group">
										<span class="input-group-addon"> <i class="material-icons">lock_outline</i>
										</span>
										<div class="form-group label-floating is-empty">
											<label class="control-label">{$i18n["admin-login"].contrasena}</label> 
											<input id="userpass" type="password" class="form-control" name="password">
											<span class="material-input"></span>
										</div>
									</div>
								</div>
								<div class="footer text-center">
									<button type="submit" class="btn btn-primary js-login-button main-button-color">{$i18n["admin-login"].login}<div class="ripple-container"></div></button>
								</div>
							</div>
						</form>
					</div>
				</div>