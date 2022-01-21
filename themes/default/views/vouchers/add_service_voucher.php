<script type="text/javascript">
    $(document).ready(function () {

    });
</script>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('add_svoucher'); ?></h2>
    </div>
    <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add_service_voucher');
    echo form_open_multipart("vouchers/svouchers_add"); ?>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>

                <div class="col-md-12">

                    <div class="form-group">
                        <?= lang("voucher_number", "type") ?>
                        <select class="form-control" name="voucher" id="voucher" required >
                            <option value="">Please select voucher</option>
                            <?php

                            foreach ($vouchers as $voucher) {
                                echo '<option value="'.$voucher->id.'" >'.$voucher->group_name.' '.$voucher->voucher_no.'</option>';
                            }

                            ?>

                        </select>

                    </div>

                    <div class="form-group">
                        <label>Special Instructions</label>
                        <textarea cols="1" rows="2" name="special_instructions" id="special_instructions">

                        </textarea>
                    </div>

                    <?php if ($Owner || $Admin) { ?>

                        <div class="form-group">
                            <label>Arrival Time</label>
                            <select class="form-control" name="arrival_time" id="arrival_time" required>
                                <option value="BB">Before Breakfast</option>
                                <option value="BL">Before Lunch</option>
                                <option value="BPL">Before PLunch</option>
                                <option value="BD">Before Dinner</option>
                            </select>
                        </div>

                    <?php } ?>


                    <table style="width:100%" class="table table-striped table-bordered" id="order_table">

                    </table>

                    <table style="width:100%" class="table table-striped table-bordered" id="meal_table">
                        <thead><tr>
                            <th>Date</th>
                            <th>Plan</th>
                            <th>BreakFast</th>
                            <th>Lunch</th>
                            <th>PLunch</th>
                            <th>Dinner</th>
                            <th>Stay</th>
                            </tr></thead>
                            <tbody id="meal_table_body">
                            </tbody>
                    </table>

                    <div class="form-group">
                        <?php echo form_submit('add_service_voucher', lang('add_svoucher'), 'class="btn btn-primary"'); ?>
                    </div>

                </div>


            </div>

        </div>

    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">

    $(document).ready(function (e){
        var checkin;
        var checkout;
        $('#add-voucher-form').bootstrapValidator({
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

        $('#voucher').on('change', function() {
            var voucher_id = this.value;
            event.preventDefault();
            $.ajax({
                type:"get",
                url: '<?= site_url('sales/getVoucher'); ?>',
                data:{id:voucher_id},
                dataType: 'json',
                cache:false,
                success: function(response) {
                    checkin=new Date(response["check_in"]);
                    checkout=new Date(response["check_out"]);
                }
            });
            $.ajax({
                type:"get",
                url: '<?= site_url('vouchers/getInvoiceItems'); ?>',
                data:{id:voucher_id},
                dataType: 'json',
                cache:false,
                success: function(response) {

                    $('#order_table').html('');
                    $('#order_table').append(' <thead><tr>' +
                        '<th>Room</th>' +
                        '<th>Quantity</th>' +
                        '<th>Adults</th>' +
                        '<th>Children</th>' +
                        '<th>Total Pax</th>' +
                        '</tr></thead>' +
                        '<tbody id="order_table_body">' +
                        '</tbody>' +
                        '<tfoot>' +
                        '<tr>' +
                        '<td>Day Total</td>' +
                        '<td>Total Quantity <input class="form-control input-sm totals_qnty" name="total_qnty" value="0" readonly="readonly"></td>' +
                        '<td>Total Adults <input class="form-control input-sm totals_adults" name="total_adults" value="0" readonly="readonly"></td>' +
                        '<td>Total Children <input class="form-control input-sm totals_children" name="total_children" value="0" readonly="readonly"></td>' +
                        '<td>Total Pax<input class="form-control input-sm totals_pax" name="total_pax" value="0" readonly="readonly"></td>' +
                        '</tr>' +
                        '</tfoot>');
                    $.each(response, function(index, data) {
                        $('#order_table_body').append('<tr>' +
                            '<td>'+data.name+'</td>' +
                            '<td><input class="form-control input-sm" readonly="readonly" type="text" id="'+data.name+'_qnty" name="'+data.name+'_qnty" value="'+data.quantity+'"></td>' +
                            '<td><input class="form-control input-sm" readonly="readonly" type="text" id="'+data.name+'_adults" name="'+data.name+'_adults" value="'+data.no_adults+'"></td>' +
                            '<td><input class="form-control input-sm" readonly="readonly" type="text" id="'+data.name+'_children" name="'+data.name+'_children" value="'+data.no_children+'"></td>' +
                            '<td><input class="form-control input-sm" readonly="readonly" type="text" id="'+data.name+'_pax" name="'+data.name+'_pax" value="'+(parseInt(data.no_adults)+parseInt(data.no_children))+'"></td>' +
                            '</tr>');
                    });
                    getTotalQnty();
                    getTotalPax();
                    getTotalAdults();
                    getTotalChildren();
                }
            });
        });

        $('#arrival_time').on('change', function() {
            $('#meal_table_body').html('');
            var checkin2 = new Date(checkin);
            var ml_plan = this.value;
            var diff=Math.abs((checkin2-checkout) / (1000 * 60 * 60 * 24));
            for(var i=0;i<=diff;i++){
                if(i == 0){
                    if(ml_plan==='BB'){
                        $('#meal_table_body').append('<tr>' +
                            '<td>'+checkin2+'</td>' +
                            '<td>FB</td>' +
                            '<td><input readonly="readonly" type="checkbox" checked></td>' +
                            '<td><input readonly="readonly" type="checkbox" checked></td>' +
                            '<td></td>' +
                            '<td><input readonly="readonly" type="checkbox" checked></td>' +
                            '<td><input readonly="readonly" type="checkbox" checked></td>' +
                            '</tr>');
                    }

                    if(ml_plan==='BL'){
                        $('#meal_table_body').append('<tr>' +
                            '<td>'+checkin2+'</td>' +
                            '<td>FB</td>' +
                            '<td></td>' +
                            '<td><input readonly="readonly" type="checkbox" checked></td>' +
                            '<td></td>' +
                            '<td><input readonly="readonly" type="checkbox" checked></td>' +
                            '<td><input readonly="readonly" type="checkbox" checked></td>' +
                            '</tr>');
                    }

                    if(ml_plan==='BPL'){
                        $('#meal_table_body').append('<tr>' +
                            '<td>'+checkin2+'</td>' +
                            '<td>FB</td>' +
                            '<td></td>' +
                            '<td></td>' +
                            '<td></td>' +
                            '<td><input readonly="readonly" type="checkbox" checked></td>' +
                            '<td><input readonly="readonly" type="checkbox" checked></td>' +
                            '</tr>');
                    }

                    if(ml_plan==='BD'){
                        $('#meal_table_body').append('<tr>' +
                            '<td>'+checkin2+'</td>' +
                            '<td>FB</td>' +
                            '<td></td>' +
                            '<td></td>' +
                            '<td></td>' +
                            '<td><input readonly="readonly" type="checkbox" checked></td>' +
                            '<td><input readonly="readonly" type="checkbox" checked></td>' +
                            '</tr>');
                    }
                }else {
                    if (i == diff){
                        checkin2.setDate(checkin2.getDate() + 1);
                        $('#meal_table_body').append('<tr>' +
                            '<td>'+checkin2+'</td>' +
                            '<td>FB</td>' +
                            '<td><input readonly="readonly" type="checkbox" checked></td>' +
                            '<td></td>' +
                            '<td></td>' +
                            '<td></td>' +
                            '<td></td>' +
                            '</tr>');

                    }else{
                        checkin2.setDate(checkin2.getDate() + 1);
                        $('#meal_table_body').append('<tr>' +
                            '<td>'+checkin2+'</td>' +
                            '<td>FB</td>' +
                            '<td><input readonly="readonly" type="checkbox" checked></td>' +
                            '<td><input readonly="readonly" type="checkbox" checked></td>' +
                            '<td></td>' +
                            '<td><input readonly="readonly" type="checkbox" checked></td>' +
                            '<td><input readonly="readonly" type="checkbox" checked></td>' +
                            '</tr>');
                    }

                }
            }
        });

    });

    function getTotalQnty() {
        var sq = parseFloat($('#single_qnty').val()) ? parseFloat($('#single_qnty').val()) : 0;
        var dq = parseFloat($('#double_qnty').val()) ? parseFloat($('#double_qnty').val()) : 0;
        var twq = parseFloat($('#twin_qnty').val()) ? parseFloat($('#twin_qnty').val()) : 0;
        var trq = parseFloat($('#triple_qnty').val()) ? parseFloat($('#triple_qnty').val()) : 0;
        var hq = parseFloat($('#honeymoon_qnty').val()) ? parseFloat($('#honeymoon_qnty').val()) : 0;
        var ebq = parseFloat($('#extrabed_qnty').val()) ? parseFloat($('#extrabed_qnty').val()) : 0;
        $('.totals_qnty').val('');
        $('.totals_qnty').val(sq+dq+twq+trq+hq+ebq);
    }

    function getTotalPax() {
        var spax = parseFloat($('#single_pax').val()) ? parseFloat($('#single_pax').val()) : 0;
        var dpax = parseFloat($('#double_pax').val()) ? parseFloat($('#double_pax').val()) : 0;
        var twpax = parseFloat($('#twin_pax').val()) ? parseFloat($('#twin_pax').val()) : 0;
        var trpax = parseFloat($('#triple_pax').val()) ? parseFloat($('#triple_pax').val()) : 0;
        var hpax = parseFloat($('#honeymoon_pax').val()) ? parseFloat($('#honeymoon_pax').val()) : 0;
        var ebpax = parseFloat($('#extrabed_pax').val()) ? parseFloat($('#extrabed_pax').val()) : 0;
        $('.totals_pax').val('');
        $('.totals_pax').val(spax+dpax+twpax+trpax+hpax+ebpax);
    }

    function getTotalAdults() {
        var sadults = parseFloat($('#single_adults').val()) ? parseFloat($('#single_adults').val()) : 0;
        var dadults = parseFloat($('#double_adults').val()) ? parseFloat($('#double_adults').val()) : 0;
        var twadults = parseFloat($('#twin_adults').val()) ? parseFloat($('#twin_adults').val()) : 0;
        var tradults = parseFloat($('#triple_adults').val()) ? parseFloat($('#triple_adults').val()) : 0;
        var hadults = parseFloat($('#honeymoon_adults').val()) ? parseFloat($('#honeymoon_adults').val()) : 0;
        var ebadults = parseFloat($('#extrabed_adults').val()) ? parseFloat($('#extrabed_adults').val()) : 0;
        $('.totals_adults').val('');
        $('.totals_adults').val(sadults+dadults+twadults+tradults+hadults+ebadults);
    }

    function getTotalChildren() {
        var schildren = parseFloat($('#single_children').val()) ? parseFloat($('#single_children').val()) : 0;
        var dchildren = parseFloat($('#double_children').val()) ? parseFloat($('#double_children').val()) : 0;
        var twchildren = parseFloat($('#twin_children').val()) ? parseFloat($('#twin_children').val()) : 0;
        var trchildren = parseFloat($('#triple_children').val()) ? parseFloat($('#triple_children').val()) : 0;
        var hchildren = parseFloat($('#honeymoon_children').val()) ? parseFloat($('#honeymoon_children').val()) : 0;
        var ebchildren = parseFloat($('#extrabed_children').val()) ? parseFloat($('#extrabed_children').val()) : 0;
        $('.totals_children').val('');
        $('.totals_children').val(schildren+dchildren+twchildren+trchildren+hchildren+ebchildren);
    }
</script>


