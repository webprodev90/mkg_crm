<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/lead_statistics/lead_statistics.php';

if(!$_SESSION['login_role']) {
    header('Location: /'); 
}

$lead_statistics_obj = new LeadStatistics();
$lead_statistics_obj->set(
    array(
        'view_name' => 'lead_statistics',
    )
);

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
                            <table class="table m-0 table-actions-bar nowrap table-bordered table-striped" cellspacing="0" width="100%"
                                id="datatable1" style="width: 50%">
                                <thead>
                                    <tr>
                                        <th class="table-lead-status">Статус</th>
                                        <th class="table-lead-count">Количество</th>
                                        <th class="table-lead-conversion">Конверсия</th>
                                    </tr>
                                </thead>
                                <tbody id="table-lead-statistics">
                                    <?php $lead_statistics_obj->handle_action('get_lead_statistics'); ?>
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

<?php require_once ($_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php'); ?>

<script src="/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script src="/plugins/datatables/dataTables.responsive.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="/scripts/lead-statistics.js"></script>