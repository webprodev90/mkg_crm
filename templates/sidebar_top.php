<?
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!-- Top Bar Start -->
<div class="topbar">
	<nav class="navbar-custom">
		<ul class="list-unstyled topbar-right-menu float-right mb-0">

			<li style="margin-top: 15px; margin-right: 10px;">
				<div class="form-group mb-0">
					<form action="/scripts/datepic.php" method="POST">
						<div class="input-group">
							<input type="text" id="reportrange" class="form-control" name="dateselector">
							<div class="v">
								<button type="submit" id="check-minutes"
									class="btn waves-effect waves-light btn-warning">Задать</button>
							</div>
						</div>
					</form>
				</div>
			</li>

			<? if(substr_count($_SERVER['REQUEST_URI'], 'users.php') > 0) { ?>
				<li style="margin-top: 15px;">
					<div class="form-group mb-0">
						<div class="input-group">
							<button type="submit" class="btn btn-dark waves-effect waves-light" id="modclick3"
								data-toggle="modal" data-target="#signup-modal3" title="Добавить пользователя"><i class="fi-head"></i></button>
						</div>
					</div>
				</li>
			<? } elseif(substr_count($_SERVER['REQUEST_URI'], 'city.php') > 0) { ?>
				<li style="margin-top: 15px;">
					<div class="form-group mb-0">
						<div class="input-group">
							<button type="submit" class="btn btn-dark waves-effect waves-light" id="modclick30"
								data-toggle="modal" data-target="#signup-modal30">Добавить город</button>
						</div>
					</div>
				</li>
			<? } elseif(substr_count($_SERVER['REQUEST_URI'], 'partner.php') > 0) { ?>
				<li style="margin-top: 15px;">
					<div class="form-group mb-0">
						<div class="input-group">
							<button type="submit" class="btn btn-dark waves-effect waves-light" id="modclick30"
								data-toggle="modal" data-target="#signup-modal30" title="Добавить партнера"><i class="fi-head"></i></button>
						</div>
					</div>
				</li>
			<? } elseif(substr_count($_SERVER['REQUEST_URI'], 'addres.php') > 0) { ?>
				<li style="margin-top: 15px;">
					<div class="form-group mb-0">
						<div class="input-group">
							<button type="submit" class="btn btn-dark waves-effect waves-light" id="modclick30"
								data-toggle="modal" data-target="#signup-modal30">Добавить адрес</button>
						</div>
					</div>
				</li>
			<? } elseif((substr_count($_SERVER['REQUEST_URI'], 'price.php') > 0) && ($_SESSION['login_role'] == 1)) { ?>
				<li style="margin-top: 15px;">
					<div class="form-group mb-0">
						<div class="input-group">
							<button type="submit" class="btn btn-dark waves-effect waves-light" id="modclick30"
								data-toggle="modal" data-target="#signup-modal30">Добавить стоимость</button>
						</div>
					</div>
				</li>
			<? } elseif((substr_count($_SERVER['REQUEST_URI'], 'cash.php') > 0) && ($_SESSION['login_role'] == 1)) { ?>
				<li style="margin-top: 15px;">
					<div class="form-group mb-0">
						<div class="input-group">
							<button type="submit" class="btn btn-dark waves-effect waves-light" id="modclick32"
								data-toggle="modal" data-target="#signup-modal32">Добавить баланс</button>
						</div>
					</div>
				</li>
			<? } elseif((substr_count($_SERVER['REQUEST_URI'], 'unprocessed-base-2.php') > 0 || substr_count($_SERVER['REQUEST_URI'], 'lead-picker.php') > 0) && ($_SESSION['login_role'] == 1 or $_SESSION['login_role'] == 10 or $_SESSION['login_role'] == 11)) { ?>
				<li style="margin-top: 15px;">
					<div class="form-group mb-0">
						<div class="input-group">
							<button type="submit" class="btn btn-dark waves-effect waves-light" id="modclick4"
								data-toggle="modal" data-target="#signup-modal4" title="Добавить лида"><i class="fi-head"></i></button>
						</div>
					</div>
				</li>
			<? } elseif((substr_count($_SERVER['REQUEST_URI'], 'partners-plan.php') > 0) && ($_SESSION['login_role'] == 1 or $_SESSION['login_role'] == 10 or $_SESSION['login_role'] == 11)) { ?>
				<li style="margin-top: 15px;">
					<div class="form-group mb-0">
						<div class="input-group">
							<button type="submit" class="btn btn-dark waves-effect waves-light" id="modclick4"
								data-toggle="modal" data-target="#signup-modal5" title="Добавить партнера"><i class="fi-head"></i></button>
						</div>
					</div>
				</li>
			<? } else {
				if($_SESSION['login_role'] <> 6) { ?>
					<li style="margin-top: 15px;">
						<div class="form-group mb-0">
							<div class="input-group">
								<button type="submit" class="btn btn-dark waves-effect waves-light" id="modclick2"
									data-toggle="modal" data-target="#signup-modal2" title="Добавить клиента"><i class="fi-head"></i></button>
							</div>
						</div>
					</li>

				<?
				}
			} ?>

		</ul>
<!--
		<ul class="list-inline menu-left mb-0">
			<li class="float-left">
				<button class="button-menu-mobile open-left disable-btn">
					<i class="dripicons-menu"></i>
				</button>
			</li>
			<li>
				<div class="page-title-box">
					<h4 class="page-title">Главная </h4>
					<ol class="breadcrumb">
						<li class="breadcrumb-item active">Добро пожаловать в CRM!</li>
					</ol>
				</div>
			</li>

		</ul>
-->
	</nav>

</div>
<!-- Top Bar End -->