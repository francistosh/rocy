<style>
    .table td:first-child {
        font-weight: bold;
    }

    label {
        margin-right: 10px;
    }
</style>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-folder-open"></i><?= lang('group_permissions'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?= lang("set_permissions"); ?></p>

                <?php if (!empty($p)) {
                    if ($p->group_id != 1) {

                        echo form_open("system_settings/permissions/" . $id); ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped">

                                <thead>
                                <tr>
                                    <th colspan="6"
                                        class="text-center"><?php echo $group->description . ' ( ' . $group->name . ' ) ' . $this->lang->line("group_permissions"); ?></th>
                                </tr>
                                <tr>
                                    <th rowspan="2" class="text-center"><?= lang("module_name"); ?>
                                    </th>
                                    <th colspan="5" class="text-center"><?= lang("permissions"); ?></th>
                                </tr>
                                <tr>
                                    <th class="text-center"><?= lang("view"); ?></th>
                                    <th class="text-center"><?= lang("add"); ?></th>
                                    <th class="text-center"><?= lang("edit"); ?></th>
                                    <th class="text-center"><?= lang("delete"); ?></th>
                                    <th class="text-center"><?= lang("misc"); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><?= lang("products"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="products-index" <?php echo $p->{'products-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="products-add" <?php echo $p->{'products-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="products-edit" <?php echo $p->{'products-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="products-delete" <?php echo $p->{'products-delete'} ? "checked" : ''; ?>>
                                    </td>
                                    <td>
                                        <input type="checkbox" value="1" id="products-cost" class="checkbox"
                                               name="products-cost" <?php echo $p->{'products-cost'} ? "checked" : ''; ?>><label
                                            for="products-cost" class="padding05"><?= lang('product_cost') ?></label>
                                        <input type="checkbox" value="1" id="products-price" class="checkbox"
                                               name="products-price" <?php echo $p->{'products-price'} ? "checked" : ''; ?>><label
                                            for="products-price" class="padding05"><?= lang('product_price') ?></label>
                                    </td>
                                </tr>

                                <tr>
                                    <td><?= lang("Room"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="rooms-index" <?php echo $p->{'rooms-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="rooms-add" <?php echo $p->{'rooms-add'} ? "checked" : ''; ?>>
                                    </td>
                                   
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="rooms-edit" <?php echo $p->{'rooms-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="rooms-delete" <?php echo $p->{'rooms-delete'} ? "checked" : ''; ?>>
                                    </td>
                                     <td>
                                        
                                        <input type="checkbox" value="1" id="rooms-pdf" class="checkbox"
                                               name="rooms-pdf" <?php echo $p->{'rooms-pdf'} ? "checked" : ''; ?>><label
                                            for="rooms-pdf" class="padding05"><?= lang('pdf') ?></label>
                                        <input type="checkbox" value="1" id="rooms-bill" class="checkbox"
                                               name="rooms-bill" <?php echo $p->{'rooms-bill'} ? "checked" : ''; ?>><label
                                            for="rooms-bill" class="padding05">Bill</label>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td><?= lang("Voucher"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="vouchers-index" <?php echo $p->{'vouchers-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="vouchers-add" <?php echo $p->{'vouchers-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="vouchers-edit" <?php echo $p->{'vouchers-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="vouchers-delete" <?php echo $p->{'vouchers-delete'} ? "checked" : ''; ?>>
                                    </td>
                                </tr>

                                <tr>
                                    <td><?= lang("sales"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-index" <?php echo $p->{'sales-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-add" <?php echo $p->{'sales-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-edit" <?php echo $p->{'sales-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-delete" <?php echo $p->{'sales-delete'} ? "checked" : ''; ?>>
                                    </td>
                                    <td>
                                        <input type="checkbox" value="1" id="sales-email" class="checkbox"
                                               name="sales-email" <?php echo $p->{'sales-email'} ? "checked" : ''; ?>><label
                                            for="sales-email" class="padding05"><?= lang('email') ?></label>
                                        <input type="checkbox" value="1" id="sales-pdf" class="checkbox"
                                               name="sales-pdf" <?php echo $p->{'sales-pdf'} ? "checked" : ''; ?>><label
                                            for="sales-pdf" class="padding05"><?= lang('pdf') ?></label>
                                        <?php if (POS) { ?>
                                            <input type="checkbox" value="1" id="pos-index" class="checkbox"
                                                   name="pos-index" <?php echo $p->{'pos-index'} ? "checked" : ''; ?>>
                                            <label for="pos-index" class="padding05"><?= lang('pos') ?></label>
                                        <?php } ?>
                                        <input type="checkbox" value="1" id="sales-payments" class="checkbox"
                                               name="sales-payments" <?php echo $p->{'sales-payments'} ? "checked" : ''; ?>><label
                                            for="sales-payments" class="padding05"><?= lang('payments') ?></label>
                                        <input type="checkbox" value="1" id="sales-return_sales" class="checkbox"
                                               name="sales-return_sales" <?php echo $p->{'sales-return_sales'} ? "checked" : ''; ?>><label
                                            for="sales-return_sales"
                                            class="padding05"><?= lang('return_sales') ?></label>
                                    </td>
                                </tr>

                                <tr>
                                    <td><?= lang("Service Voucher"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="service-vouchers-index" <?php echo $p->{'service-vouchers-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="service-vouchers-add" <?php echo $p->{'service-vouchers-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="service-vouchers-edit" <?php echo $p->{'service-vouchers-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="service-vouchers-delete" <?php echo $p->{'service-vouchers-delete'} ? "checked" : ''; ?>>
                                    </td>
                                    <td>
                                        <input type="checkbox" value="1" id="service-vouchers-check_in" class="checkbox"
                                               name="service-vouchers-check_in" <?php echo $p->{'service-vouchers-check_in'} ? "checked" : ''; ?>><label
                                                for="service-vouchers-check_in" class="padding05">Check In Guest</label>
                                        <input type="checkbox" value="1" id="service-vouchers-email" class="checkbox"
                                               name="service-vouchers-email" <?php echo $p->{'service-vouchers-email'} ? "checked" : ''; ?>><label
                                                for="service-vouchers-email" class="padding05"><?= lang('email') ?></label>
                                        <input type="checkbox" value="1" id="service-vouchers-pdf" class="checkbox"
                                               name="service-vouchers-pdf" <?php echo $p->{'service-vouchers-pdf'} ? "checked" : ''; ?>><label
                                                for="service-vouchers-pdf" class="padding05"><?= lang('pdf') ?></label>
                                    </td>
                                </tr>

                                <tr>
                                    <td><?= lang("Guest"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="guests-index" <?php echo $p->{'guests-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">

                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="guests-edit" <?php echo $p->{'guests-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="guests-delete" <?php echo $p->{'guests-delete'} ? "checked" : ''; ?>>
                                    </td>
                                    <td>
                                        <input type="checkbox" value="1" id="guests-record-temperature" class="checkbox"
                                               name="guests-record-temperature" <?php echo $p->{'guests-record-temperature'} ? "checked" : ''; ?>><label
                                                for="guests-record-temperature" class="padding05"><?= lang('Record Temperature') ?></label>
                                        <input type="checkbox" value="1" id="guests-check-out" class="checkbox"
                                               name="guests-check-out" <?php echo $p->{'guests-check-out'} ? "checked" : ''; ?>><label
                                                for="guests-check-out" class="padding05"><?= lang('Check Out Guest') ?></label>
                                    </td>
                                </tr>

                                <tr>
                                    <td><?= lang("deliveries"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-deliveries" <?php echo $p->{'sales-deliveries'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-add_delivery" <?php echo $p->{'sales-add_delivery'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-edit_delivery" <?php echo $p->{'sales-edit_delivery'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-delete_delivery" <?php echo $p->{'sales-delete_delivery'} ? "checked" : ''; ?>>
                                    </td>
                                    <td>
                                        <!--<input type="checkbox" value="1" id="sales-email" class="checkbox" name="sales-email_delivery" <?php echo $p->{'sales-email_delivery'} ? "checked" : ''; ?>><label for="sales-email_delivery" class="padding05"><?= lang('email') ?></label>-->
                                        <input type="checkbox" value="1" id="sales-pdf" class="checkbox"
                                               name="sales-pdf_delivery" <?php echo $p->{'sales-pdf_delivery'} ? "checked" : ''; ?>><label
                                            for="sales-pdf_delivery" class="padding05"><?= lang('pdf') ?></label>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?= lang("gift_cards"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-gift_cards" <?php echo $p->{'sales-gift_cards'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-add_gift_card" <?php echo $p->{'sales-add_gift_card'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-edit_gift_card" <?php echo $p->{'sales-edit_gift_card'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-delete_gift_card" <?php echo $p->{'sales-delete_gift_card'} ? "checked" : ''; ?>>
                                    </td>
                                    <td>

                                    </td>
                                </tr>

                                <tr>
                                    <td><?= lang("quotes"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="quotes-index" <?php echo $p->{'quotes-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="quotes-add" <?php echo $p->{'quotes-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="quotes-edit" <?php echo $p->{'quotes-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="quotes-delete" <?php echo $p->{'quotes-delete'} ? "checked" : ''; ?>>
                                    </td>
                                    <td>
                                        <input type="checkbox" value="1" id="quotes-email" class="checkbox"
                                               name="quotes-email" <?php echo $p->{'quotes-email'} ? "checked" : ''; ?>><label
                                            for="quotes-email" class="padding05"><?= lang('email') ?></label>
                                        <input type="checkbox" value="1" id="quotes-pdf" class="checkbox"
                                               name="quotes-pdf" <?php echo $p->{'quotes-pdf'} ? "checked" : ''; ?>><label
                                            for="quotes-pdf" class="padding05"><?= lang('pdf') ?></label>
                                    </td>
                                </tr>

                                <tr>
                                    <td><?= lang("purchases"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="purchases-index" <?php echo $p->{'purchases-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="purchases-add" <?php echo $p->{'purchases-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="purchases-edit" <?php echo $p->{'purchases-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="purchases-delete" <?php echo $p->{'purchases-delete'} ? "checked" : ''; ?>>
                                    </td>
                                    <td>
                                        <input type="checkbox" value="1" id="purchases-email" class="checkbox"
                                               name="purchases-email" <?php echo $p->{'purchases-email'} ? "checked" : ''; ?>><label
                                            for="purchases-email" class="padding05"><?= lang('email') ?></label>
                                        <input type="checkbox" value="1" id="purchases-pdf" class="checkbox"
                                               name="purchases-pdf" <?php echo $p->{'purchases-pdf'} ? "checked" : ''; ?>><label
                                            for="purchases-pdf" class="padding05"><?= lang('pdf') ?></label>
                                        <input type="checkbox" value="1" id="purchases-payments" class="checkbox"
                                               name="purchases-payments" <?php echo $p->{'purchases-payments'} ? "checked" : ''; ?>><label
                                            for="purchases-payments" class="padding05"><?= lang('payments') ?></label>
                                        <input type="checkbox" value="1" id="purchases-expenses" class="checkbox"
                                               name="purchases-expenses" <?php echo $p->{'purchases-expenses'} ? "checked" : ''; ?>><label
                                            for="purchases-expenses" class="padding05"><?= lang('expenses') ?></label>
                                    </td>
                                </tr>

                                <tr>
                                    <td><?= lang("transfers"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="transfers-index" <?php echo $p->{'transfers-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="transfers-add" <?php echo $p->{'transfers-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="transfers-edit" <?php echo $p->{'transfers-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="transfers-delete" <?php echo $p->{'transfers-delete'} ? "checked" : ''; ?>>
                                    </td>
                                    <td>
                                        <input type="checkbox" value="1" id="transfers-email" class="checkbox"
                                               name="transfers-email" <?php echo $p->{'transfers-email'} ? "checked" : ''; ?>><label
                                            for="transfers-email" class="padding05"><?= lang('email') ?></label>
                                        <input type="checkbox" value="1" id="transfers-pdf" class="checkbox"
                                               name="transfers-pdf" <?php echo $p->{'transfers-pdf'} ? "checked" : ''; ?>><label
                                            for="transfers-pdf" class="padding05"><?= lang('pdf') ?></label>
                                    </td>
                                </tr>

                                <tr>
                                    <td><?= lang("customers"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="customers-index" <?php echo $p->{'customers-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="customers-add" <?php echo $p->{'customers-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="customers-edit" <?php echo $p->{'customers-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="customers-delete" <?php echo $p->{'customers-delete'} ? "checked" : ''; ?>>
                                    </td>
                                    <td>
                                    </td>
                                </tr>

                                <tr>
                                    <td><?= lang("suppliers"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="suppliers-index" <?php echo $p->{'suppliers-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="suppliers-add" <?php echo $p->{'suppliers-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="suppliers-edit" <?php echo $p->{'suppliers-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="suppliers-delete" <?php echo $p->{'suppliers-delete'} ? "checked" : ''; ?>>
                                    </td>
                                    <td>
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive">
                            <table cellpadding="0" cellspacing="0" border="0"
                                   class="table table-bordered table-hover table-striped" style="margin-bottom: 5px;">

                                <thead>
                                <tr>
                                    <th><?= lang("reports"); ?>
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="checkbox" value="1" class="checkbox" id="product_quantity_alerts"
                                               name="reports-quantity_alerts" <?php echo $p->{'reports-quantity_alerts'} ? "checked" : ''; ?>><label
                                            for="product_quantity_alerts"
                                            class="padding05"><?= lang('product_quantity_alerts') ?></label>
                                        <input type="checkbox" value="1" class="checkbox" id="Product_expiry_alerts"
                                               name="reports-expiry_alerts" <?php echo $p->{'reports-expiry_alerts'} ? "checked" : ''; ?>><label
                                            for="Product_expiry_alerts"
                                            class="padding05"><?= lang('product_expiry_alerts') ?></label>
                                        <input type="checkbox" value="1" class="checkbox" id="products"
                                               name="reports-products" <?php echo $p->{'reports-products'} ? "checked" : ''; ?>><label
                                            for="products" class="padding05"><?= lang('products') ?></label>
                                        <input type="checkbox" value="1" class="checkbox" id="daily_sales"
                                               name="reports-daily_sales" <?php echo $p->{'reports-daily_sales'} ? "checked" : ''; ?>><label
                                            for="daily_sales" class="padding05"><?= lang('daily_sales') ?></label>
                                        <input type="checkbox" value="1" class="checkbox" id="monthly_sales"
                                               name="reports-monthly_sales" <?php echo $p->{'reports-monthly_sales'} ? "checked" : ''; ?>><label
                                            for="monthly_sales" class="padding05"><?= lang('monthly_sales') ?></label>
                                        <input type="checkbox" value="1" class="checkbox" id="payments"
                                               name="reports-payments" <?php echo $p->{'reports-payments'} ? "checked" : ''; ?>><label
                                            for="payments" class="padding05"><?= lang('payments') ?></label>
                                        <input type="checkbox" value="1" class="checkbox" id="purchases"
                                               name="reports-purchases" <?php echo $p->{'reports-purchases'} ? "checked" : ''; ?>><label
                                            for="purchases" class="padding05"><?= lang('purchases') ?></label>
                                        <input type="checkbox" value="1" class="checkbox" id="customers"
                                               name="reports-customers" <?php echo $p->{'reports-customers'} ? "checked" : ''; ?>><label
                                            for="customers" class="padding05"><?= lang('customers') ?></label>
                                        <input type="checkbox" value="1" class="checkbox" id="suppliers"
                                               name="reports-suppliers" <?php echo $p->{'reports-suppliers'} ? "checked" : ''; ?>><label
                                            for="suppliers" class="padding05"><?= lang('suppliers') ?></label>
                                    </td>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary"><?=lang('update')?></button>
                        </div>
                        <?php echo form_close();
                    } else {
                        echo $this->lang->line("group_x_allowed");
                    }
                } else {
                    echo $this->lang->line("group_x_allowed");
                } ?>


            </div>
        </div>
    </div>
</div>