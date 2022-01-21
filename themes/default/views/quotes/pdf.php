<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->lang->line("purchase") . " " . $inv->reference_no; ?></title>
    <link href="<?php echo $assets ?>styles/style.css" rel="stylesheet">
    <style type="text/css">
        html, body {
            height: 100%;
            background: #FFF;
			 font-size:12px;
        }
.smalltext{
	font-size:10px;
	font-style: italic;
}
        body:before, body:after {
            display: none !important;
        }
		.table_morecondensed>thead>tr>th, 
.table_morecondensed>tbody>tr>th, 
.table_morecondensed>tfoot>tr>th, 
.table_morecondensed>thead>tr>td, 
.table_morecondensed>tbody>tr>td, 
.table_morecondensed>tfoot>tr>td{ padding: 2px; 
 border-top: 1px solid #000;}
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
                <div class="text-center" style="margin-bottom:20px;">
                    <img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>"
                         alt="<?= $biller->company ?>">
                </div>
            <?php } ?>
            
            <div class="row padding10" style="display:none">
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
                        echo "<br>" . lang("scf6") . ": " . $customer->cf6;
                    }
                    echo "</p>";
                    echo lang("tel") . ": " . $customer->phone . "<br />" . lang("email") . ": " . $customer->email;
                    ?>

                </div>

            </div>
           
            <div class="row padding10" style="display:none">
                <div class="col-xs-5">
                    <h2 class=""><?= $Settings->site_name; ?></h2>
                    <?= $warehouse->name ?>

                    <?php
                    echo $warehouse->address . "<br>";
                    echo ($warehouse->phone ? lang("tel") . ": " . $warehouse->phone . "<br>" : '') . ($warehouse->email ? lang("email") . ": " . $warehouse->email : '');
                    ?>
                    <div class="clearfix"></div>
                </div>
                <div class="col-xs-5">
                    <div class="row bold">
                        <?= lang("date"); ?>: <?= date($dateFormats['php_ldate'], strtotime($inv->date)); ?><br>
                        <?= lang("ref"); ?>: <?= $inv->reference_no; ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
			
			<!--FROM HERE -->
			<div class="row padding10" >
                <div class="col-xs-10">
				<hr>
                    <h2 class="" style="text-align:center">WORKSHOP QUOTATION</h2>
                    <div class="row">
                        <?= lang("date"); ?>: <?= date($dateFormats['php_ldate'], strtotime($inv->date)); ?><br>
						
                        
                    </div>
					
                </div>
                <div class="col-xs-10">
				                    <h3 class=""><?= $customer->company ? $customer->company : $customer->name; ?></h3>
                    <?= $customer->company ? "" : "Attn: " . $customer->name ?>
                    <?php
                  //  echo $customer->address . "<br />" . $customer->city . " " . $customer->postal_code . " " . $customer->state . "<br />" . $customer->country;
                   
                    echo lang("tel") . ": " . $customer->phone . "<br />" . lang("email") . ": " . $customer->email;
                    ?>

                    <div class="clearfix"></div>
                </div>
            </div>
			<div class="row " >
                <div class="col-xs-12">
				
                   

                        <span  class="bold" ><?= lang("ATT"); ?></span> : <?= $inv->att; ?><br>
						<span  class="bold" ><b><?= lang("REF"); ?></span> : <?= $inv->ref; ?><br>
						<span  class="bold" ><b><?= lang("PAX"); ?></span>: <?= $inv->pax; ?> Pax<br>
						<span  class="bold" ><b><?= lang("DATE"); ?></span> : <?= date('d-M-Y',strtotime($inv->chkindate)); ?> To <?= date('d-M-Y',strtotime($inv->chkindate)); ?><br><br>
						<?php
                        echo "We thank you for the Inquiry and give you our best rates as below.";
						?>
						<br>
              
					
                </div>
                
            </div>

            <div class="clearfix"></div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped print-table order-table table_morecondensed">
                    <thead>
                    <tr>
                        <th><?= lang("no"); ?></th>
                        <th><?= lang("Item_Description"); ?> </th>
						<th><?= lang("unit_price"); ?></th>
                        <th><?= lang("quantity"); ?></th>
						<th><?= lang("Days"); ?></th>
                        <?php
                       // if ($Settings->tax1) {
                         //   echo '<th>' . lang("tax") . '</th>';
                       // }
                       // if ($Settings->product_discount) {
                        //    echo '<th>' . lang("discount") . '</th>';
                       // }
                        ?>
                        <th><?= lang("subtotal"); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $r = 1;
                    foreach ($rows as $row):
                        ?>
                        <tr>
                            <td style="text-align:center; width:40px; vertical-align:middle;"><?= $r; ?></td>
                            <td style="vertical-align:middle;"><?= $row->product_name; ?>
                               </td>
							<td style="text-align:right; width:100px;"><?= $this->sma->formatMoney($row->net_unit_price); ?></td>
						   <td style="width: 80px; text-align:center; vertical-align:middle;"><?= $this->sma->formatNumber($row->quantity); ?></td>
                            
                            <?php
                           // if ($Settings->tax1) {
                            //    echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' . ($row->item_tax != 0 && $row->tax_code ? '<small>(' . $row->tax_code . ')</small> ' : '') . $this->sma->formatMoney($row->item_tax) . '</td>';
                           // }
                           // if ($Settings->product_discount) {
                            //    echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' . ($row->discount != 0 ? '<small>(' . $row->discount . ')</small> ' : '') . $this->sma->formatMoney($row->item_discount) . '</td>';
                           // }
                            ?>
							<td style="text-align:right; width:120px;"><?= $row->days; ?></td>
                            <td style="text-align:right; width:120px;"><?= $this->sma->formatMoney($row->subtotal); ?></td>
                        </tr>
                        <?php
                        $r++;
                    endforeach;
                    ?>
                    </tbody>
                    <tfoot>
                    <?php
                    $col = 4;
                    if ($Settings->product_discount) {
                        $col++;
                    }
                    if ($Settings->tax1) {
                        $col++;
                    }
                    if ($Settings->product_discount && $Settings->tax1) {
                        $tcol = $col - 2;
                    } elseif ($Settings->product_discount) {
                        $tcol = $col - 1;
                    } elseif ($Settings->tax1) {
                        $tcol = $col - 1;
                    } else {
                        $tcol = $col;
                    }
                    ?>
                    <tr>
                        <td colspan="<?= $tcol; ?>" style="text-align:right;"><?= lang("total"); ?>
                            (<?= $default_currency->code; ?>)
                        </td>
                        <?php
                        if ($Settings->tax1) {
                            echo '<td style="text-align:right;">' . $this->sma->formatMoney($inv->product_tax) . '</td>';
                        }
                        if ($Settings->product_discount) {
                            echo '<td style="text-align:right;">' . $this->sma->formatMoney($inv->product_discount) . '</td>';
                        }
                        ?>
                        <td style="text-align:right;"><?= $this->sma->formatMoney($inv->total + $inv->product_tax); ?></td>
                    </tr>
                    <?php
                    if ($inv->order_discount != 0) {
                        echo '<tr><td colspan="' . $col . '" style="text-align:right;">' . lang("order_discount") . ' (' . $default_currency->code . ')</td><td style="text-align:right;">' . $this->sma->formatMoney($inv->order_discount) . '</td></tr>';
                    }
                    if ($Settings->tax2 && $inv->order_tax != 0) {
                        echo '<tr><td colspan="' . $col . '" style="text-align:right;">' . lang("order_tax") . ' (' . $default_currency->code . ')</td><td style="text-align:right;">' . $this->sma->formatMoney($inv->order_tax) . '</td></tr>';
                    }
                    if ($inv->shipping != 0) {
                        echo '<tr><td colspan="' . $col . '" style="text-align:right;">' . lang("shipping") . ' (' . $default_currency->code . ')</td><td style="text-align:right;">' . $this->sma->formatMoney($inv->shipping) . '</td></tr>';
                    }
                    ?>
                    <tr>
                        <td colspan="<?= $col; ?>"
                            style="text-align:right; font-weight:bold;"><?= lang("total_amount"); ?>
                            (<?= $default_currency->code; ?>)
                        </td>
                        <td style="text-align:right; font-weight:bold;"><?= $this->sma->formatMoney($inv->grand_total); ?></td>
                    </tr>

                    </tfoot>
                </table>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <?php if ($inv->note || $inv->note != "") { ?>
                        <div class="well well-sm" style="display:none">
                            <p class="bold"><?= lang("note"); ?>:</p>

                            <div><?= $this->sma->decode_html($inv->note); ?></div>
                        </div>
                    <?php } ?>
					<?php echo "For payments Please use the following Account Details:"; ?>:<br><br>
					<?php echo "Account Name:  "; ?><span  class="bold">Rocy Investments Limited</span><br>
					<?php echo "Account Number:  "; ?><span  class="bold">9030016175942</span><br>
					<?php echo "Branch:  "; ?><span  class="bold">Mbale</span><br>
					<?php echo "Bank:  "; ?><span  class="bold">Stanbic Bank</span><br>
					<?php echo "SWIFT CODE:  "; ?><span  class="bold">SBICUGKX</span><br>
					<?php echo "TIN NO:  "; ?><span  class="bold">1013580243</span><br><br>
                 <div class="clearfix"></div>
                <div class="col-xs-12  pull-left">
                    <p><?= "	Yours Sincerely" ?> </p> <br>

                    <?= "	Lucy Chemtai" ?> <br>

                   <?= "	General Manager ,Rocy Hotel" ?><br>
                  
                 
                </div>
				 
            </div>
			 <div class="col-xs-12">
<div class="clearfix"></div>
                <div class="col-xs-4  pull-right smalltext">
                    <?= "Plot 7,Bungokho Road" ?><br>
					<?= "P.O. Box 71, Mbale" ?><br>
					<?= "Call:0773237138" ?><br>
                   
                </div>
				  </div>
        </div>
    </div>
</div>
</body>
</html>