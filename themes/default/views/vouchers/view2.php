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
        <h2 class="blue"><i class="fa-fw fa fa-file"></i><?= lang("sale_no") . ' ' . $inv->id; ?></h2>

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
                    <div class="col-xs-4 border-right">

                        <div class="col-xs-2"><i class="fa fa-3x fa-building padding010 text-muted"></i></div>
                        <div class="col-xs-10">
                            <h2 class=""><?= $biller->company != '-' ? $biller->company : $biller->name; ?></h2>
                            <?= $biller->company ? "" : "Attn: " . $biller->name ?>

                            <?php
                            echo $biller->address . "<br>" . $biller->city . " " . $biller->postal_code . " " . $biller->state . "<br>" . $biller->country;

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
                    <div class="col-xs-4 border-right">

                        <div class="col-xs-2"><i class="fa fa-3x fa-user padding010 text-muted"></i></div>
                        <div class="col-xs-10">
                            <h2 class=""><?= $customer->company ? $customer->company : $customer->name; ?></h2>
                            <?= $customer->company ? "" : "Attn: " . $customer->name ?>

                            <?php
                            echo $customer->address . "<br>" . $customer->city . " " . $customer->postal_code . " " . $customer->state . "<br>" . $customer->country;

                            echo "<p>";

                            if ($customer->cf1 != "-" && $customer->cf1 != "") {
                                echo "<br>" . lang("ccf1") . ": " . $customer->cf1;
                            }
                            if ($customer->cf2 != "-" && $customer->cf2 != "") {
                                echo "<br>" . lang("ccf2") . ": " . $customer->cf2;
                            }
                            if ($customer->cf3 != "-" && $customer->cf3 != "") {
                                echo "<br>" . lang("ccf3") . ": " . $customer->cf3;
                            }
                            if ($customer->cf4 != "-" && $customer->cf4 != "") {
                                echo "<br>" . lang("ccf4") . ": " . $customer->cf4;
                            }
                            if ($customer->cf5 != "-" && $customer->cf5 != "") {
                                echo "<br>" . lang("ccf5") . ": " . $customer->cf5;
                            }
                            if ($customer->cf6 != "-" && $customer->cf6 != "") {
                                echo "<br>" . lang("ccf6") . ": " . $customer->cf6;
                            }

                            echo "</p>";
                            echo lang("tel") . ": " . $customer->phone . "<br>" . lang("email") . ": " . $customer->email;

                            echo "<br></br>";
                            ?>
                        </div>
                        <div class="clearfix"></div>

                    </div>
                    <div class="col-xs-4">
                        <div class="col-xs-2"><i class="fa fa-3x fa-building-o padding010 text-muted"></i></div>
                        <div class="col-xs-10">
                            <h2 class=""><?= $Settings->site_name; ?></h2>
                            <?= $warehouse->name ?>

                            <?php
                            echo $warehouse->address . "<br>";
                            echo ($warehouse->phone ? lang("tel") . ": " . $warehouse->phone . "<br>" : '') . ($warehouse->email ? lang("email") . ": " . $warehouse->email : '');
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
                        $datetime1 = new DateTime($inv->chkindate);

                        $datetime2 = new DateTime($inv->chkoutdate);

                        $daydifferebce = $datetime1->diff($datetime2)->format('%a');;
                        $datetime = new DateTime($inv->chkindate);
                        $datetime2 = new DateTime($inv->chkindate);
                        ?>
                        <!--<p style="font-weight:bold;"><?/*= lang("Category"); */?>
                            : <?php /*echo $qcategory->name; */?></p>-->
                        <p style="font-weight:bold;"><?= lang("No_of_Days"); ?>
                            : <?php echo $daydifferebce; ?></p>

                        <p style="font-weight:bold;"><?= lang("Meal_Plan"); ?>
                            : FB</p>

                        <p style="font-weight:bold;"><?= lang("No of Pax"); ?>
                            : <?= $inv->no_of_pax;  ?></p>
                        <p style="font-weight:bold;"><?= lang("No_of_Rooms"); ?>
                            : <?= $inv->no_of_rooms;  ?></p>
                        <p style="font-weight:bold;">Arrival Time
                            : <?= $svoucher->arrival_time;  ?></p>

                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="col-xs-4">
                    <div class="col-xs-2"><i class="fa fa-3x fa-file-text-o padding010 text-muted"></i></div>
                    <div class="col-xs-10">
                        <h2 class=""><?= lang("ref"); ?>: <?= $inv->reference_no; ?></h2>

                       <!-- <p style="font-weight:bold;"><?/*= lang("expiry_date"); */?>: <?/*= $this->sma->hrld($inv->due_date); */?></p>-->

                        <p style="font-weight:bold;"><?= lang("date"); ?>: <?= $this->sma->hrld($inv->date); ?></p>

                        <p style="font-weight:bold;"><?php if($inv->category_id == '10'){ echo lang("From");} else{echo lang("Check_In");} ?>: <?= date('d-m-Y',strtotime($inv->chkindate)); ?></p>

                        <p style="font-weight:bold;"><?php if($inv->category_id == '10'){ echo lang("To");} else{echo lang("Check_Out");} ?>: <?= date('d-m-Y',strtotime($inv->chkoutdate)); ?></p>

                        <p style="font-weight:bold;"><?= lang("sale_status"); ?>: <?= lang($inv->sale_status); ?></p>

                        <p style="font-weight:bold;"><?= lang("payment_status"); ?>
                            : <?= lang($inv->payment_status); ?></p>

                        <p>&nbsp;</p>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="clearfix"></div>

                <div class="table-responsive">
                    <p>Grp/Guest <?php echo $voucher->group_name ?></p>
                    <p>Contact <?php echo $voucher->contact_name ?></p>
                    <p>To Voucher <?php echo $voucher->voucher_no ?></p>
                    <h5 class="text-center">OCCUPANCY DETAILS</h5>
                    <table class="table table-bordered table-hover table-striped print-table order-table">
                        <thead>
                        <tr>
                            <th>Serv Date</th>
                            <th>Room</th>
                            <th>Quantity</th>
                            <th>Adults</th>
                            <th>Children</th>
                            <th>Pax</th>
                            <!--<th>Price</th>
                            <th>Amount <?php /*echo $inv->currency*/?></th>-->
                        </tr>
                        </thead>
                        <tbody>

                        <?php for($i=0;$i<$daydifferebce;$i++){ ?>
                            <?php
                            $qnty = 0;
                            $adults = 0;
                            $children = 0;
                            $pax = 0;
                            $amount = 0;
                            for ($j=0;$j<count($invoice_items);$j++){
                                if($j==count($invoice_items)-1){
                                    ?>
                                    <tr>
                                        <td></td>
                                        <td><?php echo $invoice_items[$j]['name'] ?></td>
                                        <td><?php $qnty+=$invoice_items[$j]['quantity']; echo $invoice_items[$j]['quantity'] ?></td>
                                        <td><?php $adults+=$invoice_items[$j]['no_adults']; echo $invoice_items[$j]['no_adults'] ?></td>
                                        <td><?php $children+=$invoice_items[$j]['no_children']; echo $invoice_items[$j]['no_children'] ?></td>
                                        <td><?php $pax+=$invoice_items[$j]['pax']; echo $invoice_items[$j]['pax'] ?></td>
                                        <!--<td><?php /*echo $invoice_items[$j]['price'] */?></td>
                                        <td><?php /*$amount+=$invoice_items[$j]['total']; echo $invoice_items[$j]['total'] */?></td>-->
                                    </tr>
                                    <?php
                                }else{
                                    ?>
                                    <tr>
                                        <td><?php
                                            if($i>0){
                                                $datetime = $datetime->modify('+1 day');

                                                echo $datetime->format('Y-m-d');
                                            }else{
                                                echo $inv->chkindate;
                                            }

                                            ?></td>
                                        <td><?php echo $invoice_items[$j]['name'] ?></td>
                                        <td><?php $qnty+=$invoice_items[$j]['quantity']; echo $invoice_items[$j]['quantity'] ?></td>
                                        <td><?php $adults+=$invoice_items[$j]['no_adults']; echo $invoice_items[$j]['no_adults'] ?></td>
                                        <td><?php $children+=$invoice_items[$j]['no_children']; echo $invoice_items[$j]['no_children'] ?></td>
                                        <td><?php $pax+=$invoice_items[$j]['pax']; echo $invoice_items[$j]['pax'] ?></td>
                                        <!--<td><?php /*echo $invoice_items[$j]['price'] */?></td>
                                        <td><?php /*$amount+=$invoice_items[$j]['total']; echo $invoice_items[$j]['total'] */?></td>-->
                                    </tr>
                                    <?php
                                } ?>
                                <?php
                            } ?>

                            <tr>
                                <td></td>
                                <td>DAY TOTAL</td>
                                <td><?php echo $qnty; ?></td>
                                <td><?php echo $adults; ?></td>
                                <td><?php echo $children; ?></td>
                                <td><?php echo $pax; ?></td>
                            </tr>
                            <?php

                        } ?>

                        </tbody>

                        <!--<tfoot>
                        <tr>
                            <td>TOTAL AMOUNT IN <?php /*echo $inv->currency*/?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><?php /*echo $amount*$daydifferebce; */?></td>
                        </tr>
                        </tfoot>-->

                    </table>
                    <h5 class="text-center">MEAL PLAN DETAILS</h5>
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
                        <?php for($i=0;$i<=$daydifferebce;$i++){ ?>
                        <?php if($i==0) {?>
                                <?php if($svoucher->arrival_time==='BB'){?>
                                <tr>
                                    <td><?php echo $inv->chkindate ?></td>
                                    <td>FB</td>
                                    <td><input readonly="readonly" type="checkbox" checked></td>
                                    <td><input readonly="readonly" type="checkbox" checked></td>
                                    <td></td>
                                    <td><input readonly="readonly" type="checkbox" checked></td>
                                    <td><input readonly="readonly" type="checkbox" checked></td>
                                    </tr>
                                    <?php

                                } ?>

                                <?php if($svoucher->arrival_time==='BL'){?>
                                    <tr>
                                        <td><?php echo $inv->chkindate ?></td>
                                        <td>FB</td>
                                        <td></td>
                                        <td><input readonly="readonly" type="checkbox" checked></td>
                                        <td></td>
                                        <td><input readonly="readonly" type="checkbox" checked></td>
                                        <td><input readonly="readonly" type="checkbox" checked></td>
                                    </tr>
                                    <?php

                                } ?>

                                <?php if($svoucher->arrival_time==='BPL'){?>
                                    <tr>
                                        <td><?php echo $inv->chkindate ?></td>
                                        <td>FB</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><input readonly="readonly" type="checkbox" checked></td>
                                        <td><input readonly="readonly" type="checkbox" checked></td>
                                    </tr>
                                    <?php

                                } ?>

                                <?php if($svoucher->arrival_time==='BD'){?>
                                    <tr>
                                        <td><?php echo $inv->chkindate ?></td>
                                        <td>FB</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><input readonly="readonly" type="checkbox" checked></td>
                                        <td><input readonly="readonly" type="checkbox" checked></td>
                                    </tr>
                                    <?php

                                } ?>


                        <?php

                        }else{ ?>
                                <?php if ($i == $daydifferebce){
                                $datetime2->modify('+1 day');

                                    ?>

                                <tr>
                                    <td><?php echo $datetime2->format('Y-m-d'); ?></td>
                                    <td>FB</td>
                                    <td><input readonly="readonly" type="checkbox" checked></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <?php

                            }else{ $datetime2 = $datetime2->modify('+1 day');
                                     ?>

                                    <tr>
                                        <td><?php echo $datetime2->format('Y-m-d'); ?></td>
                                        <td>FB</td>
                                        <td><input readonly="readonly" type="checkbox" checked></td>
                                        <td><input readonly="readonly" type="checkbox" checked></td>
                                        <td></td>
                                        <td><input readonly="readonly" type="checkbox" checked></td>
                                        <td><input readonly="readonly" type="checkbox" checked></td>
                                    </tr>
                                    <?php

                                } ?>
                                <?php

                            } ?>
                            <?php

                        } ?>
                        </tbody>
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
                        if ($inv->staff_note || $inv->staff_note != "") { ?>
                            <div class="well well-sm staff_note">
                                <p class="bold"><?= lang("staff_note"); ?>:</p>

                                <div><?= $this->sma->decode_html($inv->staff_note); ?></div>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="col-xs-6">
                        <div class="well well-sm">
                            <p><?= lang("created_by"); ?>
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

<script type="text/javascript">

</script>
