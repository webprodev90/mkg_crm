<?

require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/unprocessed_base/unprocessed_base.php';

if(!$_SESSION['login_role']) {
    header('Location: /'); 
}

$err = isset($_GET['err']) ? 'Неверный логин или пароль' : false;
?>
<!-- Begin page -->
<div id="wrapper">

    <!-- Левое меню -->
    <? require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/sidebar_left.php'; ?>

    <div class="content-page">
        <!-- Верхнее меню -->
        <? require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/sidebar_top.php'; ?>

        <!-- Start Page content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card-box">

                            <p class="education-paragraph-text"><b>Банкротство</b> - признанная судом неспособность должника (физического лица или организации) удовлетворить в полном объёме требования кредиторов по денежным обязательствам (и иные долговые обязательства помимо кредитов – ЖКХ, штрафы и иные взыскания).</p>
                            <h1 class="text-center"><span class="education-title-marker">СКРИПТ ПО БАНКРОТСТВУ</span></h1>
                            <h3 class="text-center">Приветствие</h3>  
                            <p class="education-paragraph-text">Кристина Леонидовна, приветствую вас. Я представлюсь - Никита Алексеевич, Центр Поддержки Населения <span class="education-text-marker">граждан РФ</span>. Нам поступило обращение, что вам необходима помощь в списании задолженности так как нет возможности оплачивать, скажите, пожалуйста, это верная информация?</p> 
                            <h3 class="text-center">Сбор информации</h3>  
                            <ul>
                                <li class="education-list-item"><i>У вас что именно: кредиты/кредитные карты/микрозаймы/жкх/налоги/штрафы?</i></li>
                                <li class="education-list-item"><i>Какая сумма по непогашенным частям?</i></li>
                                <li class="education-list-item"><i>Есть ли ипотека, автокредит, оставляли что-то под залог?</i></li>
                                <li class="education-list-item"><i>Просрочки имеются?</i></li>
                                <li class="education-list-item"><i>Что-то в имуществе, кроме собственного жилья имеется?</i></li>
                                <li class="education-list-item"><i>Закрыто исполнительное производство?</i></li>
                            </ul> 
                            <h3 class="education-text-arrow">⬇️</h3>
                            <h3 class="text-center">Побор методики решения вопроса (подводим на бфл)</h3>   
                            <p class="education-paragraph-text">Есть несколько вариантов разрешения вопроса <span class="education-text-marker">с долгами</span>. Но именно <span class="education-text-marker">по вашим условиям</span>, самый оптимальный и наилучший вариант решения вопроса - это законное списание задолженности по процедуре банкротства.</p>
                            <p class="education-paragraph-text"><i><u>Информация для вас, для клиента - в случае необходимости</u></i></p>       
                            <ul>
                                <li class="education-list-item"><b>Рефинансирование</b> - получение нового кредита под меньший процент для того, чтобы погасить свои долговые обязательства. <u>Если есть просрочки</u> - кредитная история плохая-сложно получить новый кредит, банк не одобряет как правило.</li>
                                <li class="education-list-item"><b>БФЛ МФЦ</b> – закрытое исполнительное производство, нет никакого имущества, даже единственного жилья, нет официального дохода. Это фиктивное банкротство, то есть списание и блокировка карт периодически все равно происходит.</li>
                                <li class="education-list-item"><b>Списание по сроку давности.</b> Это 199 ст ГК - частичное списание, к примеру 2 кредита - 1 из которых он периодически погашает, и по сроку давности не спишется. А вот по 2 кредиту - если 3 года прошло с момента первой просрочки и он все это время ни разу не платил и  банк требования не предъявлял – то вот это списать по 199 статье Гражданского кодекса можно.</li>
                            </ul> 
                            <h3 class="education-text-arrow">⬇️</h3>
                            <h3 class="text-center">Закрытие</h3>         
                            <p class="education-paragraph-text"> - Смотрите, как с вами поступим. Подберу по вашим условиям специалиста, который предоставит вам правовой анализ вашей ситуации и расскажет все детали, +/- процедуры банкротства именно при ваших условиях, так вы сможете понять, <span class="education-text-marker">что вам необходимо сделать, чтоб вы наконец</span> точно смогли решить свой вопрос.</p>
                            <p class="education-paragraph-text"><span class="education-text-marker">Юристу будет удобно связаться</span> с вами в течение 15 минут, поэтому ожидайте звонок от юриста, хорошо? Только подскажите, в каком регионе находитесь? (если будет возражение – «а зачем?» -«это делается специально для вас, в случае необходимости вы сможете обсудить с юристом все очно на бесплатной основе»). Всего доброго вам, <span class="education-text-marker">уверен ваш вопрос будет решен</span> в ближайшее время.</p>                    
                    </div><!-- end col -->
                </div>
                <!-- end row -->

            </div> <!-- container -->

        </div> <!-- content -->

    </div>


    <!-- ============================================================== -->
    <!-- End Right content here -->
    <!-- ============================================================== -->


</div>
<!-- END wrapper -->



<?php require_once ($_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php'); ?>

<script src="/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script src="/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="/scripts/rating.js"></script>