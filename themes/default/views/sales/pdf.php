<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->lang->line("Provisional Proforma Invoice") . " " . $inv->reference_no; ?></title>
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
                    Rocy Hotel.<br>
                    Plot 7, Bungokho Road, Mbale - Uganda<br>
                    T: 07060035522<br>
					E: rocyhotel@gmail.com<br> 
                    W: www.rocyhotel.com
                </div>
            </div>
            <div class="row padding10">
                <div class="col-xs-5 pull-left">
                    <p><b style="font-weight:bold;">Bill To</b> <?= $customer->company != '-' ? $customer->company : $customer->name; ?></p>
                </div>
                <div class="col-xs-5 pull-right" style="text-align: right;">
                    <?= lang("ref"); ?>: <?= $inv->reference_no; ?><br>
                    <?= lang("Checkin_Date"); ?>: <?= $this->sma->hrld($inv->chkindate); ?><br>
                    Check_Out <?= lang("date"); ?>: <?= $this->sma->hrld($inv->chkoutdate); ?><br>
                    <?php
                    $datetime1 = new DateTime($voucher->check_in);

                    $datetime2 = new DateTime($voucher->check_out);

                    $daydifferebce = $datetime1->diff($datetime2)->format('%a');
                    $datetime = new DateTime($voucher->check_in);
                    ?>

                    <div class="clearfix"></div>
                </div>
            </div>

            <div class="row" style="margin-left: 2px; margin-right: 2px;">
            
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
                            <td><?php echo $qnty; ?></td>
                            <td><?php echo $pax; ?></td>
                            <td></td>
                            <td><?php echo $amount; ?></td>
                        </tr>
                        <?php

                    } ?>

                    </tbody>

                    <tfoot>
                    <tr>
                        <td><b style="font-weight:bold;">TOTAL AMOUNT IN <?php echo $inv->currency?></b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><b style="font-weight:bold;"><?php echo $total = $amount*$daydifferebce; ?></b></td>
                    </tr>
                    </tfoot>

                </table>
                
            </div>

            <div class="row padding10">
                <div class="col-xs-12">
                    <ol style="margin-left: -20px">
                        <li>Payment is due as per our Contractual terms and Conditions.</li>
                        <li><p>Please make all cheques payable to <b style="font-weight:bold;"><?php echo $biller->acc_name; ?></b></p></li>
                    </ol>
                    <div class="row">
                        <div class="col-xs-4 pull-left">
                            <h2>BANK DETAILS</h2>
                            <p>Account Name <?php echo $biller->acc_name; ?></p>
                            <p>Bank Name <?php echo $biller->bank_name; ?></p>
                            <p>Branch Name <?php echo $biller->branch; ?></p>
                            <h4 class="bold">UGX A/C No <?php echo $biller->ksh_acc_no; ?></h4>
                           
                        </div>
                        <div class="col-xs-4 pull-right" style="text-align: right;">
                            <h2>Mobile Money</h2>
                            <p>Airtel No <?php echo $biller->mpesa_business_no; ?></p>
                            <p>MTN No <?php echo $biller->mpesa_acc_no; ?></p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</body>
</html>