<?php
	$this->Get->create($data);
	if(is_array($data)) extract($data , EXTR_SKIP);
	if($isAjax == 0)
	{
		echo $this->element('admin_header_add');
		?>
		<script>
			$(document).ready(function(){
				// disable language selector ONLY IF one language available !!
				if($('div.lang-selector ul.dropdown-menu li').length <= 1)
				{
					$('div.lang-selector').hide();
				}

				// focus on anchor query url IF ANY ...
				<?php if(!empty($this->request->query['anchor'])): ?>
					$('div#form-<?php echo $this->request->query['anchor']; ?>').prevAll('a.get-from-library:first').focus();
				<?php endif; ?>
                
                // Hide main_image !!
                $('div.thumbs').hide();
                $('div.change-pic').hide();
			});
		</script>
		<?php
		echo '<div id="ajaxed" class="inner-content">';
	}
	else 
	{
		?>
		<script>
			$(document).ready(function(){
				$('#cmsAlert').css('display' , 'none');
			});
		</script>
		<?php
	}
	$myChildTypeLink = (!empty($myParentEntry)&&$myType['Type']['slug']!=$myChildType['Type']['slug']?'?type='.$myChildType['Type']['slug']:'');
	$myTranslation = ( empty($lang)||empty($myEntry) ? '' : (empty($myChildTypeLink)?'?':'&').'lang='.$lang);
	$targetSubmit = (empty($myType)?'pages':$myType['Type']['slug']).(empty($myChildType)?'':'/'.$myParentEntry['Entry']['slug']).(empty($myEntry)?'/add':'/edit/'.$myEntry['Entry']['slug']).$myChildTypeLink.$myTranslation;
	$saveButton = (empty($myEntry)?'Add New':(empty($lang)?'Save Changes':'Add Translation'));
	echo $this->Form->create('Entry', array('action'=>$targetSubmit,'type'=>'file','class'=>'notif-change form-horizontal fl','inputDefaults' => array('label' =>false , 'div' => false)));	
?>
	<fieldset>
		<script>
			$(document).ready(function(){
				if($('p#id-title-description').length > 0)
				{
					$('p#id-title-description').html('Last updated by <a href="#"><?php echo (empty($myEntry['AccountModifiedBy']['username'])?$myEntry['AccountModifiedBy']['email']:$myEntry['AccountModifiedBy']['username']).'</a> at '.date_converter($myEntry['Entry']['modified'], $mySetting['date_format'] , $mySetting['time_format']); ?>');
					$('p#id-title-description').css('display','<?php echo (!empty($lang)?'none':'block'); ?>');
				}
				
				// onchange origin callback ...
                $('input#warehouse-origin , input#exhibition-origin').change(function(e){
                    var targetid = e.target.getAttribute('id');
                    var targetvalue = $('input#'+targetid).nextAll('input[type=hidden]:first').val();
                    
                    var storage = '';
                    var content = '';
                    var cleaning = true;
                    if(targetvalue.length > 0)
                    {
                        storage = targetid.split('-');
                        storage = storage[0];
                        content = targetvalue;
                        
                        if(!$(this).is(':visible'))
                        {
                            cleaning = false;
                        }
                    }
                    
                    var $products = $('div.diamond-group , div.cor-jewelry-group , div.logistic-group');
                    $products.closest('div.control-group').find('a.add-raw').attr({
                        'data-storage': storage,
                        'data-content': content
                    });
                    
                    if(cleaning)
                    {
                        $products.html('').closest('div.control-group').find('a.add-raw').click();
                    }
                    else
                    {
                        $products.find('a.get-from-table').each(function(){
                            $(this).attr('href', $(this).attr('href')+'&storage='+storage+'&content='+content );
                        });
                    }
                });
                
                // onchange delivery_type callback ...
                $('select.delivery_type').change(function(){
                    // origin toggle ...
                    if($(this).val().indexOf('Exhibition To') >= 0 )
                    {
                        $('input.warehouse_origin').closest('div.control-group').hide();
                        $('input.warehouse_origin , input#warehouse-origin').val('');
                        
                        if(!$('input#exhibition-origin').is(':visible'))
                        {
                            $('input#exhibition-origin').change().closest('div.control-group').show();
                        }
                    }
                    else
                    {
                        $('input.exhibition_origin').closest('div.control-group').hide();
                        $('input.exhibition_origin , input#exhibition-origin').val('');
                        
                        if(!$('input#warehouse-origin').is(':visible'))
                        {
                            $('input#warehouse-origin').change().closest('div.control-group').show();
                        }
                    }
                    
                    // destination toggle ...
                    if($(this).val().indexOf('To') >= 0)
                    {
                        $('input.dmd_vendor_invoice').closest('div.control-group').hide();
                        $('input.dmd_vendor_invoice , input#dmd-vendor-invoice').val('');
                        
                        $('input.cor_vendor_invoice').closest('div.control-group').hide();
                        $('input.cor_vendor_invoice , input#cor-vendor-invoice').val('');
                        
                        $('input.vendor').closest('div.control-group').hide();
                        $('input.vendor , input#vendor').val('');
                        
                        $('input.dmd_client_invoice').closest('div.control-group').hide();
                        $('input.dmd_client_invoice , input#dmd-client-invoice').val('');
                        
                        $('input.cor_client_invoice').closest('div.control-group').hide();
                        $('input.cor_client_invoice , input#cor-client-invoice').val('');
                        
                        $('input.client').closest('div.control-group').hide();
                        $('input.client , input#client').val('');
                        
                        if($(this).val().indexOf('To Warehouse') >= 0)
                        {
                            $('input.exhibition_destination').closest('div.control-group').hide();
                            $('input.exhibition_destination , input#exhibition-destination').val('');
                            
                            $('input.warehouse_destination').closest('div.control-group').show();
                        }
                        else // to exhibition ...
                        {
                            $('input.warehouse_destination').closest('div.control-group').hide();
                            $('input.warehouse_destination , input#warehouse-destination').val('');
                            
                            $('input.exhibition_destination').closest('div.control-group').show();
                        }
                    }
                    else // to invoice / souvenir ...
                    {
                        $('input.warehouse_destination').closest('div.control-group').hide();
                        $('input.warehouse_destination , input#warehouse-destination').val('');
                        
                        $('input.exhibition_destination').closest('div.control-group').hide();
                        $('input.exhibition_destination , input#exhibition-destination').val('');
                        
                        // invoice toggle ...
                        if($(this).val().indexOf('Return') >= 0)
                        {
                            $('input.dmd_client_invoice').closest('div.control-group').hide();
                            $('input.dmd_client_invoice , input#dmd-client-invoice').val('');

                            $('input.cor_client_invoice').closest('div.control-group').hide();
                            $('input.cor_client_invoice , input#cor-client-invoice').val('');

                            $('input.client').closest('div.control-group').hide();
                            $('input.client , input#client').val('');
                            
                            $('input.vendor').closest('div.control-group').show();
                            
                            if($(this).val() == 'Diamond Return')
                            {
                                $('input.cor_vendor_invoice').closest('div.control-group').hide();
                                $('input.cor_vendor_invoice , input#cor-vendor-invoice').val('');
                                
                                $('input.dmd_vendor_invoice').closest('div.control-group').show();
                            }
                            else // Cor Return...
                            {
                                $('input.dmd_vendor_invoice').closest('div.control-group').hide();
                                $('input.dmd_vendor_invoice , input#dmd-vendor-invoice').val('');
                                
                                $('input.cor_vendor_invoice').closest('div.control-group').show();
                            }
                        }
                        else // SALE / souvenir ...
                        {
                            $('input.dmd_vendor_invoice').closest('div.control-group').hide();
                            $('input.dmd_vendor_invoice , input#dmd-vendor-invoice').val('');

                            $('input.cor_vendor_invoice').closest('div.control-group').hide();
                            $('input.cor_vendor_invoice , input#cor-vendor-invoice').val('');

                            $('input.vendor').closest('div.control-group').hide();
                            $('input.vendor , input#vendor').val('');
                            
                            $('input.client').closest('div.control-group').show();
                            
                            if($(this).val() == 'Souvenir')
                            {
                                $('input.cor_client_invoice').closest('div.control-group').hide();
                                $('input.cor_client_invoice , input#cor-client-invoice').val('');
                                
                                $('input.dmd_client_invoice').closest('div.control-group').hide();
                                $('input.dmd_client_invoice , input#dmd-client-invoice').val('');
                            }
                            else if($(this).val() == 'Diamond Sale')
                            {
                                $('input.cor_client_invoice').closest('div.control-group').hide();
                                $('input.cor_client_invoice , input#cor-client-invoice').val('');
                                
                                $('input.dmd_client_invoice').closest('div.control-group').show();
                            }
                            else // Cor Sale ...
                            {
                                $('input.dmd_client_invoice').closest('div.control-group').hide();
                                $('input.dmd_client_invoice , input#dmd-client-invoice').val('');
                                
                                $('input.cor_client_invoice').closest('div.control-group').show();
                            }
                        }
                    }
                    
                    // Goods toggle ...
                    if($(this).val().indexOf('Diamond') >= 0 || $(this).val().indexOf('Cor') >= 0)
                    {
                        $('div.logistic-group').html('').closest('div.control-group').hide().find('a.add-raw').click();
                        
                        if($(this).val().indexOf('Diamond') >= 0)
                        {
                            $('div.cor-jewelry-group').html('').closest('div.control-group').hide().find('a.add-raw').click();
                            $('div.diamond-group').closest('div.control-group').show();
                        }
                        else // cor jewelry ...
                        {
                            $('div.diamond-group').html('').closest('div.control-group').hide().find('a.add-raw').click();
                            $('div.cor-jewelry-group').closest('div.control-group').show();
                        }
                    }
                    else // pindah gudang / souvenir ...
                    {
                        $('div.logistic-group').closest('div.control-group').show();
                        
                        if($(this).val() == 'Souvenir')
                        {
                            $('div.diamond-group , div.cor-jewelry-group').html('').closest('div.control-group').hide().find('a.add-raw').click();
                        }
                        else // pindah gudang ...
                        {
                            $('div.diamond-group , div.cor-jewelry-group').closest('div.control-group').show();
                        }
                    }
                }).trigger('change');
			});
		</script>
		<p class="notes important" style="color: red;font-weight: bold;">* Red input MUST NOT be empty.</p>
		<input type="hidden" value="<?php echo (isset($_POST['data']['language'])?$_POST['data']['language']:(empty($lang)?substr($myEntry['Entry']['lang_code'], 0,2):$lang)); ?>" name="data[language]" id="myLanguage"/>
		<input type="hidden" value="<?php echo (isset($_POST['data']['Entry'][2]['value'])?$_POST['data']['Entry'][2]['value']:(empty($myEntry)?'0':$myEntry['Entry']['main_image'])); ?>" name="data[Entry][2][value]" id="mySelectCoverId"/>
		<input type='hidden' id="entry_image_type" value="<?php echo $myImageTypeList[isset($_POST['data']['Entry'][2]['value'])?$_POST['data']['Entry'][2]['value']:(empty($myEntry)?'0':$myEntry['Entry']['main_image'])]; ?>" />
		<?php
			$myAutomatic = (empty($myChildType)?$myType['TypeMeta']:$myChildType['TypeMeta']);
			$titlekey = "title";
			foreach ($myAutomatic as $key => $value)
			{
				if($value['key'] == 'title_key')
				{
					$titlekey = $value['value'];
					break;
				}
			}
			
			$value = array();
			$value['key'] = 'form-'.Inflector::slug($titlekey);
			$value['validation'] = 'not_empty';
			$value['model'] = 'Entry';
			$value['counter'] = 0;
			$value['input_type'] = 'text';
            $value['inputsize'] = 'input-medium';
			$value['value'] = (isset($_POST['data'][$value['model']][$value['counter']]['value'])?$_POST['data'][$value['model']][$value['counter']]['value']:$myEntry[$value['model']]['title']);
			echo $this->element('input_'.$value['input_type'] , $value);
		?>
		<!-- BEGIN TO LIST META ATTRIBUTES -->
		<?php
			$counter = 3;
			foreach ($myAutomatic as $key => $value)
			{
				if(substr($value['key'], 0 , 5) == 'form-')
				{
					$value['optionlist'] = $value['value'];
					unset($value['value']);

					// now get value from EntryMeta if existed !!
					foreach ($myEntry['EntryMeta'] as $key10 => $value10) 
					{						
						if($value['key'] == $value10['key'])
						{
							$value['value'] = $value10['value'];
							break;
						}
					}
					$value['model'] = 'EntryMeta';
					$value['counter'] = $counter++;
					$value['p'] = $value['instruction'];
					switch ($value['input_type']) 
					{
						case 'checkbox':
						case 'radio':
						case 'dropdown':
							$temp = explode(chr(13).chr(10), $value['optionlist']);
							foreach ($temp as $key50 => $value50) 
							{
								$value['list'][$key50]['id'] = $value['list'][$key50]['name'] = $value50;
							}
							break;
						default:
							break;
					}
                    
                    // custom display ...
                    if(strpos($value['key'] , '_origin') !== FALSE)
                    {
                        $value['display'] = 'none';
                    }
                    
                    // view mode ...
                    if(!empty($myEntry))
                    {
                        $value['view_mode'] = true;
                    }
                    
					echo $this->element(($value['key']=='form-logistic'?'special':'input').'_'.$value['input_type'] , $value);
				}
			}
		?>		
		<!-- END OF META ATTRIBUTES -->
		
		<?php
			// Our CKEditor Description Field !!
			$value = array();
			$value['key'] = 'form-description';
			$value['validation'] = '';
			$value['model'] = 'Entry';
			$value['counter'] = 1;
			$value['input_type'] = 'textarea';
			$value['value'] = (isset($_POST['data'][$value['model']][$value['counter']]['value'])?$_POST['data'][$value['model']][$value['counter']]['value']:$myEntry[$value['model']]['description']);
            $value['p'] = 'Keterangan tambahan mengenai Surat Jalan ini (seperti keterangan cek fisik barang-barang yang dikirim, dll).';
			echo $this->element('input_'.$value['input_type'] , $value);

			// show status field if update (NEW ZPANEL FEATURE) !!
			$value = array();
			$value['counter'] = 3;
			$value['key'] = 'form-status';
			$value['validation'] = 'not_empty';
			$value['model'] = 'Entry';
			$value['input_type'] = 'dropdown';
			$value['list'][0]['id'] = '0';
			$value['list'][0]['name'] = 'On Process';
			$value['list'][1]['id'] = '1';
			$value['list'][1]['name'] = 'Accepted';
            $value['value'] = (isset($_POST['data'][$value['model']][$value['counter']]['value'])?$_POST['data'][$value['model']][$value['counter']]['value']:$myEntry[$value['model']]['status']);
            $value['p'] = "Pilih <strong>On Process</strong> jika pengiriman masih diproses.<br>Pilih <strong>Accepted</strong> jika seluruh pengiriman barang sudah diterima oleh pihak penerima.";
            $value['display'] = ($myEntry['Entry']['status']==1?'none':'');
			echo $this->element('input_'.$value['input_type'] , $value);
		?>
		
		<div class="control-group <?php echo ($myEntry['Entry']['status']==1?'':'hide'); ?>">
            <label class="control-label">Status</label>
            <div class="controls">
                <span class="label label-success">Accepted</span>
                <p class="help-block">Seluruh pengiriman barang sudah diterima oleh pihak penerima.</p>
            </div>
        </div>
		
		<!-- myTypeSlug is for media upload settings purpose !! -->
		<input type="hidden" value="<?php echo (empty($myChildType)?$myType['Type']['slug']:$myChildType['Type']['slug']); ?>" id="myTypeSlug"/>
	<!-- SAVE BUTTON -->
		<div class="control-action">
			<!-- always use submit button to submit form -->
			<button id="save-button" type="submit" class="btn btn-primary"><?php echo $saveButton; ?></button>
           
        	<button type="button" class="btn cancel-form-button" onclick="javascript: window.location=site+'admin/entries/<?php echo (empty($myType)?'pages':$myType['Type']['slug']).(empty($myChildType)?'':'/'.$myParentEntry['Entry']['slug']).$myChildTypeLink; ?>'">Cancel</button>
		</div>
	</fieldset>
<?php echo $this->Form->end(); ?>
	<div class="clear"></div>
<?php
	if($isAjax == 0)
	{
		echo '</div>';
	}
?>