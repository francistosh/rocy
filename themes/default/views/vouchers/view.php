<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('voucher_details') . " (Voucher No: " . $voucher->voucher_no . ")";; ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-voucher-form');
        ?>
        <div class="modal-body">

            <h2><?= lang('voucher_details'); ?></h2>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <?= lang("customer_details", "customer_details") ?>
                        <?php foreach ($customers as $customer) {
                            if($customer["id"] == $voucher->customer_id){?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" value="<?php echo $customer["name"] ?>" disabled class="form-control"/>
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" value="<?php echo $customer["email"] ?>" disabled class="form-control"/>
                                    </div>
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" value="<?php echo $customer["phone"] ?>" disabled class="form-control"/>
                                    </div>
                                </div>

                            </div>
                        <?php }
                        }?>
                    </div>

                    <div class="form-group">
                        <label>Contact Details</label>
                        <div class="row">
                            <div class="col-md-11" style="margin-left: 20px">
                                <label>Contact Name</label>
                                <input name="contact_name" class="form-control" type="text" value="<?php echo $voucher->contact_name ?>" id="contact_name" disabled>
                            </div>
                            <div class="col-md-11" style="margin-left: 20px">
                                <label>Contact Email</label>
                                <input name="contact_email" class="form-control" type="tel" value="<?php echo $voucher->contact_email ?>" id="contact_email"disabled>
                            </div>
                            <div class="col-md-11" style="margin-left: 20px">
                                <label>Contact Phone</label>
                                <input name="contact_phone" class="form-control" type="text" value="<?php echo $voucher->contact_phone ?>" id="contact_phone" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= lang("date", "type") ?>
                        <input type="text" value="<?php echo $voucher->created_at ?>" disabled class="form-control"/>
                    </div>
                    <div class="form-group">
                        <?= lang("group_name", "group_name"); ?>
                        <input type="text" value="<?php echo $voucher->group_name ?>" disabled class="form-control"/>
                    </div>

                    <div class="form-group">
                        <?= lang("status", "status"); ?>
                        <input type="text" value="<?php echo $voucher->status ?>" disabled class="form-control"/>
                    </div>

                    <div class="form-group">
                        <?= lang("no_adults", "no_adults"); ?>
                        <input type="text" value="<?php echo $voucher->no_adults ?>" disabled class="form-control"/>
                    </div>

                    <div class="form-group">
                        <?= lang("no_children", "no_children"); ?>
                        <input type="text" value="<?php echo $voucher->no_children ?>" disabled class="form-control"/>
                    </div>

                    <label>Children(s) Ages</label>

                    <?php if (count($voucher_children)>0) { ?>
                        <?php for ($i=0;$i<count($voucher_children);$i++) { ?>

                            <div class="form-group">
                                <label>Child <?php echo $i+1 ?></label>
                                <input type="text" value="<?php echo $voucher_children[$i]["age"] ?>" disabled class="form-control"/>
                            </div>
                        <?php } ?>
                    <?php }else{?>
                        <div class="col-md-12" >
                            <label>No children</label>
                        </div>
                    <?php }?>

                    <div class="form-group">
                        <?= lang("check_in", "check_in"); ?>
                        <input type="text" value="<?php echo $voucher->check_in ?>" disabled class="form-control"/>
                    </div>

                    <div class="form-group">
                        <?= lang("check_out", "check_out"); ?>
                        <input type="text" value="<?php echo $voucher->check_out ?>" disabled class="form-control"/>
                    </div>

                    <div class="form-group">
                        <?= lang("residence", "residence"); ?>
                        <input type="text" value="<?php echo $voucher->residence ?>" disabled class="form-control"/>
                    </div>

                    <div class="form-group">
                        <?= lang("total_nights", "total_nights"); ?>
                        <input type="text" value="<?php echo $voucher->total_nights ?>" disabled class="form-control"/>
                    </div>

                    <div class="form-group">
                        <?= lang("remarks", "remarks"); ?>
                        <textarea class="form-control" disabled required="required"><?php echo $voucher->remarks ?></textarea>
                    </div>

                    <div class="form-group">
                        <?= lang("no_rooms", "type") ?>
                        <div class="form-group">
                            <label>Deluxe Room</label>
                            <input type="text" value="<?php echo $voucher->deluxe_room ?>" disabled class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label>Executive Room</label>
                            <input type="text" value="<?php echo $voucher->executive_room ?>" disabled class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label>Singles Room</label>
                            <input type="text" value="<?php echo $voucher->singles_room ?>" disabled class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label>Standard Room</label>
                            <input type="text" value="<?php echo $voucher->standard_room ?>" disabled class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label>Superior Room</label>
                            <input type="text" value="<?php echo $voucher->superior_room ?>" disabled class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label>Twin Room</label>
                            <input type="text" value="<?php echo $voucher->twin_room ?>" disabled class="form-control"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= lang("extra_bed", "extra_bed"); ?>
                        <input type="text" value="<?php echo $voucher->extra_bed ?>" disabled class="form-control"/>
                    </div>

                    <div class="form-group">
                        <?= lang("meal_plan", "meal_plan"); ?>
                        <input type="text" value="<?php echo $voucher->meal_plan ?>" disabled class="form-control"/>
                    </div>
                </div>
            </div>

        </div>
        <div class="modal-footer">

        </div>
    </div>

</div>

<script type="text/javascript">

</script>
