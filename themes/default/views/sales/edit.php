-<script type="text/javascript">
    $(document).ready(function () {

    });
</script>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('edit_sale'); ?></h2>
    </div>
    <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'edit-voucher-form');
    echo form_open_multipart("sales/edit/".$sale->id); ?>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>

                <div class="col-md-12">

                   <!-- <div class="form-group">
                        <?/*= lang("voucher_number", "type") */?>
                        <select class="form-control" name="voucher" id="voucher" required >
                            <option value="">Please select voucher</option>
                            <?php
/*
                            foreach ($vouchers as $voucher) {
                                echo '<option value="'.$voucher->id.'" >'.$voucher->group_name.' '.$voucher->voucher_no.'</option>';
                            }

                            */?>

                        </select>

                    </div>-->

                    <?php if ($Owner || $Admin) { ?>

                        <div class="form-group">
                            <label>Expiry Date</label>
                            <input class="form-control" name="expiry_date" id="expiry_date" value="<?php echo $sale->due_date ?>" type="date" required>
                        </div>

                    <?php } ?>

                    <div class="form-group">
                        <label>Currency</label>
                        <select class="form-control" name="currency" id="currency" required>
                            <?php if($sale->currency == "US$"){?>
                                <option value="US$" selected >Dollar</option>
                                <option value="KES">Kenyan Shilling</option>
                            <?php }else{?>
                                <option value="US$">Dollar</option>
                                <option value="KES" selected >Kenyan Shilling</option>
                            <?php }?>

                        </select>
                    </div>

                    <table style="width:100%" class="table table-striped table-bordered" id="order_table">

                    </table>


                    <div class="form-group">
                        <?php echo form_submit('edit_sale', lang('edit_sale'), 'class="btn btn-primary"'); ?>
                    </div>

                </div>


            </div>

        </div>

    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
    var total_days;
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

        /*$('#voucher').on('change', function() {
            var voucher_id = this.value;
            event.preventDefault();

        });*/

        $.ajax({
            type:"get",
            url: '<?= site_url('sales/getVoucher'); ?>',
            data:{id:'<?= $voucher->id ?>'},
            dataType: 'json',
            cache:false,
            success: function(response) {
                var single_room=0;
                var double_room=0;
                var twin_room=0;
                var triple_room=0;
                var honeymoon_room=0;
                var extra_bed=0;
                $.ajax({
                    type:"get",
                    url: '<?= site_url('sales/getSaleItems'); ?>',
                    data:{id:'<?= $sale->id ?>'},
                    dataType: 'json',
                    cache:false,
                    success: function(response_items) {
                        var start = response["check_in"];
                        var end = response["check_out"];
                        total_days = datediff(parseDate(start), parseDate(end));
                        $('#order_table').html('');
                        $('#order_table').append(' <thead><tr>' +
                        '<th>Room</th>' +
                        '<th>Quantity</th>' +
                        '<th>Adults</th>' +
                        '<th>Children</th>' +
                        '<th>Pax</th>' +
                        '<th>VAT(14%)</th>' +
                        '<th>Tourism Levy(2%)</th>' +
                        '<th>Service Charge(2%)</th>' +
                        '<th>Price</th>' +
                        '<th>Total</th>' +
                        '<th>Amount</th>' +
                        '</tr></thead>' +
                        '<tbody id="order_table_body">' +
                        '</tbody>' +
                        '<tfoot>' +
                        '<tr>' +
                        '<td>Day Total <input class="form-control input-sm totals_days" name="total_days" value="'+total_days+'" type="hidden"></td>' +
                        '<td>Total Quantity <input class="form-control input-sm totals_qnty" name="total_qnty" value="0" readonly="readonly"></td>' +
                        '<td>Total Adults <input class="form-control input-sm totals_adults" name="total_adults" value="0" readonly="readonly"></td>' +
                        '<td>Total Children <input class="form-control input-sm totals_children" name="total_children" value="0" readonly="readonly"></td>' +
                        '<td>Total Pax <input class="form-control input-sm totals_pax" name="total_pax" value="0" readonly="readonly"></td>' +
                        '<td></td>' +
                        '<td></td>' +
                        '<td></td>' +
                        '<td></td>' +
                        '<td></td>' +
                        '<td>Total <input class="form-control input-sm totals_val" name="total" value="0" readonly="readonly"></td>' +
                        '</tr>' +
                        '</tfoot>');

                        $.each(response_items, function(key,value) {
                            if(value.name==='single'){
                                if(response["single_room"]>0){
                                    $('#order_table_body').append('<tr>' +
                                    '<td>Single</td>' +
                                    '<td><input class="form-control input-sm" readonly="readonly" type="text" id="single_qnty" name="single_qnty" value="'+response["single_room"]+'"></td>' +
                                    '<td><input class="form-control input-sm" type="text" id="single_adults" name="single_adults" value="'+value.no_adults+'"></td>' +
                                    '<td><input class="form-control input-sm" type="text" id="single_children" name="single_children" value="'+value.no_children+'"></td>' +
                                    '<td><input class="form-control input-sm single_pax" type="text" value="'+value.pax+'" id="single_pax" name="single_pax"></td>' +
                                    '<td><input class="form-control input-sm single_vat" readonly="readonly" type="text" value="'+(value.price *14)/100+'" id="single_vat" name="single_vat"></td>' +
                                    '<td><input class="form-control input-sm single_sc" readonly="readonly" type="text" value="'+(value.price *2)/100+'" id="single_sc" name="single_sc"></td>' +
                                    '<td><input class="form-control input-sm single_tl" readonly="readonly" type="text" value="'+(value.price *2)/100+'" id="single_tl" name="single_tl"></td>' +
                                    '<td><input class="form-control input-sm single_total" readonly="readonly" type="text" value="'+((value.price)-(((value.price *14)/100)+((value.price *2)/100)+((value.price *2)/100)))+'" id="single_total" name="single_total"></td>' +
                                    '<td><input class="form-control input-sm single_room" type="text" value="'+value.price+'" id="single_price" name="single_price"></td>' +
                                    '<td class="text-center"><span id="single_amount" class="text-center">'+parseFloat(value.price)*parseFloat(response["single_room"])+'</span></td>' +
                                    '</tr>');
                                }
                            }
                            if(value.name==='double'){
                                if (response["double_room"]>0){
                                    $('#order_table_body').append('<tr>' +
                                    '<td>Double</td>' +
                                    '<td><input class="form-control input-sm" readonly="readonly" type="text" id="double_qnty" name="double_qnty" value="'+response["double_room"]+'"></td>' +
                                    '<td><input class="form-control input-sm" type="text" id="double_adults" name="double_adults" value="'+value.no_adults+'"></td>' +
                                    '<td><input class="form-control input-sm" type="text" id="double_children" name="double_children" value="'+value.no_children+'"></td>' +
                                    '<td><input class="form-control input-sm double_pax" type="text" value="'+value.pax+'" id="double_pax" name="double_pax"></td>' +
                                    '<td><input class="form-control input-sm double_vat" readonly="readonly" type="text" value="'+(value.price *14)/100+'" id="double_vat" name="double_vat"></td>' +
                                    '<td><input class="form-control input-sm double_sc" readonly="readonly" type="text" value="'+(value.price *2)/100+'" id="double_sc" name="double_sc"></td>' +
                                    '<td><input class="form-control input-sm double_tl" readonly="readonly" type="text" value="'+(value.price *2)/100+'" id="double_tl" name="double_tl"></td>' +
                                    '<td><input class="form-control input-sm double_total" readonly="readonly" type="text" value="'+((value.price)-(((value.price *14)/100)+((value.price *2)/100)+((value.price *2)/100)))+'" id="double_total" name="double_total"></td>' +
                                    '<td><input class="form-control input-sm double_room" type="text" value="'+value.price+'" id="double_price" name="double_price"></td>' +
                                    '<td class="text-center"><span id="double_amount" class="text-center">'+parseFloat(value.price)*parseFloat(response["double_room"])+'</span></td>' +
                                    '</tr>');
                                }
                            }
                            if(value.name==='twin'){
                                if (response["twin_room"]>0){
                                    $('#order_table_body').append('<tr>' +
                                    '<td>Twin</td>' +
                                    '<td><input class="form-control input-sm" readonly="readonly" type="text" id="twin_qnty" name="twin_qnty" value="'+response["twin_room"]+'"></td>' +
                                    '<td><input class="form-control input-sm" type="text" id="twin_adults" name="twin_adults" value="'+value.no_adults+'"></td>' +
                                    '<td><input class="form-control input-sm" type="text" id="twin_children" name="twin_children" value="'+value.no_children+'"></td>' +
                                    '<td><input class="form-control input-sm twin_pax" type="text" value="'+value.pax+'" id="twin_pax" name="twin_pax"></td>' +
                                    '<td><input class="form-control input-sm twin_vat" readonly="readonly" type="text" value="'+(value.price *14)/100+'" id="twin_vat" name="twin_vat"></td>' +
                                    '<td><input class="form-control input-sm twin_sc" readonly="readonly" type="text" value="'+(value.price *2)/100+'" id="twin_sc" name="twin_sc"></td>' +
                                    '<td><input class="form-control input-sm twin_tl" readonly="readonly" type="text" value="'+(value.price *2)/100+'" id="twin_tl" name="twin_tl"></td>' +
                                    '<td><input class="form-control input-sm twin_total" readonly="readonly" type="text" value="'+((value.price)-(((value.price *14)/100)+((value.price *2)/100)+((value.price *2)/100)))+'" id="twin_total" name="twin_total"></td>' +
                                    '<td><input class="form-control input-sm twin_room" type="text" value="'+value.price+'" id="twin_price" name="twin_price"></td>' +
                                    '<td class="text-center"><span id="twin_amount" class="text-center">'+parseFloat(value.price)*parseFloat(response["twin_room"])+'</span></td>' +
                                    '</tr>');
                                }
                            }
                            if(value.name==='triple'){
                                if (response["triple_room"]>0){
                                    $('#order_table_body').append('<tr>' +
                                    '<td>Triple</td>' +
                                    '<td><input class="form-control input-sm" readonly="readonly" type="text" id="triple_qnty" name="triple_qnty" value="'+response["triple_room"]+'"></td>' +
                                    '<td><input class="form-control input-sm" type="text" id="triple_adults" name="triple_adults" value="'+value.no_adults+'"></td>' +
                                    '<td><input class="form-control input-sm" type="text" id="triple_children" name="triple_children" value="'+value.no_children+'"></td>' +
                                    '<td><input class="form-control input-sm triple_pax" type="text" value="'+value.pax+'" id="triple_pax" name="triple_pax"></td>' +
                                    '<td><input class="form-control input-sm triple_vat" readonly="readonly" type="text" value="'+(value.price *14)/100+'" id="triple_vat" name="triple_vat"></td>' +
                                    '<td><input class="form-control input-sm triple_sc" readonly="readonly" type="text" value="'+(value.price *2)/100+'" id="triple_sc" name="triple_sc"></td>' +
                                    '<td><input class="form-control input-sm triple_tl" readonly="readonly" type="text" value="'+(value.price *2)/100+'" id="triple_tl" name="triple_tl"></td>' +
                                    '<td><input class="form-control input-sm triple_total" readonly="readonly" type="text" value="'+((value.price)-(((value.price *14)/100)+((value.price *2)/100)+((value.price *2)/100)))+'" id="triple_total" name="triple_total"></td>' +
                                    '<td><input class="form-control input-sm triple_room" type="text" value="'+value.price+'" id="triple_price" name="triple_price"></td>' +
                                    '<td class="text-center"><span id="triple_amount" class="text-center">'+parseFloat(value.price)*parseFloat(response["triple_room"])+'</span></td>' +
                                    '</tr>');
                                }
                            }
                            if(value.name==='family'){
                                if (response["family_room"]>0){
                                    $('#order_table_body').append('<tr>' +
                                    '<td>Family</td>' +
                                    '<td><input class="form-control input-sm" readonly="readonly" type="text" id="family_qnty" name="family_qnty" value="'+response["family_room"]+'"></td>' +
                                    '<td><input class="form-control input-sm" type="text" id="family_adults" name="family_adults" value="'+value.no_adults+'"></td>' +
                                    '<td><input class="form-control input-sm" type="text" id="family_children" name="family_children" value="'+value.no_children+'"></td>' +
                                    '<td><input class="form-control input-sm family_pax" type="text" value="'+value.pax+'" id="family_pax" name="family_pax"></td>' +
                                    '<td><input class="form-control input-sm family_vat" readonly="readonly" type="text" value="'+(value.price *14)/100+'" id="family_vat" name="family_vat"></td>' +
                                    '<td><input class="form-control input-sm family_sc" readonly="readonly" type="text" value="'+(value.price *2)/100+'" id="family_sc" name="family_sc"></td>' +
                                    '<td><input class="form-control input-sm family_tl" readonly="readonly" type="text" value="'+(value.price *2)/100+'" id="family_tl" name="family_tl"></td>' +
                                    '<td><input class="form-control input-sm family_total" readonly="readonly" type="text" value="'+((value.price)-(((value.price *14)/100)+((value.price *2)/100)+((value.price *2)/100)))+'" id="family_total" name="family_total"></td>' +
                                    '<td><input class="form-control input-sm family_room" type="text" value="'+value.price+'" id="family_price" name="family_price"></td>' +
                                    '<td class="text-center"><span id="family_amount" class="text-center">'+parseFloat(value.price)*parseFloat(response["family_room"])+'</span></td>' +
                                    '</tr>');
                                }
                            }
                            if(value.name==='honeymoon'){
                                if (response["honeymoon_room"]>0){
                                    $('#order_table_body').append('<tr>' +
                                    '<td>Honeymoon</td>' +
                                    '<td><input class="form-control input-sm" readonly="readonly" type="text" id="honeymoon_qnty" name="honeymoon_qnty" value="'+response["honeymoon_room"]+'"></td>' +
                                    '<td><input class="form-control input-sm" type="text" id="honeymoon_adults" name="honeymoon_adults" value="'+value.no_adults+'"></td>' +
                                    '<td><input class="form-control input-sm" type="text" id="honeymoon_children" name="honeymoon_children" value="'+value.no_children+'"></td>' +
                                    '<td><input class="form-control input-sm honeymoon_pax" type="text" value="'+value.pax+'" id="honeymoon_pax" name="honeymoon_pax"></td>' +
                                    '<td><input class="form-control input-sm honeymoon_vat" readonly="readonly" type="text" value="'+(value.price *14)/100+'" id="honeymoon_vat" name="honeymoon_vat"></td>' +
                                    '<td><input class="form-control input-sm honeymoon_sc" readonly="readonly" type="text" value="'+(value.price *2)/100+'" id="honeymoon_sc" name="honeymoon_sc"></td>' +
                                    '<td><input class="form-control input-sm honeymoon_tl" readonly="readonly" type="text" value="'+(value.price *2)/100+'" id="honeymoon_tl" name="honeymoon_tl"></td>' +
                                    '<td><input class="form-control input-sm honeymoon_total" readonly="readonly" type="text" value="'+((value.price)-(((value.price *14)/100)+((value.price *2)/100)+((value.price *2)/100)))+'" id="honeymoon_total" name="honeymoon_total"></td>' +
                                    '<td><input class="form-control input-sm honeymoon_room" type="text" value="'+value.price+'" id="honeymoon_price" name="honeymoon_price"></td>' +
                                    '<td class="text-center"><span id="honeymoon_amount" class="text-center">'+parseFloat(value.price)*parseFloat(response["honeymoon_room"])+'</span></td>' +
                                    '</tr>');
                                }
                            }
                            if(value.name==='extrabed'){
                                if (response["extra_bed"]>0){
                                    $('#order_table_body').append('<tr>' +
                                    '<td>Extrabed</td>' +
                                    '<td><input class="form-control input-sm" readonly="readonly" type="text" id="extra_bed_qnty" name="extra_bed_qnty" value="'+response["extra_bed"]+'"></td>' +
                                    '<td><input class="form-control input-sm" type="text" id="extra_bed_adults" name="extra_bed_adults" value="'+value.no_adults+'"></td>' +
                                    '<td><input class="form-control input-sm" type="text" id="extra_bed_children" name="extra_bed_children" value="'+value.no_children+'"></td>' +
                                    '<td><input class="form-control input-sm extra_bed_pax" type="text" value="'+value.pax+'" id="extra_bed_pax" name="extra_bed_pax"></td>' +
                                    '<td><input class="form-control input-sm extra_bed_vat" readonly="readonly" type="text" value="'+(value.price *14)/100+'" id="extra_bed_vat" name="extra_bed_vat"></td>' +
                                    '<td><input class="form-control input-sm extra_bed_sc" readonly="readonly" type="text" value="'+(value.price *2)/100+'" id="extra_bed_sc" name="extra_bed_sc"></td>' +
                                    '<td><input class="form-control input-sm extra_bed_tl" readonly="readonly" type="text" value="'+(value.price *2)/100+'" id="extra_bed_tl" name="extra_bed_tl"></td>' +
                                    '<td><input class="form-control input-sm extra_bed_total" readonly="readonly" type="text" value="'+((value.price)-(((value.price *14)/100)+((value.price *2)/100)+((value.price *2)/100)))+'" id="extra_bed_total" name="extra_bed_total"></td>' +
                                    '<td><input class="form-control input-sm extra_bed" type="text" value="'+value.price+'" id="extra_bed_price" name="extra_bed_price"></td>' +
                                    '<td class="text-center"><span id="extra_bed_amount" class="text-center">'+parseFloat(value.price)*parseFloat(response["extra_bed"])+'</span></td>' +
                                    '</tr>');
                                }
                            }
                        });
                        getTotalQnty();
                        getTotalPrice();
                        getTotalPax();
                        getTotalAdults();
                        getTotalChildren();
                    }
                });


            }
        });



        $("body").on("keydown", "input.single_room", function (e) {

            if (e.keyCode === 13) {
                e.preventDefault();
                var price = $('#single_price').val();
                var amount = parseFloat(price) * parseFloat($('#single_qnty').val());
                $('#single_amount').text('');
                $('#single_amount').text(amount);
                var vat = (price *14)/100;
                var tourism_levy = (price*2)/100;
                var service_charge = (price*2)/100;
                var total_tax = vat+tourism_levy+service_charge;
                $('.single_vat').val('');
                $('.single_vat').val(vat);
                $('.single_sc').val('');
                $('.single_sc').val(service_charge);
                $('.single_tl').val('');
                $('.single_tl').val(tourism_levy);
                $('.single_total').val('');
                $('.single_total').val(price-total_tax);
                getTotalPrice();
                return false;
            }

        });

        $("body").on("keydown", "input.double_room", function (e) {

            if (e.keyCode === 13) {
                e.preventDefault();
                var price = $('#double_price').val();
                var amount = parseFloat(price) * parseFloat($('#double_qnty').val());
                $('#double_amount').text('');
                $('#double_amount').text(amount);
                var vat = (price *14)/100;
                var tourism_levy = (price*2)/100;
                var service_charge = (price*2)/100;
                var total_tax = vat+tourism_levy+service_charge;
                $('.double_vat').val('');
                $('.double_vat').val(vat);
                $('.double_sc').val('');
                $('.double_sc').val(service_charge);
                $('.double_tl').val('');
                $('.double_tl').val(tourism_levy);
                $('.double_total').val('');
                $('.double_total').val(price-total_tax);
                getTotalPrice();
                return false;
            }

        });

        $("body").on("keydown", "input.twin_room", function (e) {

            if (e.keyCode === 13) {
                e.preventDefault();
                var price = $('#twin_price').val();
                var amount = parseFloat(price) * parseFloat($('#twin_qnty').val());
                $('#twin_amount').text('');
                $('#twin_amount').text(amount);
                var vat = (price *14)/100;
                var tourism_levy = (price*2)/100;
                var service_charge = (price*2)/100;
                var total_tax = vat+tourism_levy+service_charge;
                $('.twin_vat').val('');
                $('.twin_vat').val(vat);
                $('.twin_sc').val('');
                $('.twin_sc').val(service_charge);
                $('.twin_tl').val('');
                $('.twin_tl').val(tourism_levy);
                $('.twin_total').val('');
                $('.twin_total').val(price-total_tax);
                getTotalPrice();
                return false;
            }

        });

        $("body").on("keydown", "input.triple_room", function (e) {

            if (e.keyCode === 13) {
                e.preventDefault();
                var price = $('#triple_price').val();
                var amount = parseFloat(price) * parseFloat($('#triple_qnty').val());
                $('#triple_amount').text('');
                $('#triple_amount').text(amount);
                var vat = (price *14)/100;
                var tourism_levy = (price*2)/100;
                var service_charge = (price*2)/100;
                var total_tax = vat+tourism_levy+service_charge;
                $('.triple_vat').val('');
                $('.triple_vat').val(vat);
                $('.triple_sc').val('');
                $('.triple_sc').val(service_charge);
                $('.triple_tl').val('');
                $('.triple_tl').val(tourism_levy);
                $('.triple_total').val('');
                $('.triple_total').val(price-total_tax);
                getTotalPrice();
                return false;
            }

        });

        $("body").on("keydown", "input.family_room", function (e) {

            if (e.keyCode === 13) {
                e.preventDefault();
                var price = $('#family_price').val();
                var amount = parseFloat(price) * parseFloat($('#family_qnty').val());
                $('#family_amount').text('');
                $('#family_amount').text(amount);
                var vat = (price *14)/100;
                var tourism_levy = (price*2)/100;
                var service_charge = (price*2)/100;
                var total_tax = vat+tourism_levy+service_charge;
                $('.family_vat').val('');
                $('.family_vat').val(vat);
                $('.family_sc').val('');
                $('.family_sc').val(service_charge);
                $('.family_tl').val('');
                $('.family_tl').val(tourism_levy);
                $('.family_total').val('');
                $('.family_total').val(price-total_tax);
                getTotalPrice();
                return false;
            }

        });

        $("body").on("keydown", "input.honeymoon_room", function (e) {

            if (e.keyCode === 13) {
                e.preventDefault();
                var price = $('#honeymoon_price').val();
                var amount = parseFloat(price) * parseFloat($('#honeymoon_qnty').val());
                $('#honeymoon_amount').text('');
                $('#honeymoon_amount').text(amount);
                var vat = (price *14)/100;
                var tourism_levy = (price*2)/100;
                var service_charge = (price*2)/100;
                var total_tax = vat+tourism_levy+service_charge;
                $('.honeymoon_vat').val('');
                $('.honeymoon_vat').val(vat);
                $('.honeymoon_sc').val('');
                $('.honeymoon_sc').val(service_charge);
                $('.honeymoon_tl').val('');
                $('.honeymoon_tl').val(tourism_levy);
                $('.honeymoon_total').val('');
                $('.honeymoon_total').val(price-total_tax);
                getTotalPrice();
                return false;
            }

        });

        $("body").on("keydown", "input.extra_bed", function (e) {

            if (e.keyCode === 13) {
                e.preventDefault();
                var price = $('#extra_bed_price').val();
                var amount = parseFloat(price) * parseFloat($('#extra_bed_qnty').val());
                $('#extra_bed_amount').text('');
                $('#extra_bed_amount').text(amount);
                var vat = (price *14)/100;
                var tourism_levy = (price*2)/100;
                var service_charge = (price*2)/100;
                var total_tax = vat+tourism_levy+service_charge;
                $('.extra_bed_vat').val('');
                $('.extra_bed_vat').val(vat);
                $('.extra_bed_sc').val('');
                $('.extra_bed_sc').val(service_charge);
                $('.extra_bed_tl').val('');
                $('.extra_bed_tl').val(tourism_levy);
                $('.extra_bed_total').val('');
                $('.extra_bed_total').val(price-total_tax);
                getTotalPrice();
                return false;
            }

        });


        $("body").on("keydown", "input.single_pax", function (e) {

            if (e.keyCode === 13) {
                e.preventDefault();
                getTotalPax();
                return false;
            }

        });

        $("body").on("keydown", "input.double_pax", function (e) {

            if (e.keyCode === 13) {
                e.preventDefault();
                getTotalPax();
                return false;
            }

        });

        $("body").on("keydown", "input.twin_pax", function (e) {

            if (e.keyCode === 13) {
                e.preventDefault();
                getTotalPax();
                return false;
            }

        });

        $("body").on("keydown", "input.triple_pax", function (e) {

            if (e.keyCode === 13) {
                e.preventDefault();
                getTotalPax();
                return false;
            }

        });
        
        $("body").on("keydown", "input.family_pax", function (e) {

            if (e.keyCode === 13) {
                e.preventDefault();
                getTotalPax();
                return false;
            }

        });

        $("body").on("keydown", "input.honeymoon_pax", function (e) {

            if (e.keyCode === 13) {
                e.preventDefault();
                getTotalPax();
                return false;
            }

        });

        $("body").on("keydown", "input.extra_bed_pax", function (e) {

            if (e.keyCode === 13) {
                e.preventDefault();
                getTotalPax();
                return false;
            }

        });

        $("body").on("keydown", "#single_adults", function (e) {

            if (e.keyCode === 13) {
                e.preventDefault();
                var single_adults = parseFloat($('#single_adults').val()) ? parseFloat($('#single_adults').val()) : 0;
                var single_children = parseFloat($('#single_children').val()) ? parseFloat($('#single_children').val()) : 0;
                $('#single_pax').val(single_adults+single_children);
                getTotalPax();
                getTotalAdults();
                getTotalChildren();
                return false;
            }

        });

        $("body").on("keydown", "#single_children", function (e) {

            if (e.keyCode === 13) {
                e.preventDefault();
                var single_adults = parseFloat($('#single_adults').val()) ? parseFloat($('#single_adults').val()) : 0;
                var single_children = parseFloat($('#single_children').val()) ? parseFloat($('#single_children').val()) : 0;
                $('#single_pax').val(single_adults+single_children);
                getTotalPax();
                getTotalAdults();
                getTotalChildren();
                return false;
            }

        });

        $("body").on("keydown", "#double_adults", function (e) {

            if (e.keyCode === 13) {
                e.preventDefault();
                var double_adults = parseFloat($('#double_adults').val()) ? parseFloat($('#double_adults').val()) : 0;
                var double_children = parseFloat($('#double_children').val()) ? parseFloat($('#double_children').val()) : 0;
                $('#double_pax').val(double_adults+double_children);
                getTotalPax();
                getTotalAdults();
                getTotalChildren();
                return false;
            }

        });

        $("body").on("keydown", "#double_children", function (e) {

            if (e.keyCode === 13) {
                e.preventDefault();
                var double_adults = parseFloat($('#double_adults').val()) ? parseFloat($('#double_adults').val()) : 0;
                var double_children = parseFloat($('#double_children').val()) ? parseFloat($('#double_children').val()) : 0;
                $('#double_pax').val(double_adults+double_children);
                getTotalPax();
                getTotalAdults();
                getTotalChildren();
                return false;
            }

        });

        $("body").on("keydown", "#twin_adults", function (e) {

            if (e.keyCode === 13) {
                e.preventDefault();
                var twin_adults = parseFloat($('#twin_adults').val()) ? parseFloat($('#twin_adults').val()) : 0;
                var twin_children = parseFloat($('#twin_children').val()) ? parseFloat($('#twin_children').val()) : 0;
                $('#twin_pax').val(twin_adults+twin_children);
                getTotalPax();
                getTotalAdults();
                getTotalChildren();
                return false;
            }

        });

        $("body").on("keydown", "#twin_children", function (e) {

            if (e.keyCode === 13) {
                e.preventDefault();
                var twin_adults = parseFloat($('#twin_adults').val()) ? parseFloat($('#twin_adults').val()) : 0;
                var twin_children = parseFloat($('#twin_children').val()) ? parseFloat($('#twin_children').val()) : 0;
                $('#twin_pax').val(twin_adults+twin_children);
                getTotalPax();
                getTotalAdults();
                getTotalChildren();
                return false;
            }

        });

        $("body").on("keydown", "#triple_adults", function (e) {

            if (e.keyCode === 13) {
                e.preventDefault();
                var triple_adults = parseFloat($('#triple_adults').val()) ? parseFloat($('#triple_adults').val()) : 0;
                var triple_children = parseFloat($('#triple_children').val()) ? parseFloat($('#triple_children').val()) : 0;
                $('#triple_pax').val(triple_adults+triple_children);
                getTotalPax();
                getTotalAdults();
                getTotalChildren();
                return false;
            }

        });

        $("body").on("keydown", "#triple_children", function (e) {

            if (e.keyCode === 13) {
                e.preventDefault();
                var triple_adults = parseFloat($('#triple_adults').val()) ? parseFloat($('#triple_adults').val()) : 0;
                var triple_children = parseFloat($('#triple_children').val()) ? parseFloat($('#triple_children').val()) : 0;
                $('#triple_pax').val(triple_adults+triple_children);
                getTotalPax();
                getTotalAdults();
                getTotalChildren();
                return false;
            }

        });

        $("body").on("keydown", "#family_adults", function (e) {

            if (e.keyCode === 13) {
                e.preventDefault();
                var family_adults = parseFloat($('#family_adults').val()) ? parseFloat($('#family_adults').val()) : 0;
                var family_children = parseFloat($('#family_children').val()) ? parseFloat($('#family_children').val()) : 0;
                $('#family_pax').val(family_adults+family_children);
                getTotalPax();
                getTotalAdults();
                getTotalChildren();
                return false;
            }

        });

        $("body").on("keydown", "#family_children", function (e) {

            if (e.keyCode === 13) {
                e.preventDefault();
                var family_adults = parseFloat($('#family_adults').val()) ? parseFloat($('#family_adults').val()) : 0;
                var family_children = parseFloat($('#family_children').val()) ? parseFloat($('#family_children').val()) : 0;
                $('#family_pax').val(family_adults+family_children);
                getTotalPax();
                getTotalAdults();
                getTotalChildren();
                return false;
            }

        });

        $("body").on("keydown", "#honeymoon_adults", function (e) {

            if (e.keyCode === 13) {
                e.preventDefault();
                var honeymoon_adults = parseFloat($('#honeymoon_adults').val()) ? parseFloat($('#honeymoon_adults').val()) : 0;
                var honeymoon_children = parseFloat($('#honeymoon_children').val()) ? parseFloat($('#honeymoon_children').val()) : 0;
                $('#honeymoon_pax').val(honeymoon_adults+honeymoon_children);
                getTotalPax();
                getTotalAdults();
                getTotalChildren();
                return false;
            }

        });

        $("body").on("keydown", "#honeymoon_children", function (e) {

            if (e.keyCode === 13) {
                e.preventDefault();
                var honeymoon_adults = parseFloat($('#honeymoon_adults').val()) ? parseFloat($('#honeymoon_adults').val()) : 0;
                var honeymoon_children = parseFloat($('#honeymoon_children').val()) ? parseFloat($('#honeymoon_children').val()) : 0;
                $('#honeymoon_pax').val(honeymoon_adults+honeymoon_children);
                getTotalPax();
                getTotalAdults();
                getTotalChildren();
                return false;
            }

        });

        $("body").on("keydown", "#extra_bed_adults", function (e) {

            if (e.keyCode === 13) {
                e.preventDefault();
                var extra_bed_adults = parseFloat($('#extra_bed_adults').val()) ? parseFloat($('#extra_bed_adults').val()) : 0;
                var extra_bed_children = parseFloat($('#extra_bed_children').val()) ? parseFloat($('#extra_bed_children').val()) : 0;
                $('#extra_bed_pax').val(extra_bed_adults+extra_bed_children);
                getTotalPax();
                getTotalAdults();
                getTotalChildren();
                return false;
            }

        });

        $("body").on("keydown", "#extra_bed_children", function (e) {

            if (e.keyCode === 13) {
                e.preventDefault();
                var extra_bed_adults = parseFloat($('#extra_bed_adults').val()) ? parseFloat($('#extra_bed_adults').val()) : 0;
                var extra_bed_children = parseFloat($('#extra_bed_children').val()) ? parseFloat($('#extra_bed_children').val()) : 0;
                $('#extra_bed_pax').val(extra_bed_adults+extra_bed_children);
                getTotalPax();
                getTotalAdults();
                getTotalChildren();
                return false;
            }

        });
    });
    function parseDate(str) {
        var mdy = str.split('-');
        return new Date(mdy[0], mdy[1]-1, mdy[2]);;
    }

    function datediff(first, second) {
        return Math.round((second-first)/(1000*60*60*24));
    }
    
    function getTotalQnty() {
        var sq = parseFloat($('#single_qnty').val()) ? parseFloat($('#single_qnty').val()) : 0;
        var dq = parseFloat($('#double_qnty').val()) ? parseFloat($('#double_qnty').val()) : 0;
        var twq = parseFloat($('#twin_qnty').val()) ? parseFloat($('#twin_qnty').val()) : 0;
        var trq = parseFloat($('#triple_qnty').val()) ? parseFloat($('#triple_qnty').val()) : 0;
        var fq = parseFloat($('#family_qnty').val()) ? parseFloat($('#family_qnty').val()) : 0;
        var hq = parseFloat($('#honeymoon_qnty').val()) ? parseFloat($('#honeymoon_qnty').val()) : 0;
        /*var ebq = parseFloat($('#extra_bed_qnty').val()) ? parseFloat($('#extra_bed_qnty').val()) : 0;*/
        $('.totals_qnty').val('');
        /* $('.totals_qnty').val(sq+dq+twq+trq+hq+ebq);*/
        $('.totals_qnty').val(sq+dq+twq+trq+fq+hq);
    }

    function getTotalPrice() {
        var sp = parseFloat($('#single_amount').text()) ? parseFloat($('#single_amount').text()) : 0;
        var dp = parseFloat($('#double_amount').text()) ? parseFloat($('#double_amount').text()) : 0;
        var twp = parseFloat($('#twin_amount').text()) ? parseFloat($('#twin_amount').text()) : 0;
        var trp = parseFloat($('#triple_amount').text()) ? parseFloat($('#triple_amount').text()) : 0;
        var fp = parseFloat($('#family_amount').text()) ? parseFloat($('#family_amount').text()) : 0;
        var hp = parseFloat($('#honeymoon_amount').text()) ? parseFloat($('#honeymoon_amount').text()) : 0;
        var ebp = parseFloat($('#extra_bed_amount').text()) ? parseFloat($('#extra_bed_amount').text()) : 0;

        var total = sp+dp+twp+trp+fp+hp+ebp;

        var full_total = total*total_days;
        var vat = (full_total *16)/100;
        var tourism_levy = (full_total*2)/100
        var service_charge = (full_total*2)/100

        $('.totals_val').val('');
        $('.totals_val').val(total);
        $('.vat').val('');
        $('.vat').val(vat);
        $('.service_charge').val('');
        $('.service_charge').val(service_charge);
        $('.tourism_levy').val('');
        $('.tourism_levy').val(tourism_levy);
        $('.grand_total').val('');
        $('.grand_total').val(full_total+vat+service_charge+tourism_levy);
    }
    function getTotalPax() {
        var spax = parseFloat($('#single_pax').val()) ? parseFloat($('#single_pax').val()) : 0;
        var dpax = parseFloat($('#double_pax').val()) ? parseFloat($('#double_pax').val()) : 0;
        var twpax = parseFloat($('#twin_pax').val()) ? parseFloat($('#twin_pax').val()) : 0;
        var trpax = parseFloat($('#triple_pax').val()) ? parseFloat($('#triple_pax').val()) : 0;
        var fpax = parseFloat($('#family_pax').val()) ? parseFloat($('#family_pax').val()) : 0;
        var hpax = parseFloat($('#honeymoon_pax').val()) ? parseFloat($('#honeymoon_pax').val()) : 0;
        var ebpax = parseFloat($('#extra_bed_pax').val()) ? parseFloat($('#extra_bed_pax').val()) : 0;
        $('.totals_pax').val('');
        $('.totals_pax').val(spax+dpax+twpax+trpax+fpax+hpax+ebpax);
    }

    function getTotalAdults() {
        var sadults = parseFloat($('#single_adults').val()) ? parseFloat($('#single_adults').val()) : 0;
        var dadults = parseFloat($('#double_adults').val()) ? parseFloat($('#double_adults').val()) : 0;
        var twadults = parseFloat($('#twin_adults').val()) ? parseFloat($('#twin_adults').val()) : 0;
        var tradults = parseFloat($('#triple_adults').val()) ? parseFloat($('#triple_adults').val()) : 0;
        var fadults = parseFloat($('#family_adults').val()) ? parseFloat($('#family_adults').val()) : 0;
        var hadults = parseFloat($('#honeymoon_adults').val()) ? parseFloat($('#honeymoon_adults').val()) : 0;
        var ebadults = parseFloat($('#extra_bed_adults').val()) ? parseFloat($('#extra_bed_adults').val()) : 0;
        $('.totals_adults').val('');
        $('.totals_adults').val(sadults+dadults+twadults+tradults+fadults+hadults+ebadults);
    }

    function getTotalChildren() {
        var schildren = parseFloat($('#single_children').val()) ? parseFloat($('#single_children').val()) : 0;
        var dchildren = parseFloat($('#double_children').val()) ? parseFloat($('#double_children').val()) : 0;
        var twchildren = parseFloat($('#twin_children').val()) ? parseFloat($('#twin_children').val()) : 0;
        var trchildren = parseFloat($('#triple_children').val()) ? parseFloat($('#triple_children').val()) : 0;
        var fchildren = parseFloat($('#family_children').val()) ? parseFloat($('#family_children').val()) : 0;
        var hchildren = parseFloat($('#honeymoon_children').val()) ? parseFloat($('#honeymoon_children').val()) : 0;
        var ebchildren = parseFloat($('#extra_bed_children').val()) ? parseFloat($('#extra_bed_children').val()) : 0;
        $('.totals_children').val('');
        $('.totals_children').val(schildren+dchildren+twchildren+trchildren+fchildren+hchildren+ebchildren);
    }
</script>