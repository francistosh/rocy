
<style>
#chartdiv {
  width: 600px;
  height: 300px;
}

#chartdiv1 {
  width: 500px;
  height: 300px;
}
#chartdivsalesbytype {
  width: 500px;
  height: 300px;
}

#roombooking {
  width: 500px;
  height: 300px;
}
</style>
<title><?= lang('Sales_Report'); ?></title>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('Summary_Sales_Report'); ?></h2>

    </div>
    <div class="box-content">
     <div class="row">
            <div class="col-lg-12">
			  <form target="_blank" action="sales/viewsalesumm">
			  <div class="col-md-3">
                                <div class="form-group">
                                <label>From:</label>
                               <input type="text" placeholder="From" name="sumry_fromdate" class="form-control input-tip date" value="<?= date('d/m/Y');?>">
                            </div>
                            </div>
                 <div class="col-md-3">
                                <div class="form-group">
                                <label>To:</label>
                              <input type="text" placeholder="To" name="sumry_todate" class="form-control input-tip date" value="<?= date('d/m/Y',strtotime('tomorrow'));?>">
                            </div>
                            </div>
                      <div class="col-md-3">
					  </div>
					  
                             <div class="col-md-3">
                                <div class="form-group">
                                <input type="submit" name="viewsalesumm" value="Search" class="btn btn-primary">      
                            </div>
                            </div>          
                </form>
    </div>
	</div>
</div>

</div>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('Detailed_Sales_Report'); ?></h2>

    </div>
    <div class="box-content">
     <div class="row">
            <div class="col-lg-12">
			  <form target="_blank" action="sales/saledet_report">
			  <div class="col-md-3">
                                <div class="form-group">
                                <label>From:</label>
                               <input type="text" placeholder="From" name="fromdate" class="form-control input-tip date" value="<?= date('d/m/Y');?>">
                            </div>
                            </div>
                 <div class="col-md-3">
                                <div class="form-group">
                                <label>To:</label>
                              <input type="text" placeholder="To" name="todate" class="form-control input-tip date" value="<?= date('d/m/Y',strtotime('tomorrow'));?>">
                            </div>
                            </div>
                      <div class="col-md-3">
                                <div class="form-group">
                                <?= lang("Payed_by", "paid_by_1"); ?>
                             <select name="paid_byy" id="paid_by_2" class="form-control paid_by"
                                            >
										<option value="" selected>----Select----</option>
                                        <option value="cash"><?= lang("cash"); ?></option>
                                        <option value="CC"><?= lang("CC"); ?></option>
                                        <option value="mpesa"><?= "Mobile Money"; ?></option>
                                        <option value="costcenter">Company/Cost Center</option>
										<option value="rooms">Room</option>
										<!--<option value="compli">Reception</option>-->
										<option value="reception">Reception</option>
										<option value="nonchrg">N/C</option>
										<option value="gift_card"><?= lang("gift_card"); ?></option>
                                        <option value="Cheque"><?= lang("cheque"); ?></option>
										<option value="staff"><?= lang("Staff"); ?></option>
                                        <?= $pos_settings->paypal_pro ? '<option value="ppp">' . lang("paypal_pro") . '</option>' : ''; ?>
                                        <?= $pos_settings->stripe ? '<option value="stripe">' . lang("stripe") . '</option>' : ''; ?>
                                        <option value="other"><?= lang("other"); ?></option>
                                    </select>
                            </div>
                            </div>
                             <div class="col-sm-3">
                            
							 <div class="form-group">
                                    <?= lang("Department*", "department"); ?>
                                    <select name="departmentt" id="departmentt" class="form-control paid_by"
                                            required>
											<option value="" selected>----Select----</option>
                                        <option value="recp"><?= lang("Reception"); ?></option>
                                        <option value="bar"><?= lang("Bar"); ?></option>
                                        <option value="kitchen"><?= "Restaurant"; ?></option>
                                        
                                    </select>
                                </div>
                        </div>
							  <div class="col-md-3">
                                <div class="form-group">
                                <input type="submit" name="graph" value="Search" class="btn btn-secondary">      
                            </div>
                            </div>
                                      
                </form>
    </div>
	</div>
</div>

</div>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('Costing_Report'); ?></h2>

    </div>
    <div class="box-content">
     <div class="row">
            <div class="col-lg-12">
			  <form target="_blank" action="sales/viewcostingrpt">
			  <div class="col-md-3">
                                <div class="form-group">
                                <label>From:</label>
                               <input type="text" placeholder="From" name="cost_fromdate" class="form-control input-tip date" value="<?= date('d/m/Y');?>">
                            </div>
                            </div>
                 <div class="col-md-3">
                                <div class="form-group">
                                <label>To:</label>
                              <input type="text" placeholder="To" name="cost_todate" class="form-control input-tip date" value="<?= date('d/m/Y',strtotime('tomorrow'));?>">
                            </div>
                            </div>
							<div class="col-sm-3">
                            
							 <div class="form-group">
                                    <?= lang("Department", "department"); ?>
                                    <select name="costdepartmentt" id="costdepartmentt" class="form-control paid_by"
                                            >
											<option value="" selected>----Select----</option>
                                        <option value="bar"><?= lang("Bar"); ?></option>
                                        <option value="kitchen"><?= "Restaurant"; ?></option>
                                        
                                    </select>
                                </div>
                        </div>
                      <div class="col-md-3">
					  </div>
					  
                             <div class="col-md-3">
                                <div class="form-group">
                                <input type="submit" name="viewcostingrpt" value="Search" class="btn btn-primary">      
                            </div>
                            </div>          
                </form>
    </div>
	</div>
</div>

</div>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('Food Costing'); ?></h2>

    </div>
    <div class="box-content">
     <div class="row">
            <div class="col-lg-12">
			  <form target="_blank" action="sales/foodcostingrpt">
			  <div class="col-md-3">
                                <div class="form-group">
                                <label>From:</label>
                               <input type="text" placeholder="From" name="fcost_fromdate" class="form-control input-tip date" value="<?= date('d/m/Y');?>">
                            </div>
                            </div>
                 <div class="col-md-3">
                                <div class="form-group">
                                <label>To:</label>
                              <input type="text" placeholder="To" name="fcost_todate" class="form-control input-tip date" value="<?= date('d/m/Y',strtotime('tomorrow'));?>">
                            </div>
                            </div>
							<div class="col-sm-3">
                            
							 <div class="form-group">
                                    <?= lang("Department", "department"); ?>
                                    <select name="costdepartmentt" id="fcostdepartmentt" class="form-control paid_by"
                                            >
											<option value="" selected>----Select----</option>
                                       
                                        <option value="kitchen"><?= "Restaurant"; ?></option>
                                        
                                    </select>
                                </div>
                        </div>
                      <div class="col-md-3">
					  </div>
					  
                             <div class="col-md-3">
                                <div class="form-group">
                                <input type="submit" name="foodcostingrptt" value="Search" class="btn btn-primary">      
                            </div>
                            </div>          
                </form>
    </div>
	</div>
</div>

</div>
<script type="text/javascript">
    $(document).ready(function () {
 

} );
</script>
