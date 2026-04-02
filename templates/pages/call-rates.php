<?

require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/unprocessed_base/unprocessed_base.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/call_rates/call_rates.php';

if(!$_SESSION['login_role']) {
    header('Location: /'); 
}

$err = isset($_GET['err']) ? 'Неверный логин или пароль' : false;

$call_rates_obj = new CallRates();

$campaigns = $call_rates_obj->handle_action('get_campaigns');

$campaigns_options = '';
if($campaigns != null) {
    foreach($campaigns as $item) {
        $campaigns_options .= "<option value=\"{$item['id']}\">{$item['name']}</option>";
    }    
}

$operators = $call_rates_obj->handle_action('get_operators');

$operators_options = '';
if($operators != null) {
    foreach($operators as $item) {
        $operators_options .= "<option value=\"{$item['id_atc']}\">{$item['name']}</option>";
    }    
}

?>
<style>
/*   
.card-box {
    overflow: auto;
    max-height: 700px; 
}
*/
table#datatable1 thead th {
    position: sticky;
    top: -1px;
    background-color: #ffffff;
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
.table-request td {
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
th.table-a {
    position: sticky;
    left: 0px;
    background-color: #ffffff;
    z-index: 9;
    width: 10% !important;
}
#call_table {
    max-width: 700px;
}
</style>

<?
$hours_filtr = '';
for($i = 8; $i <= 20; $i++) {
    $hours_filtr .= "<option value=\"{$i}\">{$i}:00</option>";
} 
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
                <div class="row pb-1">
                    <button id="btn_financial_stat" type="button" class="btn btn-link" style="text-decoration: underline;"><b>Финансовый отчет</b></button>
                    <button id="btn_call_stat" type="button" class="btn btn-link"><b>Отчет по статистике звонков АТС</b></button>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <div id="financial_content">
                                <div id="financial_table">
                                    
                                </div> 
                            </div> 
                            <div id="call_content" class="d-none">
                                <div class="row align-items-center ml-1 pb-3" style="gap: 10px;">
                                    <div>С</div>
                                    <div class="form-inline">
                                        <select class="form-control select-start-time" name="start-time">
                                            <option value="" selected="">Выбор времени</option>
                                                <?= $hours_filtr ?>
                                        </select>
                                    </div>
                                    <div>до</div>
                                    <div class="form-inline">
                                        <select class="form-control select-end-time" name="end-time">
                                            <option value="" selected="">Выбор времени</option>
                                                <?= $hours_filtr ?>
                                        </select>
                                    </div>
                                    <button class="btn btn-primary btn-time-filter">Показать</button> 
                                </div>
                                <div class="row align-items-center ml-1 pb-4" style="gap: 10px;">
                                    <div class="form-inline">
                                        <select class="form-control select-trunk" name="trunk">
                                            <option value="" selected="">Выбор транка</option>
                                            <option value="multifon">Мегафон</option>
                                            <option value="modem">Модем</option>
                                            <option value="rostelecom">Ростелеком</option>
                                            <option value="beeline">Билайн</option>
                                            <option value="incognito">Инкогнито</option>
                                            <option value="other">Все остальное</option>
                                        </select>
                                    </div>
                                    <div class="form-inline">
                                        <select class="form-control select-campaign" name="campaign">
                                            <option value="" selected="">Выбор кампании</option>
                                                <?= $campaigns_options; ?>
                                        </select>
                                    </div>
                                    <div class="form-inline">
                                        <select class="form-control select-operator" name="operator">
                                            <option value="" selected="">Выбор оператора (кто ответил)</option>
                                                <?= $operators_options; ?>
                                        </select>
                                    </div>
                                    <div class="form-inline">
                                        <select class="form-control select-type-autodialer" name="type-autodialer">
                                            <option value="" selected="">Вид автообзвона</option>
                                            <option value="1147">Робот</option>
                                            <option value="1148">Операторы</option>
                                        </select>
                                    </div>
                                </div>
                                <div id="call_table">
                                    
                                </div>
                            </div>                           
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
<script src="/scripts/call-rates.js"></script>