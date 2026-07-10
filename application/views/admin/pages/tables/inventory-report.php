<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header mt-4">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-8">
                    <h4>View Inventory Report</h4>
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Inventory Report</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->

        <div class="container-fluid">
            <!-- Top Row: Date Filter -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header text-white">
                            <h5 class="card-title mb-0"><i class="fas fa-filter"></i> Filter Options</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="inventory_datepicker" class="form-label">Date Range:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="inventory_datepicker" placeholder="Select Date Range To Filter" autocomplete="off">
                                        <input type="hidden" id="start_date" class="form-control">
                                        <input type="hidden" id="end_date" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label>&nbsp;</label>
                                    <div>
                                        <button type="button" id="clear_filter" class="btn btn-secondary btn-block">
                                            <i class="fas fa-times"></i> Clear
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Second Row: Charts -->
            <div class="row mb-3">
                <!-- Sales Pie Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-chart-pie"></i> Top Products by Sales
                            </h5>
                        </div>
                        <div class="card-body">
                            <div id="inventory_pie_chart" style="height: 400px;">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Units Sold Donut Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-chart-donut"></i> Top Products by Units Sold
                            </h5>
                        </div>
                        <div class="card-body">
                            <div id="inventory_units_chart" style="height: 400px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Third Row: Data Table -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-table"></i> Inventory Report Data
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php $currency_symbol = get_settings('currency'); ?>
                            <table class='table-striped' id="inventory_table" data-toggle="table" data-url="<?= base_url('admin/Invoice/get_inventory_list') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="final_total" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-export-options='{"fileName": "inventory-list" }' data-query-params="inventory_query_params">
                                <thead>
                                    <tr>
                                        <th data-field="product_name" data-sortable='true'>Product Name</th>
                                        <th data-field="product_variant_id" data-sortable='false'>Product Variant Id</th>
                                        <th data-field="unit_of_measure" data-sortable='false'>Unit Of Measure</th>
                                        <th data-field="total_units_sold" data-sortable='false'>Total Units Sold</th>
                                        <th data-field="final_total" data-sortable='true'>Total Sales (<?= $currency_symbol ?>)</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>