<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header mt-4">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h4>Manage Blogs</h4>
                </div>
                <div class="col-sm-4 d-flex justify-content-end">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Blogs</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->

        <div class="container-fluid">
            <div class="row">
                <div class="modal fade edit-modal-lg" id="category_form" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Edit Blog</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                                </button>
                            </div>
                            <div class="modal-body p-0">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 ">
                    <div class="card content-area p-4">

                        <div class="card-innr" id="list_view_html">
                            <div class="card-head d-flex justify-content-between align-items-center">
                                <div class="col-md-3">
                                    <label for="zipcode" class="col-form-label">Filter By Product Category</label>
                                    <select id="category_parent" name="category_parent">
                                        <option value=""><?= (isset($categories) && empty($categories)) ? 'No Categories Exist' : 'Select Categories' ?>
                                        </option>
                                        <?php foreach ($fetched_data as $categories) { ?>
                                            <option value="<?= $categories['id'] ?>" <?= (isset($categories[0]['id']) && $categories[0]['id'] == $categories['id']) ? 'selected' : "" ?>><?= $categories['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <a href="<?= base_url() . 'admin/blogs/create_blog' ?>" class="btn btn-block  btn-outline-primary btn-sm">Add Blogs</a>
                            </div>


                            <div class="gaps-1-5x"></div>
                            <table class='table-striped' id='blog_table' data-toggle="table" data-url="<?= base_url('admin/blogs/view_blogs') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-export-options='{
                        "fileName": "category-list",
                        "ignoreColumn": ["operate"] 
                        }' data-query-params="blog_category_query_params">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable="true" data-visible='false'>ID</th>
                                        <th data-field="title" data-sortable="false">Title</th>
                                        <th data-field="image" data-sortable="true">Image</th>
                                        <th data-field="description" data-sortable="true">Description</th>
                                        <th data-field="status" data-sortable="true">Status</th>
                                        <th data-field="operate" data-sortable="true">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div><!-- .card-innr -->
                        <div id="tree_view_html">
                        </div>
                    </div><!-- .card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>