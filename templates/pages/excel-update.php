<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/unprocessed_base_excel/unprocessed_base_excel.php';

if(!$_SESSION['login_role']) {
    header('Location: /'); 
}
/*
$unprocessed_base_obj = new UnprocessedBaseExcel();
$curdate = date('Y-m-d');
$unprocessed_base_obj->set(
    array(
        'view_name' => 'unprocessed',
        'limit_start' => 0,
        'limit_end' => 5000,
        'date_start' => $curdate,
        'date_end' => $curdate,
        'is_double' => 'n',
    )
);

$statuses_queryset = $unprocessed_base_obj->handle_action('get_statuses');
$statuses = '';
$statuses_search = '';
$statuses_for_lead = '';
foreach($statuses_queryset as $status) {
    if(($_SESSION['login_role'] == 1 && (int) $status['status_id'] == 9) || (int) $status['status_id'] != 9) {
        $statuses .= "<option value=\"{$status['status_id']}\">{$status['status_name']}</option>";
    }
}
foreach($statuses_queryset as $status) {
    if((int) $status['status_id'] !== 16) {
        $statuses_search .= "<option value=\"{$status['status_id']}\">{$status['status_name']}</option>";
    }
}
foreach($statuses_queryset as $status) {
    if(($_SESSION['login_role'] == 1 && (int) $status['status_id'] == 9) || ((int) $status['status_id'] == 6 || (int) $status['status_id'] == 15 || (int) $status['status_id'] == 16)) {
        $statuses_for_lead .= "<option value=\"{$status['status_id']}\">{$status['status_name']}</option>";
    }
}


$sources_queryset = $unprocessed_base_obj->handle_action('get_sources');
$sources_options = '';
if($sources_queryset != null) {
    foreach($sources_queryset as $source) {
        $sources_options .= "<option value=\"{$source['source']}\">{$source['source']}</option>";
    }    
}

$filtering_sources_queryset = $unprocessed_base_obj->handle_action('get_filtering_sources');
$sources_filtr = '';
if($filtering_sources_queryset != null) {
    foreach($filtering_sources_queryset as $sourcef) {
        $sources_filtr .= "<button class='btn btn-sourcesf lead-bg' data-sourcesf=\"{$sourcef['source']}\" >{$sourcef['source']} </button>";
    }    
}


$departments_queryset = $unprocessed_base_obj->handle_action('get_departments');
$departments_options = '';
foreach($departments_queryset as $department) {
    $departments_options .= "<option value=\"{$department['department_id']}\">{$department['name']}</option>";
}

$cities_group_sources_queryset = $unprocessed_base_obj->handle_action('get_cities_group_sources');
$cities_group_sources_options = '';
if($cities_group_sources_queryset != null) {
    foreach($cities_group_sources_queryset as $city) {
        $cities_group_sources_options .= "<option value=\"{$city['city_group']}\">{$city['name']}</option>";
    }
}
*/
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
                        <div class="mb-3">
                                <div class="row align-items-start">
                                    <?php if($_SESSION['login_role'] == 1 or $_SESSION['login_role'] == 4) { ?>
                                        <div class="col-12 form-inline justify-content-center align-items-start" style="gap: 10px;">
                                            <div class="form-inline" style="gap: 10px;">
                                                <button type="submit" class="btn waves-effect btn-primary import-request"
                                                    data-toggle="modal" data-target="#import-csv-modal">
                                                    Загрузить csv
                                                </button>  
                                            </div>
                                             
                                        </div>

                                    <?php } ?>
                                </div>                         
                        </div>
						<style>		
						.table-chec {
							width: 2%;
							text-align: center;
						}				
						</style>						

<?
$res1 = $db_connect->query('SELECT * FROM `settings` WHERE name_value = "oper_date_start" AND login_id = "' . $_SESSION['login_id'] . '"');	    						  
$oper_date_s = $res1->fetchAssoc();	

$res2 = $db_connect->query('SELECT * FROM `settings` WHERE name_value = "oper_date_end" AND login_id = "' . $_SESSION['login_id'] . '"');	    
$oper_date_e = $res2->fetchAssoc();		

$res3 = $db_connect->query('SELECT * FROM `bez_reg` WHERE login = "'.$_SESSION['login'].'"');	    
$login_id = $res3->fetchAssoc();		

$res4 = $db_connect->query('SELECT * FROM `st_partner_s` WHERE user_id = "'.$login_id['id'].'"');	    
$partner_id = $res4->fetchAssoc();		
if (!isset($partner_id['id'])){
	$partner_id['id'] = 0;
}
$res = $db_connect->query('SELECT `bez_excel_update`.`id`,
								  `bez_excel_update`.`unp_base_id`,
								  `bez_excel_update`.`region`,
								  `bez_excel_update`.`phone_number`,
								  `bez_excel_update`.`source`,
								  `bez_excel_update`.`status_id_in`,
								  `bez_excel_update`.`status_id_out`,
								  `bez_excel_update`.`comment`,
								  `bez_excel_update`.`date_create`
						     FROM `bez_excel_update` 							 
								   
							/*WHERE `bez_excel_update` .`date_create` between "' . $oper_date_s['date_value'] . '" and "' . $oper_date_e['date_value'] . '"*/
						  ');	
?>


                        <div class="card-box">
                            <table class="table m-0 table-actions-bar nowrap" cellspacing="0" width="100%"
                                id="datatable1">
                                <thead>
                                    <tr class="table-head-tr">
                                        <th class="table-id">ID</th>
										<th class="table-region">Регион</th>
										<th class="table-phone">Телефон</th>
                                        <th class="table-source">Источник</th>
                                        <th class="table-status-in">Статус был</th>
                                        <th class="table-status-out">Статус стал</th>
                                        <th class="table-comment">Комментарий</th>
                                        <th class="table-data-download">Дата загрузки</th>
                                    </tr>
                                </thead>
                                <tbody id="table-request">
									<?
									while( $row = $res->fetchAssoc() ){
									echo '<tr tr-id="' . $row['id'] . '">';
									   echo '<td>' . $row['unp_base_id'] . '</td>' .
											'<td>' . $row['region'] . '</td>' .
											'<td>' . $row['phone_number'] . '</td>' .
											'<td>' . $row['source'] . '</td>' .
											'<td>' . $row['status_id_in'] . '</td>' .
											'<td>' . $row['status_id_out'] . '</td>' .
											'<td>' . $row['comment'] . '</td>' .
											'<td>' . $row['date_create'] . '</td>';	
									echo '</tr>';		
									}
									?>
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
<div id="import-csv-modal" class="modal fade" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true"
    style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <h2 class="text-center m-b-30">Импорт CSV</h2>
                <form target="_blank" action="/basep/par_excel_update.php" method="post" enctype="multipart/form-data"
                    class="form-horizontal">
                    <div class="form-group col-md-12">
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <input type="file" name="file" />
                            </div>
                        </div>
                    </div>

                    <input type="submit" class="d-none run-import" />

                </form>

                <div class="row form-group account-btn mt-4">
                    <div class="col-md-12 text-center">
                        <button class="btn w-lg btn-rounded btn-primary waves-effect waves-light"
                            onclick="$('.run-import').click();">
                            Сохранить
                        </button>
                    </div>
                </div>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->




<?php require_once ($_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php'); ?>

<script src="/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script src="/plugins/datatables/dataTables.responsive.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="/scripts/main.js"></script>
<script src="/scripts/common.js"></script>