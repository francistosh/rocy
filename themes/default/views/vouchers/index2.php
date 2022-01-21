
<div class="box">
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <form method="get" action="http://localhost/safaris/vouchers/vouchers_date">
                    <div class="form-group">
                        <label>Date</label>
                        <input class="form-control" name="voucher_date" id="voucher_date" value="<?php echo set_value('voucher_date');?>" type="date" required>
                    </div>
                    <div class="form-group">
                        <?php echo form_submit('search', lang('search'), 'class="btn btn-primary"'); ?>
                    </div>
                </form>
                <p style="font-weight: bold">Capacity: 20</p>

                <p style="font-weight: bold">Rooms occupied:
                    <?php
                    $grand_total=0;
                    foreach ($vouchers as $voucher) {
                        $grand_total+=$voucher->total_rooms;
                    }

                    echo $grand_total;
                    ?>
                </p>
                <p style="font-weight: bold">Rooms remaining:
                    <?php
                    $grand_total=0;
                    foreach ($vouchers as $voucher) {
                        $grand_total+=$voucher->total_rooms;
                    }

                    echo 20-$grand_total;
                    ?>
                </p>
                <p style="margin-top: 20px" class="introtext"><?= lang('list_results'); ?></p>

                <div class="table-responsive">
                    <table id="GuestData" cellpadding="0" cellspacing="0" border="0"
                           class="table table-bordered table-condensed table-hover table-striped">
                        <thead>
                        <tr class="primary">
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkth" type="checkbox" name="check"/>
                            </th>
                            <th><?= lang("customer_name"); ?></th>
                            <th><?= lang("voucher_number"); ?></th>
                            <th><?= lang("status"); ?></th>
                            <th><?= lang("group_name"); ?></th>
                            <th><?= lang("check_in"); ?></th>
                            <th><?= lang("check_out"); ?></th>
                            <th><?= lang("total_nights"); ?></th>
                            <th><?= lang("total_rooms"); ?></th>
                            <th style="width:85px;"><?= lang("actions"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <?php
                            if (count($vouchers)>0){
                                foreach ($vouchers as $voucher) {
                                    echo '<td>'.$voucher->id.'</td>';
                                    echo '<td>'.$voucher->name.'</td>';
                                    echo '<td>'.$voucher->voucher_no.'</td>';
                                    echo '<td>'.$voucher->status.'</td>';
                                    echo '<td>'.$voucher->group_name.'</td>';
                                    echo '<td>'.$voucher->check_in.'</td>';
                                    echo '<td>'.$voucher->check_out.'</td>';
                                    echo '<td>'.$voucher->total_nights.'</td>';
                                    echo '<td>'.$voucher->total_rooms.'</td>';
                                    echo '<td><center>
                <a class="tip" title="" href="http://localhost/safaris/vouchers/show/'.$voucher->id.'" data-toggle="modal" data-target="#myModal" data-original-title="View Voucher"><i class="fa fa-eye"></i></a>
                <a class="tip" title="" href="http://localhost/safaris/vouchers/edit/'.$voucher->id.'" data-toggle="modal" data-target="#myModal" data-original-title="Amend Voucher"><i class="fa fa-pencil"></i></a>
                <a href="#" class="tip po" title="" data-content="<p>Are you sure?</p><a class=\' btn btn-danger po-delete\'  href=\' http://localhost/safaris/vouchers/delete/'.$voucher->id.'\' >Yes I\' m sure</a> <button class=\' btn po-close\' >No</button>" rel="popover" data-original-title="<b>Delete Voucher</b>"><i class="fa fa-trash-o"></i></a>
                                </center></td>' ;
                                }
                            }


                            ?>
                          
                        </tr>
                        </tbody>
                        <tfoot class="dtFilter">
                        <tr class="active">
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                            <th><?= lang("customer_name"); ?></th>
                            <th><?= lang("voucher_number"); ?></th>
                            <th><?= lang("status"); ?></th>
                            <th><?= lang("group_name"); ?></th>
                            <th><?= lang("check_in"); ?></th>
                            <th><?= lang("check_out"); ?></th>
                            <th><?= lang("total_nights"); ?></th>
                            <th><?= lang("total_rooms"); ?></th>
                            <th style="width:85px;" class="text-center"><?= lang("actions"); ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

	

