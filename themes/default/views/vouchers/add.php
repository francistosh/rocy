<script type="text/javascript">
    $(document).ready(function () {

    });
</script>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('add_voucher'); ?></h2>
    </div>
    <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-voucher-form');
    echo form_open_multipart("vouchers/add"); ?>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>

                <div class="col-md-6">
				  <div class="col-md-6">
                    <div class="form-group">
                        <?= lang("voucher_number", "voucher_number"); ?>
                        <input type="text" name="voucher_number" class="form-control" id="voucher_number" required="required"/>
                    </div>
					</div>

                   <!-- <div class="form-group">
                        <?= lang("warehouse_name", "type") ?>
                        <?php
                        $hotel[''] = "";
                        foreach ($warehouses as $warehouse) {
                            $hotel[$warehouse->id] = $warehouse->name;
                        }
                        echo form_dropdown('warehouse', $hotel, (isset($_POST['warehouse']) ? $_POST['warehouse'] : ($warehouse ? $warehouse->id : '')), 'class="form-control select" id="warehouse" placeholder="' . lang("select") . " " . lang("warehouse_name") . '" required="required" style="width:100%"')
                        ?>
                    </div> -->
<div class="col-md-6">
                    <div class="form-group">
                        <?= lang("customer_name", "type") ?>
                        <?php
                        $cust[''] = "";
                        foreach ($customers as $customer) {
                            $cust[$customer->id] = $customer->name;
                        }
                        echo form_dropdown('customer', $cust, (isset($_POST['customer']) ? $_POST['customer'] : ($customer ? $customer->id : '')), 'class="form-control select" id="customer" placeholder="' . lang("select") . " " . lang("customer_name") . '" required="required" style="width:100%"')
                        ?>
                    </div>
					</div>

                    <div class="form-group">
                        <label>Contact Details</label>
                        <div class="row">
						<div class="col-lg-12">
                            <div class="col-md-6" style="margin-left: 20px">
                                <label>Contact Name</label>
                                <input name="contact_name" class="form-control" type="text" id="contact_name" required="required">
                            </div>
                            <div class="col-md-6" style="margin-left: 20px">
                                <label>Contact Email</label>
                                <input name="contact_email" class="form-control" type="tel" id="contact_email" required="required">
                            </div>
                            <div class="col-md-6" style="margin-left: 20px">
                                <label>Contact Phone</label>
                                <input name="contact_phone" class="form-control" type="text" id="contact_phone" required="required">
                            </div>
                        </div>
						</div>
                    </div>

                   <!-- <div class="form-group">
                        <?= lang("biller_name", "type") ?>
                        <?php
                        $bil[''] = "";
                        foreach ($billers as $biller) {
                            $bil[$biller->id] = $biller->name;
                        }
                        echo form_dropdown('biller', $bil, (isset($_POST['biller']) ? $_POST['biller'] : ($biller ? $biller->id : '')), 'class="form-control select" id="biller" placeholder="' . lang("select") . " " . lang("biller_name") . '" required="required" style="width:100%"')
                        ?>
                    </div>

                    <div class="form-group">
                        <?= lang("group_name", "group_name"); ?>
                        <input type="text" name="group_name" class="form-control" id="group_name" required="required"/>
                    </div> 

                    <div class="form-group">
                        <?= lang("status", "status"); ?>
                        <select class="form-control" name="status" id="status">
                            <option value="Reserved">Reserve</option>
                        </select>
                    </div> -->
					<div class="row">
                    <div class="col-lg-12">
 <div class="col-md-4">
                    <div class="form-group">
                        <?= lang("no_adults", "no_adults"); ?>
                        <input type="text" name="no_adults" class="form-control" id="no_adults" required="required"/>
                    </div>
</div>
 <div class="col-md-4">
                    <div class="form-group">
                        <?= lang("no_children", "no_children"); ?>
                        <input type="text" name="no_children" class="form-control" id="no_children" />
                    </div>
					</div>
					</div>
					</div>
					<div class="row">
                    <div class="col-lg-12">
                    <div class="form-group">
                        <label>Children(s) Ages</label>
                        <div class="row" id="child_ages">


                        </div>
                    </div></div>
                    </div>
					<div class="row">
                    <div class="col-lg-12">
					<div class="col-md-4">
                    <div class="form-group">
                        <label>Booking Date</label>
                        <input type="date" name="date_in" class="form-control" id="date_in" required="required"/>
                    </div>
					 </div>
<div class="col-md-4">
                    <div class="form-group">
                        <?= lang("check_in", "check_in"); ?>
                        <input type="date" name="check_in" class="form-control" id="check_in" required="required"/>
                    </div>
					</div>
<div class="col-md-4">
                    <div class="form-group">
                        <?= lang("check_out", "check_out"); ?>
                        <input type="date" name="check_out" class="form-control" id="check_out" required="required"/>
                    </div>
					</div>
</div></div>
                    <div class="form-group">
                        <?= lang("residence", "residence"); ?>
                        <select class="form-control" name="residence" id="residence">
                            <option value="Resident">Resident</option>
                            <option value="Non-Resident">Non Resident</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <?= lang("remarks", "remarks"); ?>
                        <textarea rows="3" name="remarks" id="remarks" class="form-control" required="required">

                        </textarea>
                    </div>

                    <div class="form-group">
                        <?= lang("no_rooms", "type") ?>
                        <div class="row">
                            <div class="col-lg-4" style="margin-left: 20px">
                                <?php
                                foreach ($categories as $category) {
                                    echo form_label($category->name, null);

                                    echo form_input($category->code, '0', 'class="form-control" id="room_'.$category->id.'"');
                                }
                                ?>
                            </div>
                        </div>

                    </div>

                    <div class="form-group">
                        <?= lang("extra_bed", "extra_bed"); ?>
                        <input type="text" name="extra_bed" class="form-control" value="0" id="extra_bed" required="required"/>
                    </div>

                    <div class="form-group">
                        <?= lang("meal_plan", "meal_plan"); ?>
                        <input type="text" name="meal_plan" class="form-control" value="FB" id="meal_plan" required="required"/>
                    </div>

                    <div class="form-group">
                        <?php echo form_submit('add_voucher', lang('add_voucher'), 'class="btn btn-primary"'); ?>
						
						<?php echo form_submit('add_Reservation + Invoice', lang('add_voucher'), 'class="btn btn-info"'); ?>
                    </div>

                </div>

            </div>

        </div>

    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">

    $(document).ready(function (e) {
        $('#add-voucher-form').bootstrapValidator({
            feedbackIcons: {
                valid: 'fa fa-check',
                invalid: 'fa fa-times',
                validating: 'fa fa-refresh'
            }, excluded: [':disabled']
        });
        $('select.select').select2({minimumResultsForSearch: 6});
        fields = $('.modal-content').find('.form-control');
        $.each(fields, function () {
            var id = $(this).attr('id');
            var iname = $(this).attr('name');
            var iid = '#' + id;
            if (!!$(this).attr('data-bv-notempty') || !!$(this).attr('required')) {
                $("label[for='" + id + "']").append(' *');
                $(document).on('change', iid, function () {
                    $('form[data-toggle="validator"]').bootstrapValidator('revalidateField', iname);
                });
            }
        });

        $("#no_children").on("keydown", function (e) {

            if (e.keyCode === 13) {
                e.preventDefault();
                $("#child_ages").html('');
                var c =  $("#no_children").val();
                if (c>0){
                    for (var i =0;i<c;i++){
                        $("#child_ages").append('<div class="col-md-11" style="margin-left: 20px">' +
                            '<label>Child'+(i+1)+' Age</label>' +
                            '<input name="data['+i+']" class="form-control" type="text" id="data['+i+']" required="required">' +
                            '</div>');
                    }
                }

            }

        });
    });

</script>


