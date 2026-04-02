<?php
$sources = array();
$cities = array();
$cities_sources = array();
$max_kpd = 0;
foreach($results as $item) {
    $value = $item['name'];
    if(!in_array($value, $sources)) {
        $sources[] = $value;
    }
}
sort($sources);
foreach($results as $item) {
    $value = $item['city_group'];
    if(!in_array($value, $cities)) {
        $cities[] = $value;
    }
}

foreach($cities as $city) {
    foreach($sources as $source) {
        $cities_sources[$city][$source] = '-';
    }
}

foreach($results as $item) {
   $cities_sources[$item['city_group']][$item['name']] = [$item['kpd'], $item['leads'], $item['processed']]; 
}

?>

<thead>
    <tr class="table-head-tr-geo">
        <th class="p-0">ГЕО</th>
        <?php foreach($sources as $source): ?>
            <th class="p-0 text-center"><?= $source; ?></th>
        <?php endforeach; ?>    
    </tr>
</thead>
<tbody id="table-request-geo">
<?php foreach($cities_sources as $city => $sources_kpd): ?>
    <?php 
        $max_kpd = 0;
        foreach($sources_kpd as $source => $kpd) {
            if($kpd[0] !== '-') {
                $max_kpd = max($kpd[0], $max_kpd);
            }
        }
        $bg_color = $max_kpd == 0 ? ' bg-danger' : ' bg-success';

    ?>
    <tr>
        <th class="p-0"><?= $city; ?></th>
        <?php foreach($sources_kpd as $source => $kpd): ?>
            <td <?= $kpd[0] !== '-' ? " title='{$kpd[1]}/{$kpd[2]}'" : ''; ?> class="p-0 text-center<?= $kpd[0] === $max_kpd ? $bg_color : ''; ?>"><?= $kpd[0] !== '-' ? round($kpd[0], 2) . '%' : $kpd[0]; ?></td>
        <?php endforeach; ?>    
    </tr>   

<?php endforeach; ?>
</tbody>