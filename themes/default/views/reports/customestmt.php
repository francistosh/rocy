
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
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('Customer_Report'); ?></h2>

    </div>
    <div class="box-content">
     <div class="row">
            <div class="col-lg-12">
			  <form target="_blank" action="sales/stmntofaccount">
			  <div class="col-md-3">
                                <div class="form-group">
                                <label>From:</label>
                               <input type="text" placeholder="From" name="fromdate" class="form-control input-tip date" value="<?= date('d/m/Y');?>">
                            </div>
                            </div>
                 <div class="col-md-3">
                                <div class="form-group">
                                <label>To:</label>
                              <input type="text" placeholder="To" name="todate" class="form-control input-tip date" value="<?= date('d/m/Y');?>">
                            </div>
                            </div>
                      <div class="col-md-3">
                                <div class="form-group">
                                <label>Customer:**</label>
                             <?php
								$cast[''] = "";
                            foreach ($customers as $customer) {
								$cast[$customer->id] = $customer->name;
                                
                            }
							 echo form_dropdown('chkincustomer_edit', $cast, (isset($_POST['customer']) ? $_POST['customer'] : $sale->customer_id), 'class="form-control select"  id="chkincustomer_edit" placeholder="' . lang("Select")  . '" required="required" style="width:100%"')

                            ?>
                            </div>
                            </div>
                            
							  <div class="col-md-3">
                                <div class="form-group">
                                <input type="submit" name="graph" value="Search" class="btn btn-primary">      
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
