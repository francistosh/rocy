<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('guest_details') . " (" . $guest->full_name . ")";; ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-temp-form');
        ?>
        <div class="modal-body">

            <h2><?= lang('personal_details'); ?></h2>
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <?= lang("room", "room"); ?>
                        <input type="text" name="data[0][room]" class="form-control" value="<?php echo $guest->room ?>" disabled id="room" required="required"/>
                    </div>
                    <div class="form-group">
                        <?= lang("full_name", "full_name"); ?>
                        <input type="text" name="data[0][full_name]" value="<?php echo $guest->full_name ?>" disabled class="form-control" id="full_name" required="required"/>
                    </div>

                    <div class="form-group">
                        <?= lang("passport_id_number", "passport_id_number"); ?>
                        <input type="text" name="data[0][passport_id_number]" value="<?php echo $guest->passport_id_number ?>" disabled class="form-control" id="passport_id_number" required="required"/>
                    </div>

                    <div class="form-group">
                        <?= lang("alt_email_address", "alt_email_address"); ?>
                        <input type="email" name="data[0][alt_email]" value="<?php echo $guest->alt_email ?>" disabled class="form-control" id="alt_email_address"/>
                    </div>

                    <div class="form-group">
                        <?= lang("telephone", "telephone"); ?>
                        <input type="tel" name="data[0][telephone]" value="<?php echo $guest->telephone ?>" disabled class="form-control" id="telephone"/>
                    </div>

                    <div class="form-group">
                        <?= lang("dob", "dob"); ?>
                        <input type="date" name="data[0][dob]" value="<?php echo $guest->dob ?>" disabled class="form-control" id="dob" required="required"/>
                    </div>

                    <div class="form-group">
                        <?= lang("passport_expiry", "passport_expiry"); ?>
                        <input type="date" name="data[0][passport_expiry]" value="<?php echo $guest->passport_expiry ?>" disabled class="form-control" id="passport_expiry" required="required"/>
                    </div>

                </div>
                <div class="col-md-6">

                    <div class="form-group">
                        <?= lang("nationality", "nationality"); ?>
                        <input type="text" name="data[0][nationality]" value="<?php echo $guest->nationality ?>" disabled class="form-control" id="nationality" required="required"/>
                    </div>

                    <div class="form-group">
                        <?= lang("email_address", "email_address"); ?>
                        <input type="email" name="data[0][email]" value="<?php echo $guest->email ?>" disabled class="form-control" id="email_address" required="required"/>
                    </div>

                    <div class="form-group">
                        <?= lang("phone", "phone"); ?>
                        <input type="tel" name="data[0][phone]" value="<?php echo $guest->phone ?>" disabled class="form-control" id="phone" required="required"/>
                    </div>

                    <div class="form-group">
                        <?= lang("alt_phone", "alt_phone"); ?>
                        <input type="tel" name="data[0][alt_phone]" value="<?php echo $guest->alt_phone ?>" disabled class="form-control" id="alt_phone" />
                    </div>

                    <div class="form-group">
                        <?= lang("gender", "gender"); ?>
                        <select name="data[0][gender]" class="form-control" id="gender" required="required">
                            <?php if ($guest->gender == "M") { ?>
                            <option value="M" selected>Male</option>
                            <?php }else if ($guest->gender == "F") { ?>
                            <option value="F" selected>Female</option>
                            <?php }else{ ?>
                            <option value="O">Other</option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <?= lang("address", "address"); ?>
                        <input type="text" name="data[0][address]" value="<?php echo $guest->address ?>" disabled class="form-control" id="address" required="required"/>
                    </div>
                    <div class="form-group">
                        <?= lang("travel_history", "travel_history"); ?>
                        <textarea name="data[0][travel_history]" rows="3" disabled class="form-control">
                            <?php echo $guest->travel_history ?>
                        </textarea>
                    </div>
                </div>
            </div>
            <h2><?= lang('next_of_kin'); ?></h2>
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <?= lang("full_name", "full_name"); ?>
                        <input type="text" name="data[1][kin_full_name]" value="<?php echo $next_of_kin->full_name ?>" disabled class="form-control" id="kin_full_name" required="required"/>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <?= lang("phone", "phone"); ?>
                        <input type="tel" name="data[1][kin_phone]" value="<?php echo $next_of_kin->phone ?>" disabled class="form-control" id="kin_phone" required="required"/>
                    </div>
                </div>

            </div>
            <h2><?= lang('dietary_requirement'); ?></h2>
            <?php if (count($requirements)>0) { ?>
                <?php foreach ($requirements as $requirement) { ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <?= lang("requirement", "requirement"); ?>
                                <input type="text" value="<?php echo $requirement["requirement"] ?>" disabled class="form-control" required="required"/>
                            </div>
                        </div>

                    </div>
                <?php } ?>
            <?php }else{ ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <?= lang("requirement", "requirement"); ?>
                            <input type="text" value="No specified requirements" disabled class="form-control" required="required"/>
                        </div>
                    </div>

                </div>
            <?php } ?>

            <h2><?= lang('medical_condition'); ?></h2>
            <?php if (count($medical_conditions)>0) { ?>
                <?php foreach ($medical_conditions as $medical_condition) { ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <?= lang("condition", "condition"); ?>
                                <input type="text" value="<?php echo $medical_condition["medical_condition"] ?>" disabled class="form-control" required="required"/>
                            </div>
                        </div>

                    </div>
                <?php } ?>
            <?php }else{ ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <?= lang("condition", "condition"); ?>
                            <input type="text" value="No specified conditions" disabled class="form-control" required="required"/>
                        </div>
                    </div>

                </div>
            <?php } ?>

            <h2><?= lang('temperature'); ?></h2>
            <?php if (count($temperatures)>0) { ?>
                <?php foreach ($temperatures as $temperature) { ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang("temperature", "temperature"); ?>
                                <input type="text" value="<?php echo $temperature["temp"] ?>" disabled class="form-control" required="required"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang("date_time", "date_time"); ?>
                                <input type="text" value="<?php echo $temperature["created_at"] ?>" disabled class="form-control" required="required"/>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php }else{ ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <?= lang("temperature", "temperature"); ?>
                            <input type="text" value="No specified conditions" disabled class="form-control" required="required"/>
                        </div>
                    </div>

                </div>
            <?php } ?>
        </div>
        <div class="modal-footer">

        </div>
    </div>

</div>

<script type="text/javascript">

</script>
