<script>
    $(document).ready(function () {
        var oTable = $('#GuestData').dataTable({
            "aaSorting": [[5, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('vouchers/getVouchers') ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "aoColumnDefs": [
                { "bSearchable": false, "aTargets": [ 8 ] }
            ],
            "aoColumns": [{
                "bSortable": false,
                "mRender": checkbox
            }, null, null, null, null, null, null, null, null, {"bSortable": false}]
        }).dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('customer_name');?>]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('voucher_number');?>]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('status');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('group_name');?>]", filter_type: "text", data: []},
            {column_number: 5, filter_default_label: "[<?=lang('check_in');?>]", filter_type: "text", data: []},
            {column_number: 6, filter_default_label: "[<?=lang('check_out');?>]", filter_type: "text", data: []},
            {column_number: 7, filter_default_label: "[<?=lang('total_nights');?>]", filter_type: "text", data: []},
            {column_number: 8, filter_default_label: "[<?=lang('total_rooms');?>]", filter_type: "text", data: []},
        ], "footer");
    });
</script>
<div class="box">
    <!--<div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?/*= lang('customers'); */?></h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-tasks tip"
                                                                                  data-placement="left"
                                                                                  title="<?/*= lang("actions") */?>"></i></a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li><a href="<?/*= site_url('customers/add'); */?>" data-toggle="modal" data-target="#myModal"
                               id="add"><i class="fa fa-plus-circle"></i> <?/*= lang("add_customer"); */?></a></li>
                        <li><a href="<?/*= site_url('customers/import_csv'); */?>" data-toggle="modal"
                               data-target="#myModal"><i class="fa fa-plus-circle"></i> <?/*= lang("import_by_csv"); */?>
                            </a></li>
                        <?php /*if ($Owner) { */?>
                            <li><a href="#" id="excel" data-action="export_excel"><i
                                        class="fa fa-file-excel-o"></i> <?/*= lang('export_to_excel') */?></a></li>
                            <li><a href="#" id="pdf" data-action="export_pdf"><i
                                        class="fa fa-file-pdf-o"></i> <?/*= lang('export_to_pdf') */?></a></li>
                            <li class="divider"></li>
                            <li><a href="#" class="bpo" title="<b><?/*= $this->lang->line("delete_customers") */?></b>"
                                   data-content="<p><?/*= lang('r_u_sure') */?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?/*= lang('i_m_sure') */?></a> <button class='btn bpo-close'><?/*= lang('no') */?></button>"
                                   data-html="true" data-placement="left"><i
                                        class="fa fa-trash-o"></i> <?/*= lang('delete_customers') */?></a></li>
                        <?php /*} */?>
                    </ul>
                </li>
            </ul>
        </div>
    </div>-->
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
                            <td colspan="9" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
                        </tr>
                        </tbody>
                        <tfoot class="dtFilter">
                        <tr class="active">
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                            <th></th>
                            <th></th>
                            <th></th
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="width:85px;" class="text-center"><?= lang("actions"); ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

	

