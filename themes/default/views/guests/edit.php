<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_guest') . " (" . $guest->full_name . ")";; ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'edit-guest-form');
        echo form_open_multipart("guests/edit/" . $guest->id, $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
            <h2><?= lang('personal_details'); ?></h2>
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <?= lang("room", "room"); ?>
                        <input type="text" name="data[0][room]" value="<?php echo $guest->room ?>" class="form-control" id="room" required="required"/>
                    </div>
                    <div class="form-group">
                        <?= lang("full_name", "full_name"); ?>
                        <input type="text" name="data[0][full_name]" value="<?php echo $guest->full_name ?>" class="form-control" id="full_name" required="required"/>
                    </div>

                    <div class="form-group">
                        <?= lang("passport_id_number", "passport_id_number"); ?>
                        <input type="text" name="data[0][passport_id_number]" value="<?php echo $guest->passport_id_number ?>" class="form-control" id="passport_id_number" required="required"/>
                    </div>

                    <div class="form-group">
                        <?= lang("alt_email_address", "alt_email_address"); ?>
                        <input type="email" name="data[0][alt_email]" value="<?php echo $guest->alt_email ?>" class="form-control" id="alt_email_address"/>
                    </div>

                    <div class="form-group">
                        <?= lang("telephone", "telephone"); ?>
                        <input type="tel" name="data[0][telephone]" value="<?php echo $guest->telephone ?>" class="form-control" id="telephone"/>
                    </div>

                    <div class="form-group">
                        <?= lang("dob", "dob"); ?>
                        <input type="text" name="data[0][dob]"value="<?php echo $guest->dob ?>" class="form-control" id="dob" required="required"/>
                    </div>

                    <div class="form-group">
                        <?= lang("passport_expiry", "passport_expiry"); ?>
                        <input type="text" name="data[0][passport_expiry]" value="<?php echo $guest->passport_expiry ?>" class="form-control" id="passport_expiry"/>
                    </div>

                </div>
                <div class="col-md-6">

                    <div class="form-group">
                        <?= lang("nationality", "nationality"); ?>
                        <input type="text" name="data[0][nationality]" value="<?php echo $guest->nationality ?>" class="form-control" id="nationality" required="required"/>
                    </div>

                    <div class="form-group">
                        <?= lang("email_address", "email_address"); ?>
                        <input type="email" name="data[0][email]" value="<?php echo $guest->email ?>" class="form-control" id="email_address" required="required"/>
                    </div>

                    <div class="form-group">
                        <?= lang("phone", "phone"); ?>
                        <input type="tel" name="data[0][phone]" value="<?php echo $guest->phone ?>" class="form-control" id="phone" required="required"/>
                    </div>

                    <div class="form-group">
                        <?= lang("alt_phone", "alt_phone"); ?>
                        <input type="tel" name="data[0][alt_phone]" value="<?php echo $guest->alt_phone ?>" class="form-control" id="alt_phone" />
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
                        <input type="text" name="data[0][address]" value="<?php echo $guest->address ?>" class="form-control" id="address" required="required"/>
                    </div>
                    <div class="form-group">
                        <?= lang("travel_history", "travel_history"); ?>
                        <textarea name="data[0][travel_history]" rows="3" class="form-control">
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
                        <input type="text" name="data[1][kin_full_name]" value="<?php echo $next_of_kin->full_name ?>" class="form-control" id="kin_full_name" required="required"/>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <?= lang("phone", "phone"); ?>
                        <input type="tel" name="data[1][kin_phone]" value="<?php echo $next_of_kin->phone ?>" class="form-control" id="kin_phone" required="required"/>
                    </div>
                </div>

            </div>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('edit_guest', lang('edit_guest'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function (e) {
        $('#edit-guest-form').bootstrapValidator({
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
    });
</script>
