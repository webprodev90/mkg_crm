<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/pulse_call_center/pulse_call_center.php';

if(!$_SESSION['login_role']) {
    header('Location: /'); 
}

$pulse_call_center_obj = new PulseCallCenter();

$pulse_call_center_obj->set(
    array(
        'view_name' => 'operator_states',
    )
);

$trunks_queryset = $pulse_call_center_obj->handle_action('get_trunks');
$trunks_filtr = '';
foreach($trunks_queryset as $trunk) {
    $trunks_filtr .= "<option value=\"{$trunk['trunk_name']}\">{$trunk['trunk_name']}</option>";
}

?>
<!-- Begin page -->
<div id="wrapper">

    <!-- Левое меню -->
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/sidebar_left.php'; ?>

    <div class="content-page">
        <!-- Верхнее меню -->
        <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/sidebar_top.php'; ?>

        <!-- Start Page content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card-box"> 
                            <div class="mb-3 d-flex align-items-center justify-content-end">
                                <div class="form-inline">
                                    <select class="select-trunk form-control">
                                        <option value="" selected="">Выбор транка</option>
                                        <?= $trunks_filtr; ?>
                                     </select>
                                </div>
                            </div>                             
                            <div class="mb-3">
                                <canvas id="myChart1"></canvas>
                            </div>          
                        </div>
                    </div><!-- end col -->
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card-box"> 
                            <div class="mb-3">
                                <canvas id="myChart2"></canvas>
                            </div>          
                        </div>
                    </div><!-- end col -->
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-12">
                        <div class="card-box"> 
                            <div class="mb-3">
                                <div class="mb-3 d-flex align-items-center justify-content-end">
                                    <div class="form-inline">
                                        <select class="select-state-operators form-control">
                                            <option value="Все" selected="">Все</option>
                                            <option value="Кто в сети">Кто в сети</option>
                                         </select>
                                    </div>
                                </div> 
                                <table class="table table-hover table-states m-0 table-actions-bar dt-responsive nowrap" cellspacing="0" width="100%" id="datatable1">
                                    <thead>
                                        <tr>
                                            <th>Отдел</th>
                                            <th>ФИО оператора</th>
                                            <th>Статус в АТС</th>
                                            <th>Количество секунд без звонка</th>    
                                            <th>Всего получено звонков</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $pulse_call_center_obj->handle_action('get_info_atc'); ?>
                                    </tbody>
                                </table>
                            </div>          
                        </div>
                    </div><!-- end col -->
                </div>    

                <div class="row">
                    <div class="col-12">
                        <div class="card-box"> 
                            <div class="mb-3">
                                <div class="mb-2 font-weight-bold">Отчет по операторам связи</div>
                                <table class="table table-bordered table-operators-stats m-0 table-actions-bar dt-responsive nowrap text-center" cellspacing="0" width="100%" id="datatable1" style="table-layout: fixed;">
                                    <thead>
                                        <tr>
                                            <th colspan="2"></th>
                                            <th>Билайн</th>
                                            <th>Мегафон</th>
                                            <th>МТС</th>
                                            <th>Т2</th>    
                                            <th>Все остальное</th>
                                            <th>Городской телефон</th>
                                            <th>Оператор не определен</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>                                    
                                </table>
                            </div>          
                        </div>
                    </div><!-- end col -->
                </div>              

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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
<script src="/scripts/pulse-call-center.js"></script>