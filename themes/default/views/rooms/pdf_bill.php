<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->lang->line("Room Bill ") . " " . $room->id; ?></title>
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
            <h2>ROCY HOTEL</h2>
            <h2>BILL</h2>

            <div class="row">
                <div class="col-sm-6">
                    <p>Room Name: <?= $room->name; ?></p>

                    <p><?= lang("date"); ?>: <?= $this->sma->hrsd($current_date); ?>  Time: <?= date("h:i:sa"); ?></p>

                    <p><?= lang("Waiter Name"); ?>: <?= $user->first_name .' '. $user->last_name; ?></p>
                </div>
            </div>

                <h2><?= $settings->payment_name; ?> : <?= $settings->acc_no; ?></h2>
                <table>
                        <thead>
                            <tr>

                                <th style="padding-right: 10px">ITEM NAME</th>
                                <th style="padding-right: 10px">PRICE</th>
                                <th style="padding-right: 10px">QTY</th>
                                <th style="padding-right: 10px">AMOUNT</th>


                            </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($sale_items)) {
                            $total=0;
                            foreach ($sale_items as $sale_item) { ?>
                                <tr class="row<?= $sale_item->id ?>">

                                    <td style="padding-right: 10px"><?= $sale_item->product_name ?></td>
                                    <td style="padding-right: 10px"><?= $this->sma->formatMoney($sale_item->unit_price); ?></td>
                                    <td style="padding-right: 10px"><?= $this->sma->formatMoney($sale_item->quantity); ?></td>
                                    <td style="padding-right: 10px"><?= $this->sma->formatMoney($sale_item->subtotal); ?></td>


                                </tr>

                                <?php $total+=$sale_item->subtotal; $currency=$sale_item->currency; $vat = ($total *$settings->vat)/100;
                                $tourism_levy = ($total*$settings->tourism_fund)/100;
                                $service_charge = ($total*$settings->service_charge)/100;}
                        } else {
                            echo "<tr><td colspan='8'>" . lang('no_data_available') . "</td></tr>";
                        } ?>
                        </tbody>

                </table>
                <h2>TOTAL <?= $this->sma->formatMoney($total); ?> <?= $currency; ?></h2>
                <?php if ($settings->enable_tax) { ?>
                  <p>Vatable <?= $this->sma->formatMoney($total-$vat-$tourism_levy-$service_charge); ?></p>
            <p>VAT (<?= $settings->vat; ?>%) <?= $this->sma->formatMoney($vat); ?></p>
            <p>Service Charge (<?= $settings->service_charge; ?>%) <?= $this->sma->formatMoney($service_charge); ?></p>
            <p>Tourism Fund (<?= $settings->tourism_fund; ?>%) <?= $this->sma->formatMoney($tourism_levy); ?></p>
            <?php } ?>
         


        </div>
    </div>
</div>
</body>
</html>