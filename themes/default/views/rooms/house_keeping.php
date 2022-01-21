<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('House Keeping Service'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
        echo form_open_multipart("customers/housekeeping", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="form-group">
                <label class="control-label"
                       for="customer_group"><?php echo $this->lang->line("Room_Number"); ?></label>

                <div class="controls"> <?php
                    foreach ($rooms as $room) {
                        $cgs[$room->id] = $room->name;
                    }
                    echo form_dropdown('roomhk', $cgs, '', 'class="form-control tip select" id="roomhk" style="width:100%;" required="required"');
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group company">
                        <?= lang("Date", "date"); ?>
                        <?php echo form_input('hkdate', '', 'class="form-control tip datetime" id="hkdate" data-bv-notempty="true" required="required"'); ?>
                    </div>
                    
                    <div class="form-group">
                        <?= lang("Status", "hkstatus") ?>
                        <?php
                        $opts = array('vc' => lang('Vacant Cleaned'),'vd' => lang('Vacant Dirty'),'oc' => lang('Occupied Clean'),'ooo' => lang('Out of Order'),'oos' => lang('Out of Service')
						,'od' => lang('Occupied Dirty'),'dnr' => lang('Departure Not Ready'),'vnr' => lang('Vacant Not Ready'));
                        echo form_dropdown('hkstatus', $opts, (isset($_POST['hkstatus']) ? $_POST['hkstatus'] : ($product ? $product->type : '')), 'class="form-control" id="hkstatus" required="required"');
                        ?>
                    </div>
                    
                   
                            <div class="form-group">
                                <?= lang("House Keeping Rmks", "House Keeping Rmks"); ?>
                                <?php echo form_textarea('hsekrmks', '', 'class="form-control skip" id="hsekrmks" style="height:100px;"'); ?>
                            </div>
                      
                    
                   
                   

                </div>
                <div class="col-md-6">
                   
					
                   <!-- <div class="form-group">
                        <?= lang("ccf1", "cf1"); ?>
                        <?php echo form_input('cf1', '', 'class="form-control" id="cf1"'); ?>
                    </div> -->
                     <!-- <div class="form-group">
                        <?= lang("ccf2", "cf2"); ?>
                        <?php echo form_input('cf2', '', 'class="form-control" id="cf2"'); ?>

                    </div> -->
                   <!-- <div class="form-group">
                        <?= lang("ccf3", "cf3"); ?>
                        <?php echo form_input('cf3', '', 'class="form-control" id="cf3"'); ?>
                    </div> -->
                    <!-- <div class="form-group">
                        <?= lang("ccf4", "cf4"); ?>
                        <?php echo form_input('cf4', '', 'class="form-control" id="cf4"'); ?>

                    </div> -->
                   <!-- <div class="form-group">
                        <?= lang("ccf5", "cf5"); ?>
                        <?php echo form_input('cf5', '', 'class="form-control" id="cf5"'); ?>

                    </div> -->
                   <!-- <div class="form-group">
                        <?= lang("ccf6", "cf6"); ?>
                        <?php echo form_input('cf6', '', 'class="form-control" id="cf6"'); ?>
                    </div> -->
                </div>
            </div>


        </div>
        <div class="modal-footer">
            <?php echo form_submit('update_hk', lang('Update'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function (e) {
        $('#add-customer-form').bootstrapValidator({
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
