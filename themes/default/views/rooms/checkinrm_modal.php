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
<div class="modal-dialog modal-lg" id="modalrm">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Select Room'); ?></h4>
        </div>
             <div class="modal-body">
			 
          				 <?php  foreach ($roomsbydate as $room) { 
						 if ($room->status =='occupied'){?>
						 <div class="form-check">
  <input class="form-check-input roomstat" name="checkboxname"  type="checkbox" value="<?php  echo $room->id;?>" id="flexCheckDefault" disabled>
  <label class="form-check-label" for="flexCheckDefault">
     <?php  echo $room->name;?>
  </label>
</div>
          						 <?php } 
						 else if ($room->status =='booked') {
						?>
			<div class="form-check">
  <input class="form-check-input roomstat"  name="checkboxname" type="checkbox" value="<?php  echo $room->id;?>" id="flexCheckDefault">
  <label class="form-check-label" for="flexCheckDefault">
   <?php  echo $room->name;?>
  </label>
</div>		
						<?php }
						else { 
						?>
			<div class="form-check">
			<input type="text" value="<?php  echo $room->name;?>" id="roomname<?php  echo $room->id;?>" hidden>
  <input class="form-check-input roomstat"  name="checkboxname" type="checkbox" value="<?php  echo $room->id;?>" id="flexCheckDefault">
  <label class="form-check-label" for="flexCheckDefault">
   <?php  echo $room->name;?>
  </label>
</div>			 
						 
					<?php	}}?>
					
					<br><b> <u>Colour Code: </u></b><br>
			   <button class="btn btn-success roomsbtn1" > </button> Occupied<br>
			   <button class="btn btn-primary roomsbtn1" > </button> Booked<br>
			   <button class="btn btn-danger roomsbtn1" > </button> Empty<br>
			   <br>
			   
             <button class="btn btn-info roomsbtn2" data-dismiss="modal" value="<?php echo $categories->id; ?>"  > Update </button>  
				<button class="btn btn-info roomsbtn21" data-dismiss="modal" value="<?php echo $categories->id; ?>"  > Clear </button>  			 
			</div>
        </div>
        <div class="modal-footer">
                      
          
			   
			   

          
        </div>
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
		 $('.roomsbtn2').click(function () {
		 var id = $(this).val();
		 var checkboxval =[];
		 var nameval =[];
		 $('input[name=checkboxname]:checked').map(function(){
			 checkboxval.push($(this).val());
			 $id2 = $(this).val();
			 nameval.push($('#roomname'+$id2).val());
		 });
		
		$('[name="chkinrms'+id).val(checkboxval);
		$('[name="chkinrms_edit'+id).val(checkboxval);
		 $("#room_view"+id).val(nameval);
			// alert(id);
			$('#modalrm').modal('hide');
		 });
		 
		 $('.roomsbtn21').click(function () {
			 var id = $(this).val();
		$('[name="chkinrms_edit'+id).val(0);
		 $('[name="standard_room'+id+"_edit").val(0);
			//alert(id);
			$('#modalrm').modal('hide');
		 });
		 
    });
</script>
