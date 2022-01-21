<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('checkout_guest') . " (" . $guest->full_name . ")"; ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'checkout-guest-form');
        echo form_open_multipart("guests/checkout/" . $guest->id, $attrib); ?>
        <div class="modal-body">

            <h2>Are you sure to checkout guest <?= $guest->full_name; ?> ?</h2>
            <input type="hidden" value="<?= $guest->email; ?>" name="email" class="form-control" id="email" required="required"/>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('checkout_guest', lang('checkout_guest'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>

<?= $modal_js ?>
