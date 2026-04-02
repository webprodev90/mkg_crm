<?

require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/testing/testing.php';

if(!$_SESSION['login_role']) {
    header('Location: /'); 
}

$err = isset($_GET['err']) ? 'Неверный логин или пароль' : false;

$test_id = 2;
$testing_obj = new Testing();

$testing_obj->set(
    array(
        'test_id' => $test_id,
    )
);

$available_test_id = $testing_obj->handle_action('get_available_test');

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

                            <h1 class="education-title" data-theory="<?= $test_id; ?>">📑Работа с возражениями</h1>
                            <?php if(isset($_SESSION['deny_access']) AND $_SESSION['deny_access'] == $test_id): ?>
                                <div class="education-warning">Во время тестирования просмотр теории запрещен!</div>
                            <?php else: ?>  
                                <div class="education-theory">  
                                    <div class="education-box-answer">
                                        <p class="education-paragraph-text-answer">Это платно?</p>  
                                        <p class="education-paragraph-text">Процедура списания является платной, но выгодной. Я предлагаю вам начать с бесплатной консультации, после которой вы уже точно будете знать обо всех плюсах, минусах и условиях данной процедуры.</p>
                                    </div>
                                    <div class="education-box-answer">
                                        <p class="education-paragraph-text-answer">Нечем заплатить.</p>  
                                        <p class="education-paragraph-text">С вас сейчас что-то взыскивают? На время проведения процедуры банкротства все взыскания приостанавливаются. Те же самые деньги вы можете направить на оплату финансового управляющего, но через полгода для вас вся эта история с долгами закончится и не нужно будет платить их несколько лет, общаться с кредиторами и коллекторами.</p>
                                    </div>
                                    <div class="education-box-answer">
                                        <p class="education-paragraph-text-answer">Сколько стоит?</p>  
                                        <p class="education-paragraph-text">Цена варьируется. Я передам ваше обращение практикующему специалисту, он проанализирует ваши долговые обязательства, предоставит всю необходимую информацию о проведении процедуры и в том числе озвучит точную стоимость.</p>
                                    </div>    
                                    <div class="education-box-answer">
                                        <p class="education-paragraph-text-answer">С меня нечего взыскивать я никому ничего не плачу и денег у меня нет.</p>  
                                        <p class="education-paragraph-text">Клиент, который заведомо говорит о своем нежелании сотрудничать ни с банками, ни со специалистом по списанию нам не подходит, и мы с таким клиентом ПРОЩАЕМСЯ.</p>
                                    </div> 
                                    <div class="education-box-answer">
                                        <p class="education-paragraph-text-answer">Это к вам ехать нужно?</p>  
                                        <p class="education-paragraph-text">На данном этапе говорить об этом рано. Позвольте специалисту сначала проанализировать ситуацию. Иногда удаётся провести процедуру частично дистанционно, а иногда даже полностью. На случай, если понадобится ваше очное присутствие, у нас есть несколько филиалов по вашему региону и специалист подберёт максимально комфортный для вас.</p>
                                    </div>  
                                    <div class="education-box-answer">  
                                        <p class="education-paragraph-text-answer">Где вы находитесь?</p>  
                                        <p class="education-paragraph-text">У нас есть несколько филиалов по вашему региону и специалист подберёт максимально комфортный для вас.</p>
                                    </div>    
                                    <div class="education-box-answer">  
                                        <p class="education-paragraph-text-answer">Я уже консультировался.</p>  
                                        <p class="education-paragraph-text">Все специалисты работают по разным условиям, в том числе финансовым, и имеют разную практику. Там-где вам не помог один специалист, зачастую поможет другой.</p>
                                    </div>  
                                    <div class="education-box-answer">    
                                        <p class="education-paragraph-text-answer">Я уже консультировался, мне процедура банкротства не подходит.</p>  
                                        <p class="education-paragraph-text">Что именно мешает вам провести процедуру банкротства?</p>
                                        <p class="education-paragraph-text"><b>Если</b> клиент причиной называет личные мотивы, боязнь процедуры, стыд от признания себя банкротством, или наслушался, что он не сможет взять кредит, уехать за границу, устроиться на работу и т.д., то мы можем его замотивировать на действия, успокоив и объяснив, что в данной процедуре никаких рисков нет и это лишь возможность законно избавиться от долгов, которой может потом и не быть в связи с быстро меняющимся законодательством.</p>  
                                        <p class="education-paragraph-text"><b>Если</b> клиент причиной называет реальные факторы, мешающие проведению процедуры (Излишнее имущество, с которым не готов проститься; задолженности, не подходящие под списание (Алименты, субсидиарная ответственность, ДТП, долги по гражданским искам и т.д.), высокий задекламированный доход), то такой клиент нам не подходит, и мы с ним ПРОЩАЕМСЯ.</p> 
                                    </div>
                                    <div class="education-box-answer">  
                                        <p class="education-paragraph-text-answer">Откуда поступило обращение?</p>  
                                        <p class="education-paragraph-text">Обращение поступило с сайта либо с горячей линии.</p>
                                    </div>
                                    <div class="education-box-answer">  
                                        <p class="education-paragraph-text-answer">А когда оно поступило?</p>  
                                        <p class="education-paragraph-text">Я точно сказать не могу, потому что обращений поступает очень много и не всегда успеваем откликаться на все быстро. До меня оно дошло только сейчас. Для вас вопрос списания задолженности сейчас актуален?</p>
                                    </div>
                                    <div class="education-box-answer">  
                                        <p class="education-paragraph-text-answer">Я никуда не обращался и списание мне не требуется.</p>  
                                        <p class="education-paragraph-text">Данное возражение мы <b>не обрабатываем</b>. Задаём контрольный вопрос: На данный момент нет задолженностей, которые списать хотели бы?</p>
                                        <p class="education-paragraph-text"><b>Если</b> ответ – нет, то мы с клиентом ПРОЩАЕМСЯ.</p>
                                        <p class="education-paragraph-text"><b>Если</b> ответ – есть, но я никуда не обращался: Позвольте я задам вам пару вопросов, мы разберём ваши задолженности и посмотрим можем ли мы помочь вам избавиться от этих задолженностей.</p>
                                    </div>
                                    <div class="education-box-answer">  
                                        <p class="education-paragraph-text-answer">Не знаю сумму задолженности.</p>
                                        <p class="education-paragraph-text">Клиент должен сам озвучивать сумму задолженности, поэтому по скрипту мы спрашиваем за что задолженности и на какую точную сумму. Если клиент не знает точную сумму, то уточняем примерную. И <b>только если</b> клиент затрудняется с ответом, мы можем спросить его о минимальном пороге. Набирается ли суммарная задолженность более 300 тыс. рублей.</p>
                                    </div>
                                    <div class="education-box-answer">  
                                        <p class="education-paragraph-text-answer">Мне сейчас неудобно разговаривать.</p>  
                                        <p class="education-paragraph-text">Никаких давайте минуточку уделите, я у вас критерии узнаю!!!</p>
                                        <p class="education-paragraph-text">Узнаем, когда клиенту будет удобно побеседовать на предмет списания задолженности и ставим созвон на это время.</p>
                                    </div>
                                    <div class="education-box-answer">  
                                        <p class="education-paragraph-text-answer">Я уже собираюсь обратиться в МФЦ.</p>  
                                        <p class="education-paragraph-text">Почему вы решили списать задолженности именно через МФЦ? А рассматривали процедуру списания через арбитражный суд?</p>
                                        <p class="education-paragraph-text">Если клиент уже подал документы на рассмотрение в МФЦ, такой клиент нам не подходит и мы с ним ПРОЩАЕМСЯ.</p>
                                        <p class="education-paragraph-text">Если клиент только собирается идти в МФЦ: Разные виды списания подразумевают разные критерии задолженностей и условия. Советую вам проконсультироваться со специалистом по списанию, который точно сможет сказать под какие условия списания вы подходите и каким в вашем случае будет выгоднее воспользоваться.</p>
                                    </div>
                                    <div class="education-box-answer">  
                                        <p class="education-paragraph-text-answer">Может вы мошенники.</p>  
                                        <p class="education-paragraph-text">Понимаю вашу настороженность, но мы не спрашиваем ваши личные данные, номера карточек и прочее. Я лишь уточняю заинтересованы ли вы в избавлении от задолженностей и задаю пару вопросов, чтобы понять можем ли вам помочь в этом или нет, потому что в законодательстве прописаны чёткие условия для возможности провести процедуру списания.</p>
                                        <p class="education-paragraph-text">Мы вам предлагаем подробную консультацию о возможности списания задолженности, с чего начать, какие документы понадобятся и т.д. Разложим всё по полочкам и конечно предложим свои услуги, которыми вы вправе воспользоваться, если сочтёте данную услугу полезной для вас, либо отказаться если считаете, что у вас есть более выгодные возможности решить вопрос с задолженностями.</p>
                                    </div>
                                    <div class="education-box-answer">  
                                        <p class="education-paragraph-text-answer">СТОП ФРАЗЫ</p>  
                                        <p class="education-paragraph-text">Вам никуда ехать не нужно</p>
                                        <p class="education-paragraph-text">Вам ничего платить не нужно</p>
                                        <p class="education-paragraph-text">Всё равно послушайте, лишним не будет</p>
                                    </div>   
                                    <?php if($available_test_id): ?>
                                        <form method="POST" action="testing.php" class="text-center mt-3">
                                            <input class="d-none" id="test_id" name="test_id" value="<?= $test_id; ?>" />
                                            <input class="d-none" id="user_test_id" name="user_test_id" value="<?= $available_test_id; ?>" />
                                            <button type="submit" class="btn btn-info testing-button">Перейти к тесту</button>
                                        </form> 
                                    <?php endif; ?>  
                                </div>
                            <?php endif; ?>                                                                       
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
<script src="/scripts/knowledge-base.js"></script>