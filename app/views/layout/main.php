<?php checkLoggedIn('true'); ?>
<!doctype html>
<html lang="en" class="layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr" data-skin="default" data-bs-theme="light" data-assets-path="/template/" data-template="vertical-menu-template">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
		<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
		<meta name="robots" content="noindex, nofollow" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
		<title><?php echo CONFIGURATION_SYSTEM_NAME; ?> <?php echo CONFIGURATION_SYSTEM_VERSION; ?></title>
		<meta name="description" content="" />
		<!-- Favicon -->
		<link rel="icon" type="image/x-icon" href="/public/img/favicon/favicon.ico" />
		<!-- Fonts -->
		<link rel="preconnect" href="https://fonts.googleapis.com" />
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
		<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"/>
		<link rel="stylesheet" href="/public/vendor/fonts/iconify-icons.css" />
		<!-- Core CSS -->
		<!-- build:css assets/vendor/css/theme.css  -->
		<link rel="stylesheet" href="/public/vendor/libs/node-waves/node-waves.css" />
		<link rel="stylesheet" href="/public/vendor/libs/pickr/pickr-themes.css" />
		<link rel="stylesheet" href="/public/vendor/css/core.css" />
		<link rel="stylesheet" href="/public/css/demo.css?ver=<?php echo strtotime(date('Ymd')); ?>" />
		<!-- Vendors CSS -->
		<link rel="stylesheet" href="/public/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
		<link rel="stylesheet" href="/public/vendor/libs/bootstrap-select/bootstrap-select.css" />
		<!-- endbuild -->
		<!-- Page CSS -->
		<link rel="stylesheet" href="/public/vendor/css/pages/page-faq.css" />
		<link rel="stylesheet" href="/public/vendor/libs/notyf/notyf.css" />

		<!-- Added Core jQuery -->
		<script src="/public/lib/jquery/jquery.js"></script>

		<!-- Helpers -->
		<script src="/public/vendor/js/helpers.js"></script>
		<!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
		<!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
		<script src="/public/vendor/js/template-customizer.js"></script>
		<!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
		<script src="/public/js/config.js"></script>
	</head>
	<body id="<?php echo (getVar('controller') ? getVar('controller') : '') . '-' . (getVar('view') ? getVar('view') : ''); ?>">
		<!-- Layout wrapper -->
		<div class="layout-wrapper layout-content-navbar">
			<div class="layout-container">
			
				<!-- Menu -->
				<aside id="layout-menu" class="layout-menu menu-vertical menu">
					<div class="app-brand">
						<a href="/" class="app-brand-link">
							<img src="/public/img/logo.png" class="logo">
						</a>
						<a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
							<i class="icon-base ti menu-toggle-icon d-none d-xl-block"></i>
							<i class="icon-base ti tabler-x d-block d-xl-none"></i>
						</a>
					</div>
					<div class="menu-inner-shadow"></div>
					<br>
					<ul class="menu-inner py-1">
						<li class="menu-item <?php activeDashboard('active'); ?>">
							<a href="/" class="menu-link">
								<i class="menu-icon icon-base ti tabler-settings"></i>
								<div data-i18n="DASHBOARD">DASHBOARD</div>
							</a>
						</li>
						<li class="menu-header small">
							<span class="menu-header-text" data-i18n="MODULES">MODULES</span>
						</li>

						<li class="menu-item <?php activeView(['finance'], ['soa-dashboard', 'soa-immediate', 'soa-scheduled', 'soa-setting', 'soa-master', 'soa-letter', 'soa-letter-manage', 'soa-download', 'soa-email-template', 'soa-email-generic'], 'active open'); ?>">
							<a href="javascript:void(0)" class="menu-link menu-toggle">
								<i class="menu-icon icon-base ti tabler-settings"></i>
								<div data-i18n="Finance">Finance</div>
							</a>
							<ul class="menu-sub">
								<li class="menu-item <?php activeView(['finance'], ['soa-dashboard', 'soa-immediate', 'soa-scheduled', 'soa-setting', 'soa-master', 'soa-letter', 'soa-letter-manage', 'soa-download', 'soa-email-template', 'soa-email-generic'], 'active open'); ?>">
									<a href="javascript:void(0)" class="menu-link menu-toggle">
										<div data-i18n="SOA">SOA</div>
									</a>
									<ul class="menu-sub">
										<li class="menu-item <?php activeView(['finance'], ['soa-dashboard'], 'active'); ?>">
											<a href="/finance/soa-dashboard" class="menu-link">
												<div data-i18n="Dashboard">Dashboard</div>
											</a>
										</li>
										<li class="menu-item <?php activeView(['finance'], ['soa-immediate', 'soa-scheduled'], 'active open'); ?>">
											<a href="javascript:void(0)" class="menu-link menu-toggle">
												<div data-i18n="Mailing">Mailing</div>
											</a>
											<ul class="menu-sub">
												<li class="menu-item <?php activeView(['finance'], ['soa-immediate'], 'active'); ?>">
													<a href="/finance/soa-immediate/1" class="menu-link">
														<div data-i18n="Immediate">Immediate</div>
													</a>
												</li>
												<li class="menu-item <?php activeView(['finance'], ['soa-scheduled'], 'active'); ?>">
													<a href="/finance/soa-scheduled/1" class="menu-link">
														<div data-i18n="Scheduled">Scheduled</div>
													</a>
												</li>
											</ul>
										</li>
										<li class="menu-item <?php activeView(['finance'], ['soa-setting'], 'active'); ?>">
											<a href="/finance/soa-setting/1" class="menu-link">
												<div data-i18n="Settings">Settings</div>
											</a>
										</li>
										<li class="menu-item <?php activeView(['finance'], ['soa-master'], 'active'); ?>">
											<a href="/finance/soa-master/1" class="menu-link">
												<div data-i18n="Master">Master</div>
											</a>
										</li>
										<li class="menu-item <?php activeView(['finance'], ['soa-letter', 'soa-letter-manage'], 'active'); ?>">
											<a href="/finance/soa-letter/1" class="menu-link">
												<div data-i18n="Letters">Letters</div>
											</a>
										</li>
										<li class="menu-item <?php activeView(['finance'], ['soa-download'], 'active'); ?>">
											<a href="/finance/soa-download/1" class="menu-link">
												<div data-i18n="Download">Download</div>
											</a>
										</li>
										<li class="menu-item <?php activeView(['finance'], ['soa-email-template', 'soa-email-generic'], 'active open'); ?>">
											<a href="javascript:void(0)" class="menu-link menu-toggle">
												<div data-i18n="Email">Email</div>
											</a>
											<ul class="menu-sub">
												<li class="menu-item <?php activeView(['finance'], ['soa-email-template'], 'active'); ?>">
													<a href="/finance/soa-email-template/1" class="menu-link">
														<div data-i18n="Template">Template</div>
													</a>
												</li>
												<li class="menu-item <?php activeView(['finance'], ['soa-email-generic'], 'active'); ?>">
													<a href="/finance/soa-email-generic/1" class="menu-link">
														<div data-i18n="Generic">Generic</div>
													</a>
												</li>
											</ul>
										</li>
									</ul>
								</li>
							</ul>
						</li>
						<li class="menu-item <?php activeView(['collection'], ['all', 'manage'], 'active'); ?>">
							<a href="/collection/all/1" class="menu-link">
								<i class="menu-icon icon-base ti tabler-settings"></i>
								<div data-i18n="Collection">Collection</div>
							</a>
						</li>
						<li class="menu-item <?php activeView(['report'], ['manual', 'automatic'], 'active open'); ?>">
							<a href="javascript:void(0);" class="menu-link menu-toggle">
								<i class="menu-icon icon-base ti tabler-settings"></i>
								<div data-i18n="Reports">Reports</div>
							</a>
							<ul class="menu-sub">
								<li class="menu-item <?php activeView(['report'], ['manual'], 'active open'); ?>">
									<a href="/report/manual" class="menu-link">
										<div data-i18n="Manual Generation">Manual Generation</div>
									</a>
								</li>
								<li class="menu-item <?php activeView(['report'], ['automatic'], 'active open'); ?>">
									<a href="/report/automatic/1" class="menu-link">
										<div data-i18n="Automatic Generation">Automatic Generation</div>
									</a>
								</li>
							</ul>
						</li>
						<li class="menu-header small">
							<span class="menu-header-text" data-i18n="MASTER">MASTER</span>
						</li>
						<li class="menu-item <?php activeView(['master'], ['topro', 'branch', 'segment', 'handler', 'team-leader', 'sales-channel', 'intermediary', 'class-business'], 'active open'); ?>">
							<a href="javascript:void(0);" class="menu-link menu-toggle">
								<i class="menu-icon icon-base ti tabler-settings"></i>
								<div data-i18n="Maintenance">Maintenance</div>
							</a>
							<ul class="menu-sub">
								<li class="menu-item <?php activeView(['master'], ['topro'], 'active open'); ?>">
									<a href="/master/topro" class="menu-link">
										<div data-i18n="TOPRO">TOPRO</div>
									</a>
								</li>
								<li class="menu-item <?php activeView(['master'], ['branch'], 'active open'); ?>">
									<a href="/master/branch" class="menu-link">
										<div data-i18n="Branch">Branch</div>
									</a>
								</li>
								<li class="menu-item <?php activeView(['master'], ['segment'], 'active open'); ?>">
									<a href="/master/segment" class="menu-link">
										<div data-i18n="Segment">Segment</div>
									</a>
								</li>
								<li class="menu-item <?php activeView(['master'], ['handler'], 'active open'); ?>">
									<a href="/master/handler" class="menu-link">
										<div data-i18n="Handler">Handler</div>
									</a>
								</li>
								<li class="menu-item <?php activeView(['master'], ['team-leader'], 'active open'); ?>">
									<a href="/master/team-leader" class="menu-link">
										<div data-i18n="Team Leader">Team Leader</div>
									</a>
								</li>
								<li class="menu-item <?php activeView(['master'], ['sales-channel'], 'active open'); ?>">
									<a href="/master/sales-channel" class="menu-link">
										<div data-i18n="Sales Channel">Sales Channel</div>
									</a>
								</li>
								<li class="menu-item <?php activeView(['master'], ['intermediary'], 'active open'); ?>">
									<a href="/master/intermediary" class="menu-link">
										<div data-i18n="Intermediary">Intermediary</div>
									</a>
								</li>
								<li class="menu-item <?php activeView(['master'], ['class-business'], 'active open'); ?>">
									<a href="/master/class-business" class="menu-link">
										<div data-i18n="Class of Busines">Class of Business</div>
									</a>
								</li>
							</ul>
						</li>
						<li class="menu-header small">
							<span class="menu-header-text" data-i18n="MAINTENANCE">MAINTENANCE</span>
						</li>
						<li class="menu-item <?php activeView(['account'], ['all', 'manage', 'user'], 'active open'); ?>">
							<a href="javascript:void(0);" class="menu-link menu-toggle">
								<i class="menu-icon icon-base ti tabler-settings"></i>
								<div data-i18n="Account">Account</div>
							</a>
							<ul class="menu-sub">
								<li class="menu-item <?php activeView(['account'], ['all', 'manage'], 'active'); ?>">
									<a href="/account/all/1" class="menu-link">
										<div data-i18n="Records">Records</div>
									</a>
								</li>
								<li class="menu-item <?php activeView(['account'], ['user'], 'active'); ?>">
									<a href="/account/user/1" class="menu-link">
										<div data-i18n="User Profile">User Profile</div>
									</a>
								</li>
							</ul>
						</li>
						<li class="menu-item <?php activeView(['notification', 'miscellaneous'], ['email', 'cron-job', 'faq'], 'active open'); ?>">
							<a href="javascript:void(0);" class="menu-link menu-toggle">
								<i class="menu-icon icon-base ti tabler-settings"></i>
								<div data-i18n="Control Panel">Control Panel</div>
							</a>
							<ul class="menu-sub">
								<li class="menu-item <?php activeView(['notification'], ['email'], 'active'); ?>">
									<a href="/notification/email/1" class="menu-link">
									<div data-i18n="Email Notificaton">Email Notificaton</div>
									</a>
								</li>
								<li class="menu-item <?php activeView(['miscellaneous'], ['cron-job'], 'active'); ?>">
									<a href="/miscellaneous/cron-job" class="menu-link">
										<div data-i18n="Cron Jobs Schedule">Cron Jobs Schedule</div>
									</a>
								</li>
								<li class="menu-item <?php activeView(['miscellaneous'], ['faq'], 'active'); ?>">
									<a href="/miscellaneous/faq" class="menu-link">
										<div data-i18n="FAQs">FAQs</div>
									</a>
								</li>
							</ul>
						</li>
					</ul>
				</aside>

				<div class="menu-mobile-toggler d-xl-none rounded-1">
					<a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large text-bg-secondary p-2 rounded-1">
						<i class="ti tabler-menu icon-base"></i>
						<i class="ti tabler-chevron-right icon-base"></i>
					</a>
				</div>
				<!-- / Menu -->

				<!-- Layout container -->
				<div class="layout-page">
					<!-- Navbar -->
					<nav class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
						<div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
							<a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
								<i class="icon-base ti tabler-menu-2 icon-md"></i>
							</a>
						</div>
						<div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
							<!-- Search -->
							<div class="navbar-nav align-items-center">
								<div class="nav-item navbar-search-wrapper px-md-0 px-2 mb-0">
									<a class="nav-item nav-link search-toggler d-flex align-items-center px-0" href="javascript:void(0);">
										<span class="d-inline-block text-body-secondary fw-normal" id="autocomplete"></span>
									</a>
								</div>
							</div>
							<!-- /Search -->

							<ul class="navbar-nav flex-row align-items-center ms-md-auto">
								<!-- Style Switcher -->
								<li class="nav-item dropdown">
									<a class="nav-link dropdown-toggle hide-arrow btn btn-icon btn-text-secondary rounded-pill" id="nav-theme" href="javascript:void(0);" data-bs-toggle="dropdown">
										<i class="icon-base ti tabler-sun icon-22px theme-icon-active text-heading"></i>
										<span class="d-none ms-2" id="nav-theme-text">Toggle theme</span>
									</a>
									<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="nav-theme-text">
										<li>
											<button type="button" class="dropdown-item align-items-center active" data-bs-theme-value="light" aria-pressed="false">
												<span><i class="icon-base ti tabler-sun icon-22px me-3" data-icon="sun"></i>Light</span>
											</button>
										</li>
										<li>
											<button type="button" class="dropdown-item align-items-center" data-bs-theme-value="dark" aria-pressed="true">
												<span><i class="icon-base ti tabler-moon-stars icon-22px me-3" data-icon="moon-stars"></i>Dark</span>
											</button>
										</li>
									</ul>
								</li>
								<!-- / Style Switcher-->

								<!-- User -->
								<li class="nav-item navbar-dropdown dropdown-user dropdown">
									<a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
										<div class="avatar avatar-online">
											<img src="/public/img/avatars/1.png" alt class="rounded-circle" />
										</div>
									</a>
									<ul class="dropdown-menu dropdown-menu-end">
										<li>
											<a class="dropdown-item mt-0">
												<div class="d-flex align-items-center">
													<div class="flex-shrink-0 me-2">
														<div class="avatar avatar-online">
															<img src="/public/img/avatars/1.png" alt class="rounded-circle" />
														</div>
													</div>
													<div class="flex-grow-1">
														<h6 class="mb-0">John Doe</h6>
														<small class="text-body-secondary">Admin</small>
													</div>
												</div>
											</a>
										</li>
										<li>
											<div class="dropdown-divider my-1 mx-n2"></div>
										</li>
										<li>
											<a href="/account/profile" class="dropdown-item">
												<i class="icon-base ti tabler-user me-3 icon-md"></i><span class="align-middle">Profile</span>
											</a>
										</li>
										<li>
											<a href="/account/security" class="dropdown-item">
												<i class="icon-base ti tabler-settings me-3 icon-md"></i><span class="align-middle">Security</span>
											</a>
										</li>
										<li>
											<div class="dropdown-divider my-1 mx-n2"></div>
										</li>
										<li>
											<div class="d-grid px-2 pt-2 pb-1">
												<a href="/logout" class="btn btn-sm btn-danger d-flex">
													<small class="align-middle">Logout</small>
													<i class="icon-base ti tabler-logout ms-2 icon-14px"></i>
												</a>
											</div>
										</li>
									</ul>
								</li>
								<!--/ User -->
							</ul>
						</div>
					</nav>
					<!-- / Navbar -->

					<!-- Content wrapper -->
					<div class="content-wrapper">
						<?php require_once('routes.php'); ?>
						<div class="content-backdrop fade"></div>
					</div>
					<!-- Content wrapper -->
				</div>
				<!-- / Layout page -->
			</div>

			<!-- Overlay -->
			<div class="layout-overlay layout-menu-toggle"></div>
			<!-- Drag Target Area To SlideIn Menu On Small Screens -->
			<div class="drag-target"></div>
		</div>
		<!-- / Layout wrapper -->

		<!-- Core JS -->
		<!-- build:js assets/vendor/js/theme.js  -->
		<script src="/public/vendor/libs/jquery/jquery.js"></script>
		<script src="/public/vendor/libs/popper/popper.js"></script>
		<script src="/public/vendor/js/bootstrap.js"></script>
		<script src="/public/vendor/libs/node-waves/node-waves.js"></script>
		<script src="/public/vendor/libs/@algolia/autocomplete-js.js"></script>
		<script src="/public/vendor/libs/pickr/pickr.js"></script>
		<script src="/public/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
		<script src="/public/vendor/libs/hammer/hammer.js"></script>
		<script src="/public/vendor/libs/i18n/i18n.js"></script>
		<script src="/public/vendor/js/menu.js"></script>
		<script src="/public/vendor/libs/bootstrap-select/bootstrap-select.js"></script>

		<!-- endbuild -->
		<!-- Vendors JS -->
		<!-- Main JS -->
		<script src="/public/js/main.js"></script>
		<!-- Page JS -->

		<!-- Default Javascript - customize script -->
		<script src="/public/js/default.js?ver=<?php echo strtotime(date('Ymd')); ?>"></script>
	</body>
</html>