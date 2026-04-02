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
                    <button id="department_1" type="button" class="btn btn-link" style="text-decoration: underline;"><b>1 отдел</b></button>
                    <button id="department_2" type="button" class="btn btn-link"><b>2 отдел</b></button>
                    <button id="department_3" type="button" class="btn btn-link"><b>3 отдел</b></button>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card-box">

                            <table class="table m-0 table-actions-bar nowrap" cellspacing="0" width="100%" 
                                id="datatable1" style="font-size: 22px;">
                                <thead>
                                    <tr class="table-head-tr">
                                        <th class="table-id">ID</th>
                                        <th class="table-fio" style="width: 30%">ФИО</th>
                                        <th class="table-requests" style="width: 30%">Лиды</th>
                                        <!--
                                        <th class="table-zp">Зарплата</th>
                                        -->
                                        <th class="table-bonus">Бонусы</th>
                                    </tr>
                                </thead>
                                <tbody id="table-request">

                                </tbody>
                            </table>
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