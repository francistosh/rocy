<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('view_payments'); ?></h4>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table id="CompTable" cellpadding="0" cellspacing="0" border="0"
                       class="table table-bordered table-hover table-striped">
                    <thead>
                    <tr>
                        <th style="width:30%;"><?= $this->lang->line("date"); ?></th>
                        <th style="width:30%;"><?= $this->lang->line("reference_no"); ?></th>
                        <th style="width:15%;"><?= $this->lang->line("amount"); ?></th>
                        <!--<th style="width:15%;"><?/*= $this->lang->line("balance"); */?></th>-->
                        <th style="width:15%;"><?= $this->lang->line("currency"); ?></th>
                        <th style="width:15%;"><?= $this->lang->line("paid_by"); ?></th>
                        <th style="width:10%;"><?= $this->lang->line("actions"); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($payments)) {
                        foreach ($payments as $payment) { ?>
                            <tr class="row<?= $payment->id ?>">
                                <td><?= $this->sma->hrld($payment->date); ?></td>
                                <td><?= lang($payment->reference_no); ?></td>
                                <td><?= $this->sma->formatMoney($payment->amount) . ' ' . (($payment->attachment) ? '<a href="' . base_url('assets/uploads/' . $payment->attachment) . '" target="_blank"><i class="fa fa-chain"></i></a>' : ''); ?></td>
                                <!--<td><?/*= $this->sma->formatMoney($payment->balance) */?></td>-->
                                <td><?= $payment->currency ?></td>
                                <td><?= lang($payment->paid_by); ?></td>
                                <td>
                                    <div class="text-center">
                                        <a href="<?= site_url('sales/payment_note/' . $payment->id) ?>"
                                           data-toggle="modal" data-target="#myModal2"><i class="fa fa-file-text-o"></i></a>
                                        <?php if ($payment->paid_by != 'gift_card') { ?>
                                            <a href="<?= site_url('sales/edit_payment/' . $payment->id) ?>"
                                               data-toggle="modal" data-target="#myModal2"><i
                                                    class="fa fa-edit"></i></a>
                                            <a href="<?= site_url('sales/pdf_payment/' . $payment->id) ?>"><i
                                                        class="fa fa-file-pdf-o"></i></a>
                                            <a href="<?= site_url('sales/email_payment/' . $payment->id) ?>"
                                               data-toggle="modal" data-target="#myModal2"><i
                                                        class="fa fa-envelope"></i></a>
                                            <a href="#" class="po"
                                               title="<b><?= $this->lang->line("delete_payment") ?></b>"
                                               data-content="<p><?= lang('r_u_sure') ?></p><a class='btn btn-danger' id='<?= $payment->id ?>' href='<?= site_url('sales/delete_payment/' . $payment->id) ?>'><?= lang('i_m_sure') ?></a> <button class='btn po-close'><?= lang('no') ?></button>"
                                               rel="popover"><i class="fa fa-trash-o"></i></a>
                                        <?php } ?>
                                    </div>
                                </td>
                            </tr>
                        <?php }
                    } else {
                        echo "<tr><td colspan='4'>" . lang('no_data_available') . "</td></tr>";
                    } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" charset="UTF-8">
    $(document).ready(function () {
        $(document).on('click', '.po-delete', function () {
            var id = $(this).attr('id');
            $(this).closest('tr').remove();
        });
    });
</script>