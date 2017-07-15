<div class="row lock-form">
	<div class="col-md-4 col-sm-6 col-md-offset-4 col-sm-offset-3">
		<form action="./lockscreen" method="POST">
			<div class="card card-profile">
				<div class="card-avatar">
					<a href=""> <img class="avatar"
						src="modules/GFStarterKit/images/user.jpg" alt="...">
					</a>
				</div>
				<div class="card-content">
					<h4 class="card-title black">{$userName}</h4>
					<div class="form-group label-floating is-empty">
						<label class="control-label">Enter Password</label> <input
							type="password" name="password" class="form-control"> <span
							class="material-input"></span>
					</div>
				</div>
				<div class="card-footer">
					<button type="button" class="btn btn-rose btn-round js-unlock">Unlock</button>
				</div>
			</div>
		</form>
	</div>
</div>