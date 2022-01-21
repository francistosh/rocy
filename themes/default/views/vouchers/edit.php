<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_voucher') . " (" . $voucher->voucher_no . ")";; ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'edit-voucher-form');
        echo form_open_multipart("vouchers/edit/" . $voucher->id, $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
            <div class="row">

                <div class="col-md-6">
                   <!-- <div class="form-group">
                        <?= lang("voucher_number", "voucher_number"); ?>
                        <input type="text" name="voucher_number" class="form-control" value="<?php echo $voucher->voucher_no ?>" id="voucher_number" required="required"/>
                    </div>
                    <div class="form-group">
                        <?= lang("warehouse_name", "warehouse_name") ?>
                        <select class="form-control" name="warehouse" id="warehouse" required >
                            <option value="">Please select hotel</option>
                            <?php

                            foreach ($warehouses as $warehouse) {
                                if($voucher->hotel_id == $warehouse->id){
                                    echo '<option selected value="'.$warehouse->id.'" >'.$warehouse->name.'</option>';
                                }else{
                                    echo '<option value="'.$warehouse->id.'" >'.$warehouse->name.'</option>';
                                }

                            }

                            ?>

                        </select>
                    </div>  -->
                    <div class="form-group">
                        <?= lang("customer_name", "customer_name"); ?>

                        <select class="form-control" name="customer" id="customer" required >
                            <option value="">Please select customer</option>
                            <?php

                            foreach ($customers as $customer) {
                                if($voucher->customer_id == $customer->id){
                                    echo '<option selected value="'.$customer->id.'" >'.$customer->name.'</option>';
                                }else{
                                    echo '<option value="'.$customer->id.'" >'.$customer->name.'</option>';
                                }

                            }

                            ?>

                        </select>
                    </div>
                   <!-- <div class="form-group">
                        <?= lang("biller_name", "biller_name"); ?>

                        <select class="form-control" name="biller" id="biller" required >
                            <option value="">Please select biller</option>
                            <?php

                            foreach ($billers as $biller) {
                                if($voucher->biller_id == $biller->id){
                                    echo '<option selected value="'.$biller->id.'" >'.$biller->name.'</option>';
                                }else{
                                    echo '<option value="'.$biller->id.'" >'.$biller->name.'</option>';
                                }

                            }

                            ?>

                        </select>
                    </div>  -->

                    <div class="form-group">
                        <?= lang("group_name", "group_name"); ?>
                        <input type="text" name="group_name" class="form-control" id="group_name" value="<?php echo $voucher->group_name ?>" />
                    </div>

                    <div class="form-group">
                        <?= lang("status", "status"); ?>
                        <select class="form-control" name="status" id="status">
                            <?php if($voucher->status == "Reserved"){?>
                            <option selected value="Reserved">Reserve</option>
                            <?php }else{?>
                            <option value="Reserved">Reserve</option>
                            <?php }?>
                            <?php if($voucher->status == "Canceled"){?>
                                <option selected value="Canceled">Cancel</option>
                            <?php }else{?>
                                <option value="Canceled">Cancel</option>
                            <?php }?>
                        </select>
                    </div>

                    <div class="form-group">
                        <?= lang("no_adults", "no_adults"); ?>
                        <input type="text" name="no_adults" class="form-control" id="no_adults" value="<?php echo $voucher->no_adults ?>" required="required"/>
                    </div>

                    <div class="form-group">
                        <?= lang("no_children", "no_children"); ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input type="text" name="no_children" value="<?php echo $voucher->no_children ?>" class="form-control" id="no_children" readonly="readonly"/>
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary form-control" id="add_new_age">ADD NEW</button>
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <label>Children(s) Ages</label>
                        <div class="row" id="child_ages">
                            <?php if (count($voucher_children)>0) { ?>
                                <?php for ($i=0;$i<count($voucher_children);$i++) { ?>
                                    <div style="margin-top: 20px" class="col-md-12" id="age<?php echo $i ?>">
                                        <div class="input-group">
                                            <input type="text" name="data[<?php echo $i ?>]" class="form-control" value="<?php echo $voucher_children[$i]["age"] ?>" id="actual_age<?php echo $i ?>" required="required"/>
                                            <span class="input-group-btn">
                                                <button class="btn btn-danger" id="remove_new_age<?php echo $i ?>" onclick="$('#age<?php echo $i ?>').remove();$('#no_children').val(parseInt($('#no_children').val(), 10)-1);deleteChild('data[<?php echo $i ?>]',<?php echo $voucher_children[$i]["id"] ?>);">X</button>
                                            </span>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php }else{?>
                                <div class="col-md-12" >
                                <label>No children</label>
                                </div>
                            <?php }?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="date_in" class="form-control" id="date_in" value="<?php echo $voucher->date_in ?>" required="required"/>
                    </div>

                    <div class="form-group">
                        <?= lang("check_in", "check_in"); ?>
                        <input type="date" name="check_in" class="form-control" id="check_in" value="<?php echo $voucher->check_in ?>" required="required"/>
                    </div>

                    <div class="form-group">
                        <?= lang("check_out", "check_out"); ?>
                        <input type="date" name="check_out" class="form-control" id="check_out" value="<?php echo $voucher->check_out ?>" required="required"/>
                    </div>

                    <div class="form-group">
                        <?= lang("residence", "residence"); ?>
                        <select class="form-control" name="residence" id="residence">
                            <?php if($voucher->status == "Resident"){?>
                                <option selected value="Resident">Resident</option>
                            <?php }else{?>
                                <option value="Resident">Resident</option>
                            <?php }?>
                            <?php if($voucher->status == "Non-Resident"){?>
                                <option selected value="Non-Resident">Non Resident</option>
                            <?php }else{?>
                                <option value="Non-Resident">Non Resident</option>
                            <?php }?>
                        </select>
                    </div>

                    <div class="form-group">
                        <?= lang("remarks", "remarks"); ?>
                        <textarea rows="5" name="remarks" id="remarks" class="form-control" required="required"><?php echo $voucher->remarks ?></textarea>
                    </div>

                    <div class="form-group">
                        <?= lang("no_rooms", "type") ?>
                        <div class="row">
                            <div class="col-lg-4" style="margin-left: 20px">
                                <label for="">Deluxe Room</label>
                                <input type="number" name="deluxe_room" value="<?php echo $voucher->deluxe_room ?>" class="form-control" id="room_47">
                                <label for="">Executive Room</label>
                                <input type="number" name="executive_room" value="<?php echo $voucher->executive_room ?>" class="form-control" id="room_52">
                                <label for="">Singles Room</label>
                                <input type="number" name="singles_room" value="<?php echo $voucher->singles_room ?>" class="form-control" id="room_53">
                                <label for="">Standard Room</label>
                                <input type="number" name="standard_room" value="<?php echo $voucher->standard_room ?>" class="form-control" id="room_48">
                                <label for="">Superior Room</label>
                                <input type="number" name="superior_room" value="<?php echo $voucher->superior_room ?>" class="form-control" id="room_51">
                                <label for="">Twin Room</label>
                                <input type="number" name="twin_room" value="<?php echo $voucher->twin_room ?>" class="form-control" id="room_50">
                            </div>
                        </div>

                    </div>

                    <div class="form-group">
                        <?= lang("extra_bed", "extra_bed"); ?>
                        <input type="text" name="extra_bed" class="form-control" id="extra_bed" value="<?php echo $voucher->extra_bed ?>" required="required"/>
                    </div>

                    <div class="form-group">
                        <?= lang("meal_plan", "meal_plan"); ?>
                        <input type="text" name="meal_plan" class="form-control" id="meal_plan" value="<?php echo $voucher->meal_plan ?>" required="required"/>
                    </div>

                    <div class="form-group">
                        <label>Contact Details</label>
                        <div class="row">
                            <div class="col-md-11" style="margin-left: 20px">
                                <label>Contact Name</label>
                                <input name="contact_name" class="form-control" type="text" value="<?php echo $voucher->contact_name ?>" id="contact_name" required="required">
                            </div>
                            <div class="col-md-11" style="margin-left: 20px">
                                <label>Contact Email</label>
                                <input name="contact_email" class="form-control" type="tel" value="<?php echo $voucher->contact_email ?>" id="contact_email" required="required">
                            </div>
                            <div class="col-md-11" style="margin-left: 20px">
                                <label>Contact Phone</label>
                                <input name="contact_phone" class="form-control" type="text" value="<?php echo $voucher->contact_phone ?>" id="contact_phone" required="required">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('edit_voucher', lang('edit_voucher'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">

    $(document).ready(function (e) {
        $('#edit-voucher-form').bootstrapValidator({
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
        $('#add_new_age').click(function(e){
            e.preventDefault();
            $("#no_children").attr('value', parseInt($("#no_children").val(), 10)+1);
            let last_input_id = $('#child_ages input:last').attr('id');
            if(last_input_id==null){
                $('#child_ages').append('<div style="margin-top: 20px" class="col-md-12" id="age0">\n' +
                    '      <div class="input-group">\n' +
                    '          <input type="text" name="data[0]" class="form-control" id="actual_age0"/>\n' +
                    '          <span class="input-group-btn">\n' +
                    '              <button class="btn btn-danger" id="remove_new_age0" onclick="$(\'#age0\').remove();$(\'#no_children\').val(parseInt($(\'#no_children\').val(), 10)-1);">X</button>\n' +
                    '          </span>\n' +
                    '      </div>\n' +
                    '</div>');
            }else {
                let actual_id = last_input_id.replace('actual_age','');
                let new_id =  parseInt(actual_id, 10)+1;

                $('#child_ages').append('<div style="margin-top: 20px" class="col-md-12" id="age'+new_id+'">\n' +
                    '      <div class="input-group">\n' +
                    '          <input type="text" name="data['+new_id+']" class="form-control" id="actual_age'+new_id+'"/>\n' +
                    '          <span class="input-group-btn">\n' +
                    '              <button class="btn btn-danger" id="remove_new_age'+new_id+'" onclick="$(\'#age'+new_id+'\').remove();$(\'#no_children\').val(parseInt($(\'#no_children\').val(), 10)-1);">X</button>\n' +
                    '          </span>\n' +
                    '      </div>\n' +
                    '</div>');
            }

        });

    });
    function deleteChild(element_name,id) {
        $('#edit-voucher-form').bootstrapValidator('enableFieldValidators', element_name, false);
        $.ajax({
            type:"get",
            url: '<?= site_url('vouchers/deleteChild'); ?>',
            data:{id:id},
            dataType: 'json',
            cache:false,
            success: function(response) {
                console.log(response);
            }
        });
    }
</script>