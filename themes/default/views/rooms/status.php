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
</style>
<div class="modal-dialog modal-lg" id="testmodal">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Room_Status'); echo ' on :-   '. date('d-m-Y',strtotime($datemm));?></h4>
			<div><input type="date" id="rmdate" value="<?php echo $datemm; ?>" ></input> </div>
		
        </div>
             <div class="modal-body">
			 
          				 <?php 

						 foreach ($roomsbydate as $room) { 
						 if ($room->status =='occupied'){?>
           <button class="btn-prni btn-success roomsbtn1" value="<?php  echo $room->id;?>" data-toggle="modal" data-target="#roomservicemodal"><b><?php  echo $room->name;?> <br>Occupied </b></button>
						 <?php } 
						 else if ($room->status =='booked') {
						?>
			<button class="btn-prni btn-primary roomsbtn1" value="<?php  echo $room->id;?>"> <?php  echo $room->name;?></button>
			
						<?php }
						else { 
						?>
			<button class="btn-prni btn-danger roomsbtn1" value="<?php  echo $room->id;?>"> <?php  echo $room->name;?></button>
			 
						 
					<?php	}}?>
					
					<br><b> <u>Colour Code: </u></b><br>
			   <button class="btn btn-success roomsbtn1" > </button> Occupied<br>
			   <button class="btn btn-primary roomsbtn1" > </button> Booked<br>
			   <button class="btn btn-danger roomsbtn1" > </button> Vacant<br>
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
$("#testmodal").load("../../rocy/customers/status/?sdate="+$date);
    });
					 
			
 });

</script>
