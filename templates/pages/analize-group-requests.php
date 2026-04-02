<?

require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/unprocessed_base/unprocessed_base.php';

if(!$_SESSION['login_role']) {
    header('Location: /'); 
}

$err = isset($_GET['err']) ? 'Неверный логин или пароль' : false;
?>
<style>
.card-box {
    overflow: auto;
    max-height: 700px;
    padding-top: 0;
}
table#datatable1 thead th {
    position: sticky;
    top: -1px;
    background-color: #ffffff;
}
.table {
    table-layout: fixed;
}
.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
    font-size: 12px;
    text-align: center;
    overflow-wrap: break-word;
    word-break: auto-phrase;
    padding: 5px !important;
    width: 6%;
}
#table-request td {
    padding: 0px !important;
    margin: 0px !important;
    text-align: center;
}
table#datatable1 td.table-id {
    position: sticky;
    left: 0;
    background-color: #ffffff;
}
table#datatable1 td.table-total {
    position: sticky;
    left: 0;
    background-color: #cfebf9;
}
th.table-source-analize {
    position: sticky;
    left: 0px;
    background-color: #ffffff;
    z-index: 9;
    width: 15% !important;
}
</style>
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
                    <div class="col-3 justify-content-end d-flex">
                        <select class="form-control mb-2" id="groups_requests">
                            <option>Не выбран период</option>
                        </select>
                    </div>
                    <button id="event_source" type="button" class="btn btn-link" style="text-decoration: underline;"><b>КПД по источнику</b></button>
                    <button id="event_kpd_geo" type="button" class="btn btn-link"><b>КПД по ГЕО</b></button>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card-box">

                            <table class="table m-0 table-actions-bar nowrap" cellspacing="0" width="100%"
                                id="datatable1">
                                <thead>
                                    <tr class="table-head-tr">
                                        <th class="table-source-analize">Город</th>
										<th class="table-stat10">Не обработано</th>
										<th class="table-stat16">Лид (без продажи)</th>
										<th class="table-stat21">Долг менее 300 тысяч</th>
										<th class="table-stat22">Автоответчик</th>
										<th class="table-stat23">Ипотека - единственное жилье</th>
										<th class="table-stat24">Залог/Автокредит</th>
										<th class="table-stat25">Ипотека + Имущество</th>
										<th class="table-stat26">Много имущества</th>
										<th class="table-stat27">Плохой контакт (битый номер)</th>
										<th class="table-stat28">Негатив/Неадекват</th>
										<th class="table-stat29">Уже банкрот (Менее 5 лет)</th>
										<th class="table-stat30">Бросил трубку</th>
                                        <th class="table-stat31">Организация</th>
                                        <th class="table-stat32">Сброс-ЦЕЛЕВОЙ</th>
                                        <th class="table-stat9">Брак</th>
                                        <th class="table-leads">Лиды</th>
                                        <th class="table-not-calls">Недо<br>звоны</th>
                                        <th class="table-rejection">Отказы</th>
                                        <th class="table-calling">Соз<br>воны</th>
                                        <th class="table-all-requests">Всего заявок</th>
                                        <th class="table-kpd">КПД</th>
                                    </tr>
                                </thead>
                                <tbody id="table-request">

                                </tbody>
                            </table>
                            <table class="table m-0 table-actions-bar nowrap d-none" cellspacing="0" width="100%" id="datatable2">
                                
                            </table>
                        </div>
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
<script src="/scripts/analize-group-requests.js"></script>