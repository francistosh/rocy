<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->lang->line("receipt") . " " . $payment->reference_no; ?></title>
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

            <?php if ($logo) { ?>
                <!--<div class="text-center" style="margin-bottom:20px;">-->
                <img src="assets/uploads/logos/logo.png"
                     alt="<?= $biller->company != '-' ? $biller->company : $biller->name; ?>">
                <!--</div>-->
            <?php } ?>
            <div class="clearfix"></div>
            <div class="row padding10">
                <div class="col-xs-5">
                    <h2 class=""><?= $biller->company != '-' ? $biller->company : $biller->name; ?></h2>
                    <?= $biller->company ? "" : "Attn: " . $biller->name ?>
                    <?php
                    echo $biller->address . "<br />" . $biller->city . " " . $biller->postal_code . " " . $biller->state . "<br />" . $biller->country;
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
                    echo lang("tel") . ": " . $biller->phone . "<br />" . lang("email") . ": " . $biller->email;
                    ?>
                    <div class="clearfix"></div>
                </div>
                <div class="col-xs-5">
                    <h2 class=""><?= $customer->company ? $customer->company : $customer->name; ?></h2>
                    <?= $customer->company ? "" : "Attn: " . $customer->name ?>
                    <?php
                    echo $customer->address . "<br />" . $customer->city . " " . $customer->postal_code . " " . $customer->state . "<br />" . $customer->country;
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
                    echo lang("tel") . ": " . $customer->phone . "<br />" . lang("email") . ": " . $customer->email;
                    ?>

                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <p style="font-weight:bold;"><?= lang("date"); ?>: <?= $this->sma->hrsd($payment->date); ?></p>

                    <p style="font-weight:bold;"><?= lang("payment_reference"); ?>: <?= $payment->reference_no; ?></p>
                </div>
            </div>
            <div class="well">
                <table class="table table-bordered" style="margin-bottom:0;">
                    <thead>
                    <tr>
                        <td>Guest/Client Name</td>
                        <td>Amount</td>
                        <td>Current Balance</td>
                        <td>Currency</td>
                        <td>Payment Method</td>
                        <td>Payment Note</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <strong><?php echo $voucher->group_name;?></strong>
                        </td>
                        <td>
                            <strong><?php echo $this->sma->formatMoney($payment->amount); ?></strong>
                        </td>
                        <td>
                            <strong><?php echo $this->sma->formatMoney($inv->grand_total-$inv->paid); ?></strong>
                        </td>
                        <td>
                            <strong><?php echo $payment->currency;?></strong>
                        </td>
                        <td><strong><?php echo lang($payment->paid_by);
                                if ($payment->paid_by == 'gift_card' || $payment->paid_by == 'CC') {
                                    echo ' (' . $payment->cc_no . ')';
                                } elseif ($payment->paid_by == 'Cheque') {
                                    echo ' (' . $payment->cheque_no . ')';
                                }
                                ?></strong>
                        </td>
                        <td><?php echo $payment->note; ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div style="clear: both;"></div>
            <div class="row">
                <div class="col-sm-4 pull-left">
                    <p>&nbsp;</p>

                    <p>&nbsp;</p>

                    <p>&nbsp;</p>

                    <p style="border-bottom: 1px solid #666;">&nbsp;</p>

                    <p><?= lang("stamp_sign"); ?></p>
                </div>
            </div>
            <div class="clearfix"></div>
         


        </div>
    </div>
</div>
</body>
</html>