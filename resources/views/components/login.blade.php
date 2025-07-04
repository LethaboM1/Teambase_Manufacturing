<!doctype html>
<html class="fixed dark">
<head>
<meta charset="utf-8">
		<title>TeamBase | Hillary Construction</title>
		<link rel="shortcut icon" type="image/png" href="img/logos/teambase_fav.png"/>
	
		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.css" />
		<link rel="stylesheet" href="vendor/animate/animate.compat.css">
		<link rel="stylesheet" href="vendor/font-awesome-6/css/all.min.css" />
		<link rel="stylesheet" href="vendor/boxicons/css/boxicons.min.css" />
		<link rel="stylesheet" href="vendor/magnific-popup/magnific-popup.css" />
		<link rel="stylesheet" href="vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />

		<!-- Theme CSS -->
		<link rel="stylesheet" href="css/theme.css" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="css/skins/default.css" />

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="css/custom.css">

		<!-- Head Libs -->
		<script src="vendor/modernizr/modernizr.js"></script>	
</head>

<body>
	<!-- start: page -->
		<section class="body-sign">
			<div class="center-sign">
				<div class="main_logo"><img src="img/logos/teambase_logo.png" height="169" alt="teambase" /></div>
				<div class="panel card-sign">
					<div class="card-title-sign mt-3 text-end">
						<h2 class="title text-uppercase font-weight-bold m-0"><i class="bx bx-user-circle me-1 text-6 position-relative top-5"></i>Sign In</h2>
					</div>
					<div class="card-body">
						<form action="login" method="post">
							@csrf
							<div class="form-group mb-3">
								<label>Username</label>
								<div class="input-group">
									<input name="username" type="text" class="form-control form-control-lg" />
									<span class="input-group-text">
										<i class="bx bx-user text-4"></i>
									</span>
								</div>
							</div>

							<div class="form-group mb-3">
								<div class="clearfix">
									<label class="float-left">Password</label>									
								</div>
								<div class="input-group">
									<input name="password" type="password" class="form-control form-control-lg" />
									<span class="input-group-text">
										<i class="bx bx-lock text-4"></i>
									</span>
								</div>
							</div>

							<div class="row">
								<div class="col-sm-9">
									<x-alert-messages />
								</div>
								<div class="col-sm-3 text-end">
									<button type="submit" class="btn btn-primary mt-2">Sign In</button>
								</div>
							</div>
						</form>
					</div>
			  </div>

				<p class="text-center text-muted mt-3 mb-3">TeamBase &copy; Copyright 2022. All Rights Reserved. {{env('APP_VER')}}</p>
		  </div>
		</section>
		<!-- end: page -->
	
		<!-- Vendor -->
<script src="vendor/jquery/jquery.js"></script>
<script src="vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
<script src="vendor/popper/umd/popper.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="vendor/common/common.js"></script>
<script src="vendor/nanoscroller/nanoscroller.js"></script>
<script src="vendor/magnific-popup/jquery.magnific-popup.js"></script>
<script src="vendor/jquery-placeholder/jquery.placeholder.js"></script>

		<!-- Specific Page Vendor -->

		<!-- Theme Base, Components and Settings -->
<script src="js/theme.js"></script>

		<!-- Theme Custom -->
<script src="js/custom.js"></script>

		<!-- Theme Initialization Files -->
<script src="js/theme.init.js"></script><!-- Vendor -->
<script src="vendor/jquery/jquery.js"></script>
<script src="vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
<script src="vendor/popper/umd/popper.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="vendor/common/common.js"></script>
<script src="vendor/nanoscroller/nanoscroller.js"></script>
<script src="vendor/magnific-popup/jquery.magnific-popup.js"></script>
<script src="vendor/jquery-placeholder/jquery.placeholder.js"></script>

		<!-- Specific Page Vendor -->

		<!-- Theme Base, Components and Settings -->
<script src="js/theme.js"></script>

		<!-- Theme Custom -->
<script src="js/custom.js"></script>

		<!-- Theme Initialization Files -->
<script src="js/theme.init.js"></script>
	
</body>
</html>