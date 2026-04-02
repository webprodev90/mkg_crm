<?

require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/unprocessed_base/unprocessed_base.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/sending_asterisk/sending_asterisk.php';

if(!$_SESSION['login_role']) {
    header('Location: /'); 
}

$err = isset($_GET['err']) ? 'Неверный логин или пароль' : false;

$sending_asterisk_obj = new SendingAsterisk();
$lists_choices = $sending_asterisk_obj->handle_action('get_campaigns');

$lists_choices_options = '';
if($lists_choices != null) {
    foreach($lists_choices as $item) {
        $lists_choices_options .= "<option value=\"{$item['id']}\">{$item['name']}</option>";
    }    
}

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
    width: calc(100% / 11);
    overflow-wrap: break-word;
    word-break: auto-phrase;
    padding: 5px !important;
}
#table-request td {
    padding: 0px !important;
    margin: 0px !important;
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
    width: 20% !important;
}

th.table-chec {
    width: 2% !important;
}

/* Ширина самого контейнера select2 */
.select2-container {
    width: 250px !important;
    min-width: 150px; /* Минимальная ширина, если список пуст */
}

/* Ширина выпадающего списка */
.select2-dropdown {
    width: auto !important;
    min-width: 100%; /* Список не уже самого селекта */
}

/* Гарантируем, что текст не переносится внутри опций */
.select2-results__option {
    white-space: nowrap;
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
                    <div class="justify-content-between d-flex mr-3 ml-3 w-100">
                        <div class="mb-2">
                            <div id="box-update-count-row--inactive">
                                <button type="button" id="btn-sel-count-row" class="btn btn-secondary">
                                    Изменить количество строк
                                </button>
                            </div>
                            <div id="box-update-count-row--active" class="align-items-center d-none">
                                <div class="w-100"><b>Количество строк: </b></div>
                                <input class="form-control" type="number" id="count-rows" value="0" min="0">
                                <button type="button" id="btn-save-count-row" class="btn btn-success ml-2">
                                    Cохранить
                                </button>                                
                            </div>
                        </div> 
                        <div class="mb-2 ml-2 d-flex">
                            <select class="form-control mr-2" id="select-city">
                                <option value="" selected="">Выбор города</option>
                            </select>
                            <select class="form-control mr-2" id="select-source">
                                <option value="" selected="">Выбор источника</option>
                            </select>
                            <select class="form-control mr-2" id="select-department">
                                <option value="" selected="">Выбор отдела</option>
                            </select>
                            <select class="form-control" id="select-mobile-operator">
                                <option value="" selected="">Выбор оператора связи</option>
                            </select>
                        </div>
                    </div>
                    <div class="justify-content-between d-flex mr-3 ml-3 w-100">
                        <div class="mb-2 d-flex">
                            <select class="form-control" id="select-campany">
                                <option value="" selected disabled>Выбор кампании</option>
                                <?= $lists_choices_options; ?>
                            </select>                            
                            <button type="button" id="btn-to-send" class="btn btn-primary ml-2" disabled>
                                Отправить <span class="badge badge-light">0</span>
                            </button>
                        </div> 
                        <div class="mb-2 ml-2 d-flex">
                            <select class="form-control mr-2" id="select-is-sog">
                                <option value="" selected="">Все</option>
                                <option value="1">Согласие получено</option>
                            </select>
                            <select class="form-control mr-2" id="select-count-touches">
                                <option value="" selected="">Выбор кол-ва касаний</option>
                                <option value="0-10">0-10</option>
                                <option value="10-15">10-15</option>
                                <option value="15-20">15-20</option>
                                <option value="20">Более 20</option>
                            </select>
                            <select class="form-control mr-2" id="select-dozvon">
                                <option value="" selected="">Выбор дозвона</option>
                                <option value="0-30">0-30%</option>
                                <option value="30-50">30%-50%</option>
                                <option value="50-60">50-60%</option>
                                <option value="60-70">60-70%</option>
                                <option value="70">Более 70%</option>
                            </select>
                            <select class="form-control" id="select-chist-kpd">
                                <option value="" selected="">Выбор Чист. КПД</option>
                                <option value="0-0.5">0-0,5%</option>
                                <option value="0.5-1">0,5-1%</option>
                                <option value="1-1.5">1-1,5%</option>
                                <option value="1.5-2">1,5-2%</option>
                                <option value="2">Более 2%</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card-box">

                            <table class="table m-0 table-actions-bar nowrap" cellspacing="0" width="100%"
                                id="datatable1">
                                <thead>
                                    <tr class="table-head-tr">
                                        <th class="table-chec"><input type="checkbox" id="singleCheckbox1" value="option2"></th>
                                        <th class="table-source-analize">Город</th>
										<th class="table-not-processed">Не обработано</th>
										<th class="table-avtootvet">Автоответчик</th>
                                        <th class="table-not-calls">Недозвоны</th>
                                        <th class="table-count-requests">НО+АО+НДЗ</th>
                                        <th class="table-total-count-requests">Всего заявок</th>
                                        <th class="table-count-dozvon">Дозвонов</th>
                                        <th class="table-leads">Лиды</th>
                                        <th class="table-dozvon">% дозвона</th>
                                        <th class="table-kpd">КПД</th>
                                        <th class="table-chist-kpd">Чистое КПД</th>
                                        <th class="table-gr-kpd">Грязное КПД</th>
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
<script src="/scripts/sending-asterisk.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>