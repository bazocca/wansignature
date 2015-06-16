<?php
	if(is_array($data)) extract($data , EXTR_SKIP);
	$shortkey = substr($key, 5 );
	
	$required = "";
	if(strpos(strtolower($validation), 'not_empty') !== FALSE)
	{
		$required = 'REQUIRED';
	}
?>
<div class="control-group" <?php echo (empty($display)?'':'style="display:none"'); ?>>            
	<label class="control-label" <?php echo (!empty($required)?'style="color: red;"':''); ?>>
        <?php echo string_unslug($shortkey); ?>
    </label>
	<div class="controls radio">
		<?php
            $labelclass = array(
                'Cek Lunas' => 'btn btn-mini btn-success text-uppercase',
                'Cek Titip' => 'btn btn-mini btn-inverse text-uppercase',
                'Credit' => 'btn btn-mini btn-danger text-uppercase',
                'Debit' => 'btn btn-mini btn-primary text-uppercase',
            );

			$pertama = 1;
			$value = isset($_POST['data'][$model][$counter]['value'])?$_POST['data'][$model][$counter]['value']:$value;
			foreach ($list as $key10 => $value10)
			{
                $labelfor = 'data-'.$model.'-'.$counter.'-'.get_slug($value10['id']);
				if(strtolower($value10['id']) == strtolower($value) || $pertama == 1)
				{
					$pertama = 0;
					echo "<input id='".$labelfor."' class='".$shortkey."' ".$required." ".(empty($required)?'':'CHECKED')." value='".$value10['id']."' name='data[".$model."][".$counter."][value]' type='radio' /><label class='".$labelclass[$value10['name']]."' for='".$labelfor."'>".$value10['name']."</label>";
				}
				else
				{
					echo "<input id='".$labelfor."' class='".$shortkey."' ".$required." value='".$value10['id']."' name='data[".$model."][".$counter."][value]' type='radio' /><label class='".$labelclass[$value10['name']]."' for='".$labelfor."'>".$value10['name']."</label>";
				}
			}
			if(!empty($p))
			{
				echo '<p style="color:red;" class="help-block">'.$p.'</p>';
			}
		?>
	</div>
	<input type="hidden" value="<?php echo $key; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][key]"/>
	<input type="hidden" value="<?php echo $optionlist; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][optionlist]"/>	
	<input type="hidden" value="<?php echo $input_type; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][input_type]"/>
	<input type="hidden" value="<?php echo $validation; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][validation]"/>
	<input type="hidden" value="<?php echo $p; ?>" name="data[<?php echo $model; ?>][<?php echo $counter; ?>][instruction]"/>
</div>