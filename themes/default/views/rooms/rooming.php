<script src="https://unpkg.com/bootstrap-table@1.18.3/dist/extensions/print/bootstrap-table-print.min.js"></script>
<style>
.btn-prni {
    border: 1px solid #eee;
    cursor: pointer;
    height: 115px;
    margin: 0 0 3px 2px;
    padding: 2px;
    width: 10.5%;
    min-width: 100px;
    overflow: hidden;
    display: inline-block;
    font-size: 13px;
}
@media (min-width: 1200px) {
   .modal-xlg {
      width: 90%; 
   }
}
@media print {
  .modal-xlg {
      width: 90%; 
   }
   .table-bordereds td, .table-bordereds th{
    border-color: black !important;
	font-size:12px;
	margin-top:-2px;
	 padding:2px;
}
.datehide{
	display:none;
}

}


.table-bordereds td, .table-bordereds th{
    border-color: black !important;
}
</style>
<div class="modal-dialog modal-xlg " id="testmodal">
    <div class="modal-content">
        <div class="modal-header">
		<h3 class="modal-title" id="myModalLabel"><?php echo lang('ROCY HOTEL MBALE');?></h3>
		<br>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Rooming List'); echo ' on :-   '. date('d-m-Y',strtotime($datemm));?></h4>
			<div ><input style="float:right" class="datehide" type="date" id="rmdate" value="<?php echo $datemm; ?>" ></input> </div>
		
        </div>
             <div class="modal-body" >
			
          		<table   class="table-bordered table-striped  table-bordereds modal-xlg ">
  <thead>
    <tr>
      <th scope="col">Room No</th>
      <th scope="col" class="col-md-3">Guest Name</th>
      <th scope="col" class="col-md-2">Company</th>
      <th scope="col">No of Adults</th>
	  <th scope="col">No of Children</th>
	  <th scope="col">Arrival Date</th>
	  <th scope="col">Departue Date</th>
	  <th scope="col">No of Days</th>
	   <th scope="col">Amount</th>
		<th scope="col">Remarks</th>
    </tr>
  </thead>
  <tbody>
  <?php
  $totalamnt = 0;
  foreach ($roomsbydate as $room) { 
  $i=1;
  $i++;
  if( !empty($room->check_in)){
	  $check_in = date('d-m-Y',strtotime($room->check_in));
  }else{
	   $check_in = '';
  }
  if( !empty($room->check_out)){
	  $check_out = date('d-m-Y',strtotime($room->check_out));
  }else{
	   $check_out = '';
  }
  if( !empty($room->amount)){
	  $amount = number_format($room->amount);
	  $totalamnt = $totalamnt + $room->amount;
  }else{
	   $amount = '';
  }
  ?>
   <tr>
      <td ><b><?php  echo $room->id;?></b></td>
      <td ><?php  echo $room->customer;?></td>
      <td ><?php  echo $room->company;?></td>
      <td ><?php  echo $room->no_adults;?></td>
	  <td ><?php  echo $room->no_children;?></td>
	  <td ><?php  echo $check_in;?></td>
	  <td ><?php  echo $check_out;?></td>
	  <td ><?php  echo $room->total_nights;?></td>
	   <td class="text-right"><?php  echo $amount ;?>&nbsp;</td>
		<td ></th>
    </tr>
  <?php
  
  }
  ?>
   <tr>
      <td colspan="8" class="text-center"> <b>Total Amount</b></td>
    
	   <td colspan="2" >&nbsp;<?php  echo number_format($totalamnt) ;?></td>
		
		</tr>
  </tbody>
</table>
            </div>
        </div>
        <div class="modal-footer">
                      
              
			   
			   

          
        </div>
    </div>


<script type="text/javascript">
    $(document).ready(function (e) {
        $('#add-customer-form').bootstrapValidator({
            feedbackIcons: {
                valid: 'fa fa-check',
                invalid: 'fa fa-times',
                validating: 'fa fa-refresh'
            }, excluded: [':disabled']
        });
        $('select.select').select2({minimumResultsForSearch: 6});
        fields = $('.modal-content').find('.form-control');
        $.each(fields, function () {
            var id = $(this).attr('id');
            var iname = $(this).attr('name');
            var iid = '#' + id;
            if (!!$(this).attr('data-bv-notempty') || !!$(this).attr('required')) {
                $("label[for='" + id + "']").append(' *');
                $(document).on('change', iid, function () {
                    $('form[data-toggle="validator"]').bootstrapValidator('revalidateField', iname);
                });
            }
        });
		
		 $('#rmdate').on('change', function (e) {
           $(this).val();
		   $date = $(this).val();
		  //window.open("../../rocy/customers/status/?sdate="+$date);
$("#testmodal").load("../../rocy/customers/rooming/?sdate="+$date);
    });
					 
			
 });

</script>
