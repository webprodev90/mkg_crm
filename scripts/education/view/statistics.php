<?php 

function get_background_table($status) {
    $color_class = '';
    $button = '';
    switch(true) {
        case $status == 'Не начат':  
            $color_class = 'table-danger';
            break;
        case $status == 'В процессе': 
            $color_class = 'table-warning';
            break;
        case $status == 'Выполнен':  
            $color_class = 'table-success';
            break;
        case $status == 'Не выполнен':  
            $color_class = 'table-secondary';
            break;    
    }

    return $color_class;
}

$i = 0; 
while($i < count($results)): 
$user_id = $results[$i]['user_id'];
?>    

    <tr>
        <td class="table-id"><?= $results[$i]['user_id']; ?></td>
        <td class="table-fio"><?= $results[$i]['name']; ?></td>
        <?php 
            while($i < count($results) and $user_id === $results[$i]['user_id']): 
        ?>	              
            <td class="text-center <?= get_background_table($results[$i]['status']); ?>"><?= $results[$i]['status']; ?></td>
		<?php
		    $i++;
			endwhile; 
		?> 
                                        
    </tr>

<?php endwhile; ?>