<script type="text/javascript">
$(document).ready(function () {
$(".amountd").on("change", function (e) {

          var total = 0;
        $('.amountd').each( function(){
           total += parseFloat($(this).val());
        });
        $('#total_amnt').val(total);
//alert(total);
        });
//$('#checkth').on('change', function() {
 //    var checked = this.checked
 //  $('span').html(checked.toString())
//});
		
    });
</script>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Bar Bills') . " (" . $room->name . ")";; ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'bill-room-form');
        echo form_open_multipart("rooms/bill/" . $room->id, $attrib); ?>
        <div class="modal-body">
            <div class="table-responsive">
                <table id="CompTable" cellpadding="0" cellspacing="0" border="0"
                       class="table table-bordered table-hover table-striped">
                    <thead>
                    <tr>
					    <th style="width:10%;"><input class="checkbox checkth" type="checkbox" /></th>
                        <th style="width:30%;"><?= $this->lang->line("date"); ?></th>
                        <th style="width:20%;"><?= $this->lang->line("reference_no"); ?></th>
						<th style="width:30%;"><?= $this->lang->line("customer"); ?></th>
                        <th style="width:30%;"><?= $this->lang->line("Product_name"); ?></th>
                        <th style="width:30%;"><?= $this->lang->line("Amount"); ?></th>
                        
                        <th style="width:15%;"><?= $this->lang->line("Balance"); ?></th>
                        <th width="150px"><?= $this->lang->line("Pay Amountugx"); ?></th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($sale_items)) {
                        $total=0;
                        foreach ($sale_items as $sale_item) { ?>
                            <tr class="row<?= $sale_item->id ?>">
                                <input type="text" style="display: none" name="warehouse" value="1">
								<td><input class="checkbox checkth" type="checkbox" id="check<?= $sale_item->id; ?>" name="check[<?= $sale_item->id; ?>]" value="<?= $sale_item->id; ?>"/></td>
                                <td><?= $this->sma->hrld($sale_item->date); ?></td>
                                <td><?= $sale_item->reference_no ?></td>
								<td><?= $sale_item->name ?></td>
                                <td><?= $sale_item->product_name ?></td>
                                <td><?= $sale_item->unit_price ?></td>
                                
                                <td><?= $this->sma->formatMoney($sale_item->subtotal); ?></td>
                                <td ><input style="width:150px" class="form-control amountd" type="text" name="amount[<?= $sale_item->id; ?>]" value="0"/></td>

                            </tr>

                        <?php $total+=$sale_item->subtotal;$vat = ($total *18)/100;
                            $tourism_levy = ($total*2)/100;
                            $service_charge = ($total*2)/100; }
                    } else {
                        echo "<tr><td colspan='8'>" . lang('no_data_available') . "</td></tr>";
                    } ?>
                    </tbody>
                    <tfoot>
                        <td></td>
                        <td></td>
						 <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><?= $this->sma->formatMoney($total); ?></td>
                        <td></td>
                    </tfoot>
                </table>
            </div>
            <div class="row">
                    <div class="col-lg-12">
					<div class="col-md-3">
                                <div class="form-group">
                                <label>Date</label>
                                <input name="bill_date" class="form-control date" type="text" id="bill_date" value="<?php echo date('d/m/Y'); ?>" readonly>
                            </div>
                            </div>
					<div class="col-md-3">
                                <div class="form-group">
                                <label>Total Amount</label>
                                <input name="total_amnt" class="form-control" type="text" id="total_amnt" value="0" readonly>
                            </div>
                            </div>
							<div class="col-md-3">
                                <div class="form-group">
                                <label>Paid By</label>
                               <select name="paid_by" id="paid_by_1" class="form-control paid_by"
                                            required="required">
                                        <option value="cash"><?= lang("cash"); ?></option>
                                        <option value="cheque"><?= lang("cheque"); ?></option>
                                        <option value="bank">Bank</option>
                                        <option value="mpesa"><?= lang("Mobile Money"); ?></option>
                                        <option value="cc">Credit Card</option>

                                    </select>
                            </div>
                            </div>
							
            </div>
			</div>
          
        </div>
        <div class="modal-footer">
            <?php echo form_submit('bill_room', lang('Pay_Bill'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

