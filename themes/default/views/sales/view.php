<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click', '.sledit', function (e) {
            if (localStorage.getItem('slitems')) {
                e.preventDefault();
                var href = $(this).attr('href');
                bootbox.confirm("<?=lang('you_will_loss_sale_data')?>", function (result) {
                    if (result) {
                        window.location.href = href;
                    }
                });
            }
        });
    });
</script>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-file"></i><?= lang("sale_no") . ' E00' . $inv->id; ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="icon fa fa-tasks tip" data-placement="left" title="<?= lang("actions") ?>">
                        </i>
                    </a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                        <?php if ($inv->attachment) { ?>
                            <li>
                                <a href="<?= site_url('welcome/download/' . $inv->attachment) ?>">
                                    <i class="fa fa-chain"></i> <?= lang('attachment') ?>
                                </a>
                            </li>
                        <?php } ?>
                        <li>
                            <a href="<?= site_url('sales/edit/' . $inv->id) ?>" class="sledit">
                                <i class="fa fa-edit"></i> <?= lang('edit_sale') ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?= site_url('sales/payments/' . $inv->id) ?>" data-target="#myModal" data-toggle="modal">
                                <i class="fa fa-money"></i> <?= lang('view_payments') ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?= site_url('sales/add_payment/' . $inv->id) ?>" data-target="#myModal" data-toggle="modal">
                                <i class="fa fa-money"></i> <?= lang('add_payment') ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?= site_url('sales/add_delivery/' . $inv->id) ?>" data-target="#myModal" data-toggle="modal">
                                <i class="fa fa-truck"></i> <?= lang('add_delivery') ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?= site_url('sales/email/' . $inv->id) ?>" data-target="#myModal" data-toggle="modal">
                                <i class="fa fa-envelope-o"></i> <?= lang('send_email') ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?= site_url('sales/pdf/' . $inv->id) ?>">
                                <i class="fa fa-file-pdf-o"></i> <?= lang('export_to_pdf') ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?= site_url('sales/return_sale/' . $inv->id) ?>">
                                <i class="fa fa-angle-double-left"></i> <?= lang('return_sale') ?>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <?php if ($return_sale) { ?>
                    <div class="alert alert-info"
                         role="alert"><?= lang('return_has_been_added') . ' <a class="btn btn-primary btn-sm" href="' . site_url('sales/view_return/' . $return_sale->id) . '">' . lang('view_details') . '</a>'; ?></div>
                <?php } ?>
                <div class="print-only col-xs-12">
                    <img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>"
                         alt="<?= $biller->company != '-' ? $biller->company : $biller->name; ?>">
                </div>
                <div class="well well-sm">
                    <div class="col-xs-6 border-right">

                        <div class="col-xs-2">From:</i></div>
                        <div class="col-xs-10">
                            <h2 class=""><?= $biller->company != '-' ? $biller->company : $biller->name; ?></h2>
                            <?= $biller->company ? "" : "Attn: " . $biller->name ?>

                            <?php
                            echo $biller->address . "<br> " . $biller->postal_code . " " . $biller->state . "<br>" . $biller->country;

                            echo "<p>";

                            if ($biller->cf1 != "-" && $biller->cf1 != "") {
                                echo "<br>" . lang("bcf1") . ": " . $biller->cf1;
                            }
                            if ($biller->cf2 != "-" && $biller->cf2 != "") {
                                echo "<br>" . lang("bcf2") . ": " . $biller->cf2;
                            }
                            if ($biller->cf3 != "-" && $biller->cf3 != "") {
                                echo "<br>" . lang("bcf3") . ": " . $biller->cf3;
                            }
                            if ($biller->cf4 != "-" && $biller->cf4 != "") {
                                echo "<br>" . lang("bcf4") . ": " . $biller->cf4;
                            }
                            if ($biller->cf5 != "-" && $biller->cf5 != "") {
                                echo "<br>" . lang("bcf5") . ": " . $biller->cf5;
                            }
                            if ($biller->cf6 != "-" && $biller->cf6 != "") {
                                echo "<br>" . lang("bcf6") . ": " . $biller->cf6;
                            }

                            echo "</p>";
                            echo lang("tel") . ": " . $biller->phone . "<br>" . lang("email") . ": " . $biller->email;
                            ?>
                        </div>
                        <div class="clearfix"></div>

                    </div>
                    <div class="col-xs-6 border-right">

                        <div class="col-xs-2">To:</div>
                        <div class="col-xs-10">
                            <h2 class=""><?= $customer->company ? $customer->company : $customer->name; ?></h2>
                            <?= $customer->company ? "" : "Attn: " . $customer->name ?>

                            <?php
                            echo $customer->address . "<br>" . $customer->city . " " . $customer->postal_code . " " . $customer->state . "<br>" . $customer->country;

                            
                            echo lang("tel") . ": " . $customer->phone . "<br>" . lang("email") . ": " . $customer->email;
							
							echo "<br></br>";
							if($voucher->contact_name == 'contact_name'){
								
							}else{
							 echo "<b>".lang("Customer") . ": " . ucfirst($voucher->contact_name)."</b>";
							}
                            ?>
                        </div>
                        <div class="clearfix"></div>

                    </div>
                   
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
                <?php if ($Settings->invoice_view == 1) { ?>
                    <div class="col-xs-12 text-center">
                        <h1><?= lang('tax_invoice'); ?></h1>
                    </div>
                <?php } ?>
                <div class="clearfix"></div>
                <div class="col-xs-8 pull-right">
                    <div class="col-xs-12 text-right">
                       <!-- <?php /*$br = $this->sma->save_barcode($inv->reference_no, 'code39', 70, false); */?>
                        <img src="<?/*= base_url() */?>assets/uploads/barcode<?/*= $this->session->userdata('user_id') */?>.png"
                             alt="<?/*= $inv->reference_no */?>"/>
                        <?php /*$this->sma->qrcode('link', urlencode(site_url('sales/view/' . $inv->id)), 2); */?>
                        <img src="<?/*= base_url() */?>assets/uploads/qrcode<?/*= $this->session->userdata('user_id') */?>.png"
                             alt="<?/*= $inv->reference_no */?>"/>
							 <br>-->
							 <?php
							  $datetime1 = new DateTime($voucher->check_in);

                                $datetime2 = new DateTime($voucher->check_out);

                                $daydifferebce = $datetime1->diff($datetime2)->format('%a');
                                $datetime = new DateTime($voucher->check_in);
								 ?>
								<!--<p style="font-weight:bold;"><?/*= lang("Category"); */?>
                            : <?php /*echo $qcategory->name; */?></p>-->
							<p style="font-weight:bold;"><?= lang("No_of_Days"); ?>
                            : <?php echo $daydifferebce; ?></p>

							 <p style="font-weight:bold;"><?= lang("Meal_Plan"); ?>
                            : <?php echo $voucher->meal_plan ?></p>

                            <!-- <p style="font-weight:bold;"><?/*= lang("No of Pax"); */?>
                                 : <?/*= $inv->no_of_pax;  */?></p>-->
							<p style="font-weight:bold;"><?= lang("No_of_Rooms"); ?>
                            : <?= $voucher->deluxe_room + $voucher->executive_room + $voucher->singles_room + $voucher->standard_room + $voucher->superior_room + $voucher->twin_room ;  ?></p>
                        <p style="font-weight:bold;"><?= lang("Bill to"); ?>: <?= $room->name ; ?></p>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="col-xs-4">
                    <div class="col-xs-2"><i class="fa fa-3x fa-file-text-o padding010 text-muted"></i></div>
                    <div class="col-xs-10">
                        <h2 class=""><?= lang("ref"); ?>: <?= $inv->reference_no; ?></h2>

                       
						<p style="font-weight:bold;"><?php if($inv->category_id == '10'){ echo lang("From");} else{echo lang("Check_In");} ?>: <?= date('d/m/Y',strtotime($voucher->check_in)); ?></p>
							
                        <p style="font-weight:bold;"><?php if($inv->category_id == '10'){ echo lang("To");} else{echo lang("Check_Out");} ?>: <?= date('d/m/Y',strtotime($voucher->check_out)); ?></p>
						
						

                        <p>&nbsp;</p>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="clearfix"></div>

                <div class="table-responsive">

                    <table class="table table-bordered table-hover table-striped print-table order-table">
                        <thead>
                        <tr>
                            <th>Serv Date</th>
                            <th>Meal Plan</th>
                            <th>Room</th>
                            <th>Quantity</th>
                            <th>Pax</th>
                            <th>Price</th>
                            <th>Amount <?php echo $inv->currency?></th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php for($i=0;$i<$daydifferebce;$i++){ ?>
                            <?php
                            $qnty = 0;
                            $pax = 0;
                            $amount = 0;
                            for ($j=0;$j<count($invoice_items);$j++){
                                if($j==0){
                                ?>
                                <tr>
                                    <td><?php
                                        if($i==0){
                                            echo date('d/m/Y',strtotime($voucher->check_in));
                                        }else{
                                            $datetime = $datetime->modify('+1 day');

                                            echo $datetime->format('d/m/Y');
                                        }

                                         ?></td>
                                    <td><?php echo $voucher->meal_plan ?></td>
                                    <td><?php echo $invoice_items[$j]['name'] ?></td>
                                    <td><?php $qnty+=$invoice_items[$j]['quantity']; echo $invoice_items[$j]['quantity'] ?></td>
                                    <td><?php $pax+=$invoice_items[$j]['pax']; echo $invoice_items[$j]['pax'] ?></td>
                                    <td><?php echo $invoice_items[$j]['price'] ?></td>
                                    <td><?php $amount+=$invoice_items[$j]['total']; echo $invoice_items[$j]['total'] ?></td>
                                </tr>
                                    <?php
                                }else{ ?>
                                    <tr>
                                        <td></td>
                                        <td><?php echo $voucher->meal_plan ?></td>
                                        <td><?php echo $invoice_items[$j]['name'] ?></td>
                                        <td><?php $qnty+=$invoice_items[$j]['quantity']; echo $invoice_items[$j]['quantity'] ?></td>
                                        <td><?php $pax+=$invoice_items[$j]['pax']; echo $invoice_items[$j]['pax'] ?></td>
                                        <td><?php echo $invoice_items[$j]['price'] ?></td>
                                        <td><?php $amount+=$invoice_items[$j]['total']; echo $invoice_items[$j]['total'] ?></td>
                                    </tr>
                                    <?php }?>
                                <?php
                            } ?>

                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td>DAY TOTAL</td>
                                    <td><?php //echo $qnty; ?></td>
                                    <td><?php echo $pax; ?></td>
                                    <td></td>
                                    <td><?php echo $amount; ?></td>
                                </tr>
                                <?php

                            } ?>

                        </tbody>

                        <tfoot>
                        <tr>
                           
                            
							<td style="font-weight:bold;text-align:right" colspan="6">TOTAL AMOUNT IN <?php echo $inv->currency;?></td>
                            <td style="font-weight:bold;"><?php echo number_format($amount*$daydifferebce,2); ?></td>
                        </tr>
						<tr>
                           <?php 
						   $totalpamount = 0;
						   foreach ($payments as $payment1) { 
						   $totalpamount =$totalpamount +$payment1->amount;
						   }?>
                            
							<td style="font-weight:bold;text-align:right" colspan="6">PAID <?php echo $inv->currency;?></td>
                            <td style="font-weight:bold;"><?php echo number_format($totalpamount,2); ?></td>
                        </tr>
						<tr>
                           
                            
							<td style="font-weight:bold;text-align:right" colspan="6">BALANCE <?php echo $inv->currency;?></td>
                            <td style="font-weight:bold;"><?php echo number_format($amount*$daydifferebce-$totalpamount,2); ?></td>
                        </tr>
                        </tfoot>

                    </table>
                </div>

                <div class="row">
                    <div class="col-xs-6">
                        <?php
                        if ($inv->note || $inv->note != "") { ?>
                            <div class="well well-sm">
                                <p class="bold"><?= lang("note"); ?>:</p>

                                <div><?= $this->sma->decode_html($inv->note); ?></div>
                            </div>
                        <?php
                        }
                        
                         ?>
                    </div>

                    <div class="col-xs-6">
                        <div class="well well-sm">
                            <p><?= lang("Check In by"); ?>
                                : <?= $created_by->first_name . ' ' . $created_by->last_name; ?> </p>

                            <p><?= lang("date"); ?>: <?= $this->sma->hrld($inv->date); ?></p>
                            <?php if ($inv->updated_by) { ?>
                                <p><?= lang("updated_by"); ?>
                                    : <?= $updated_by->first_name . ' ' . $updated_by->last_name;; ?></p>
                                <p><?= lang("update_at"); ?>: <?= $this->sma->hrld($inv->updated_at); ?></p>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <?php if ($payments) { ?>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-condensed print-table">
                                    <thead>
                                    <tr>
                                        <th><?= lang('date') ?></th>
                                        <th><?= lang('payment_reference') ?></th>
                                        <th><?= lang('paid_by') ?></th>
                                        <th><?= lang('amount') ?></th>
                                        <th><?= lang('created_by') ?></th>
                                        <th><?= lang('type') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($payments as $payment) { ?>
                                        <tr <?= $payment->type == 'returned' ? 'class="warning"' : ''; ?>>
                                            <td><?= $this->sma->hrld($payment->date) ?></td>
                                            <td><?= $payment->reference_no; ?></td>
                                            <td><?= lang($payment->paid_by);
                                                if ($payment->paid_by == 'gift_card' || $payment->paid_by == 'CC') {
                                                    echo ' (' . $payment->cc_no . ')';
                                                } elseif ($payment->paid_by == 'Cheque') {
                                                    echo ' (' . $payment->cheque_no . ')';
                                                }
                                                ?></td>
                                            <td><?= $this->sma->formatMoney($payment->amount); ?></td>
                                            <td><?= $payment->first_name . ' ' . $payment->last_name; ?></td>
                                            <td><?= lang($payment->type); ?></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <div class="row" style="padding-left: 20px; padding-right: 20px;" >
                    <div class="col-xs-12">
                        <ol>
                            <li>Payment is due as per our Contractual terms and Conditions.</li>
                            <li>Please make all cheques payable to <b><?php echo $biller->acc_name; ?></b></li>
                        </ol>
                        <div class="row">
                            <div style="float: left;">
                                <p><b>BANK DETAILS</b></p>
                                <p>Account Name <?php echo $biller->acc_name; ?></p>
                                <p>Bank Name <?php echo $biller->bank_name; ?></p>
                                <p>Branch Name <?php echo $biller->branch; ?></p>
                                <p><b>UGX A/C No <?php echo $biller->ksh_acc_no; ?></b></p>
                                                 </div>
                            <div style="float: right;">
                                <p><b>Mobile Money</b></p>
                                <p>Airtel No <?php echo $biller->mpesa_business_no; ?></p>
                                <p>MTN No <?php echo $biller->mpesa_acc_no; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if (!$Supplier || !$Customer) { ?>
            <div class="buttons">
                <div class="btn-group btn-group-justified">
                    <?php if ($inv->attachment) { ?>
                        <div class="btn-group">
                            <a href="<?= site_url('welcome/download/' . $inv->attachment) ?>" class="tip btn btn-primary" title="<?= lang('attachment') ?>">
                                <i class="fa fa-chain"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('attachment') ?></span>
                            </a>
                        </div>
                    <?php } ?>
                    <div class="btn-group"><a href="<?= site_url('sales/payments/' . $inv->id) ?>" data-toggle="modal"
                                              data-target="#myModal" class="tip btn btn-primary tip"
                                              title="<?= lang('view_payments') ?>"><i class="fa fa-money"></i> <span
                                class="hidden-sm hidden-xs"><?= lang('view_payments') ?></span></a></div>
                    <div class="btn-group"><a href="<?= site_url('sales/add_payment/' . $inv->id) ?>"
                                              data-toggle="modal" data-target="#myModal" class="tip btn btn-primary tip"
                                              title="<?= lang('add_payment') ?>"><i class="fa fa-money"></i> <span
                                class="hidden-sm hidden-xs"><?= lang('add_payment') ?></span></a></div>
                    <div class="btn-group"><a href="<?= site_url('sales/email/' . $inv->id) ?>" data-toggle="modal"
                                              data-target="#myModal" class="tip btn btn-primary tip"
                                              title="<?= lang('email') ?>"><i class="fa fa-envelope-o"></i> <span
                                class="hidden-sm hidden-xs"><?= lang('email') ?></span></a></div>
                    <div class="btn-group"><a href="<?= site_url('sales/pdf/' . $inv->id) ?>"
                                              class="tip btn btn-primary" title="<?= lang('download_pdf') ?>"><i
                                class="fa fa-download"></i> <span class="hidden-sm hidden-xs"><?= lang('pdf') ?></span></a>
                    </div>
                    <div class="btn-group"><a href="<?= site_url('sales/edit/' . $inv->id) ?>"
                                              class="tip btn btn-warning tip sledit" title="<?= lang('edit') ?>"><i
                                class="fa fa-edit"></i> <span class="hidden-sm hidden-xs"><?= lang('edit') ?></span></a>
                    </div>
                    <div class="btn-group"><a href="#" class="tip btn btn-danger bpo"
                                              title="<b><?= $this->lang->line("delete_sale") ?></b>"
                                              data-content="<div style='width:150px;'><p><?= lang('r_u_sure') ?></p><a class='btn btn-danger' href='<?= site_url('sales/delete/' . $inv->id) ?>'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button></div>"
                                              data-html="true" data-placement="top"><i class="fa fa-trash-o"></i> <span
                                class="hidden-sm hidden-xs"><?= lang('delete') ?></span></a></div>
                    <!--<div class="btn-group"><a href="<?= site_url('sales/excel/' . $inv->id) ?>" class="tip btn btn-primary"  title="<?= lang('download_excel') ?>"><i class="fa fa-download"></i> <?= lang('excel') ?></a></div>-->
                </div>
            </div>
        <?php } ?>
    </div>
</div>
