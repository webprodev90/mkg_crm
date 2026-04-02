<?require_once dirname(__DIR__ ) . '/header.php'; 

$err = isset($_GET['err'])  ? 'Неверный логин или пароль' : false;

?>
<script src="https://cdn.jsdelivr.net/gh/Alaev-Co/snowflakes/dist/Snow.min.js"></script>

<style>
.card-box {
    margin-top: 38%;
}
</style>

        <!-- Begin page 
        <div class="accountbg" style="background: url('https://mkggroup.ru/assets/images/header-background.png');background-size: cover;   background-color: rgb(14, 0, 45);"></div>
		-->
        <div class="accountbg" style="background: url(https://drevodelatel.ru/uploads/gallery/562177540.jpg);
										background-repeat: no-repeat;
										background-size: cover;
										background-color: rgb(14, 0, 45);
										transform: scaleX(-1);"></div>
		
        <div class="wrapper-page account-page-full" style="background-color: #ffffff42;">

            <div class="card" style="background-color: #ffffff00;">
                <div class="card-block">

                    <div class="account-box">

                        <div class="card-box p-5" style="background-color: #ffffff00;">
                            <h2 class="text-uppercase text-center pb-4">
                                <a href="index.html" class="text-success">
                                    <span><img src="https://mkggroup.ru/assets/images/logo.png" alt="" height="100"></span>
                                </a>
                            </h2>
									<? if($err) { ?>
										<div class="alert alert-danger" role="alert">
											<?echo $err;?>
										</div>
									<? } ?>

                            <form class="" action="/?mode=auth" method="POST">

                                <div class="form-group m-b-20 row">
                                    <div class="col-12">
                                        <label for="emailaddress" style="color: #ffffff;">Логин</label>
                                        <input class="form-control" type="text" id="emailaddress" required="" name="email" placeholder="Введите логин">
                                    </div>
                                </div>

                                <div class="form-group row m-b-20">
                                    <div class="col-12">
                                        <!--<a href="page-recoverpw.html" class="text-muted pull-right"><small>Forgot your password?</small></a>-->
                                        <label for="password" style="color: #ffffff;">Пароль</label>
                                        <input class="form-control" type="password" required="" id="password" name="pass" placeholder="Введите пароль">
                                    </div>
                                </div>
	<?/*?>
                                <div class="form-group row m-b-20">
                                    <div class="col-12">
									
                                        <div class="checkbox checkbox-custom">
                                            <input id="remember" type="checkbox" checked="">
                                            <label for="remember">
                                                Запомнить меня
                                            </label>
                                        </div>
										
                                    </div>
                                </div>
<?*/?>
                                <div class="form-group row text-center m-t-10">
                                    <div class="col-12">
                                        <button style="margin-top: 25px;background: rgb(71 176 235);border-color: rgb(71 176 235);" class="btn btn-block btn-custom waves-effect waves-light" name="submit" type="submit">Войти</button>
                                    </div>
                                </div>

                            </form>
							<!--
                            <div class="row m-t-50">
                                <div class="col-sm-12 text-center">
                                    <p class="text-muted">Don't have an account? <a href="page-register.html" class="text-dark m-l-5"><b>Sign Up</b></a></p>
                                </div>
                            </div>
							-->
                        </div>
                    </div>

                </div>
            </div>
			<!--
            <div class="m-t-40 text-center">
                <p class="account-copyright">2018 © Highdmin. - Coderthemes.com</p>
            </div>
			-->
        </div>
	<script>
		new Snow ({
			showSnowBalls: true,
			showSnowBallsIsMobile: true,
			showSnowflakes: true,
			countSnowflake: 100,
			snowBallsLength: 10,
			snowBallIterations: 40,
			snowBallupNum: 1,
			snowBallIterationsInterval: 1000,
			clearSnowBalls: 20000,
		});
	</script>		
<?require_once dirname(__DIR__ ) . '/footer.php'; ?>