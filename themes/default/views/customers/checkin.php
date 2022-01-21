<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('check_in_customer'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'check_in-customer-form');
        echo form_open_multipart("customers/checkin/" . $company->id, $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
            <h2><?= lang('personal_details'); ?></h2>
            <div class="row">

                <div class="col-md-6">

                    <div class="form-group">
                        <?= lang("full_name", "full_name"); ?>
                        <input type="text" name="data[0][full_name]" class="form-control" id="full_name" required="required"/>
                    </div>

                    <div class="form-group">
                        <?= lang("passport_id_number", "passport_id_number"); ?>
                        <input type="text" name="data[0][passport_id_number]" class="form-control" id="passport_id_number" required="required"/>
                    </div>

                    <div class="form-group">
                        <?= lang("alt_email_address", "alt_email_address"); ?>
                        <input type="email" name="data[0][alt_email]" class="form-control" id="alt_email_address"/>
                    </div>

                    <div class="form-group">
                        <?= lang("telephone", "telephone"); ?>
                        <input type="tel" name="data[0][telephone]" class="form-control" id="telephone"/>
                    </div>

                    <div class="form-group">
                        <?= lang("dob", "dob"); ?>
                        <input type="date" name="data[0][dob]" class="form-control" id="dob" required="required"/>
                    </div>

                    <div class="form-group">
                        <?= lang("passport_expiry", "passport_expiry"); ?>
                        <input type="date" name="data[0][passport_expiry]" class="form-control" id="passport_expiry" required="required"/>
                    </div>

                </div>
                <div class="col-md-6">

                    <div class="form-group">
                        <?= lang("nationality", "nationality"); ?>
                        <input type="text" name="data[0][nationality]" class="form-control" id="nationality" required="required"/>
                    </div>

                    <div class="form-group">
                        <?= lang("email_address", "email_address"); ?>
                        <input type="email" name="data[0][email]" class="form-control" id="email_address" required="required"/>
                    </div>

                    <div class="form-group">
                        <?= lang("phone", "phone"); ?>
                        <input type="tel" name="data[0][phone]" class="form-control" id="phone" required="required"/>
                    </div>

                    <div class="form-group">
                        <?= lang("alt_phone", "alt_phone"); ?>
                        <input type="tel" name="data[0][alt_phone]" class="form-control" id="alt_phone" />
                    </div>

                    <div class="form-group">
                        <?= lang("gender", "gender"); ?>
                        <select name="data[0][gender]" class="form-control" id="gender" required="required">
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                            <option value="O">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <?= lang("address", "address"); ?>
                        <input type="text" name="data[0][address]" class="form-control" id="address" required="required"/>
                    </div>

                </div>
            </div>
            <h2><?= lang('next_of_kin'); ?></h2>
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <?= lang("full_name", "full_name"); ?>
                        <input type="text" name="data[1][kin_full_name]" class="form-control" id="kin_full_name" required="required"/>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <?= lang("phone", "phone"); ?>
                        <input type="tel" name="data[1][kin_phone]" class="form-control" id="kin_phone" required="required"/>
                    </div>
                </div>

            </div>
            <h2><?= lang('dietary_requirement'); ?></h2>
            <?= lang("requirement", "requirement"); ?>
            <div class="row" id="req">
                <div class="col-md-12">
                    <div class="input-group">
                        <input type="text" name="data[2][0][requirement]" class="form-control" id="requirement1"/>
                        <span class="input-group-btn">
                            <button class="btn btn-primary form-control" id="add_new_req">ADD NEW</button>
                        </span>
                    </div>
                </div>

            </div>
            <h2><?= lang('medical_condition'); ?></h2>
            <?= lang("condition", "condition"); ?>
            <div class="row" id="med">
                <div class="col-md-12">
                    <div class="input-group">
                        <input type="text" name="data[3][0][medical_condition]" class="form-control" id="medical_condition1"/>
                        <span class="input-group-btn">
                            <button class="btn btn-primary form-control" id="add_new_med">ADD NEW</button>
                        </span>
                    </div>
                </div>

            </div>
            <h2><?= lang('temperature'); ?></h2>
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <?= lang("temperature", "temperature"); ?>
                        <input type="number" name="data[4][0][temperature]" class="form-control" id="temperature" required="required"/>
                    </div>
                </div>

            </div>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('check_in_customer', lang('check_in_customer'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function (e) {
        $('#check_in-customer-form').bootstrapValidator({
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
        $('#add_new_req').click(function(){
            let last_input_id = $('#req input:last').attr('id');
            let actual_id = last_input_id.replace('requirement','');
            let new_id =  parseInt(actual_id, 10)+1;

            $('#req').append('<div style="margin-top: 20px" class="col-md-12" id="req'+new_id+'">\n' +
                '                    <div class="input-group col-md-12">\n' +
                '                        <input type="text" name="data[2]['+actual_id+'][requirement]" class="form-control" id="requirement'+new_id+'" required="required"/>\n' +
                '                        <span class="input-group-btn">\n' +
                '                            <button class="btn btn-danger form-control" id="remove_new_req'+new_id+'" onclick="$(\'#req'+new_id+'\').remove();">X</button>\n' +
                '                        </span>\n' +
                '                    </div>\n' +
                '                </div>');
        });
        $('#add_new_med').click(function(){
            let last_input_id = $('#med input:last').attr('id');
            let actual_id = last_input_id.replace('medical_condition','');
            let new_id =  parseInt(actual_id, 10)+1;

            $('#med').append('<div style="margin-top: 20px" class="col-md-12" id="med'+new_id+'">\n' +
                '                    <div class="input-group col-md-12">\n' +
                '                        <input type="text" name="data[3]['+actual_id+'][medical_condition]" class="form-control" id="medical_condition'+new_id+'" required="required"/>\n' +
                '                        <span class="input-group-btn">\n' +
                '                            <button class="btn btn-danger form-control" id="remove_new_med'+new_id+'" onclick="$(\'#med'+new_id+'\').remove();">X</button>\n' +
                '                        </span>\n' +
                '                    </div>\n' +
                '                </div>');
        });
    });
</script>
