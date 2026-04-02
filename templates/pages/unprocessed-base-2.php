<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/unprocessed_base2/unprocessed_base2.php';

if(!$_SESSION['login_role']) {
    header('Location: /'); 
}

$unprocessed_base_obj = new UnprocessedBase2();
$curdate = date('Y-m-d');

$unprocessed_base_obj->set(
    array(
        'view_name' => 'unprocessed2',
        'limit_start' => 0,
        'limit_end' => 500,
        'date_start' => $curdate,
        'date_end' => $curdate,
    )
);

$statuses_queryset = $unprocessed_base_obj->handle_action('get_statuses');
$statuses = '';
foreach($statuses_queryset as $status) {
    $statuses .= "<option value=\"{$status['status_id']}\">{$status['status_name']}</option>";
}

$operators = $unprocessed_base_obj->handle_action('get_operators');

$counters = $unprocessed_base_obj->handle_action('get_counters');

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
                                <div class="d-flex">
                                    <div class="event_counter mr-3" status="6">
                                        Созвоны <span class="count_callings">
                                            <?= $counters['callings']; ?>
                                        </span>
                                    </div>
                                    <div class="event_counter mr-3" status="8">
                                        Недозвоны <span class="count_non_calls">
                                            <?= $counters['non_calls']; ?>
                                        </span>
                                    </div>
                                    <div class="event_counter mr-3" status="11">
                                        Отказ <span class="count_rejections">
                                            <?= $counters['rejections']; ?>
                                        </span>
                                    </div>
                                    <div class="event_counter mr-3" status="9">
                                        Брак <span class="count_defects">
                                            <?= $counters['defects']; ?>
                                        </span>
                                    </div>
                                    <?php if($_SESSION['login_role'] == 1 or $_SESSION['login_role'] == 10): ?>
                                    <div class="event_counter mr-3" status="17">
                                        Договор <span class="count_contracts">
                                            <?= $counters['contracts']; ?>
                                        </span>
                                    </div>
                                    <?php endif; ?>                                      
                                    <div class="event_counter">
                                        Всего <span class="count_all">
                                            <?= $counters['all_leads']; ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <?php if($_SESSION['login_role'] == 1 or $_SESSION['login_role'] == 10): ?>
                                        <div class="form-inline mr-4">
                                            <select class="select-sales-operator form-control">
                                                <option selected="">Оператор продаж</option>
                                                <?php
                                                    foreach($operators as $operator) {
                                                        echo "<option value=\"{$operator['id']}\">{$operator['name']}</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    <?php endif; ?>
                                    <div class="form-inline">
                                        <input placeholder="Поиск..." type="text" class="form-control search-input2 mr-1" />
                                        <button type="submit" class="btn waves-effect btn-primary search-request2">Найти</button>
                                    </div>
                                </div>
                            </div>        
                            <table class="table m-0 table-actions-bar nowrap" cellspacing="0" width="100%"
                                id="datatable1">
                                <thead>
                                    <tr class="table-head-tr">
                                        <th class="table-id">№</th>
                                        <th class="table-created-by-user">Кто</th>
                                        <th class="table-date-create">Создан</th>
                                        <th class="table-phone">Телефон</th>
                                        <th class="table-name">Имя</th>
                                        <th class="table-city">Город</th>
                                        <th class="table-comment">Комментарий</th>
                                        <?php if($_SESSION['login_role'] == 1 or $_SESSION['login_role'] == 10 or $_SESSION['login_role'] == 11): ?>
                                            <th class="table-operator">Колл</th>
                                        <?php endif; ?>
                                        <th class="table-status">Статус</th>
                                        <th class="table-date_time_status_change">Дата/Время</th>
                                        <th class="table-update "></th>
                                        <?php if($_SESSION['login_role'] == 1 or $_SESSION['login_role'] == 10): ?>
                                            <th class="table-delete"></th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody id="table-request">
                                    <?php $unprocessed_base_obj->handle_action('get_unprocessed_base'); ?>
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
                <h2 class="text-center m-b-30">Редактирование заявки
                </h2>
                <input type="hidden" id="idval" name="id">
                <form class="form-horizontal form-request">
                    <div class="form-group col-md-12">
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="phone_number">Телефон</label>
                                <input type="text" name="phone_number" class="form-control" placeholder="Телефон" />
                            </div>
                            <div class="col-md-6">
                                <label for="fio">ФИО</label>
                                <input type="text" name="fio" class="form-control" placeholder="ФИО" />
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="city">Город</label>
                                <input type="text" name="city" class="form-control" placeholder="Город" />
                            </div>
                            <div class="col-md-6">
                                <label for="status">Статус <div class="back-to-status d-none">к выбору статуса</div>
                                </label>
                                <select class="form-control" name="status" id="status-modal">

                                </select>
                                <input type="text" name="date_time_status_change" id="datepicker-calling"
                                    class="form-control d-none">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="сompany_name">Название компании</label>
                                <input type="text" name="сompany_name" class="form-control" placeholder="Название компании" />
                            </div>
                            <div class="col-md-6">
                                <label for="sales_department">Отдел продаж</label>
                                <input type="text" name="sales_department" class="form-control" placeholder="Отдел продаж" />
                            </div>
                        </div>		
                        <div class="row mt-2">                            
                            <div class="col-md-6">
                                <label for="experience">Опыт работы</label>
                                <input type="text" name="experience" class="form-control" placeholder="Опыт работы" />
                            </div>
                            <div class="col-md-6">
                                <label for="have_crm">CRM?</label>
                                <input type="text" name="have_crm" class="form-control" placeholder="CRM?" />
                            </div>

                        </div>  
                        <div class="row mt-2">                            
                            <div class="col-md-6">
                                <label for="time_difference">Разница во времени</label>
                                <input type="text" name="time_difference" class="form-control" placeholder="Разница во времени" />
                            </div>
                            <div class="col-md-6">
                                <label for="job">Должность</label>
                                <input type="text" name="job" class="form-control" placeholder="Должность" />
                            </div>
                        </div>
                        <?php if($_SESSION['login_role'] == 1 or $_SESSION['login_role'] == 10): ?>
                            <div class="row mt-2">                            
                                <div class="col-md-12">
                                    <label for="created_by_user_id">Кто</label>
                                    <select class="form-control" name="created_by_user_id" id="created_by_user_id">
                                        <?php
                                            foreach($operators as $operator) {
                                                echo "<option value=\"{$operator['id']}\">{$operator['name']}</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div> 
                        <?php endif; ?> 				
                    </div>

                </form>
                <hr>
                <button id="create-comment" class="btn btn-outline-info btn-sm waves-effect waves-light mb-2 ml-3">Добавить комментарий</button>
                <form class="form-horizontal form-comment d-none">
                    <div class="form-group col-md-12">
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label for="new-comment">Новый комментарий</label>
                                <textarea id="new-comment" type="text" name="new-comment" class="form-control"></textarea>
                            </div>
                        </div> 
                        <div class="row mt-2">
                            <button id="add-new-comment" type="button" class="btn btn-sm btn-outline-success ml-3">Добавить</button>
                            <button id="create-comment-cancel" type="button" class="btn btn-sm btn-outline-danger ml-2">Отменить</button>
                        </div>                
                    </div>
                </form>
                <h4 class="ml-3 mb-1 mt-0">Комментарии</h4>
                <div id="comments" class="pl-3 pr-3">

                </div>

                <div class="row form-group account-btn mt-4">
                    <div class="col-md-12 text-center">
                        <button id="save-request2" class="btn w-lg btn-rounded btn-primary waves-effect waves-light mr-2">
                            Сохранить
                        </button>
                    </div>
                </div>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="d-none statuses">
    <?= $statuses; ?>
</div>

<?php require_once ($_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php'); ?>

<script src="/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script src="/plugins/datatables/dataTables.responsive.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="/scripts/main.js"></script>