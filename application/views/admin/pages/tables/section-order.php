<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header mt-4">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h4>Manage Section Order</h4>
                </div>
                <div class="col-sm-4 d-flex justify-content-end">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Section Order</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->

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
                                        <label for="subcategory_id" class="col-form-label p-2 fs-5 fw-bold">Section List</label>
                                        <div class="table-responsive">
                                            <table class="table table-borderless table">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Row Order Id</th>
                                                        <th>Style</th>
                                                        <th>Title</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="move order-container" id="sortable">
                                                    <?php
                                                    $i = 0;
                                                    foreach ($section_result as $row) {
                                                    ?>
                                                        <tr class="bg-gray-light align-items-center h-25" id="section_id-<?= $row['id'] ?>">
                                                            <td><span> <?= $i ?> </span></td>
                                                            <td><span> <?= $row['row_order'] ?> </span></td>
                                                            <td><span><?= str_replace('_', ' ', $row['style']) ?></span></td>
                                                            <td><span><?= $row['title'] ?></span></td>
                                                        </tr>
                                                    <?php
                                                        $i++;
                                                    }
                                                    ?>

                                                </tbody>
                                            </table>
                                        </div>
                                                                                <?php if (empty($section_result)) { ?>
                                            <div class="text-center mt-3">
                                                <p class="text-muted">No sections available. Please add sections to manage their order.</p>
                                            </div>
                                        <?php } else {?>
                                        <button type="button" class="btn btn-block btn-success btn-lg mt-3" id="save_section_order">Save</button>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div><!-- .card-innr -->
                        </div><!-- .card -->
                    </div>

                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>