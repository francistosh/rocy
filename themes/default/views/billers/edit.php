
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('edit_biller'); ?></h2>
    </div>
    <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'edit-biller-form');
    echo form_open_multipart("billers/edit/" . $biller->id, $attrib); ?>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>

                <div class="col-md-12">

                    <p><?= lang('enter_info'); ?></p>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang("logo", "biller_logo"); ?>
                                <?php
                                $biller_logos[''] = '';
                                foreach ($logos as $key => $value) {
                                    $biller_logos[$value] = $value;
                                }
                                echo form_dropdown('logo', $biller_logos, $biller->logo, 'class="form-control select" id="biller_logo" required="required" '); ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div id="logo-con" class="text-center"><img
                                        src="<?= base_url('assets/uploads/logos/' . $biller->logo) ?>" alt=""></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group company">
                                <?= lang("company", "company"); ?>
                                <?php echo form_input('company', $biller->company, 'class="form-control tip" id="company" required="required"'); ?>
                            </div>
                            <div class="form-group person">
                                <?= lang("name", "name"); ?>
                                <?php echo form_input('name', $biller->name, 'class="form-control tip" id="name" required="required"'); ?>
                            </div>
                            <div class="form-group">
                                <?= lang("vat_no", "vat_no"); ?>
                                <?php echo form_input('vat_no', $biller->vat_no, 'class="form-control" id="vat_no"'); ?>
                            </div>
                            <div class="form-group">
                                <?= lang("email_address", "email_address"); ?>
                                <input type="email" name="email" class="form-control" required="required" id="email_address"
                                       value="<?= $biller->email ?>"/>
                            </div>
                            <div class="form-group">
                                <?= lang("phone", "phone"); ?>
                                <input type="tel" name="phone" class="form-control" required="required" id="phone"
                                       value="<?= $biller->phone ?>"/>
                            </div>
                            <div class="form-group">
                                <?= lang("address", "address"); ?>
                                <?php echo form_input('address', $biller->address, 'class="form-control" id="address" required="required"'); ?>
                            </div>
                            <div class="form-group">
                                <?= lang("city", "city"); ?>
                                <?php echo form_input('city', $biller->city, 'class="form-control" id="city" required="required"'); ?>
                            </div>
                            <div class="form-group">
                                <?= lang("state", "state"); ?>
                                <?php echo form_input('state', $biller->state, 'class="form-control" id="state"'); ?>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang("postal_code", "postal_code"); ?>
                                <?php echo form_input('postal_code', $biller->postal_code, 'class="form-control" id="postal_code"'); ?>
                            </div>
                            <div class="form-group">
                                <?= lang("country", "country"); ?>
                                <?php echo form_input('country', $biller->country, 'class="form-control" id="country"'); ?>
                            </div>
                            <div class="form-group">
                                <?= lang("bcf1", "cf1"); ?>
                                <?php echo form_input('cf1', $biller->cf1, 'class="form-control" id="cf1"'); ?>
                            </div>
                            <div class="form-group">
                                <?= lang("bcf2", "cf2"); ?>
                                <?php echo form_input('cf2', $biller->cf2, 'class="form-control" id="cf2"'); ?>

                            </div>
                            <div class="form-group">
                                <?= lang("bcf3", "cf3"); ?>
                                <?php echo form_input('cf3', $biller->cf3, 'class="form-control" id="cf3"'); ?>
                            </div>
                            <div class="form-group">
                                <?= lang("bcf4", "cf4"); ?>
                                <?php echo form_input('cf4', $biller->cf4, 'class="form-control" id="cf4"'); ?>

                            </div>
                            <div class="form-group">
                                <?= lang("bcf5", "cf5"); ?>
                                <?php echo form_input('cf5', $biller->cf5, 'class="form-control" id="cf5"'); ?>

                            </div>
                            <div class="form-group">
                                <?= lang("bcf6", "cf6"); ?>
                                <?php echo form_input('cf6', $biller->cf6, 'class="form-control" id="cf6"'); ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <?= lang("invoice_footer", "invoice_footer"); ?>
                                <?php echo form_textarea('invoice_footer', $biller->invoice_footer, 'class="form-control skip" id="invoice_footer" style="height:100px;"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group">
                                <?= lang("acc_name", "acc_name"); ?>
                                <?php echo form_input('acc_name', $biller->acc_name, 'class="form-control" id="acc_name"'); ?>
                            </div>
                            <div class="form-group">
                                <?= lang("bank_name", "bank_name"); ?>
                                <?php echo form_input('bank_name', $biller->bank_name, 'class="form-control" id="bank_name"'); ?>
                            </div>
                            <div class="form-group">
                                <?= lang("branch", "branch"); ?>
                                <?php echo form_input('branch', $biller->branch, 'class="form-control" id="branch"'); ?>
                            </div>
                            <div class="form-group">
                                <?= lang("swift_code", "swift_code"); ?>
                                <?php echo form_input('swift_code', $biller->swift_code, 'class="form-control" id="swift_code"'); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang("dollar_acc_no", "dollar_acc_no"); ?>
                                <?php echo form_input('dollar_acc_no', $biller->dollar_acc_no, 'class="form-control" id="dollar_acc_no"'); ?>
                            </div>
                            <div class="form-group">
                                <?= lang("ksh_acc_no", "ksh_acc_no"); ?>
                                <?php echo form_input('ksh_acc_no', $biller->ksh_acc_no, 'class="form-control" id="ksh_acc_no"'); ?>
                            </div>
                            <div class="form-group">
                                <?= lang("mpesa_business_no", "mpesa_business_no"); ?>
                                <?php echo form_input('mpesa_business_no', $biller->mpesa_business_no, 'class="form-control" id="mpesa_business_no"'); ?>
                            </div>
                            <div class="form-group">
                                <?= lang("mpesa_acc_no", "mpesa_acc_no"); ?>
                                <?php echo form_input('mpesa_acc_no', $biller->mpesa_acc_no, 'class="form-control" id="mpesa_acc_no"'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <?php echo form_submit('edit_biller', lang('edit_biller'), 'class="btn btn-primary"'); ?>
                    </div>

                </div>


            </div>

        </div>

    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">

    $(document).ready(function () {
        $('#biller_logo').change(function (event) {
            var biller_logo = $(this).val();
            $('#logo-con').html('<img src="<?=base_url('assets/uploads/logos')?>/' + biller_logo + '" alt="">');
        });
    });
</script>

