<div class="content-wrapper">
    <section class="content-header mt-4">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h4>Manage Categories Order</h4>
                </div>
                <div class="col-sm-4 d-flex justify-content-end">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Categories Orders</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 main-content">
                    <div class="card content-area p-4">
                        <div class="card-header border-0">
                        </div>
                        <div class="card-innr">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-12 offset-md-3">
                                        <label for="subcategory_id" class="col-form-label p-2 fs-5 fw-bold">Category
                                            List</label>

                                        <div class="table-responsive">

                                            <table class="table table-hover table-bordered table-striped text-center">
                                                <thead class="container-bg-secondary">
                                                    <tr>
                                                        <th class="align-middle">Display Order</th>
                                                        <th class="align-middle text-start">Name</th>
                                                        <th class="align-middle" >Image</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="move order-container" id="sortable">
                                                    <?php
                                                    $i = 0;
                                                    if (!empty($categories)) {
                                                        foreach ($categories as $row) {
                                                            ?>
                                                            <tr class="align-middle" style="cursor: move;"
                                                                id="category_id-<?= $row['id'] ?>">
                                                                <td><span
                                                                        class="badge bg-primary fs-6"><?= $row['row_order'] ?></span>
                                                                </td>
                                                                <td class="text-start fw-bold"><?= $row['name'] ?></td>
                                                                <td><img src="<?= $row['image'] ?>" class="img-thumbnail"
                                                                        style="height: 60px; width: 60px; object-fit: cover;">
                                                                </td>
                                                            </tr>
                                                            <?php
                                                            $i++;
                                                        }
                                                    } else {
                                                        ?>
                                                        <tr>
                                                            <td colspan="3" class="text-center text-muted">No Categories
                                                                Exist</td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php if (!empty($categories)) { ?>
                                            <button type="button" class="btn btn-block btn-success btn-lg mt-3"
                                                id="save_category_order">Save Order</button>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
    </section>
</div>