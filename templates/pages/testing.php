<?

require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/testing/testing.php';

$testing_obj = new Testing();

$test_id = null;
$user_test_id = null;
if(isset($_POST["test_id"]) and isset($_POST["user_test_id"])) {
    $test_id = $_POST["test_id"];
    $user_test_id = $_POST["user_test_id"];
}
else {
    header("Location: /templates/pages/unprocessed-base-1.php?p=10");
    exit;
}

$testing_obj->set(
    array(
        'test_id' => $test_id,
    )
);

$test_name = $testing_obj->handle_action('get_test_name');


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
                            <h1 class="education-title" data-user-test-id="<?= $user_test_id; ?>">📝 <?= $test_name; ?></h1>
                            <div class="testing m-5">
                                <div class="text-center">
                                    <button id="start-testing" class="btn btn-info testing-button" type="button" data-test-id="<?= $test_id; ?>">Начать тестирование</button>
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
<script src="/scripts/testing.js"></script>