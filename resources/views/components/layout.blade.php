
<!doctype html>
<html class="fixed dark">
	<head>
		<!-- Basic -->
		<meta charset="UTF-8">

		<title>TeamBase | Hillary Construction | Dashboard</title>
		<link rel="shortcut icon" type="image/png" href="{{url('img/logos/teambase_fav.png')}}"/>

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="{{url('vendor/bootstrap/css/bootstrap.css')}}" />
		<link rel="stylesheet" href="{{url('vendor/animate/animate.compat.css')}}">
		<link rel="stylesheet" href="{{url('vendor/font-awesome-6/css/all.min.css')}}" />
		<link rel="stylesheet" href="{{url('vendor/boxicons/css/boxicons.min.css')}}" />
		<link rel="stylesheet" href="{{url('vendor/magnific-popup/magnific-popup.css')}}" />
		<link rel="stylesheet" href="{{url('vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css')}}" />
		<link rel="stylesheet" href="{{url('vendor/jquery-ui/jquery-ui.css')}}" />
		<link rel="stylesheet" href="{{url('vendor/jquery-ui/jquery-ui.theme.css')}}" />
		<link rel="stylesheet" href="{{url('vendor/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" />
		<link rel="stylesheet" href="{{url('vendor/bootstrap-tagsinput/bootstrap-tagsinput.css')}}" />
		<link rel="stylesheet" href="{{url('vendor/bootstrap-colorpicker/css/bootstrap-colorpicker.css')}}" />
		<link rel="stylesheet" href="{{url('vendor/bootstrap-timepicker/css/bootstrap-timepicker.css')}}" />
		<link rel="stylesheet" href="{{url('vendor/bootstrap-fileupload/bootstrap-fileupload.min.css')}}" />
		<link rel="stylesheet" href="{{url('vendor/dropzone/basic.css')}}" />
		<link rel="stylesheet" href="{{url('vendor/dropzone/dropzone.css')}}" />
		<link rel="stylesheet" href="{{url('vendor/bootstrap-markdown/css/bootstrap-markdown.min.css')}}" />
		<link rel="stylesheet" href="{{url('vendor/summernote/summernote-bs4.css')}}" />
		<link rel="stylesheet" href="{{url('vendor/codemirror/lib/codemirror.css')}}" />
		<link rel="stylesheet" href="{{url('vendor/codemirror/theme/monokai.css')}}" />
		
		<!-- Specific Page Vendor CSS -->		
		<link rel="stylesheet" href="{{url('vendor/select2/css/select2.css')}}" />		
		<link rel="stylesheet" href="{{url('vendor/select2-bootstrap-theme/select2-bootstrap.min.css')}}" />		
		<link rel="stylesheet" href="{{url('vendor/pnotify/pnotify.custom.css')}}" />	

		@livewireStyles

		<!-- Theme CSS -->
		<link rel="stylesheet" href="{{url('css/theme.css')}}" />
		
		<!-- Skin CSS -->
		<link rel="stylesheet" href="{{url('css/skins/default.css')}}" />

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="{{url('css/custom.c')}}ss">

		<!-- Head Libs -->
		<script src="{{url('vendor/modernizr/modernizr.js')}}"></script>
		<script src="{{url('vendor/jquery/jquery.js')}}"></script>
		{{-- <script src="{{url('vendor/jquery-inputmask/jquery.inputmask.js')}}"></script> --}}
		{{-- <script src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script> --}}
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>

	</head>

<body>
	<!-- start: page -->
		<section class="body">            
			<!-- start: header -->
			<header class="header">
				<div class="logo-container">
					<a href="#" class="logo">
						<img src="{{url('img/logos/teambase_logo_long.png')}}" width="202" height="45" alt="TeamBase Logo" />
					</a>
					<div class="d-md-none toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
						<i class="fas fa-bars" aria-label="Toggle sidebar"></i>
				  </div>
			  </div>
				<!-- start: user box -->
				<div class="header-right">
					<span class="separator"></span>
					<livewire:components.notifications-livewire />
					<script>
						window.setInterval(function(){
							Livewire.emit('refreshNotifications');
						}, 60000);
				 	</script>
					 {{-- <ul class="notifications">
						<li>							
							<a href="#" class="dropdown-toggle notification-icon" data-bs-toggle="dropdown">
								<i class="bx bx-list-ol"></i>
								@if($total_notifications > 0)
									<span class="badge">{{$total_notifications}}</span>
								@endif
							</a>
							<div class="dropdown-menu notification-menu large">
								<div class="notification-title">
									@if($total_notifications > 0)
										<span class="float-end badge badge-default">{{$total_notifications}}</span>
										Requests
									@endif
								</div>
								<div class="content"> --}}
									 
									{{-- <ul>
										@if($total_notifications > 0)

											@foreach ($transfer_requests as $transfer)
												<li>
													<p class="clearfix mb-1">
														<span class="message float-start">Transfer Requested - {{$transfer['created_at']}} Dispatch No {{$transfer['dispatch_number']}}</span>													
													</p>												
												</li>											
											@endforeach

											@foreach ($adjustment_requests as $adjustments)
												<li>
													<p class="clearfix mb-1">
														<span class="message float-start">Adjustment Requested - {{$adjustment['created_at']}} Product {{$adjustment['description']}}</span>													
													</p>												
												</li>											
											@endforeach
										
										@else

											<li>
												<p class="clearfix mb-1">
													<span class="message float-start">Nothing for now...</span>													
												</p>												
											</li>
										
										@endif --}}

										{{--<li>
											<p class="clearfix mb-1">
												<span class="message float-start">Generating Sales Report</span>
												<span class="message float-end text-dark">60%</span>
											</p>
											<div class="progress progress-xs light">
												<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;"></div>
											</div>
										</li>
										 <li>
											<p class="clearfix mb-1">
												<span class="message float-start">Importing Contacts</span>
												<span class="message float-end text-dark">98%</span>
											</p>
											<div class="progress progress-xs light">
												<div class="progress-bar" role="progressbar" aria-valuenow="98" aria-valuemin="0" aria-valuemax="100" style="width: 98%;"></div>
											</div>
										</li>
										<li>
											<p class="clearfix mb-1">
												<span class="message float-start">Uploading something big</span>
												<span class="message float-end text-dark">33%</span>
											</p>
											<div class="progress progress-xs light mb-1">
												<div class="progress-bar" role="progressbar" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100" style="width: 33%;"></div>
											</div>
										</li> 
									</ul>
								</div>
							</div>
						</li>
					</ul>--}}
					<span class="separator"></span>
					<div id="userbox" class="userbox">
						<a href="#" data-bs-toggle="dropdown">
							<figure class="profile-picture">
								{{-- <img src="../img/staff/Jack.jpg" alt="Joseph Doe" class="rounded-circle" data-lock-picture="../img/staff/Jack.jpg" /> --}}
							</figure>
							<div class="profile-info" data-lock-name="{{auth()->user()->name}} {{auth()->user()->last_name}}" data-lock-email="{{auth()->user()->email}}">
								<span class="name">{{ucfirst(auth()->user()->name)}} {{ucfirst(auth()->user()->last_name)}}</span>
								<span class="role">{{strtoupper(auth()->user()->role)}}</span>
							</div>
							<i class="fa custom-caret"></i>
						</a>
						<div class="dropdown-menu">
							<ul class="list-unstyled mb-2">								
								@if(null != Auth::user()->getSec())
									
									@if(Auth::user()->getSec()->getCRUD('user_profile_crud')['read']=='true'  || Auth::user()->getSec()->global_admin_value)
										<li><a role="menuitem" tabindex="-1" href="/user/view/{{Auth::user()->user_id}}"><i class="bx bx-user-circle"></i>&nbsp; My Profile</a></li>  
									@endif

									@if(Auth::user()->out_of_office == 0)
										<li>
											<form method="post" action="{{ url('user/ooo/'.Auth::user()->user_id) }}" id="frm_ooo">
												@csrf
												<input type="hidden" name="user_id" id="user_id" value="{{Auth::user()->user_id}}" >
												<input type="hidden" name="out_of_office" id="out_of_office" value=false >												
												<a onclick="document.getElementById('frm_ooo').submit();" role="menuitem" ><i class="bx bx-lock"></i>&nbsp; Out of Office</a>
											</form>
										</li>                    
									@else
										<li>
											<form method="post" action="{{ url('user/ooo/'.Auth::user()->user_id) }}" id="frm_ooo">
												@csrf
												<input type="hidden" name="user_id" id="user_id" value="{{Auth::user()->user_id}}" >
												<input type="hidden" name="out_of_office" id="out_of_office" value=true >												
												<a onclick="document.getElementById('frm_ooo').submit();" role="menuitem" ><i class="bx bx-lock"></i>&nbsp; Back at Office</a>
											</form>
										</li>										
									@endif

									<li class="divider"></li>

									@if(Auth::user()->getSec()->getCRUD('user_man_crud')['read'] == 'true' || Auth::user()->getSec()->global_admin_value)
										<li><a role="menuitem" tabindex="-1" href="{{url('users')}}"><i class='bx bx-group'></i>&nbsp; User Management</a></li>                    
									@endif 

									@if(Auth::user()->getSec()->settings_admin_value || Auth::user()->getSec()->global_admin_value)
										<li><a role="menuitem" tabindex="-1" href="/settings"><i class="fa-regular fa-compass"></i>&nbsp; Settings</a></li>                    
									@endif									

								@endif

								{{-- @if (strtoupper(auth()->user()->role) == 'MANAGER')
									<li>
										<a role="menuitem" tabindex="-1" href="{{route('settings')}}"><i class="fa-regular fa-compass"></i> Settings</a>
									</li>
								@endif --}}
								
								<li class="divider"></li>
								
								<li>
									<a role="menuitem" tabindex="-1" href="{{route('logout') }}"><i class="bx bx-power-off"></i> Logout</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<!-- end: search & user box -->
			</header>
			<!-- end: header -->
			<div class="inner-wrapper">
				<!-- start: sidebar -->
				<aside id="sidebar-left" class="sidebar-left">
				    <div class="sidebar-header">
				        <div class="sidebar-title">Navigation</div>
				        <div class="sidebar-toggle d-none d-md-block" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
				            <i class="fas fa-bars" aria-label="Toggle sidebar"></i>
				        </div>
				    </div>
					<!-- Navigation Start -->
                    <x-navigation.nav />

				    {{-- <?= include('navigation/nav.php'); ?> --}}

					<!-- Navigation end -->
				</aside>
				<!-- end: sidebar -->
				<section role="main" class="content-body">
					<header class="page-header">
						<h2>{{$page_title}}</h2>
					</header>
					<x-alert-messages />
					<!-- start: page -->			
					{{$slot}}
					<!-- end: page -->
				</section>
			</div>
		</section>
		<!-- end: page -->
	
<!-- Vendor -->
{{-- <script src="{{url('vendor/jquery/jquery.js')}}"></script> --}}
<script src="{{url('vendor/jquery-browser-mobile/jquery.browser.mobile.js')}}"></script>
<script src="{{url('vendor/popper/umd/popper.min.js')}}"></script>	
<script src="{{url('vendor/bootstrap/js/bootstrap.js')}}"></script>
<script src="{{url('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{url('vendor/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>
<script src="{{url('vendor/common/common.js')}}"></script>	
<script src="{{url('vendor/nanoscroller/nanoscroller.js')}}"></script>
<script src="{{url('vendor/magnific-popup/jquery.magnific-popup.js')}}"></script>
<script src="{{url('vendor/jquery-placeholder/jquery.placeholder.js')}}"></script>
<script src="{{url('vendor/jquery.easy-pie-chart/jquery.easypiechart.js')}}"></script>
<script src="{{url('vendor/jquery-ui/jquery-ui.js')}}"></script>		
<script src="{{url('vendor/jqueryui-touch-punch/jquery.ui.touch-punch.js')}}"></script>
<script src="{{url('vendor/jquery-appear/jquery.appear.js')}}"></script>
<script src="{{url('vendor/jquery-maskedinput/jquery.maskedinput.js')}}"></script>
<script src="{{url('vendor/bootstrap-tagsinput/bootstrap-tagsinput.js')}}"></script>
<script src="{{url('vendor/bootstrapv5-multiselect/js/bootstrap-multiselect.js')}}"></script>
<script src="{{url('vendor/flot/jquery.flot.js')}}"></script>
<script src="{{url('vendor/flot.tooltip/jquery.flot.tooltip.js')}}"></script>
<script src="{{url('vendor/flot/jquery.flot.pie.js')}}"></script>
<script src="{{url('vendor/flot/jquery.flot.categories.js')}}"></script>
<script src="{{url('vendor/flot/jquery.flot.resize.js')}}"></script>
<script src="{{url('vendor/jquery-sparkline/jquery.sparkline.js')}}"></script>
<script src="{{url('vendor/raphael/raphael.js')}}"></script>
<script src="{{url('vendor/morris/morris.js')}}"></script>
<script src="{{url('vendor/gauge/gauge.js')}}"></script>
<script src="{{url('vendor/snap.svg/snap.svg.js')}}"></script>
<script src="{{url('vendor/liquid-meter/liquid.meter.js')}}"></script>
<script src="{{url('vendor/chartist/chartist.js')}}"></script>

<!-- Specific Page Vendor -->		
<script src="{{url('vendor/select2/js/select2.js')}}"></script>
<script src="{{url('vendor/pnotify/pnotify.custom.js')}}"></script>

<!-- Theme Base, Components and Settings -->
<script src="{{url('js/theme.js')}}"></script>

<!-- Theme Custom -->
<script src="{{url('js/custom.js')}}"></script>

<!-- Theme Initialization Files -->
<script src="{{url('js/theme.init.js')}}"></script>

<!-- Examples -->
<script src="{{url('js/examples/examples.dashboard.js')}}"></script>
<script src="{{url('js/examples/examples.modals.js')}}"></script>
<script src="{{url('js/examples/examples.charts.js')}}"></script>

@stack('scripts')

@livewireScripts

<!-- Examples --> 
<style>
    #ChartistCSSAnimation .ct-series.ct-series-a .ct-line {
        fill: none;
        stroke-width: 4px;
        stroke-dasharray: 5px;
        -webkit-animation: dashoffset 1s linear infinite;
        -moz-animation: dashoffset 1s linear infinite;
        animation: dashoffset 1s linear infinite;
    }

    #ChartistCSSAnimation .ct-series.ct-series-b .ct-point {
        -webkit-animation: bouncing-stroke 0.5s ease infinite;
        -moz-animation: bouncing-stroke 0.5s ease infinite;
        animation: bouncing-stroke 0.5s ease infinite;
    }

    #ChartistCSSAnimation .ct-series.ct-series-b .ct-line {
        fill: none;
        stroke-width: 3px;
    }

    #ChartistCSSAnimation .ct-series.ct-series-c .ct-point {
        -webkit-animation: exploding-stroke 1s ease-out infinite;
        -moz-animation: exploding-stroke 1s ease-out infinite;
        animation: exploding-stroke 1s ease-out infinite;
    }

    #ChartistCSSAnimation .ct-series.ct-series-c .ct-line {
        fill: none;
        stroke-width: 2px;
        stroke-dasharray: 40px 3px;
    }

    @-webkit-keyframes dashoffset {
        0% {
            stroke-dashoffset: 0px;
        }

        100% {
            stroke-dashoffset: -20px;
        };
    }

    @-moz-keyframes dashoffset {
        0% {
            stroke-dashoffset: 0px;
        }

        100% {
            stroke-dashoffset: -20px;
        };
    }

    @keyframes dashoffset {
        0% {
            stroke-dashoffset: 0px;
        }

        100% {
            stroke-dashoffset: -20px;
        };
    }

    @-webkit-keyframes bouncing-stroke {
        0% {
            stroke-width: 5px;
        }

        50% {
            stroke-width: 10px;
        }

        100% {
            stroke-width: 5px;
        };
    }

    @-moz-keyframes bouncing-stroke {
        0% {
            stroke-width: 5px;
        }

        50% {
            stroke-width: 10px;
        }

        100% {
            stroke-width: 5px;
        };
    }

    @keyframes bouncing-stroke {
        0% {
            stroke-width: 5px;
        }

        50% {
            stroke-width: 10px;
        }

        100% {
            stroke-width: 5px;
        };
    }

    @-webkit-keyframes exploding-stroke {
        0% {
            stroke-width: 2px;
            opacity: 1;
        }

        100% {
            stroke-width: 20px;
            opacity: 0;
        };
    }

    @-moz-keyframes exploding-stroke {
        0% {
            stroke-width: 2px;
            opacity: 1;
        }

        100% {
            stroke-width: 20px;
            opacity: 0;
        };
    }

    @keyframes exploding-stroke {
        0% {
            stroke-width: 2px;
            opacity: 1;
        }

        100% {
            stroke-width: 20px;
            opacity: 0;
        };
    }
</style>
</body>
</html>