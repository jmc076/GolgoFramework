<div class="row">
					<div class="col-md-4 col-sm-6 col-md-offset-4 col-sm-offset-3">
						<form id="js-login-form" class="login-form">
							<input type="hidden" value="read" name="op">
							<input type="hidden" value="doLogin" name="sop">
							<div class="card card-login">
								<div class="card-header text-center header-card-color" >
									<h4 class="card-title">{$i18n["admin-register"].formTitle}</h4>
								</div>
								<div class="card-content">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">face</i>
                                                </span>
                                                <div class="form-group is-empty"><input type="text" name="user" class="form-control" placeholder="{$i18n["admin-register"].user}"><span class="material-input"></span></div>
                                            </div>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">email</i>
                                                </span>
                                                <div class="form-group is-empty"><input type="text" name="email" class="form-control" placeholder="{$i18n["admin-register"].email}"><span class="material-input"></span></div>
                                            </div>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">lock_outline</i>
                                                </span>
                                                <div class="form-group is-empty"><input type="password" name="pass" placeholder="{$i18n["admin-register"].pass}" class="form-control"><span class="material-input"></span></div>
                                            </div>
                                            <!-- If you want to add a checkbox to this form, uncomment this code -->
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="optionsCheckboxes" checked=""><span class="checkbox-material"></span> I agree to the
                                                    <a href="#something">terms and conditions</a>.
                                                </label>
                                            </div>
                                        </div>
								<div class="footer text-center">
									<button type="submit" class="btn btn-primary js-login-button main-button-color">{$i18n["admin-register"].register}<div class="ripple-container"></div></button>
								</div>
							</div>
						</form>
					</div>
				</div>