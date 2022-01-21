<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_room') . " (" . $room->name . ")";; ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'edit-room-form');
        echo form_open_multipart("rooms/edit/" . $room->id, $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
            <div class="row">

                <div class="col-md-6">
                <div class="form-group">
                    <?= lang("Room_Type", "type") ?>
                    <select class="form-control" name="warehouse" id="warehouse" required >
                        <option value="">--Select Room type--</option>
                        <?php

                        foreach ($warehouses as $warehouse) {
                            if($room->hotel_id == $warehouse->id){
                                echo '<option selected value="'.$warehouse->id.'" >'.$warehouse->name.'</option>';
                            }else{
                                echo '<option value="'.$warehouse->id.'" >'.$warehouse->name.'</option>';
                            }

                        }

                        ?>

                    </select>
                </div>
  

                <div class="form-group">
                    <?= lang("name", "name"); ?>
                    <input type="text" name="name" class="form-control" id="name" value="<?php echo $room->name ?>" required="required"/>
                </div>

                <div class="form-group">
                    <?= lang("quantity", "quantity"); ?>
                    <input type="number" name="quantity" class="form-control" id="quantity" value="<?php echo $room->quantity ?>" required="required"/>
                </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('edit_room', lang('edit_room'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">

    $(document).ready(function (e) {
        $('#edit-room-form').bootstrapValidator({
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