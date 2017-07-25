	<!-- HEADER -->
	<div id="header">

		<div id="logo">
			<a href="index.php"><img src="img/logo.png" alt="logo"></a>
		</div>

		<!-- LOG IN START -->
		<div id="login-container">
			<div id="form-wrapper">
				<form id="login" method="post" action="">
					<fieldset>
						<div class="input input-username">
							<label for="userLoginName"></label>
							<input name="name" type="text" id="userLoginName" placeholder="Name" required />
						</div>

						<div class="input input-password">
							<label for="userLoginPass"></label>
							<input name="pass" type="password" id="userLoginPass" placeholder="Password" required />
						</div>

						<div class="input input-phone">
							<label for="phone"></label>
							<input name="phone" type="text" id="phone" placeholder="Phone number" required />
						</div>

						<button id="btnCreateUser" type="button" class="submit">Create User</button>
						<button id="btnSubmitLogin" type="button" class="submit">Sign In</button>
						<button id="btnLogOut" type="button" class="submit">Log out</button>

					</fieldset>
				</form>
			</div> <!-- FORM-WRAPPER END -->
		</div> <!-- LOG IN END -->
	</div> <!-- HEADER END -->
	<div id="status-msg"></div>

