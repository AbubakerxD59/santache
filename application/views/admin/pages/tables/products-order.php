<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header mt-4">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-12 col-md-8">
                    <h4 class="mb-2 mb-md-0">Manage Products Order</h4>
                </div>
                <div class="col-12 col-md-4 d-flex justify-content-md-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                            <li class="breadcrumb-item active">Products Orders</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div><!-- /.container-fluid -->        <div class="container-fluid">
            <div class="row">
                <!-- Filter Section -->
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Filter By Product Category</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3 align-items-end">
                                <div class="col-12 col-md-6 col-lg-4">
                                    <label for="category_parent" class="form-label">Category</label>
                                    <select name="category_parent" id="category_parent" class="form-select">
                                        <option value="">--Select Category--</option>
                                        <option value="0" selected="">All</option>
                                        <?php
                                        echo get_categories_option_html($categories);
                                        ?>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 col-lg-4">
                                    <button type="button" class="btn btn-outline-primary" id="row_order_search" onclick="search_category_wise_products()">
                                        <i class="fas fa-search me-1"></i>Search
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                <!-- Products List Section -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Products List</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($product_result)) { ?>
                                <!-- Desktop Container - Centered and Narrower -->
                                <div class="row justify-content-center d-none d-md-block">
                                    <div class="col-lg-10 col-xl-8 mx-auto">
                                        <!-- Header Row for Desktop -->
                                        <div class="row fw-bold border-bottom pb-2 mb-3">
                                            <div class="col-2 text-center">Display Order</div>
                                            <div class="col-4">Product Name</div>
                                            <div class="col-3 text-center">Image</div>
                                            <div class="col-3 text-center">Status</div>
                                        </div>
                                    </div>
                                </div>                                <!-- Sortable Products List -->
                                <div class="row justify-content-center">
                                    <div class="col-lg-10 col-xl-8">
                                        <div id="sortable">
                                            <?php
                                            $i = 0;
                                            foreach ($product_result as $row) {
                                            ?>
                                                <div class="card mb-3 border" id="product_id-<?= $row['id'] ?>">
                                                    <div class="card-body py-3">
                                                        <!-- Desktop Layout -->
                                                        <div class="row align-items-center d-none d-md-flex">
                                                            <div class="col-2 text-center">
                                                                <span class="badge bg-primary fs-6"><?= $row['row_order'] ?></span>
                                                            </div>
                                                            <div class="col-4">
                                                                <h6 class="mb-0"><?= $row['name'] ?></h6>
                                                            </div>
                                                            <div class="col-3 text-center">
                                                                <img src="<?= !empty($row['image']) ? base_url($row['image']) : base_url(NO_IMAGE); ?>"
                                                                    class="img-fluid rounded object-fit-cover" width="80"
                                                                    height="80" alt="<?= html_escape($row['name']); ?>"
                                                                    title="<?= html_escape($row['name']); ?>"
                                                                    onerror="this.onerror=null;this.src='<?= base_url(NO_IMAGE); ?>';">
                                                            </div>
                                                            <div class="col-3 text-center">
                                                                <?= $row['status'] == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>' ?>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Mobile Layout -->
                                                        <div class="d-block d-md-none">
                                                            <div class="row g-3">
                                                                <div class="col-12">
                                                                    <div class="d-flex justify-content-between align-items-start">
                                                                        <h6 class="mb-1"><?= $row['name'] ?></h6>
                                                                        <span class="badge bg-primary">Order: <?= $row['row_order'] ?></span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <img src="<?= base_url() . $row['image'] ?>" class="img-fluid rounded w-100" style="max-height: 120px; object-fit: cover;" alt="Product Image">
                                                                </div>
                                                                <div class="col-6 d-flex align-items-center justify-content-center">
                                                                    <div class="text-center">
                                                                        <small class="text-muted d-block mb-1">Status</small>
                                                                        <?= $row['status'] == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>' ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                                $i++;
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                  <!-- Save Button -->
                                <div class="row justify-content-center mt-4">
                                    <div class="col-lg-10 col-xl-8">
                                        <button type="button" class="btn btn-success btn-lg w-100" id="save_product_order">
                                            <i class="fas fa-save me-2"></i>Save Product Order
                                        </button>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="text-center py-5">
                                    <div class="mb-3">
                                        <i class="fas fa-box-open fa-3x text-muted"></i>
                                    </div>
                                    <h5 class="text-muted">No Products Available</h5>
                                    <p class="text-muted">No products available. Please add products to manage their order.</p>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>