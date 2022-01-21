<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->lang->line("Service Voucher") . " " . $inv->reference_no; ?></title>
    <link href="<?php echo $assets ?>styles/style.css" rel="stylesheet">
    <style type="text/css">
        html, body {
            height: 100%;
            background: #FFF;
        }

        body:before, body:after {
            display: none !important;
        }

        .table th {
            text-align: center;
            padding: 5px;
        }

        .table td {
            padding: 4px;
        }
    </style>
</head>
<body>
<div id="wrap">
    <div class="row">
        <div class="col-lg-12">

            <div class="row padding10">
                <div class="col-xs-5 pull-left">
                    <img height="100" src="assets/uploads/logos/logo.png" alt="<?= $biller->company != '-' ? $biller->company : $biller->name; ?>">
                </div>
                <div class="col-xs-5 pull-right">
                    ROCY HOTEL.<br>
                    T: <br>
                    P: <br>
                    E: rocyhotel@gmail.com<br>
                    W: www.rocyhotel.com
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row padding10">
                <div class="col-xs-5 pull-left">
                    <p style="font-weight:bold;">Booked Agent <?= $customer->company != '-' ? $customer->company : $customer->name; ?></p>
                    <p style="font-weight:bold;">Booking Date <?= lang("date"); ?>: <?= $svoucher->created_at; ?></p>
                    <p style="font-weight:bold;">Group Name <?php echo $voucher->group_name ?></p>
                    <p style="font-weight:bold;">Contact Person Details <br> Name: <?php echo $voucher->contact_name ?><br> Email:  <?php echo $voucher->contact_email ?><br> Phone:  <?php echo $voucher->contact_phone ?></p>
                    <p style="font-weight:bold;">To Voucher <?php echo $voucher->voucher_no ?></p>
                </div>
                <div class="col-xs-5 pull-right" style="text-align: right;">
                    <?php
                    $datetime1 = new DateTime($voucher->check_in);

                    $datetime2 = new DateTime($voucher->check_out);

                    $daydifferebce = $datetime1->diff($datetime2)->format('%a');
                    $datetime = new DateTime($voucher->check_in);
                    $datetime2 = new DateTime($voucher->check_in);
                    ?>
                    <p style="font-weight:bold;"><?= lang("ref"); ?>: <?= $inv->reference_no; ?></p>

                    <p style="font-weight:bold;"><?= lang("Arr Date"); ?>: <?= date('d/m/Y',strtotime($voucher->check_in)); ?></p>
                    <p style="font-weight:bold;"><?= lang("Dep Date"); ?>: <?= date('d/m/Y',strtotime($voucher->check_out)); ?></p>
                    <p style="font-weight:bold;"><?= lang("payment_status"); ?>: <?= lang($inv->payment_status); ?></p>
                    <p style="font-weight:bold;"><?= lang("Nights"); ?>: <?= $daydifferebce ?></p>
                    <p style="font-weight:bold;">Arrival Time : <?= $svoucher->arrival_time;  ?></p>

                    <div class="clearfix"></div>
                </div>
            </div>

            <div class="clearfix"></div>


            <div class="row" style="margin-left: 2px; margin-right: 2px;">
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
                                    <td><?php echo $invoice_items[$j]['name'] ?></td>
                                    <td><?php $qnty+=$invoice_items[$j]['quantity']; echo $invoice_items[$j]['quantity'] ?></td>
                                    <td><?php $adults+=$invoice_items[$j]['no_adults']; echo $invoice_items[$j]['no_adults'] ?></td>
                                    <td><?php $children+=$invoice_items[$j]['no_children']; echo $invoice_items[$j]['no_children'] ?></td>
                                    <td><?php $pax+=$invoice_items[$j]['pax']; echo $invoice_items[$j]['pax'] ?></td>
                                </tr>
                                <?php
                            }else{ ?>
                                <tr>
                                    <td></td>
                                    <td><?php echo $invoice_items[$j]['name'] ?></td>
                                    <td><?php $qnty+=$invoice_items[$j]['quantity']; echo $invoice_items[$j]['quantity'] ?></td>
                                    <td><?php $adults+=$invoice_items[$j]['no_adults']; echo $invoice_items[$j]['no_adults'] ?></td>
                                    <td><?php $children+=$invoice_items[$j]['no_children']; echo $invoice_items[$j]['no_children'] ?></td>
                                    <td><?php $pax+=$invoice_items[$j]['pax']; echo $invoice_items[$j]['pax'] ?></td>
                                </tr>
                            <?php } ?>
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
                                    <td><?php echo date('d/m/Y',strtotime($voucher->check_in)) ?></td>
                                    <td><?php echo $voucher->meal_plan ?></td>
                                    <td><input style="width: 10px;background-color: #0d0d0d"></td>
                                    <td><input style="width: 10px;background-color: #0d0d0d"></td>
                                    <td></td>
                                    <td><input style="width: 10px;background-color: #0d0d0d"></td>
                                    <td><input style="width: 10px;background-color: #0d0d0d"></td>
                                </tr>
                                <?php

                            } ?>

                            <?php if($svoucher->arrival_time==='BL'){?>
                                <tr>
                                    <td><?php echo date('d/m/Y',strtotime($voucher->check_in)) ?></td>
                                    <td><?php echo $voucher->meal_plan ?></td>
                                    <td></td>
                                    <td><input style="width: 10px;background-color: #0d0d0d"></td>
                                    <td></td>
                                    <td><input style="width: 10px;background-color: #0d0d0d"></td>
                                    <td><input style="width: 10px;background-color: #0d0d0d"></td>
                                </tr>
                                <?php

                            } ?>

                            <?php if($svoucher->arrival_time==='BPL'){?>
                                <tr>
                                    <td><?php echo date('d/m/Y',strtotime($voucher->check_in)) ?></td>
                                    <td><?php echo $voucher->meal_plan ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><input style="width: 10px;background-color: #0d0d0d"></td>
                                    <td><input style="width: 10px;background-color: #0d0d0d"></td>
                                </tr>
                                <?php

                            } ?>

                            <?php if($svoucher->arrival_time==='BD'){?>
                                <tr>
                                    <td><?php echo date('d/m/Y',strtotime($voucher->check_in)) ?></td>
                                    <td><?php echo $voucher->meal_plan ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><input style="width: 10px;background-color: #0d0d0d"></td>
                                    <td><input style="width: 10px;background-color: #0d0d0d"></td>
                                </tr>
                                <?php

                            } ?>

                            <?php

                        }else{ ?>
                            <?php if ($i == $daydifferebce){
                                $datetime2->modify('+1 day');

                                  if($svoucher->arrival_time==='BD'){
                                  
                                ?>

                                <tr>
                                    <td><?php echo $datetime2->format('d/m/Y'); ?></td>
                                    <td><?php echo $voucher->meal_plan ?></td>
                                    <td><input style="width: 10px;background-color: #0d0d0d"></td>
                                    <td><input style="width: 10px;background-color: #0d0d0d"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <?php
                                   }
                                  else{
                                  ?>

                                <tr>
                                    <td><?php echo $datetime2->format('d/m/Y'); ?></td>
                                    <td><?php echo $voucher->meal_plan ?></td>
                                    <td><input style="width: 10px;background-color: #0d0d0d"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <?php
                                  }
                            }else{ $datetime2 = $datetime2->modify('+1 day');
                                ?>

                                <tr>
                                    <td><?php echo $datetime2->format('d/m/Y'); ?></td>
                                    <td><?php echo $voucher->meal_plan ?></td>
                                    <td><input style="width: 10px;background-color: #0d0d0d"></td>
                                    <td><input style="width: 10px;background-color: #0d0d0d"></td>
                                    <td></td>
                                    <td><input style="width: 10px;background-color: #0d0d0d"></td>
                                    <td><input style="width: 10px;background-color: #0d0d0d"></td>
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

            <div class="row padding10">
                <div class="col-xs-12">
                    <?php
                    if ($svoucher->special_instructions || $svoucher->special_instructions != "") { ?>
                        <div class="well well-sm staff_note">
                            <p class="bold"><?= lang("SPECIAL INSTRUCTIONS"); ?>:</p>

                            <div><?= $this->sma->decode_html($svoucher->special_instructions); ?></div>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="row padding10">
                <div class="col-xs-12">
                    <p>Disclaimer : This voucher is for services as stated above only. All other expenses to be paid direct by clients</p>
                    <p>The above booking is subject to the standard terms and conditions of Enkorok Mara Camp Ltd and furthermore subject to our
                        contractual terms & conditions.</p>
                </div>
            </div>
           
        </div>
    </div>
</div>
</body>
</html>

