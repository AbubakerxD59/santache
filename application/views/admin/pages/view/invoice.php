<style type="text/css" media="screen">
   #section-to-print {
      font-family: Arial, sans-serif;
      background: white;
      padding: 10px;
      margin: 0;
   }

   #section-to-print .invoice_logo {
      max-width: 80px;
      height: auto;
      margin: 5px auto;
      display: block;
   }

   #section-to-print table {
      width: 100%;
      border-collapse: collapse;
      font-size: 12px;
      right: 0px;
      margin: 20px 0;
   }

   #section-to-print th,
   #section-to-print td {
      padding: 6px;
      text-align: left;
      border-bottom: 1px solid #ddd;
   }

   #section-to-print h1,
   #section-to-print h2,
   #section-to-print h3,
   #section-to-print h4,
   #section-to-print h5,
   #section-to-print h6 {
      margin: 6px 0;
      padding: 0;
   }

   #section-to-print p {
      margin: 3px 0;
      padding: 0;
      line-height: 1.3;
   }

   #section-to-print .m-3 {
      margin: 8px !important;
      padding: 0;
   }

   #section-to-print .row {
      margin: 0;
      padding: 0;
   }

   #section-to-print .invoice-col {
      width: 33.33%;
      float: left;
      box-sizing: border-box;
      padding: 0 10px;
   }

   #section-to-print .fw-bold {
      font-weight: bold;
   }

   #section-to-print .fw-bolder {
      font-weight: 900;
   }

   #section-to-print .text-center {
      text-align: center;
   }

   #section-to-print .d-flex {
      display: flex;
   }

   #section-to-print .justify-content-between {
      justify-content: space-between;
   }

   #section-to-print .justify-content-end {
      justify-content: flex-end;
   }

   #section-to-print .col-md-6 {
      width: 100%;
      max-width: 50%;
      float: right;
      box-sizing: border-box;
   }

   #section-to-print .table-borderless th,
   #section-to-print .table-borderless td {
      border: none !important;
      padding: 4px 8px !important;
   }

   #section-to-print .table-responsive {
      overflow: visible !important;
      overflow-x: auto !important;
   }
</style>
<style type="text/css" media="print">
   @page {
      size: a4;
      margin: 5mm;
   }

   body {
      margin: 0;
      padding: 0;
      font-size: 12px;
   }

   .invoice-col {
      width: 33.33%;
      float: left;
      font-size: 12px;
   }

   .invoices-col {
      text-align: center;
   }

   .invoice-col:last-child {
      float: right;
   }

   .invoice_logo {
      max-width: 80px !important;
      height: auto !important;
      margin: 5px auto !important;
   }

   table {
      width: 100%;
      border-collapse: collapse;
      font-size: 12px;
      margin-right: 20px !important;
   }

   th,
   td {
      padding: 4px !important;
      border-bottom: 1px solid #ddd;
   }

   .m-3 {
      margin: 8px !important;
   }

   .card {
      margin: 0 !important;
      padding: 0 !important;
   }

   h1,
   h2,
   h3,
   h4,
   h5,
   h6 {
      margin: 5px 0 !important;
      padding: 0 !important;
   }

   p {
      margin: 2px 0 !important;
   }

   .text-center {
      text-align: center;
   }

   .text-left {
      text-align: left;
   }

   .col-md-6 {
      width: 100% !important;
      max-width: 100% !important;
      float: right !important;
      box-sizing: border-box !important;
   }

   .offset-md-2 {
      margin-left: 0 !important;
   }

   .table-responsive {
      overflow: visible !important;
   }

   .table-borderless th,
   .table-borderless td {
      border: none !important;
      padding: 3px 6px !important;
   }

   .d-flex {
      display: flex;
   }

   .justify-content-end {
      justify-content: flex-end;
   }
</style>
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <!-- Main content -->
   <section class="content-header mt-4">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-md-8">
               <h1>Invoice</h1>
            </div>

         </div>
      </div>
      <!-- /.container-fluid -->
      <div class="container-fluid">
         <div class="row">
            <div class="col-md-12">
               <div class="card card-info " id="section-to-print">
                  <div class="row m-3">
                     <div class="col-md-12 invoices-col text-center">
                        <h2 class="text-left">
                           <h1>Tax Invoice/Bill of Supply</h1>
                           <img src="<?= base_url() . get_settings('logo') ?>" class="d-block invoice_logo">
                        </h2>
                     </div>
                     <div class="col-md-12 d-flex justify-content-between">

                     </div>

                  </div>
                  <!-- info row -->
                  <div class="row m-3 d-flex justify-content-between">
                     <div class="col-md-4 invoice-col">
                        <strong>From : </strong><br>
                        <strong><?= $settings['app_name'] ?></strong><br>
                        Email: <?= $settings['support_email'] ?><br>
                        Customer Care: <?= $settings['support_number'] ?><br>
                        Admin State: <?= $settings['admin_store_state'] ?><br>
                        <?php if (isset($settings['tax_name']) && !empty($settings['tax_name'])) { ?>
                           <b><?= $settings['tax_name'] ?></b>: <?= $settings['tax_number'] ?><br>
                        <?php } ?>
                        <?php foreach ($items as $row) {
                        }
                        if ($row['type'] != 'digital_product') {
                        } ?>
                        <?php if (!empty($items[0]['delivery_boy'])) { ?>Delivery By:
                           <?= $items[0]['delivery_boy'] ?><?php } ?>
                     </div>
                     <!-- /.col -->
                     <div class="col-md-4 invoice-col">
                        <?php
                        $customer_address_state = fetch_details('addresses', ['id' => $order_detls[0]['address_id']], 'state')[0]['state'];
                        $settings['admin_store_state'];
                        ?>
                        <strong>TO: </strong><br>
                        <strong><?= ($order_detls[0]['user_name'] != "") ? $order_detls[0]['user_name'] : $order_detls[0]['uname'] ?></strong><br>
                        <p>
                           <?php if (!isset($order_detls[0]['is_pos_order']) || $order_detls[0]['is_pos_order'] != 1) { ?>
                              <strong> Username:</strong> <?= $order_detls[0]['user_name'] ?><br>
                           <?php } ?>
                           <strong>Address:</strong> <?= $order_detls[0]['address'] ?><br>
                           <strong>State/UT Code:</strong> <?= $customer_address_state ?><br>
                           <strong>Place of Supply:</strong> <?= $settings['admin_store_state'] ?><br>
                           <?php $mobile = (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) ? str_repeat("X", strlen($order_detls[0]['mobile']) - 3) . substr($order_detls[0]['mobile'], -3) : $order_detls[0]['mobile'];
                           $recipient_contact = (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) ? str_repeat("X", strlen($order_detls[0]['recipient_contact']) - 3) . substr($order_detls[0]['recipient_contact'], -3) : $order_detls[0]['recipient_contact']; ?>
                           <strong>Mobile :</strong>
                           <?= ($recipient_contact != "") ? $recipient_contact : $mobile; ?></strong><br>
                           <strong><?= (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) ? str_repeat("X", strlen($order_detls[0]['email']) - 3) . substr($order_detls[0]['email'], -3) : $order_detls[0]['email']; ?></strong><br>
                        </p>
                     </div>
                     <!-- /.col -->
                     <?php if (!empty($order_detls[0]['id'])) { ?>
                        <div class="col-md-4 invoice-col">
                           <br> <b>Retail Invoice</b>
                           <br> <b>Order ID : </b>#
                           <?= $order_detls[0]['id'] ?>
                           <br> <b>Date: </b>
                           <?= $order_detls[0]['date_added'] ?>
                           <br>
                        <?php } ?>
                     </div>
                  </div>
                  <!-- /.row -->
                  <!-- Table row -->
                  <div class="row m-3">
                     <div class="col-xs-12 table-responsive">
                        <table class="table borderless text-center text-sm">
                           <thead class="">
                              <tr>
                                 <th>Sr No.</th>
                                 <th>Product Code</th>
                                 <?= (isset($row['product_identity']) && !empty($row['product_identity'])) ? "<th>Product Identity</th>" : "" ?>
                                 <th>Name</th>
                                 <?php if ($row['type'] != 'digital_product') { ?>
                                    <th>Variants</th>
                                 <?php } else { ?>
                                    <th></th>
                                 <?php } ?>
                                 <th>Price</th>
                                 <th>Tax (%)</th>
                                 <th class="">Tax Amount (<?= $settings['currency'] ?>)</th>
                                 <th>Total price</th>
                                 <th>Qty</th>
                                 <th>SubTotal (<?= $settings['currency'] ?>)</th>
                                 <th class="d-none">Order Status</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php
                              $i = 1;
                              $total = $quantity = $total_tax = $final_sub_total = 0;

                              // Retrieve all tax IDs from order details
                              $order_tax_ids = isset($order_detls[0]['tax_id']) ? explode(',', $order_detls[0]['tax_id']) : array();
                              $taxes = [];
                              foreach ($order_tax_ids as $tax_id) {
                                 $tax = get_tax_by_id($tax_id);
                                 if ($tax) {
                                    $taxes[] = $tax;
                                 }
                              }

                              foreach ($items as $row) {
                                 $total_tax_for_row = 0;
                                 $product_id = $row['product_id'];  // Ensure $row['product_id'] contains the correct product ID
                              
                                 if ($row['type'] != 'digital_product') {
                                    $product_variants = get_variants_values_by_id($row['product_variant_id']);
                                    $product_variants = isset($product_variants[0]['variant_values']) && !empty($product_variants[0]['variant_values']) ? str_replace(',', ' | ', $product_variants[0]['variant_values']) : '-';
                                 } else {
                                    $product_variants = '';
                                 }

                                 // Calculate the tax amount for this order item using the tax details retrieved
                                 $tax_amounts = [];
                                 foreach ($taxes as $tax) {
                                    $tax_amount_for_row = ($row['price'] * $tax['percentage']) / 100;
                                    $tax_amounts[] = ['title' => $tax['title'], 'amount' => $tax_amount_for_row];
                                    $total_tax_for_row += $tax_amount_for_row;
                                 }

                                 $sub_total = floatval($row['price']) * floatval($row['quantity']);
                                 $final_sub_total += $sub_total;
                                 $total += $sub_total;
                                 $quantity += floatval($row['quantity']);
                                 ?>
                                 <tr>
                                    <td><?= $i ?><br></td>
                                    <td><?= $row['product_variant_id'] ?><br></td>
                                    <?= (isset($row['product_identity']) && !empty($row['product_identity'])) ? "<td>" . $row['product_identity'] . "<br></td>" : "" ?>
                                    <td class="product_name"><?= isset($row['pname']) ? $row['pname'] : '' ?><br></td>
                                    <td class="product_variant"><?= $product_variants ?><br></td>
                                    <td>
                                       <?= $settings['currency'] . ' ' . number_format($row['price'] - $total_tax_for_row, 2) ?><br>
                                    </td>
                                    <td>
                                       <?php foreach ($taxes as $tax) { ?>
                                          <div class="tax-details">
                                             <span><?= $tax['title'] ?></span>
                                             <span>-</span>
                                             <span><?= $tax['percentage'] . '%' ?> </span>
                                          </div>
                                       <?php } ?>
                                    </td>
                                    <td>
                                       <?php foreach ($tax_amounts as $tax_amount) { ?>
                                          <div class="tax-amount">
                                             <span><?= $tax_amount['title'] ?></span>
                                             <span> - </span>
                                             <span><?= number_format($tax_amount['amount'], 2) ?></span>
                                          </div>
                                       <?php } ?>
                                       <div class="tax-total">
                                          <span><b>Total - <?= number_format($total_tax_for_row, 2) ?></b></span>
                                       </div>
                                    </td>
                                    <td><?= number_format($row['price'], 2) ?><br></td>
                                    <td><?= $row['quantity'] ?><br></td>
                                    <td><?= $settings['currency'] . ' ' . number_format($sub_total, 2); ?><br></td>
                                    <td class="d-none"><?= $row['active_status'] ?><br></td>
                                 </tr>
                                 <?php
                                 $i++;
                              }
                              ?>
                           </tbody>
                        </table>
                     </div>
                     <!-- /.col -->
                  </div>
                  <!-- /.row -->
                  <div class="row m-6 d-flex justify-content-end">
                     <div class="col-md-8">
                        <div class="table-responsive">
                           <table class="table table-borderless align-middle">
                              <tbody>

                                 <tr>
                                    <th class="text-start">Total Order Price (<?= $settings['currency'] ?>)</th>
                                    <td class="text-end">+ <?= number_format($total, 2) ?></td>
                                 </tr>

                                 <?php foreach ($items as $row) {
                                 } ?>
                                 <?php if ($row['type'] != 'digital_product') { ?>
                                    <tr>
                                       <th class="text-start">Delivery Charge (<?= $settings['currency'] ?>)</th>
                                       <td class="text-end">
                                          + <?php
                                          $total += floatval($order_detls[0]['delivery_charge']);
                                          echo number_format($order_detls[0]['delivery_charge'], 2);
                                          ?>
                                       </td>
                                    </tr>
                                 <?php } ?>

                                 <tr class="d-none">
                                    <th class="text-start">Tax (<?= $settings['currency'] ?>)</th>
                                    <td class="text-end">+ <?= $total_tax ?></td>
                                 </tr>

                                 <tr>
                                    <th class="text-start">Wallet Used (<?= $settings['currency'] ?>)</th>
                                    <td class="text-end">
                                       <?php
                                       $total -= floatval($order_detls[0]['wallet_balance']);
                                       echo '- ' . number_format($order_detls[0]['wallet_balance'], 2);
                                       ?>
                                    </td>
                                 </tr>
                                 <tr>
                                    <th class="text-start">Bulk Discount (<?= $settings['currency'] ?>)</th>
                                    <td class="text-end">
                                       <?php
                                       $total -= floatval($order_detls[0]['bulk_discount']);
                                       echo '- ' . number_format($order_detls[0]['bulk_discount'], 2);
                                       ?>
                                    </td>
                                 </tr>

                                 <?php if (isset($promo_code[0]['promo_code'])) { ?>
                                    <tr>
                                       <th class="text-start">
                                          Promo (<?= $promo_code[0]['promo_code'] ?>)
                                          Discount (<?= floatval($promo_code[0]['discount']); ?>
                                          <?= ($promo_code[0]['discount_type'] == 'percentage') ? '%' : '' ?>)
                                       </th>
                                       <td class="text-end">
                                          - <?php
                                          echo number_format($order_detls[0]['promo_discount'], 2);
                                          $total = $total - $order_detls[0]['promo_discount'];
                                          $total = max(0, $total);
                                          ?>
                                       </td>
                                    </tr>
                                 <?php } ?>

                                 <?php if (!empty($order_detls[0]['discount']) && $order_detls[0]['discount'] > 0) { ?>
                                    <tr>
                                       <th class="text-start">
                                          Special Discount (<?= $settings['currency'] ?>)
                                          (<?= $order_detls[0]['discount'] ?>%)
                                       </th>
                                       <td class="text-end">
                                          - <?php
                                          $special_discount = round($total * $order_detls[0]['discount'] / 100, 2);
                                          echo number_format($special_discount, 2);
                                          $total = floatval($total - $special_discount);
                                          $total = max(0, $total);
                                          ?>
                                       </td>
                                    </tr>
                                 <?php } ?>

                                 <!-- CUSTOM CHARGES -->
                                 <?php if (!empty($order_detls[0]['custom_charges'])): ?>
                                    <?php
                                    $custom_charges = json_decode($order_detls[0]['custom_charges'], true);
                                    if (is_array($custom_charges)):
                                       foreach ($custom_charges as $cc):
                                          ?>
                                          <tr>
                                             <th class="text-start">
                                                <?= htmlspecialchars(ucwords(str_replace('_', ' ', $cc['name']))) ?>
                                                (<?= htmlspecialchars($settings['currency']) ?>)
                                             </th>
                                             <td class="text-end">
                                                + <?= number_format((float) $cc['amount'], 2) ?>
                                                <?php $total += (float) $cc['amount']; ?>
                                             </td>
                                          </tr>
                                          <?php
                                       endforeach;
                                    endif;
                                    ?>
                                 <?php endif; ?>

                                 <tr class="border-top fw-bold">
                                    <th class="text-start">Final Total (<?= $settings['currency'] ?>)</th>
                                    <td class="text-end">
                                       <?php
                                       $final_total_with_charges = floatval($final_sub_total);
                                       $final_total_with_charges -= floatval($order_detls[0]['discount'] ?? 0);
                                       $final_total_with_charges -= floatval($order_detls[0]['promo_discount'] ?? 0);
                                       $final_total_with_charges += floatval($order_detls[0]['delivery_charge'] ?? 0);
                                       $final_total_with_charges += floatval($order_detls[0]['platform_fees'] ?? 0);

                                       $custom_total = 0;
                                       if (!empty($order_detls[0]['custom_charges'])) {
                                          $custom_charges = json_decode($order_detls[0]['custom_charges'], true);
                                          if (is_array($custom_charges)) {
                                             foreach ($custom_charges as $cc) {
                                                $custom_total += floatval($cc['amount'] ?? 0);
                                             }
                                          }
                                       }

                                       $final_total_with_charges += $custom_total;
                                       $final_total = max(0, $final_total_with_charges);
                                       echo number_format($total, 2);
                                       ?>
                                    </td>
                                 </tr>

                                 <?php if ($order_detls[0]['payment_method'] == "COD") { ?>
                                    <tr class="fw-bolder">
                                       <th class="text-start">
                                          Total Payable On COD (<?= $settings['currency'] ?>)
                                       </th>
                                       <td class="text-end">
                                          <?= number_format($order_detls[0]['total_payable'], 2) ?>
                                       </td>
                                    </tr>
                                 <?php } ?>

                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>

                  <!-- /.row -->
                  <!-- this row will not appear when printing -->
                  <div class="row m-3" id="section-not-to-print">
                     <div class="col-xs-12">
                        <button type='button' value='Print this page' onclick='{window.print()};'
                           class="btn btn-default"><i class="fa fa-print"></i> Print</button>
                     </div>
                  </div>
               </div>
               <!--/.card-->
            </div>
            <!--/.col-md-12-->
         </div>
         <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
   </section>
   <!-- /.content -->
</div>