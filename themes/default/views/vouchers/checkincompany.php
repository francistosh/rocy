<script type="text/javascript">
$(document).ready(function () {
$(".rpday").on("change", function (e) {

          var total = 0;
        $('.rpday').each( function(){
           total += parseFloat($(this).val());
        });
        $('#totalrpday').val(total);

        });
$(".rmtype").on("change", function (e) {

          var total = 0;
        $('.rmtype').each( function(){
           total += parseFloat($(this).val());
        });
        $('#totalrms').val(total);

        });		
		
    });
</script>


<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('Company_Checkin'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("vouchers/checkincompany", $attrib)
                ?>

				
                <div class="row">
                    <div class="col-lg-12">
                        <?php// if ($Owner || $Admin) { ?>
						<div class="col-md-4">
                                        <div class="form-group">
                                            <?= lang("Company", "chkincustomer"); ?>
                                            <div class="input-group">
      
                            <?php
							$cast[''] = "";
                            foreach ($customers as $customer) {
								$cast[$customer->id] = $customer->name;
                                
                            }
							 echo form_dropdown('chkincustomer', $cast, (isset($_POST['customer']) ? $_POST['customer'] : ($product ? $product->category_id : '')), 'class="form-control select" id="chkincustomer" placeholder="' . lang("Select_Company") . '" required="required" style="width:100%"')

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
                                <input name="contact_name" class="form-control" type="text" id="contact_name" required="required">
                            </div>
                            </div>
							<div class="col-md-3">
                                <div class="form-group">
                                <label>Contact Email</label>
                                <input name="contact_email" class="form-control" type="text" id="contact_email" >
                            </div>
                            </div>
							<div class="col-md-2">
                                <div class="form-group">
                                <label>Contact Phone</label>
                                <input name="contact_phone" class="form-control" type="text" id="contact_phone" >
                            </div>
                            </div>
							<div class="col-md-4">
                                <div class="form-group">
                                   				<?= lang("check_in", "check_in"); ?>
                        <input type="date" name="chkindate" class="form-control" id="chkindate" required="required"/>
                                     <?php //echo form_input('chkindate', (isset($_POST['date']) ? $_POST['date'] : ""), 'class="form-control date" required="required" id="chkindate"'); ?>
                                                                    </div>
                            </div>
							<div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("Check_Out / To Date", "chkoutdate"); ?>
									<input type="date" name="check_out" class="form-control" id="check_out" required="required"/>
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
                                 <?= lang("no_adults", "no_adults"); ?>
                                <?php echo form_input('no_adults', '', 'class="form-control input-tip" id="no_adults" '); ?>
                            </div>
                        </div>
						<div class="col-md-4" >
                            <div class="form-group">
                                <?= lang("no_children", "no_children"); ?>
                                <?php echo form_input('no_children', '', 'class="form-control input-tip" id="no_children" required'); ?>
                            </div>
                        </div>
                       
						
						 <div class="col-md-4">
                             <div class="form-group all">
                        <?= lang("Meal_Plan / Category", "meal_plan") ?>
						<select id="meal_plan" class="form-control select" name="meal_plan" placeholder="Select Category">
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
                                        echo form_dropdown('currency', $cu, $Settings->default_currency, 'class="form-control tip" id="currency" required="required" style="width:100%;"');
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

                                    echo form_input($category->code.$category->id, '0', 'class="form-control rmtype" id="room_'.$category->id.'"');
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
                                    <?php echo form_input($category->code, '0', 'class="form-control" style="display:none" id="room_'.$category->id.'"');
																	$stdate = date('Y-m-d');?>
									  <div class="input-group-addon no-print" style="padding: 2px 5px;"><a
                                                        href="<?= site_url('customers/status/?room_type='.$category->code.'&sdate='.$stdate); ?>" id="add-customer"
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

                                    echo form_input('chkinrms'.$category->id, '0', 'class="form-control" readonly id="room_'.$category->id.'"');
										?>
									                              <?php }
                                ?>
                            
                        </div>

                    </div>
					 <div class="col-md-2">
                        <?= lang("No_of_Adults", "type") ?>
                        
                            <div class="form-group">
                                <?php
                                foreach ($categories as $category) {
                                    echo form_label('**', null);

                                    echo form_input('adults'.$category->id, '0', 'class="form-control rmtype" id="adults'.$category->id.'"');
										?>
									                              <?php }
                                ?>
                            
                        </div>

                    </div>
					 <div class="col-md-2">
                        <?= lang("Rate per Day", "type") ?>
                        
                            <div class="form-group">
                                <?php
                                foreach ($categories as $category) {
                                    echo form_label('--', null);

                                    echo form_input('rate'.$category->id, '0', 'class="form-control rpday" id="rate'.$category->id.'"');
										?>
									                              <?php }
                                ?>
                            
                        </div>

                    </div>
                        </div>
					<div class="col-md-12">
					 <div class="col-md-3">
                        <?= lang("Total Rooms", "type") ?>
                        <input type="text" name="totalrms" class="form-control" id="totalrms" readonly/>
                            <div class="form-group">
                               
                        </div>

                    </div>
					<div class="col-md-3">
					</div>
					<div class="col-md-3">
					<?= lang("Bill to Room", "type") ?>
                        <input type="text" name="billtoroom" class="form-control" id="billtoroom" required />
                            <div class="form-group">
                               
                        </div>
					</div>
					<div class="col-md-3">
                        <?= lang("Rate per Day", "type") ?>
                        <input type="text" name="totalrpday" class="form-control" id="totalrpday" readonly/>
                            <div class="form-group">
                               
                        </div>
					</div>

                        <div class="clearfix"></div>
                        <div class="col-md-12">
                       

                        <div class="col-sm-12">
                            <div
                                class="fprom-group"><?php echo form_submit('add_quote', $this->lang->line("submit"), 'id="add_quote" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                <button type="button" class="btn btn-danger" id="reset"><?= lang('reset') ?></div>
                        </div>
                    </div>
                </div>
               

                <?php echo form_close(); ?>

            </div>

        </div>
    </div>
</div>

<div class="modal" id="prModal" tabindex="-1" role="dialog" aria-labelledby="prModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
                            class="fa fa-2x">&times;</i></span><span class="sr-only"><?=lang('close');?></span></button>
                <h4 class="modal-title" id="prModalLabel"></h4>
            </div>
            <div class="modal-body" id="pr_popover_content">
                <form class="form-horizontal" role="form">
                    <?php if ($Settings->tax1) { ?>
                        <div class="form-group">
                            <label class="col-sm-4 control-label"><?= lang('product_tax') ?></label>
                            <div class="col-sm-8">
                                <?php
                                $tr[""] = "";
                                foreach ($tax_rates as $tax) {
                                    $tr[$tax->id] = $tax->name;
                                }
                                echo form_dropdown('ptax', $tr, "", 'id="ptax" class="form-control pos-input-tip" style="width:100%;"');
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="pquantity" class="col-sm-4 control-label"><?= lang('quantity') ?></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="pquantity">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="poption" class="col-sm-4 control-label"><?= lang('product_option') ?></label>

                        <div class="col-sm-8">
                            <div id="poptions-div"></div>
                        </div>
                    </div>
                    <?php if ($Settings->product_discount) { ?>
                        <div class="form-group">
                            <label for="pdiscount"
                                   class="col-sm-4 control-label"><?= lang('product_discount') ?></label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="pdiscount">
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="pprice" class="col-sm-4 control-label"><?= lang('unit_price') ?></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="pprice">
                        </div>
                    </div>
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th style="width:25%;"><?= lang('net_unit_price'); ?></th>
                            <th style="width:25%;"><span id="net_price"></span></th>
                            <th style="width:25%;"><?= lang('product_tax'); ?></th>
                            <th style="width:25%;"><span id="pro_tax"></span></th>
                        </tr>
                    </table>
                    <input type="hidden" id="punit_price" value=""/>
                    <input type="hidden" id="old_tax" value=""/>
                    <input type="hidden" id="old_qty" value=""/>
                    <input type="hidden" id="old_price" value=""/>
                    <input type="hidden" id="row_id" value=""/>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="editItem"><?= lang('submit') ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="mModal" tabindex="-1" role="dialog" aria-labelledby="mModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
                            class="fa fa-2x">&times;</i></span><span class="sr-only"><?=lang('close');?></span></button>
                <h4 class="modal-title" id="mModalLabel"><?= lang('add_product_manually') ?></h4>
            </div>
            <div class="modal-body" id="pr_popover_content">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label for="mcode" class="col-sm-4 control-label"><?= lang('product_code') ?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="mcode">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="mname" class="col-sm-4 control-label"><?= lang('product_name') ?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="mname">
                        </div>
                    </div>
                    <?php if ($Settings->tax1) { ?>
                        <div class="form-group">
                            <label for="mtax" class="col-sm-4 control-label"><?= lang('product_tax') ?> *</label>

                            <div class="col-sm-8">
                                <?php
                                $tr[""] = "";
                                foreach ($tax_rates as $tax) {
                                    $tr[$tax->id] = $tax->name;
                                }
                                echo form_dropdown('mtax', $tr, "", 'id="mtax" class="form-control input-tip select" style="width:100%;"');
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="mquantity" class="col-sm-4 control-label"><?= lang('quantity') ?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="mquantity">
                        </div>
                    </div>
                    <?php if ($Settings->product_discount) { ?>
                        <div class="form-group">
                            <label for="mdiscount"
                                   class="col-sm-4 control-label"><?= lang('product_discount') ?></label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="mdiscount">
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="mprice" class="col-sm-4 control-label"><?= lang('unit_price') ?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="mprice">
                        </div>
                    </div>
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th style="width:25%;"><?= lang('net_unit_price'); ?></th>
                            <th style="width:25%;"><span id="mnet_price"></span></th>
                            <th style="width:25%;"><?= lang('product_tax'); ?></th>
                            <th style="width:25%;"><span id="mpro_tax"></span></th>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="addItemManually"><?= lang('submit') ?></button>
            </div>
        </div>
    </div>
</div>
