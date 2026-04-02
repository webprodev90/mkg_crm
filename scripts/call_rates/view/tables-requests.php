<?php if(count($campaigns) > 0) { ?>

    <?php
    $total_b = 0;
    $total_c = 0;
    $total_d = 0;
    $total_e = 0;
    $total_f = 0;
    $total_g = 0;
    $total_h = 0;
    $total_i = 0;
    $total_j = 0;
    $total_k = 0;
    $total_l = 0;
    $total_m = 0;
    $total_n = 0;
    $total_o = 0;
    ?>

    <div class="mb-2 font-weight-bold">Отчет по Кампаниям в АТС</div>
    <table class="table m-0 table-actions-bar nowrap table-bordered" cellspacing="0" width="100%"
        id="datatable1">
        <thead>
            <tr class="table-head-tr">
                <th class="table-a">Кампания</th>
                <th class="table-b">Звонков</th>
                <th class="table-c">Ответил оператор</th>
                <th class="table-d">Дозвонов</th>
                <th class="table-e">Лид</th>
                <th class="table-f">Длительность разговора</th>
                <th class="table-g">лид/доз</th>
                <th class="table-h">Количество не АО</th>
                <th class="table-i">% дозвона</th>
                <th class="table-j">Цена разговора+Робот</th>
                <th class="table-k">Цена МАВ+Этикетка</th>
                <th class="table-l">Цена Лида разговор</th>
                <th class="table-m">Цена лида (МАВ)</th>
                <th class="table-n">Цена лида ИТОГО</th>
                <th class="table-o">Цена разговора+Цена МАВ и Этикетка</th>
            </tr>
        </thead>
        <tbody class="table-request">
    <?php foreach($campaigns as $campaign_name => $campaign): ?>
        <?php 

            $total_b += (int) $campaign['B'];
            $total_c += (int) $campaign['C'];
            $total_d += (int) $campaign['D'];
            $total_e += (int) $campaign['E'];
            $total_f += (int) $campaign['F'];
            $total_j += (int) $campaign['J'];
            $total_k += (int) $campaign['K'];
            $total_o += (int) $campaign['O'];

        ?>
            <tr>
                <td class="table-id" name="id"><?= $campaign_name; ?></td>
                <td class="table-b" name="id"><?= $campaign['B']; ?></td>
                <td class="table-c" name="id"><?= $campaign['C']; ?></td>
                <td class="table-d" name="id"><?= $campaign['D']; ?></td>
                <td class="table-e" name="id"><?= $campaign['E']; ?></td>
                <td class="table-f" name="id"><?= $campaign['F']; ?></td>
                <td class="table-g" name="id"><?= $campaign['G']; ?>%</td>
                <td class="table-h" name="id"><?= $campaign['H']; ?>%</td>
                <td class="table-i" name="id"><?= $campaign['I']; ?>%</td>
                <td class="table-j" name="id"><?= $campaign['J']; ?> ₽</td>
                <td class="table-k" name="id"><?= $campaign['K']; ?> ₽</td>
                <td class="table-l" name="id"><?= $campaign['L']; ?> ₽</td>
                <td class="table-m" name="id"><?= $campaign['M']; ?> ₽</td>
                <td class="table-n" name="id"><?= $campaign['N']; ?> ₽</td>
                <td class="table-o" name="id"><?= $campaign['O']; ?> ₽</td>
            </tr>         

    <?php endforeach; ?>

    <?php
    if((int) $total_d !== 0) {
        $total_g = round($total_e / $total_d * 100, 1);
    }

    if((int) $total_c !== 0) {
        $total_h = round($total_d / $total_c * 100, 1);
    } 

    if($total_b !== 0) {
        $total_i = round( $total_d / $total_b * 100, 1);
    }

    if($total_e !== 0) {
        $total_l = round($total_j / $total_e);
    }

    if($total_e !== 0) {
        $total_m = round($total_k / $total_e);
    }

    if($total_e !== 0) {
        $total_n = round(($total_j + $total_k) / $total_e);
    }

    ?>

            <tr class="table-info">
                <td class="table-total">Общий итог</td>
                <td class="table-total-b"><?= $total_b; ?></td>
                <td class="table-total-c"><?= $total_c; ?></td>
                <td class="table-total-d"><?= $total_d; ?></td>
                <td class="table-total-e"><?= $total_e; ?></td>
                <td class="table-total-f"><?= $total_f; ?></td>
                <td class="table-total-g"><?= $total_g; ?>%</td>
                <td class="table-total-h"><?= $total_h; ?>%</td>
                <td class="table-total-i"><?= $total_i; ?>%</td>
                <td class="table-total-j"><?= $total_j; ?> ₽</td>
                <td class="table-total-k"><?= $total_k; ?> ₽</td>
                <td class="table-total-l"><?= $total_l; ?> ₽</td>
                <td class="table-total-m"><?= $total_m; ?> ₽</td>
                <td class="table-total-n"><?= $total_n; ?> ₽</td>
                <td class="table-total-o"><?= $total_o; ?> ₽</td>
            </tr> 
        </tbody>
    </table>

<?php } ?>

<?php if(count($trunks) > 0) { ?>

    <?php
    $total_b = 0;
    $total_c = 0;
    $total_d = 0;
    $total_e = 0;
    $total_f = 0;
    $total_g = 0;
    $total_h = 0;
    $total_i = 0;
    $total_j = 0;
    $total_k = 0;
    $total_l = 0;
    $total_m = 0;
    $total_n = 0;
    $total_o = 0;
    ?>

    <div class="mb-2 pt-4 font-weight-bold">Отчет по Транку</div>
    <table class="table m-0 table-actions-bar nowrap table-bordered" cellspacing="0" width="100%"
        id="datatable1">
        <thead>
            <tr class="table-head-tr">
                <th class="table-a">Транк</th>
                <th class="table-b">Звонков</th>
                <th class="table-c">Ответил оператор</th>
                <th class="table-d">Дозвонов</th>
                <th class="table-e">Лид</th>
                <th class="table-f">Длительность разговора</th>
                <th class="table-g">лид/доз</th>
                <th class="table-h">Количество не АО</th>
                <th class="table-i">% дозвона</th>
                <th class="table-j">Цена разговора+Робот</th>
                <th class="table-k">Цена МАВ+Этикетка</th>
                <th class="table-l">Цена Лида разговор</th>
                <th class="table-m">Цена лида (МАВ)</th>
                <th class="table-n">Цена лида ИТОГО</th>
                <th class="table-o">Цена разговора+Цена МАВ и Этикетка</th>
            </tr>
        </thead>
        <tbody class="table-request">
    <?php foreach($trunks as $trunk_name => $trunk): ?>
        <?php 

            $total_b += (int) $trunk['B'];
            $total_c += (int) $trunk['C'];
            $total_d += (int) $trunk['D'];
            $total_e += (int) $trunk['E'];
            $total_f += (int) $trunk['F'];
            $total_j += (int) $trunk['J'];
            $total_k += (int) $trunk['K'];
            $total_o += (int) $trunk['O'];

        ?>
            <tr>
                <td class="table-id" name="id"><?= $trunk_name; ?></td>
                <td class="table-b" name="id"><?= $trunk['B']; ?></td>
                <td class="table-c" name="id"><?= $trunk['C']; ?></td>
                <td class="table-d" name="id"><?= $trunk['D']; ?></td>
                <td class="table-e" name="id"><?= $trunk['E']; ?></td>
                <td class="table-f" name="id"><?= $trunk['F']; ?></td>
                <td class="table-g" name="id"><?= $trunk['G']; ?>%</td>
                <td class="table-h" name="id"><?= $trunk['H']; ?>%</td>
                <td class="table-i" name="id"><?= $trunk['I']; ?>%</td>
                <td class="table-j" name="id"><?= $trunk['J']; ?> ₽</td>
                <td class="table-k" name="id"><?= $trunk['K']; ?> ₽</td>
                <td class="table-l" name="id"><?= $trunk['L']; ?> ₽</td>
                <td class="table-m" name="id"><?= $trunk['M']; ?> ₽</td>
                <td class="table-n" name="id"><?= $trunk['N']; ?> ₽</td>
                <td class="table-o" name="id"><?= $trunk['O']; ?> ₽</td>
            </tr>         

    <?php endforeach; ?>

    <?php
    if((int) $total_d !== 0) {
        $total_g = round($total_e / $total_d * 100, 1);
    }

    if((int) $total_c !== 0) {
        $total_h = round($total_d / $total_c * 100, 1);
    } 

    if($total_b !== 0) {
        $total_i = round( $total_d / $total_b * 100, 1);
    }

    if($total_e !== 0) {
        $total_l = round($total_j / $total_e);
    }

    if($total_e !== 0) {
        $total_m = round($total_k / $total_e);
    }

    if($total_e !== 0) {
        $total_n = round(($total_j + $total_k) / $total_e);
    }

    ?>

            <tr class="table-info">
                <td class="table-total">Общий итог</td>
                <td class="table-total-b"><?= $total_b; ?></td>
                <td class="table-total-c"><?= $total_c; ?></td>
                <td class="table-total-d"><?= $total_d; ?></td>
                <td class="table-total-e"><?= $total_e; ?></td>
                <td class="table-total-f"><?= $total_f; ?></td>
                <td class="table-total-g"><?= $total_g; ?>%</td>
                <td class="table-total-h"><?= $total_h; ?>%</td>
                <td class="table-total-i"><?= $total_i; ?>%</td>
                <td class="table-total-j"><?= $total_j; ?> ₽</td>
                <td class="table-total-k"><?= $total_k; ?> ₽</td>
                <td class="table-total-l"><?= $total_l; ?> ₽</td>
                <td class="table-total-m"><?= $total_m; ?> ₽</td>
                <td class="table-total-n"><?= $total_n; ?> ₽</td>
                <td class="table-total-o"><?= $total_o; ?> ₽</td>
            </tr> 
        </tbody>
    </table>

<?php } ?>

<?php if(count($sources) > 0) { ?>

    <?php
    $total_b = 0;
    $total_c = 0;
    $total_d = 0;
    $total_e = 0;
    $total_f = 0;
    $total_g = 0;
    $total_h = 0;
    $total_i = 0;
    $total_j = 0;
    $total_k = 0;
    $total_l = 0;
    $total_m = 0;
    $total_n = 0;
    $total_o = 0;
    ?>

    <div class="mb-2 pt-4 font-weight-bold">Отчет по источнику в CRM</div>
    <table class="table m-0 table-actions-bar nowrap table-bordered" cellspacing="0" width="100%"
        id="datatable1">
        <thead>
            <tr class="table-head-tr">
                <th class="table-a">Источник</th>
                <th class="table-b">Звонков</th>
                <th class="table-c">Ответил оператор</th>
                <th class="table-d">Дозвонов</th>
                <th class="table-e">Лид</th>
                <th class="table-f">Длительность разговора</th>
                <th class="table-g">лид/доз</th>
                <th class="table-h">Количество не АО</th>
                <th class="table-i">% дозвона</th>
                <th class="table-j">Цена разговора+Робот</th>
                <th class="table-k">Цена МАВ+Этикетка</th>
                <th class="table-l">Цена Лида разговор</th>
                <th class="table-m">Цена лида (МАВ)</th>
                <th class="table-n">Цена лида ИТОГО</th>
                <th class="table-o">Цена разговора+Цена МАВ и Этикетка</th>
            </tr>
        </thead>
        <tbody class="table-request">
    <?php foreach($sources as $source_name => $source): ?>
        <?php 

            $total_b += (int) $source['B'];
            $total_c += (int) $source['C'];
            $total_d += (int) $source['D'];
            $total_e += (int) $source['E'];
            $total_f += (int) $source['F'];
            $total_j += (int) $source['J'];
            $total_k += (int) $source['K'];
            $total_o += (int) $source['O'];

        ?>
            <tr>
                <td class="table-id" name="id"><?= $source_name; ?></td>
                <td class="table-b" name="id"><?= $source['B']; ?></td>
                <td class="table-c" name="id"><?= $source['C']; ?></td>
                <td class="table-d" name="id"><?= $source['D']; ?></td>
                <td class="table-e" name="id"><?= $source['E']; ?></td>
                <td class="table-f" name="id"><?= $source['F']; ?></td>
                <td class="table-g" name="id"><?= $source['G']; ?>%</td>
                <td class="table-h" name="id"><?= $source['H']; ?>%</td>
                <td class="table-i" name="id"><?= $source['I']; ?>%</td>
                <td class="table-j" name="id"><?= $source['J']; ?> ₽</td>
                <td class="table-k" name="id"><?= $source['K']; ?> ₽</td>
                <td class="table-l" name="id"><?= $source['L']; ?> ₽</td>
                <td class="table-m" name="id"><?= $source['M']; ?> ₽</td>
                <td class="table-n" name="id"><?= $source['N']; ?> ₽</td>
                <td class="table-o" name="id"><?= $source['O']; ?> ₽</td>
            </tr>         

    <?php endforeach; ?>

    <?php
    if((int) $total_d !== 0) {
        $total_g = round($total_e / $total_d * 100, 1);
    }

    if((int) $total_c !== 0) {
        $total_h = round($total_d / $total_c * 100, 1);
    } 

    if($total_b !== 0) {
        $total_i = round( $total_d / $total_b * 100, 1);
    }

    if($total_e !== 0) {
        $total_l = round($total_j / $total_e);
    }

    if($total_e !== 0) {
        $total_m = round($total_k / $total_e);
    }

    if($total_e !== 0) {
        $total_n = round(($total_j + $total_k) / $total_e);
    }

    ?>

            <tr class="table-info">
                <td class="table-total">Общий итог</td>
                <td class="table-total-b"><?= $total_b; ?></td>
                <td class="table-total-c"><?= $total_c; ?></td>
                <td class="table-total-d"><?= $total_d; ?></td>
                <td class="table-total-e"><?= $total_e; ?></td>
                <td class="table-total-f"><?= $total_f; ?></td>
                <td class="table-total-g"><?= $total_g; ?>%</td>
                <td class="table-total-h"><?= $total_h; ?>%</td>
                <td class="table-total-i"><?= $total_i; ?>%</td>
                <td class="table-total-j"><?= $total_j; ?> ₽</td>
                <td class="table-total-k"><?= $total_k; ?> ₽</td>
                <td class="table-total-l"><?= $total_l; ?> ₽</td>
                <td class="table-total-m"><?= $total_m; ?> ₽</td>
                <td class="table-total-n"><?= $total_n; ?> ₽</td>
                <td class="table-total-o"><?= $total_o; ?> ₽</td>
            </tr> 
        </tbody>
    </table>

<?php } ?>

<?php if(count($regions) > 0) { ?>

    <?php
    $total_b = 0;
    $total_c = 0;
    $total_d = 0;
    $total_e = 0;
    $total_f = 0;
    $total_g = 0;
    $total_h = 0;
    $total_i = 0;
    $total_j = 0;
    $total_k = 0;
    $total_l = 0;
    $total_m = 0;
    $total_n = 0;
    $total_o = 0;
    ?>

    <div class="mb-2 pt-4 font-weight-bold">Отчет по Региону</div>
    <table class="table m-0 table-actions-bar nowrap table-bordered" cellspacing="0" width="100%"
        id="datatable1">
        <thead>
            <tr class="table-head-tr">
                <th class="table-a">Регион</th>
                <th class="table-b">Звонков</th>
                <th class="table-c">Ответил оператор</th>
                <th class="table-d">Дозвонов</th>
                <th class="table-e">Лид</th>
                <th class="table-f">Длительность разговора</th>
                <th class="table-g">лид/доз</th>
                <th class="table-h">Количество не АО</th>
                <th class="table-i">% дозвона</th>
                <th class="table-j">Цена разговора+Робот</th>
                <th class="table-k">Цена МАВ+Этикетка</th>
                <th class="table-l">Цена Лида разговор</th>
                <th class="table-m">Цена лида (МАВ)</th>
                <th class="table-n">Цена лида ИТОГО</th>
                <th class="table-o">Цена разговора+Цена МАВ и Этикетка</th>
            </tr>
        </thead>
        <tbody class="table-request">
    <?php foreach($regions as $region_name => $region): ?>
        <?php 

            $total_b += (int) $region['B'];
            $total_c += (int) $region['C'];
            $total_d += (int) $region['D'];
            $total_e += (int) $region['E'];
            $total_f += (int) $region['F'];
            $total_j += (int) $region['J'];
            $total_k += (int) $region['K'];
            $total_o += (int) $region['O'];

        ?>
            <tr>
                <td class="table-id" name="id"><?= $region_name; ?></td>
                <td class="table-b" name="id"><?= $region['B']; ?></td>
                <td class="table-c" name="id"><?= $region['C']; ?></td>
                <td class="table-d" name="id"><?= $region['D']; ?></td>
                <td class="table-e" name="id"><?= $region['E']; ?></td>
                <td class="table-f" name="id"><?= $region['F']; ?></td>
                <td class="table-g" name="id"><?= $region['G']; ?>%</td>
                <td class="table-h" name="id"><?= $region['H']; ?>%</td>
                <td class="table-i" name="id"><?= $region['I']; ?>%</td>
                <td class="table-j" name="id"><?= $region['J']; ?> ₽</td>
                <td class="table-k" name="id"><?= $region['K']; ?> ₽</td>
                <td class="table-l" name="id"><?= $region['L']; ?> ₽</td>
                <td class="table-m" name="id"><?= $region['M']; ?> ₽</td>
                <td class="table-n" name="id"><?= $region['N']; ?> ₽</td>
                <td class="table-o" name="id"><?= $region['O']; ?> ₽</td>
            </tr>         

    <?php endforeach; ?>

    <?php
    if((int) $total_d !== 0) {
        $total_g = round($total_e / $total_d * 100, 1);
    }

    if((int) $total_c !== 0) {
        $total_h = round($total_d / $total_c * 100, 1);
    } 

    if($total_b !== 0) {
        $total_i = round( $total_d / $total_b * 100, 1);
    }

    if($total_e !== 0) {
        $total_l = round($total_j / $total_e);
    }

    if($total_e !== 0) {
        $total_m = round($total_k / $total_e);
    }

    if($total_e !== 0) {
        $total_n = round(($total_j + $total_k) / $total_e);
    }

    ?>

            <tr class="table-info">
                <td class="table-total">Общий итог</td>
                <td class="table-total-b"><?= $total_b; ?></td>
                <td class="table-total-c"><?= $total_c; ?></td>
                <td class="table-total-d"><?= $total_d; ?></td>
                <td class="table-total-e"><?= $total_e; ?></td>
                <td class="table-total-f"><?= $total_f; ?></td>
                <td class="table-total-g"><?= $total_g; ?>%</td>
                <td class="table-total-h"><?= $total_h; ?>%</td>
                <td class="table-total-i"><?= $total_i; ?>%</td>
                <td class="table-total-j"><?= $total_j; ?> ₽</td>
                <td class="table-total-k"><?= $total_k; ?> ₽</td>
                <td class="table-total-l"><?= $total_l; ?> ₽</td>
                <td class="table-total-m"><?= $total_m; ?> ₽</td>
                <td class="table-total-n"><?= $total_n; ?> ₽</td>
                <td class="table-total-o"><?= $total_o; ?> ₽</td>
            </tr> 
        </tbody>
    </table>

<?php } ?>