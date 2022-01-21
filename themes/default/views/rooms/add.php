<?php
if (!empty($variants)) {
    foreach ($variants as $variant) {
        $vars[] = addslashes($variant->name);
    }
} else {
    $vars = array();
}
?>
<script type="text/javascript">
    $(document).ready(function () {

    });
</script>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('add_room'); ?></h2>
    </div>
    <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-room-form');
    echo form_open_multipart("rooms/add"); ?>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>

                <div class="col-md-5">
                    <div class="form-group">
                        <?= lang("Room_Type", "type") ?>
                        <?php
                        $hotel[''] = "";
                        foreach ($warehouses as $warehouse) {
                            $hotel[$warehouse->id] = $warehouse->name;
                        }
                        echo form_dropdown('roomtype', $hotel, (isset($_POST['roomtype']) ? $_POST['roomtype'] : ($warehouse ? $warehouse->id : '')), 'class="form-control select" id="roomtype" placeholder="' . lang("select") . " " . lang("warehouse_name") . '" required="required" style="width:100%"')
                        ?>
                    </div>

                    <div class="form-group">
                        <?= lang("name", "name"); ?>
                        <input type="text" name="name" class="form-control" id="name" required="required"/>
                    </div>

                    <div class="form-group">
                        <?php echo form_submit('add_room', lang('add_room'), 'class="btn btn-primary"'); ?>
                    </div>
                </div>

            </div>

        </div>

    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">

    $(document).ready(function (e) {
        $('#add-room-form').bootstrapValidator({
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


