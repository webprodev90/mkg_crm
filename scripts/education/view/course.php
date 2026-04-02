<?php 

function get_background_badge($status) {
    $color_class = '';
    $button = '';
    switch(true) {
        case $status == 'Не начат':  
            $color_class = 'badge-primary';
            $button = '<button class="lesson-button btn btn-info start-lesson">Начать</button>';
            break;
        case $status == 'В процессе': 
            $color_class = 'badge-warning';
            $button = '';
            break;
        case $status == 'Выполнен':  
            $color_class = 'badge-success';
            $button = '<button class="lesson-button btn btn-info repeat-lesson">Начать заново</button>';
            break;
        case $status == 'Не выполнен':  
            $color_class = 'badge-secondary';
            $button = '<button class="lesson-button btn btn-info repeat-lesson">Попробовать снова</button>';
            break;    
    }

    return [$color_class, $button];
}

$i = 0; 
while($i < count($results)): 
$block_name = $results[$i]['block_name'];
?>    
		    <div class="lesson" data-block-id="<?= $results[$i]['id']; ?>">
		        <div class="lesson-wrapper">
		            <div class="d-flex justify-content-between">
		                <div class="lesson-title"><?= $results[$i]['block_name']; ?></div>
		                <?php if((int) $results[$i]['is_completed'] === 1 OR $_SESSION['login_role'] != 5): ?>
		                	<div class="lesson-status"><span class="badge badge-pill <?= get_background_badge($results[$i]['status'])[0]; ?>"><?= $results[$i]['status']; ?></span></div>
		                <?php endif; ?>	
		            </div>

		            <?php if((int) $results[$i]['is_completed'] === 1 OR $_SESSION['login_role'] != 5): ?>
			            <div class="lesson-text">
			                <div class="lesson-plan-text">План:</div>
			                <ul>
							<?php while($i < count($results) and $block_name === $results[$i]['block_name']): 
								$is_done = '';
								if($results[$i]['is_done']) {
									$is_done = "<span class='lesson-plan-status'>{$results[$i]['is_done']}</span>";
								}
							?>	                	
			                    	<li class="lesson-plan-text">
			                    		<?php 
			                    			if($results[$i]['link'] !== null and $results[$i]['type_task'] === 'reading'):
			                    		?>		
			                    			 <a href="<?= $results[$i]['link']; ?>"><?= $results[$i]['description_task']; ?></a>
			                    		<?php 
			                    			elseif((int) $results[$i]['is_available_test'] === 1 and $results[$i]['type_task'] === 'testing'):
			                    		?>
											<form method="POST" action="testing.php" class="d-inline-block">
	                                    		<input class="d-none" id="test_id" name="test_id" value="<?= $results[$i]['test_id']; ?>" />
	                                    		<input class="d-none" id="user_test_id" name="user_test_id" value="<?= $results[$i]['user_task_id']; ?>" />
	                                    		<button type="submit" class="btn btn-link p-0 testing-button-link"><?= $results[$i]['description_task']; ?></button>
	                                		</form> 
			                    		<?php 
			                    			else:
			                    		?>
			                    			<?= $results[$i]['description_task']; ?>
			                    		<?php 
			                    			endif;
			                    		?>		                    			
			                    		<?= $results[$i]['passing_score'] !== null ? "<b class='lesson-plan-count'> {$is_done} (<span class='lesson-plan-count-value'>{$results[$i]['score']}</span>/{$results[$i]['passing_score']})</b>" : '' ?></li>
			                	<?php
			                		$i++;
								endwhile; 
								?>    
			                </ul>
			            </div> 
			            <div class="lesson-button-box text-right">
			                <?= get_background_badge($results[$i - 1]['status'])[1]; ?>
			            </div>
		            <?php else: ?>
		            	<?php while($i < count($results) and $block_name === $results[$i]['block_name']): 
			                		$i++;
							  endwhile; 
		            	 ?>
			        	<div class="lesson-blocked d-flex justify-content-center align-items-center flex-column">
			        		<i class="fi-lock lesson-blocked-icon"></i>
			        		<h3>Заблокировано</h3>
			        	</div>	
		        	<?php endif; ?>
		        </div>
		    </div>


<?php endwhile; ?>