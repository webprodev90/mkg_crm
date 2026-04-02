<?

require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/education/education.php';

if(!$_SESSION['login_role']) {
    header('Location: /'); 
}

$education_obj = new Education();


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

                            <h1 class="education-title">🎓Начальный курс обучения</h1>

                            <div class="lessons">

                                <?php $education_obj->handle_action('get_course'); ?>

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
<script src="/scripts/education.js"></script>