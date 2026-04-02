<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/partners_plan/partners_plan.php';

if(!$_SESSION['login_role']) {
    header('Location: /'); 
}

$partners_plan_obj = new PartnersPlan();
$partners_plan_obj->set(
    array(
        'view_name' => 'partners_plan',
    )
);

$partners_queryset = $partners_plan_obj->handle_action('get_partners');
$partners = '';
foreach($partners_queryset as $partner) {
    $partners .= "<option value=\"{$partner['id']}\">{$partner['partner_name']}</option>";
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
                            <div class="mb-3 d-flex align-items-center justify-content-between">
                                <div>
                                    <button type="button" class="sort-table btn btn-link pr-0 pl-0" data-sort="remainder1 DESC"><b>Сортировать по остаткам</b></button>
                                    <button type="button" class="sort-table btn btn-link pr-0" data-sort="date_start DESC"><b>Сортировать по дате открытия (убыв.)</b></button>
                                    <button type="button" class="sort-table btn btn-link pr-0" data-sort="date_start ASC"><b>Сортировать по дате открытия (возр.)</b></button>
                                </div>
                                <div>
                                    <div class="form-inline">
                                        <input placeholder="Поиск..." type="text" class="form-control search-plan-input mr-1" />
                                        <button type="submit" class="btn waves-effect btn-primary search-plan-request">Найти</button>
                                    </div>
                                </div>
                            </div>          
                            <table class="table m-0 table-actions-bar nowrap table-bordered" cellspacing="0" width="100%"
                                id="datatable1">
                                <thead>
                                    <tr class="table-head-tr">
                                        <th class="table-id align-middle" style="padding-left: 5px !important;">ID</th>
                                        <th class="table-partner-name align-middle" style="padding-left: 5px !important;">Название</th>
                                        <th class="table-partner-city align-middle" style="padding-left: 5px !important;">Город</th>
                                        <th class="table-total-quantity align-middle" style="padding-left: 5px !important;">Общее кол-во</th>
                                        <th class="table-date-start align-middle" style="padding-left: 5px !important;">Дата открытия</th>
                                        <th class="table-shipped1 align-middle" style="padding-left: 5px !important;">Отгружено</th>
                                        <th class="table-remainder1 align-middle" style="padding-left: 5px !important;">Остаток/<br>Дата закрытия</th>
                                        <th class="table-otbrakovka align-middle table-secondary" style="padding-left: 5px !important;">Отбраковка</th>
                                        <th class="table-shipped2 align-middle table-secondary" style="padding-left: 5px !important;">Отгружено</th>
                                        <th class="table-remainder2 align-middle table-secondary" style="padding-left: 5px !important;">Остаток</th> 
                                        <th class="table-date-end2 align-middle table-secondary" style="padding-left: 5px !important;">Дата закрытия</th>    
                                        <th class="table-settings table-secondary"></th>
                                        <th class="table-settings table-secondary"></th>
                                    </tr>
                                </thead>
                                <tbody id="table-request">
                                    <?php $partners_plan_obj->handle_action('get_partners_plan'); ?>
                                </tbody>
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

<!-- Signup modal content -->
<div id="signup-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <h2 class="text-center m-b-30">Редактирование плана
                </h2>
                <form class="form-horizontal form-request">
                    <input type="hidden" id="idval" name="id">
                    <div class="form-group col-md-12">
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="partner_id">Партнер</label>
                                <select class="form-control" name="partner_id">

                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="city">Город</label>
                                <input type="text" name="city" class="form-control" placeholder="Город" />
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="date_start">Дата открытия</label>
                                <input type="date" name="date_start" class="form-control" />
                            </div>
                            <div class="col-md-6">
                                <label for="quantity_per_day">Кол-во лидов в день</label>
                                <input type="text" name="quantity_per_day" class="form-control" placeholder="Кол-во лидов в день" />
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="total_quantity">Общее кол-во лидов</label>
                                <input type="number" name="total_quantity" class="form-control" placeholder="Общее кол-во лидов" />
                            </div>
                            <div class="col-md-6">
                                <label for="otbrakovka">Отбраковка</label>
                                <input type="number" name="otbrakovka" class="form-control" placeholder="Отбраковка" />
                            </div>
                        </div>      					
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label for="vopros">Комментарий</label>
                                <textarea type="text" name="vopros" class="form-control"></textarea>
                            </div>
                        </div> 
                    </div>

                </form>

                <div class="row form-group account-btn mt-4">
                    <div class="col-md-12 text-center">
                        <button id="save-request" class="btn w-lg btn-rounded btn-primary waves-effect waves-light mr-2">
                            Сохранить
                        </button>
                    </div>
                </div>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Details modal content -->
<div id="details-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h2 class="text-center m-b-30">Детализация</h2>
                <div class="col-md-12">
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <table id="details-table" class="table" data-id="" data-shipped="">
                                <thead>
                                    <tr>
                                        <th scope="col" class="w-50">Дата</th>
                                        <th scope="col" class="w-50">Лиды</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row form-group account-btn mt-4">
                        <div class="col-md-12 text-center">
                            <button id="close-details-modal" onclick="$('#details-modal').modal('hide');"
                                class="btn w-lg btn-rounded btn-primary waves-effect waves-light">
                                Закрыть
                            </button>
                        </div>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>

<div id="date-requests-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <h2 class="text-center m-b-30">Заявки</h2>
                <div class="col-md-12">
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <table id="date-requests-table" class="table table-hover m-0 table-actions-bar nowrap">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">ФИО</th>
                                        <th scope="col">Телефон</th>
                                        <th scope="col">Город</th>
                                        <th scope="col">Вопрос</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row form-group account-btn mt-4">
                        <div class="col-md-12 text-center">
                            <button id="close-date-requests-modal" onclick="$('#date-requests-modal').modal('hide');"
                                class="btn w-lg btn-rounded btn-primary waves-effect waves-light">
                                Закрыть
                            </button>
                        </div>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>

<div class="d-none partners">
    <?= $partners; ?>
</div>

<?php require_once ($_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php'); ?>

<script src="/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script src="/plugins/datatables/dataTables.responsive.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="/scripts/partners-plan.js"></script>