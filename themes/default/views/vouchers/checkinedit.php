<script type="text/javascript">
$(document).ready(function () {
$(".rpday").on("change", function (e) {

          var total = 0;
        $('.rpday').each( function(){
           total += parseFloat($(this).val());
        });
        $('#totalrpday_edit').val(total);
//alert(total);
        });
$(".rmtype").on("change", function (e) {

          var total = 0;
        $('.rmtype').each( function(){
           total += parseFloat($(this).val());
        });
        $('#totalrms_edit').val(total);

        });		
		
    });
</script>


<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('add_checkin'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

				<p class="introtext"><?php echo lang('enter_info'); ?></p>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'class' => 'edit-so-form');
                echo form_open_multipart("sales/checkinedit/" . $sale->id, $attrib)
                ?>
                <div class="row">
                    <div class="col-lg-12">
                        <?php// if ($Owner || $Admin) { ?>
						<div class="col-md-4">
                                        <div class="form-group">
                                            <?= lang("customer", "chkincustomer_edit"); ?>
                                            <div class="input-group">
      
                            <?php
								$cast[''] = "";
                            foreach ($customers as $customer) {
								$cast[$customer->id] = $customer->name;
                                
                            }
							 echo form_dropdown('chkincustomer_edit', $cast, (isset($_POST['customer']) ? $_POST['customer'] : $sale->customer_id), 'class="form-control select" id="chkincustomer_edit" placeholder="' . lang("Select")  . '" required="required" style="width:100%"')

                            ?>


                                                <div class="input-group-addon no-print" style="padding: 2px 5px;"><a
                                                        href="<?= site_url('customers/add'); ?>" id="add-customer"
                                                        class="external" data-toggle="modal" data-target="#myModal"><i
                                                            class="fa fa-2x fa-plus-circle" id="addIcon"></i></a></div>
                                            </div>
                                        </div>
                                    </div>
									<div class="col-md-3">
                                <div class="form-group">
                                <label>Contact Name</label>
                                <input name="contact_name_edit" class="form-control" type="text" id="contact_name_edit" value="<?= isset($_POST['contact_name_edit']) ? $_POST['contact_name_edit'] : $voucher->contact_name; ?>">
                            </div>
                            </div>
							<div class="col-md-3">
                                <div class="form-group">
                                <label>Contact Email</label>
                                <input name="contact_email_edit" class="form-control" type="text" id="contact_email_edit" value="<?= isset($_POST['contact_email_edit']) ? $_POST['contact_email_edit'] : $voucher->contact_email; ?>" >
                            </div>
                            </div>
							<div class="col-md-2">
                                <div class="form-group">
                                <label>Contact Phone</label>
                                <input name="contact_phone_edit" class="form-control" type="text" id="contact_phone_edit" value="<?= isset($_POST['contact_phone_edit']) ? $_POST['contact_phone_edit'] : $voucher->contact_phone; ?>" >
                            </div>
                            </div>
							<div class="col-md-4">
                                <div class="form-group">
                                   				<?= lang("check_in", "check_in_edit"); ?>
                        <input type="date" name="chkindate_edit" class="form-control" id="chkindate_edit" required="required" value="<?= isset($_POST['chkindate_edit']) ? $_POST['chkindate_edit'] : $voucher->check_in; ?>"/>
                                     <?php //echo form_input('chkindate', (isset($_POST['date']) ? $_POST['date'] : ""), 'class="form-control date" required="required" id="chkindate"'); ?>
                                                                    </div>
                            </div>
							<div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("Check_Out / To Date", "chkoutdate_edit"); ?>
									<input type="date" name="check_out_edit" class="form-control" id="check_out_edit" required="required" value="<?= isset($_POST['check_out_edit']) ? $_POST['check_out_edit'] : $voucher->check_out; ?>"/>
                                     <?php //echo form_input('chkoutdate', (isset($_POST['date']) ? $_POST['date'] : ""), 'class="form-control date" required="required" id="chkoutdate"'); ?>
                                    <?php //echo form_input('date', (isset($_POST['date']) ? $_POST['date'] : ""), 'class="form-control input-tip datetime" id="qudate" required="required"'); ?>
                                </div>
                            </div>
                        <?php //} ?>
                        <div class="col-md-4" style="display:none">
                            <div class="form-group">
                                <?= lang("reference_no", "quref"); ?>
                                <?php //echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : $qunumber), 'class="form-control input-tip" id="quref"'); ?>
                            </div>
                        </div>
                        <?php //if ($Owner || $Admin) { ?>
                            
                        <?php //} else {
                           // $biller_input = array(
                           //     'type' => 'hidden',
                           //     'name' => 'biller',
                           //     'id' => 'qubiller',
                          //      'value' => $this->session->userdata('biller_id'),
                          //  );

                          //  echo form_input($biller_input);
                       // } ?>

                      
                        <div class="col-md-4" >
                            <div class="form-group">
                                 <?= lang("no_adults", "no_adults_edit"); ?>
                                <?php echo form_input('no_adults_edit', (isset($_POST['no_adults_edit']) ? $_POST['no_adults_edit'] : $voucher->no_adults), 'class="form-control input-tip" id="no_adults_edit" required'); ?>
                            </div>
                        </div>
						<div class="col-md-4" >
                            <div class="form-group">
                                <?= lang("no_children", "no_children"); ?>
                                <?php echo form_input('no_children_edit', (isset($_POST['no_children_edit']) ? $_POST['no_children_edit'] : $voucher->no_children), 'class="form-control input-tip" id="no_children_edit" required'); ?>
                            </div>
                        </div>
                       
						
						 <div class="col-md-4">
                             <div class="form-group all">
                        <?= lang("Meal_Plan / Category", "meal_plan") ?>
						<select id="meal_plan_edit" class="form-control select" name="meal_plan_edit" placeholder="Select Category">
						<?= '<option selected value="'.$voucher->meal_plan.'"> '.$voucher->meal_plan.'</>' ?>
						<option value="BO"> Bed Only</option>
						<option value="BB"> Bed & Breakfast</option>
						<option value="HB"> Half Board</option>
						<option value="FB"> Full Board</option>
						</select>
                        
							</div>
                        </div>
                      
                      
                      <div class="col-md-4">
                            <div class="form-group">
                                    <label class="control-label" for="currency"><?= lang("Currency"); ?></label>

                                    <div class="controls"> <?php
									//print_r($currencies);
                                        foreach ($currencies as $currency) {
                                            $cu[$currency->code] = $currency->name;
                                        }
                                        echo form_dropdown('currency_edit', $cu, $Settings->default_currency, 'class="form-control tip" id="currency_edit" required="required" style="width:100%;"');
                                        ?>
                                    </div>
                                </div>
                        </div>
                        <div class="col-md-12">
                               <div class="col-md-2">
                        <?= lang("no_rooms", "type") ?>
                        
                            <div class="form-group">
                                <?php
							       foreach ($categories as $category) {
                                    echo form_label($category->name, null);
									if($category->code=='deluxe_room'){
									echo form_input($category->code.$category->id.'_edit', (isset($_POST['deluxe_room62_edit']) ? $_POST['deluxe_room62_edit'] : $voucher->deluxe_room), 'class="form-control rmtype" id="room_edit_'.$category->id.'"');	
									} else if($category->code=='executive_room'){
									echo form_input($category->code.$category->id.'_edit', (isset($_POST['executive_room60_edit']) ? $_POST['executive_room60_edit'] : $voucher->executive_room), 'class="form-control rmtype" id="room_edit_'.$category->id.'"');	
									} else if($category->code=='singles_room'){
									echo form_input($category->code.$category->id.'_edit', (isset($_POST['singles_room64_edit']) ? $_POST['singles_room64_edit'] : $voucher->singles_room), 'class="form-control rmtype" id="room_edit_'.$category->id.'"');		
									}else if($category->code=='standard_room'){
									echo form_input($category->code.$category->id.'_edit', (isset($_POST['standard_room63_edit']) ? $_POST['standard_room63_edit'] : $voucher->standard_room), 'class="form-control rmtype" id="room_edit_'.$category->id.'"');			
									}else if($category->code=='superior_room'){
									echo form_input($category->code.$category->id.'_edit', (isset($_POST['superior_room61_edit']) ? $_POST['superior_room61_edit'] : $voucher->superior_room), 'class="form-control rmtype" id="room_edit_'.$category->id.'"');			
									}else if($category->code=='twin_room'){
									echo form_input($category->code.$category->id.'_edit', (isset($_POST['twin_room65_edit']) ? $_POST['twin_room65_edit'] : $voucher->twin_room), 'class="form-control rmtype" id="room_edit_'.$category->id.'"');			
									}
									else{
                                    echo form_input($category->code.$category->id.'_edit', '0', 'class="form-control rmtype" id="room_edit_'.$category->id.'"');
									}
										?>
									                              <?php }
                                ?>
                            
                        </div>

                    </div>
						<div class="col-md-2">
                        <?= lang("Select_Room", "type") ?>
                        
                            <div class="form-group">
                                <?php
								                                foreach ($categories as $category) {
                                    echo form_label('**', null);?>
									
									<input type="hidden" name="roomids" class="form-control" id="roomids" />
                                    <?php echo form_input($category->code.'_edit', '0', 'class="form-control" style="display:none" id="room_edit_'.$category->id.'"');
															$sdatee = date('Y-m-d');		?>
									  <div class="input-group-addon no-print" style="padding: 2px 5px;"><a
                                                        href="<?= site_url('customers/status/?room_type='.$category->code.'&sdate='.$sdatee); ?>" id="add-customer"
                                                        class="external" data-toggle="modal" data-target="#myModal"><i
                                                            class="fa fa-2x fa-plus-circle" id="addIcon"></i></a></div>
                               <?php }
                                ?>
                            
                        </div>

                    </div>
					 <div class="col-md-3">
                        <?= lang("Room Numbers", "type") ?>
                        
                            <div class="form-group">
                                <?php
                                foreach ($categories as $category) {
                                    echo form_label('--', null);
								if($category->code=='deluxe_room'){
									echo form_input('chkinrms_edit'.$category->id, (isset($_POST['chkinrms_edit62']) ? $_POST['chkinrms_edit62'] : $voucher->deluxe_roomnos), 'class="form-control" readonly id="room_edit_'.$category->id.'"');
								}else if($category->code=='executive_room'){
									echo form_input('chkinrms_edit'.$category->id, (isset($_POST['chkinrms_edit60']) ? $_POST['chkinrms_edit60'] : $voucher->executive_roomnos), 'class="form-control" readonly id="room_edit_'.$category->id.'"');
								}
								else if($category->code=='singles_room'){
									echo form_input('chkinrms_edit'.$category->id, (isset($_POST['chkinrms_edit64']) ? $_POST['chkinrms_edit64'] : $voucher->single_roomnos), 'class="form-control" readonly id="room_edit_'.$category->id.'"');
								}
								else if($category->code=='standard_room'){
									echo form_input('chkinrms_edit'.$category->id, (isset($_POST['chkinrms_edit63']) ? $_POST['chkinrms_edit63'] : $voucher->standard_roomnos), 'class="form-control" readonly id="room_edit_'.$category->id.'"');
								}
								else if($category->code=='superior_room'){
									echo form_input('chkinrms_edit'.$category->id, (isset($_POST['chkinrms_edit61']) ? $_POST['chkinrms_edit61'] : $voucher->superior_roomnos), 'class="form-control" readonly id="room_edit_'.$category->id.'"');
								}
								else if($category->code=='twin_room'){
									echo form_input('chkinrms_edit'.$category->id, (isset($_POST['chkinrms_edit65']) ? $_POST['chkinrms_edit65'] : $voucher->twin_roomnos), 'class="form-control" readonly id="room_edit_'.$category->id.'"');
								}
								
										 }
                                ?>
                            
                        </div>

                    </div>
					<div class="col-md-2">
                        <?= lang("No_of_Adults", "type") ?>
                        
                            <div class="form-group">
                                 <?php
                                foreach ($categories as $category) {
                                    echo form_label('--', null);
								if($category->code=='deluxe_room'){
									echo form_input('adult_edit'.$category->id, (isset($_POST['adult_edit62']) ? $_POST['adult_edit62'] : $voucher->deluxe_adults), 'class="form-control"  id="adult_edit_'.$category->id.'"');
								}else if($category->code=='executive_room'){
									echo form_input('adult_edit'.$category->id, (isset($_POST['adult_edit60']) ? $_POST['adult_edit60'] : $voucher->executive_adults), 'class="form-control"  id="adult_edit_'.$category->id.'"');
								}
								else if($category->code=='singles_room'){
									echo form_input('adult_edit'.$category->id, (isset($_POST['adult_edit64']) ? $_POST['adult_edit64'] : $voucher->single_adult), 'class="form-control"  id="adult_edit_'.$category->id.'"');
								}
								else if($category->code=='standard_room'){
									echo form_input('adult_edit'.$category->id, (isset($_POST['adult_edit63']) ? $_POST['adult_edit63'] : $voucher->standard_adult), 'class="form-control"  id="adult_edit_'.$category->id.'"');
								}
								else if($category->code=='superior_room'){
									echo form_input('adult_edit'.$category->id, (isset($_POST['adult_edit61']) ? $_POST['adult_edit61'] : $voucher->superior_adult), 'class="form-control"  id="adult_edit_'.$category->id.'"');
								}
								else if($category->code=='twin_room'){
									echo form_input('adult_edit'.$category->id, (isset($_POST['adult_edit65']) ? $_POST['adult_edit65'] : $voucher->twin_adult), 'class="form-control"  id="adult_edit_'.$category->id.'"');
								}
								
										 }
                                ?>
                            
                        </div>

                    </div>
					 <div class="col-md-2">
                        <?= lang("Rate per Day", "type") ?>
                        
                            <div class="form-group">
                                <?php
                                foreach ($categories as $category) {
                                    echo form_label('--', null);
									if($category->code=='deluxe_room'){
									echo form_input('rate_edit'.$category->id, (isset($_POST['rate_edit62']) ? $_POST['rate_edit62'] : $voucher->deluxe_rate), 'class="form-control rpday" id="rate_edit'.$category->id.'"');
									} else if($category->code=='executive_room'){
									echo form_input('rate_edit'.$category->id, (isset($_POST['rate_edit60']) ? $_POST['rate_edit60'] : $voucher->executive_rate), 'class="form-control rpday" id="rate_edit'.$category->id.'"');
									} else if($category->code=='singles_room'){
									echo form_input('rate_edit'.$category->id, (isset($_POST['rate_edit64']) ? $_POST['rate_edit64'] : $voucher->singles_rate), 'class="form-control rpday" id="rate_edit'.$category->id.'"');
									}else if($category->code=='standard_room'){
										echo form_input('rate_edit'.$category->id, (isset($_POST['rate_edit63']) ? $_POST['rate_edit63'] : $voucher->standard_rate), 'class="form-control rpday" id="rate_edit'.$category->id.'"');
		
									}else if($category->code=='superior_room'){
										echo form_input('rate_edit'.$category->id, (isset($_POST['rate_edit61']) ? $_POST['rate_edit61'] : $voucher->superior_rate), 'class="form-control rpday" id="rate_edit'.$category->id.'"');
		
									}else if($category->code=='twin_room'){
											echo form_input('rate_edit'.$category->id, (isset($_POST['rate_edit65']) ? $_POST['rate_edit65'] : $voucher->twin_rate), 'class="form-control rpday" id="rate_edit'.$category->id.'"');
		
									}
                                    										?>
									                              <?php }
                                ?>
                            
                        </div>

                    </div>
                        </div>
					<div class="col-md-12">
					 <div class="col-md-3">
                        <?= lang("Total Rooms", "type") ?>
                        <input type="text" name="totalrms_edit" class="form-control" id="totalrms_edit" value="<?= isset($_POST['totalrms_edit']) ? $_POST['totalrms_edit'] : $voucher->deluxe_room + $voucher->executive_room+ $voucher->singles_room + $voucher->standard_room + $voucher->superior_room + $voucher->twin_room; ?>" readonly/>
                            <div class="form-group">
                               
                        </div>

                    </div>
					<div class="col-md-3">
					<?= lang("Bill to Room", "type") ?>
					<div class="input-group-addon no-print" style="padding: 2px 5px;"><a
                                                        href="<?= site_url('customers/status/?room_type=all&sdate='.$sdatee)?>" id="add-customer"
                                                        class="external" data-toggle="modal" data-target="#myModal"><i
                                                            class="fa fa-2x fa-plus-circle" id="addIcon"></i></a></div>
					</div>
					<div class="col-md-3">
					<?= lang("Bill to Room", "type") ?>
                        <input type="text" name="billtoroom_edit" class="form-control" id="billtoroom_edit" value="<?= isset($_POST['billtoroom_edit']) ? $_POST['billtoroom_edit'] : $sale->room_id; ?>" required />
                            <div class="form-group">
                               
                        </div>
					</div>
					
					<div class="col-md-3">
                        <?= lang("Rate per Day", "type") ?>
                        <input type="text" name="totalrpday_edit" class="form-control" id="totalrpday_edit" value="<?= isset($_POST['totalrpday_edit']) ? $_POST['totalrpday_edit'] : $sale->grand_total/$voucher->total_nights; ?>" readonly/>
                            <div class="form-group">
                               
                        </div>
					</div>

                        <div class="clearfix"></div>
                        <div class="col-md-12">
                       

                        <div class="col-sm-12">
                            <div
                                class="fprom-group"><?php echo form_submit('update_checkin', $this->lang->line("submit"), 'id="update_checkin" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                <button type="button" class="btn btn-danger" id="reset"><?= lang('reset') ?></div>
                        </div>
                    </div>
                </div>
               

                <?php echo form_close(); ?>

            </div>

        </div>
    </div>
</div>

