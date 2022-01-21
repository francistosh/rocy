<?php
function product_name($name)
{
    return character_limiter($name, (isset($pos_settings->char_per_line) ? ($pos_settings->char_per_line-8) : 35));
}
//print_r($inv);
//die('WOO');
if ($modal) {
    echo '<div class="modal-dialog no-modal-header"><div class="modal-content"><div class="modal-body"><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i></button>';
} else { ?>
    <!doctype html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>SUMMARY Report</title>
        <base href="<?= base_url() ?>"/>
        <meta http-equiv="cache-control" content="max-age=0"/>
        <meta http-equiv="cache-control" content="no-cache"/>
        <meta http-equiv="expires" content="0"/>
        <meta http-equiv="pragma" content="no-cache"/>
        <link rel="shortcut icon" href="<?= $assets ?>images/icon.png"/>
        <link rel="stylesheet" href="<?= $assets ?>styles/theme.css" type="text/css"/>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

        <style type="text/css" media="all">
            body {
                color: #000;
                font-size:13px;
            }

            #wrapper {
                max-width: 1000px;
                margin: 0 auto;
                padding-top: 20px;
            }

            .btn {
                border-radius: 0;
                margin-bottom: 5px;
            }

            h3 {
                margin: 5px 0;
            }

            @media print {
                .no-print {
                    display: none;
                }

                #wrapper {
                    max-width: 1000px;
                    width: 100%;
                    min-width: 250px;
                    margin: 0 auto;
                }
                .page-break  {page-break-after: always; }
/*                footer {page-break-after: always;}*/
            }
            
          
        </style>

    </head>

    <body>

<?php } ?>
<div id="wrapper">
    <div id="receiptData">
    <div class="no-print">
        <?php if ($message) { ?>
            <div class="alert alert-success">
                <button data-dismiss="alert" class="close" type="button">×</button>
                <?= is_array($message) ? print_r($message, true) : $message; ?>
            </div>
        <?php } ?>
    </div>
   	    <div id="receipt-data" <?php echo $display;?>>
        <div class="text-center">
          <!--    <img src="<?//= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>" alt="<?//= $biller->company; ?>">
<!--            <h3 style="text-transform:uppercase;"><b>$inv->id$biller->company != '-' ? $biller->company : $biller->name; ?></b></h3>-->
             <h5 style="text-transform:uppercase;"><b><?= $biller->company != '-' ? $biller->company : $biller->name; ?></b></h5>
 <h4 style="text-transform:uppercase;"><b> ROCY HOTEL MBALE </b></h4>
 <h4 ><b> SUMMARY SHEET </b></h4>
 
            <?php
           //echo "<br>" . lang("tel") . ": " . $biller->phone . "<br>";
            ?>
            <?php
          
            echo '</p></div>';
			 echo '-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------<br>';
				//echo '<hr style="height:1px;border:none;color:#333;background-color:#333;">';
            if ($Settings->invoice_view == 1) { ?>
                <div class="col-sm-12 text-center">
                    <h4 style="font-weight:bold;"><?= lang('tax_invoice'); ?></h4>
                </div>
            <?php }
			//echo  $warehousename->name . " <br>";
           // echo "<p>" . lang("reference_no") . ": " . $inv->reference_no . "<br>";
           // echo lang("customer") . ": " . $inv->customer . "   -    "."No of Customer(s) : ". $inv->count_cust.  "<br>";
           // echo "Cashier" . ": " . $inv->cashier . "</br>";
           // echo "Chef" . ": " . $inv->chef . "</br>";
            echo "<b> Between  ".lang("date") . ": " . $this->sma->hrld($fromdate) . "  AND   " . $this->sma->hrld($todate) . "</p></b>";
           
            ?>
            <div style="clear:both;"></div>
            <table class="table table-striped table-condensed" border="1">
                <tbody>
				<tr><th style="text-align:center" >No</th>
				<th style="text-align:center">Particulars</th>
				<th align="centre" width="150px">Name</th>
				<th align="centre">Total Sales</th>
				<th align="centre">Credit Sales</th>
				<th align="centre">Cash Sales</th>
				<th align="centre">Mobile Money</th>
				<th align="centre">Credit Card</th>
				<th align="centre">Debt Payment</th>
				<th align="centre">Deposit</th>
				<th align="centre">Total</th>
				</tr>
            <tr>
			<?php 
			 //reception cash sales
			 $recpcsalesamt=0; 
				foreach($receptioncashsales as $recpcsales)
				{ $recpcsalesamt = $recpcsales->amnt; } 
				//reception mobile money
			$recpmbilesalesamt=0; $recpmbilersalesamt=0; $recpmbilersalesamtcs=0;
				foreach($receptionmpesasales as $recpmsales)
				{ $recpmbilesalesamt = $recpmsales->amnt; } 
				foreach($receptionroommpesasales as $recpmrsales)
				{ $recpmbilersalesamt = $recpmrsales->amnt; } 
				foreach($receptionmpesasalescs as $recpmrsalescs)
				{ $recpmbilersalesamtcs = $recpmrsalescs->amnt; } 
				
			//reception CC 
			$recpccbilesalesamt=0; $recpccbilersalesamt=0; $recpccbilersalesamtcs=0;
				foreach($receptionccsales as $recpccsales)
				{ $recpccbilesalesamt = $recpccsales->amnt; } 
				foreach($receptionroomccsales as $recpccrsales)
				{ $recpccbilersalesamt = $recpccrsales->amnt; }
				foreach($receptionccsalescs as $recpccrsalescs)
				{ $recpccbilersalesamtcs = $recpccrsalescs->amnt; } 
				
				
				$recpcreditsales = 0;
				
				
				
			?>
			<tr><td style="text-align:center" >1</td>
				<td style="text-align:center">Reception</td>
				<td style="text-align:center"> </td>
				<td  class="text-right"><?php $receptionsalesamnt =0; foreach($receptionsales as $recpsales){ $receptionsalesamnt=$recpsales->totalsales; echo number_format($recpsales->totalsales); } ?></td>
				<td align="centre" class="text-right"><?php  $recpcreditsales = $receptionsalesamnt- ($recpcsalesamt+$recpmbilersalesamtcs+$recpccbilersalesamtcs); echo number_format($recpcreditsales);  ?></td>
				<td class="text-right"><?php
				
				
				echo number_format($recpcsalesamt);
				?>
				</td>
				<td class="text-right"><?php
				 
				echo number_format($recpmbilersalesamtcs);
				?></td>
				<td class="text-right"><?php
			
				echo number_format($recpccbilersalesamtcs);
				?></td>
				<td class="text-right">
				<a data-toggle="modal" href="#recptiiondebtdiv">
				<?php
				 $receptiondebtpayamt=0; 
				foreach($receptiondebtpaymnt as $receptiondebtpay)
				{ $receptiondebtpayamt = $receptiondebtpay->amnt; } 
				
				echo number_format($receptiondebtpayamt);
				?>
				</a>
				</td>
				<td class="text-right"> </td>
				<td class="text-right"> </td>
			</tr>
			<?php
			//barcashsales
				$barcashsalesamnt=0;
				foreach($barcashsales as $bacsales){ 
				 $barcashsalesamnt = $bacsales->amnt; }
				 //barmpesa
				$barmpesasalesamnt =0; 
				 foreach($barmpesasales as $rstmsales){ 
				$barmpesasalesamnt = $rstmsales->amnt; }
				//barCC
				$barccsalesamt =0;
				foreach($barccsales as $rstccsales){ 
				$barccsalesamt = $rstccsales->amnt; }
				
				//debtpayment
				$recpcrsalesamt=0;
				foreach($receptionroombarcashsales as $recpcrsales)
				{ $recpcrsalesamt = $recpcrsales->amnt; 
				} 
			?>
			
			<tr><td style="text-align:center" >2</td>
				<td style="text-align:center">Bar</td>
				<td style="text-align:center"> </td>
				<td class="text-right"><?php foreach($barsales as $basales){ $barsalesamnt = $basales->amnt; echo number_format($basales->amnt); } ?></td>
				<td class="text-right"><?php $barcredit = $barsalesamnt -($barcashsalesamnt+$barmpesasalesamnt+$barccsalesamt); echo number_format($barcredit); ?></td>
				<td class="text-right"><?php 
				
				echo number_format($barcashsalesamnt)
				?>
				</td>
				<td class="text-right">
				<?php  
				echo number_format($barmpesasalesamnt); 
				
				
				?></td>
				<td class="text-right"><?php 
				echo number_format($barccsalesamt); 
				
				
				?></td>
				<td class="text-right">
					<?php 
				echo number_format($recpcrsalesamt); 
				?> 
				</td>
				<td class="text-right"> </td>
				<td class="text-right"> </td>
			</tr>
			<?php
			//total Restaurant Sales
			$restsalesamnt =0;
			foreach($restsales as $resales){ $restsalesamnt= $resales->amnt; }
			
			?>
			<tr><td style="text-align:center" >3</td>
				<td style="text-align:center">Restaurant</td>
				<td style="text-align:center"> </td>
				<td class="text-right"><?php  echo number_format($restsalesamnt);  ?></td>
				<td class="text-right"><?php foreach($restcostcentersales as $restcostcentersale){ echo number_format($restcostcentersale->amnt); } ?></td>
				<td class="text-right"><?php 
				$restcashsalesamt=0;
				foreach($restcashsales as $recsales){
				$restcashsalesamt = $recsales->amnt;					
				echo number_format($recsales->amnt); }
				
				
				?></td>
				<td class="text-right"><?php 
				$restmpesasalesamt=0;
				foreach($restmpesasales as $rempsales){ 
				$restmpesasalesamt = $rempsales->amnt;
				echo number_format($rempsales->amnt); }
				
				
				?></td>
				<td class="text-right"><?php 
				$restccsalesamt=0;
				foreach($restccsales as $reccsales){
					$restccsalesamt =	$reccsales->amnt;
				echo number_format($restccsalesamt); }
				
				
				?></td>
				<td class="text-right">
					<?php 
				$restdebtamnt2=0;
				foreach($receptionroomrestcashsales as $recpcrsales2)
				{ $restdebtamnt2 = $recpcrsales2->amnt; 
				echo number_format($restdebtamnt2);} 
				?> </td>
				<td class="text-right"> </td>
				<td class="text-right"> </td>
			</tr>
			<?php
			$servicecashsalesamt =0;
				foreach($servicecashsales as $servicecsales){
						$servicecashsalesamt = $servicecsales->amnt;
				 }
				 
			$servicempesasalesamt=0;
				foreach($servicempesasales as $servicempsales){ 
				$servicempesasalesamt = $servicempsales->amnt;
				 }
				 
			$serviceccsalesamt =0;
				foreach($serviceccsales as $servccsales){ 
				$serviceccsalesamt = $servccsales->amnt;
			 }
					 
				
			?>
						<tr><td style="text-align:center" >4</td>
				<td style="text-align:center">Delivery/Room Service</td>
				<td style="text-align:center"> </td>
				<td class="text-right"><?php $servicesalesamnt=0; foreach($servicesales as $servsales){ $servicesalesamnt = $servsales->amnt; echo number_format($servicesalesamnt); } ?></td>
				<td class="text-right"><?php $creditservice = $servicesalesamnt -($servicecashsalesamt+$servicempesasalesamt+$serviceccsalesamt); echo number_format($creditservice); ?></td>
				<td class="text-right"><?php 
				
				echo number_format($servicecashsalesamt);
				
				
				?></td>
				<td class="text-right"><?php 
				
				echo number_format($servicempesasalesamt); 
				
				
				?></td>
				<td class="text-right"><?php 
			
				echo number_format($serviceccsalesamt); 
				
				
				?></td>
				<td class="text-right">
					
					<?php 
					$receptionroomservicecashsalesamt =0;
				foreach($receptionroomservicecashsales as $servcashrecpsales){ 
				$receptionroomservicecashsalesamt = $servcashrecpsales->amnt;
				echo number_format($servcashrecpsales->amnt); }
				
				
				?>
				</td>
				<td class="text-right"> </td>
				<td class="text-right"> </td>
			</tr>
			<tr><td style="text-align:center" >5</td>
				<td style="text-align:center">Printing</td>
				<td style="text-align:center"> </td>
				<td class="text-right"> </td>
				<td class="text-right"> </td>
				<td class="text-right"> </td>
				<td class="text-right"> </td>
				<td class="text-right"> </td>
				<td class="text-right"> </td>
				<td class="text-right"> </td>
				<td class="text-right"> </td>
			</tr>
			<tr><td style="text-align:center" >6</td>
				<td style="text-align:center">Laundry</td>
				<td style="text-align:center"> </td>
				<td align="centre"> </td>
				<td align="centre"> </td>
				<td align="centre"> </td>
				<td align="centre"> </td>
				<td align="centre"> </td>
				<td align="centre"> </td>
				<td align="centre"> </td>
				<td align="centre"> </td>
			</tr>
			<tr><td style="text-align:center" >7</td>
				<td style="text-align:center">Room Service</td>
				<td style="text-align:center"> </td>
				<td align="centre"> </td>
				<td align="centre"> </td>
				<td align="centre"> </td>
				<td align="centre"> </td>
				<td align="centre"> </td>
				<td align="centre"> </td>
				<td align="centre"> </td>
				<td align="centre"> </td>
			</tr>
			<tr><td style="text-align:center" >8</td>
				<td style="text-align:center">Hotel Service</td>
				<td style="text-align:center"> </td>
				<td align="centre"> </td>
				<td align="centre"> </td>
				<td align="centre"> </td>
				<td align="centre"> </td>
				<td align="centre"> </td>
				<td align="centre"> </td>
				<td align="centre"> </td>
				<td align="centre"> </td>
			</tr>
			<tr style="border-top: 2px solid #000;"><td style="text-align:center" ></td>
				<td style="text-align:center"></td>
				<td style="text-align:center"> </td>
				<td class="text-right"><?php echo number_format($receptionsalesamnt+$barsalesamnt+$restsalesamnt+$servicesalesamnt);  ?> </td>
				<td align="centre"> </td>
				<td class="text-right"><?php echo number_format($recpcsalesamt+$barcashsalesamnt+$restcashsalesamt+$servicecashsalesamt) ;  ?> </td>
				<td class="text-right"><?php echo number_format($recpmbilersalesamtcs+$barmpesasalesamnt+$restmpesasalesamt+$servicempesasalesamt) ;  ?>  </td>
				<td class="text-right"><?php echo number_format($recpccbilersalesamtcs+$barccsalesamt+$restccsalesamt+$serviceccsalesamt) ;  ?>  </td>
				<td class="text-right"><?php echo number_format($receptiondebtpayamt+$recpcrsalesamt+$restdebtamnt2+$serviceccsalesamt) ;  ?>  </td>
				<td align="centre"> </td>
				<td align="centre"> </td>
			</tr>
				</tbody>
                <tfoot>
                
                  
                <?php
                //if ($inv->product_tax != 0) {
	
                   // echo '<tr><th>' . lang("Sub Total") . '</th><th class="text-right">' . $this->sma->formatMoney($inv->grand_total- $inv->product_tax - (0.02 * $inv->grand_total) ) . '</th></tr>';
                  //  echo '<tr><td colspan="4">' . lang("VAT 18%") . '</td><td class="text-right">' . $this->sma->formatMoney($inv->grand_total*0.18) . '</td></tr>';
                   // echo '<tr><td>' . lang("Catering Levy 2%") . '</td><td class="text-right">' . $this->sma->formatMoney(0.02 * $inv->grand_total) . '</td></tr>';
               

				//}
                if ($inv->order_discount != 0) {
                    echo '<tr><th  colspan="4">' . lang("order_discount") . '</th><th class="text-right">'.$this->sma->formatMoney($inv->order_discount) . '</th></tr>';
                }
                
                if ($pos_settings->rounding) { 
                    $round_total = $this->sma->roundNumber($inv->grand_total, $pos_settings->rounding);
                    $rounding = $this->sma->formatMoney($round_total - $inv->grand_total);
                ?>
                    <tr>
                        <th><?= lang("rounding"); ?></th>
                        <th class="text-right"><?= $rounding; ?></th>
                    </tr>
                   
                <?php } else { ?>
                    
                <?php }
                if ($inv->paid < $inv->grand_total) { ?>
                
                    <tr>
                        <th colspan="4"><?= lang("due_amount"); ?></th>
                        <th class="text-right"><?= $this->sma->formatMoney($inv->grand_total - $inv->paid); ?></th>
                    </tr>

                <?php }
  //echo '<p>You were served by:'.$soldby->first_name.'&nbsp;</p>';
   echo 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx<br>';
			
                 ?>
               <!--   <tr>
                        <th><?= "<b>Total Paid</b>"; ?></th>
                        <th class="text-right"><?= $this->sma->formatMoney($inv->bill_change+$inv->paid); ?></th>
                  </tr>-->
                 
                </tfoot>
            </table>
			<div class="well well-sm" style="text-align: center">
               
                <br></br>
                <?php
				echo "<b>Printed on: ".date('d-m-Y H:i:s')." by  ". $users->first_name ;
				echo "</b><br><br><< THANK YOU >>";
		?>
				
            </div>
      
        </div>
		<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      ...
    </div>
  </div>
</div>
			<div class="modal fade" id="recptiiondebtdiv" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Reception Debt Payment</h4>
		<br>
		 <table class="table table-striped table-condensed" border="1">
                <tbody>
				<tr><th style="text-align:center" >No</th>
				<th style="text-align:center">Date</th>
				<th align="centre" >Paid_By</th>
				<th align="centre">Created By</th>
				<th align="centre">Sale Id</th>
				<th align="centre">Reference No</th>
				<th align="centre">Amount</th>
				
							</tr>
							<?php 
							$totaldbtamt =0;
				foreach($receptiondebtpaymnt_details as $receptiondebtpaymnt_detail)
				{ $totaldbtamt = $totaldbtamt +$receptiondebtpaymnt_detail->amnt; ?>
				<tr>
				<td></td>
				<td><?= date('d-m-Y H:i:s',strtotime($receptiondebtpaymnt_detail->ddate)); ?></td>
				<td><?= $receptiondebtpaymnt_detail->paid_by; ?></td>
				<td><?= $receptiondebtpaymnt_detail->created_by; ?></td>
				<td><?= $receptiondebtpaymnt_detail->sale_id; ?></td>
				<td><?= $receptiondebtpaymnt_detail->reference_no; ?></td>
				<td class="text-right"><?= number_format($receptiondebtpaymnt_detail->amnt); ?></td>
				
				
				</tr>
				<?php
				} 			
							?>
					<tr style="border-top: 2px solid #000;"><td></td><td></td><td></td><td></td><td colspan='2'> Total</td>
					<td class="text-right"><?= number_format($totaldbtamt); ?></td></tr>		
				</tbody>
				</table>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	    <form role="form">
 <div class="box-body">
				
				

</div>
<div class="box-footer">
                
			
              </div>
</form>
            </div>
     
    </div>
  </div>
</div>
        <div style="clear:both;"></div>
    </div>  
	
<?php if ($modal) {
    echo '</div></div></div></div>';
} else { ?>
<div id="buttons" style="padding-top:10px; text-transform:uppercase;" class="no-print">
    <hr>
    <?php if ($message) { ?>
    <div class="alert alert-success">
        <button data-dismiss="alert" class="close" type="button">×</button>
        <?= is_array($message) ? print_r($message, true) : $message; ?>
    </div>
<?php } ?>

    <?php if ($pos_settings->java_applet) { ?>
        <span class="col-xs-12"><a class="btn btn-block btn-primary" onClick="printReceipt()"><?= lang("print"); ?></a></span>
        <span class="col-xs-12"><a class="btn btn-block btn-info" type="button" onClick="openCashDrawer()">Open Cash
                Drawer</a></span>
        <div style="clear:both;"></div>
    <?php } else { ?>
        <span class="pull-right col-xs-12">
        <a href="javascript:window.print()" id="web_print" class="btn btn-block btn-primary"
           onClick="window.print();return false;"><?= lang("web_print"); ?></a>
    </span>
    <?php } ?>
   
    <?php if (!$pos_settings->java_applet) { ?>
        <div style="clear:both;"></div>
        <div class="col-xs-12" style="background:#F5F5F5; padding:10px;">
            <p style="font-weight:bold;">Please don't forget to disable the header and footer in browser print
                settings.</p>

            <p style="text-transform: capitalize;"><strong>FF:</strong> File &gt; Print Setup &gt; Margin &amp;
                Header/Footer Make all --blank--</p>

            <p style="text-transform: capitalize;"><strong>chrome:</strong> Menu &gt; Print &gt; Disable Header/Footer
                in Option &amp; Set Margins to None</p></div>
    <?php } ?>
    <div style="clear:both;"></div>

</div>

</div>
<canvas id="hidden_screenshot" style="display:none;">

</canvas>
<div class="canvas_con" style="display:none;"></div>
<script type="text/javascript" src="<?= $assets ?>pos/js/jquery-1.7.2.min.js"></script>
<?php if ($pos_settings->java_applet) {
        function drawLine()
        {
            $size = $pos_settings->char_per_line;
            $new = '';
            for ($i = 1; $i < $size; $i++) {
                $new .= '-';
            }
            $new .= ' ';
            return $new;
        }

        function printLine($str, $sep = ":", $space = NULL)
        {
            $size = $space ? $space : $pos_settings->char_per_line;
            $lenght = strlen($str);
            list($first, $second) = explode(":", $str, 2);
            $new = $first . ($sep == ":" ? $sep : '');
            for ($i = 1; $i < ($size - $lenght); $i++) {
                $new .= ' ';
            }
            $new .= ($sep != ":" ? $sep : '') . $second;
            return $new;
        }

        function printText($text)
        {
            $size = $pos_settings->char_per_line;
            $new = wordwrap($text, $size, "\\n");
            return $new;
        }

        function taxLine($name, $code, $qty, $amt, $tax)
        {
            return printLine(printLine(printLine(printLine($name . ':' . $code, '', 18) . ':' . $qty, '', 25) . ':' . $amt, '', 35) . ':' . $tax, ' ');
        }

        ?>

        <script type="text/javascript" src="<?= $assets ?>pos/qz/js/deployJava.js"></script>
        <script type="text/javascript" src="<?= $assets ?>pos/qz/qz-functions.js"></script>
        <script type="text/javascript">
            deployQZ('themes/<?=$Settings->theme?>/assets/pos/qz/qz-print.jar', '<?= $assets ?>pos/qz/qz-print_jnlp.jnlp');
            usePrinter("<?= $pos_settings->receipt_printer; ?>");
            <?php /*$image = $this->sma->save_barcode($inv->reference_no);*/ ?>
            function printReceipt() {
                //var barcode = 'data:image/png;base64,<?php /*echo $image;*/ ?>';
                receipt = "";
                receipt += chr(27) + chr(69) + "\r" + chr(27) + "\x61" + "\x31\r";
                receipt += "<?= $biller->company; ?>" + "\n";
                receipt += " \x1B\x45\x0A\r ";
                receipt += "<?= $biller->address . " " . $biller->city . " " . $biller->country; ?>" + "\n";
                receipt += "<?= $biller->phone; ?>" + "\n";
                receipt += "<?php if ($pos_settings->cf_title1 != "" && $pos_settings->cf_value1 != "") { echo printLine($pos_settings->cf_title1 . ": " . $pos_settings->cf_value1); } ?>" + "\n";
                receipt += "<?php if ($pos_settings->cf_title2 != "" && $pos_settings->cf_value2 != "") { echo printLine($pos_settings->cf_title2 . ": " . $pos_settings->cf_value2); } ?>" + "\n";
                receipt += "<?=drawLine();?>\r\n";
                receipt += "<?php if($Settings->invoice_view == 1) { echo lang('tax_invoice'); } ?>\r\n";
                receipt += "<?php if($Settings->invoice_view == 1) { echo drawLine(); } ?>\r\n";
                receipt += "\x1B\x61\x30";
                receipt += "<?= printLine(lang("reference_no") . ": " . $inv->reference_no) ?>" + "\n";
                receipt += "<?= printLine(lang("sales_person") . ": " . $biller->name); ?>" + "\n";
                receipt += "<?= printLine("Chef" . ": " . $inv->chef); ?>" + "\n";
                receipt += "<?= printLine("Cashier". ": " . $inv->cashier); ?>" + "\n";
                receipt += "<?= printLine(lang("customer") . ": " . $inv->customer); ?>" + "\n";
                receipt += "<?= printLine(lang("date") . ": " . date($dateFormats['php_ldate'], strtotime($inv->date))) ?>" + "\n\n";
                receipt += "<?php $r = 1;
            foreach ($rows as $row): ?>";
                receipt += "<?= "#" . $r ." "; ?>";
                receipt += "<?= printLine(product_name(addslashes($row->product_name)).($row->variant ? ' ('.$row->variant.')' : '').":".$row->tax_code, '*'); ?>" + "\n";
                receipt += "<?= printLine($this->sma->formatNumber($row->quantity)."x".$this->sma->formatMoney($row->net_unit_price+($row->item_tax/$row->quantity)) . ":  ". $this->sma->formatMoney($row->subtotal), ' ') . ""; ?>" + "\n";
                receipt += "<?php $r++;
            endforeach; ?>";
                receipt += "\x1B\x61\x31";
                receipt += "<?=drawLine();?>\r\n";
                receipt += "\x1B\x61\x30";
                receipt += "<?= printLine(lang("total") . ": " . $this->sma->formatMoney($inv->total+$inv->product_tax)); ?>" + "\n";
                <?php if ($inv->order_tax != 0) { ?>
                receipt += "<?= printLine(lang("tax") . ": " . $this->sma->formatMoney($inv->order_tax)); ?>" + "\n";
                <?php } ?>
                <?php if ($inv->total_discount != 0) { ?>
                receipt += "<?= printLine(lang("discount") . ": (" . $this->sma->formatMoney($inv->product_discount).") ".$this->sma->formatMoney($inv->order_discount)); ?>" + "\n";
                <?php } ?>
                <?php if($pos_settings->rounding) { ?>
                receipt += "<?= printLine(lang("rounding") . ": " . $rounding); ?>" + "\n";
                receipt += "<?= printLine(lang("grand_total") . ": " . $this->sma->formatMoney($this->sma->roundMoney($inv->grand_total+$rounding))); ?>" + "\n";
                <?php } else { ?>
                receipt += "<?= printLine(lang("grand_total") . ": " . $this->sma->formatMoney($inv->grand_total)); ?>" + "\n";
                <?php } ?>
                <?php if($inv->paid < $inv->grand_total) { ?>
                receipt += "<?= printLine(lang("paid_amount") . ": " . $this->sma->formatMoney($inv->paid)); ?>" + "\n";
                receipt += "<?= printLine(lang("due_amount") . ": " . $this->sma->formatMoney($inv->grand_total-$inv->paid)); ?>" + "\n\n";
                <?php 
  echo '<p>You were served by:'.$soldby->first_name.'&nbsp;'.$soldby->last_name.'</p>';
            } 

                ?>

                <?php
                if($payments) {
                    foreach($payments as $payment) {
                        if ($payment->paid_by == 'cash' && $payment->pos_paid) { ?>
                receipt += "<?= printLine(lang("paid_by") . ": " . lang($payment->paid_by)); ?>" + "\n";
                receipt += "<?= printLine(lang("amount") . ": " . $this->sma->formatMoney($payment->pos_paid)); ?>" + "\n";
                receipt += "<?= printLine(lang("change") . ": " . ($payment->pos_balance > 0 ? $this->sma->formatMoney($payment->pos_balance) : 0)); ?>" + "\n";
                <?php  } if (($payment->paid_by == 'CC' || $payment->paid_by == 'ppp' || $payment->paid_by == 'stripe') && $payment->cc_no) { ?>
                receipt += "<?= printLine(lang("paid_by") . ": " . lang($payment->paid_by)); ?>" + "\n";
                receipt += "<?= printLine(lang("amount") . ": " . $this->sma->formatMoney($payment->pos_paid)); ?>" + "\n";
                receipt += "<?= printLine(lang("card_no") . ": xxxx xxxx xxxx " . substr($payment->cc_no, -4)); ?>" + "\n";
                <?php } if ($payment->paid_by == 'Cheque' && $payment->cheque_no) { ?>
                receipt += "<?= printLine(lang("paid_by") . ": " . lang($payment->paid_by)); ?>" + "\n";
                receipt += "<?= printLine(lang("amount") . ": " . $this->sma->formatMoney($payment->pos_paid)); ?>" + "\n";
                receipt += "<?= printLine(lang("cheque_no") . ": " . $payment->cheque_no); ?>" + "\n";
                <?php if ($payment->paid_by == 'other' && $payment->amount) { ?>
                receipt += "<?= printLine(lang("paid_by") . ": " . lang($payment->paid_by)); ?>" + "\n";
                receipt += "<?= printLine(lang("amount") . ": " . $this->sma->formatMoney($payment->amount)); ?>" + "\n";
                receipt += "<?= printText(lang("payment_note") . ": " . $payment->note); ?>" + "\n";
                <?php }
            }

        }
    }

    if($Settings->invoice_view == 1) {
        if(!empty($tax_summary)) {
    ?>
                receipt += "\n" + "<?= lang('tax_summary'); ?>" + "\n";
                receipt += "<?= taxLine(lang('name'),lang('code'),lang('qty'),lang('tax_excl'),lang('tax_amt')); ?>" + "\n";
                receipt += "<?php foreach ($tax_summary as $summary): ?>";
                receipt += "<?= taxLine($summary['name'],$summary['code'],$this->sma->formatNumber($summary['items']),$this->sma->formatMoney($summary['amt']),$this->sma->formatMoney($summary['tax'])); ?>" + "\n";
                receipt += "<?php endforeach; ?>";
                receipt += "<?= printLine(lang("total_tax_amount") . ":" . $this->sma->formatMoney($inv->product_tax)); ?>" + "\n";
                <?php
                    }
                }
                ?>
                receipt += "\x1B\x61\x31";
                receipt += "\n" + "<?= $biller->invoice_footer ? printText(str_replace(array('\n', '\r'), ' ', $this->sma->decode_html($biller->invoice_footer))) : '' ?>" + "\n";
                receipt += "\x1B\x61\x30";
                <?php if(isset($pos_settings->cash_drawer_cose)) { ?>
                print(receipt+receipt, '', '<?=$pos_settings->cash_drawer_cose;?>');
            
                
                <?php } else { ?>
                print(receipt+receipt, '', '');
                
                
                <?php } ?>

            }

        </script>
    <?php } ?>
            <script type="text/javascript">
                $(document).ready(function () {
                    $('#email').click(function () {
                        var email = prompt("<?= lang("email_address"); ?>", "<?= $customer->email; ?>");
                        if (email != null) {
                            $.ajax({
                                type: "post",
                                url: "<?= site_url('pos/email_receipt') ?>",
                                data: {<?= $this->security->get_csrf_token_name(); ?>: "<?= $this->security->get_csrf_hash(); ?>", email: email, id: <?= $inv->id; ?>},
                                dataType: "json",
                                success: function (data) {
                                    alert(data.msg);
                                },
                                error: function () {
                                    alert('<?= lang('ajax_request_failed'); ?>');
                                    return false;
                                }
                            });
                        }
                        return false;
                    });
                });
        <?php if (!$pos_settings->java_applet) { ?>
        $(window).load(function () {
            window.print();
        });
    <?php } ?>
            </script>
</body>
</html>
<?php } ?>