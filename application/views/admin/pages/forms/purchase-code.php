<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header mt-4">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h4>eShop Purchase Code Validator</h4>
                </div>
                <div class="col-sm-4 d-flex justify-content-end">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Purchase Code</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <form class="form-horizontal form-submit-event" action="<?= base_url('admin/purchase-code/validator'); ?>" method="POST" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <label for="web_purchase_code" class="form-label">eShop Purchase Code for Web<span class='text-danger text-sm'>*</span></label>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="web_purchase_code" placeholder="Enter your purchase code here" name="web_purchase_code" value="">
                                    </div>
                                    <div class="col-sm-3">
                                        <button class="btn btn-primary" type="button" id="de_register_web_purchase_code" value="<?= $web_doctor_brown; ?>">De-Register Web Purchase Code</button>
                                    </div>
                                    <?php $web_doctor_brown = get_settings('web_doctor_brown', true);
                                    if (!empty($web_doctor_brown) && isset($web_doctor_brown['code_bravo'])) { ?>
                                        <div class="alert alert-success m-2">
                                            Your system is successfully registered with us! Enjoy selling online!
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <label for="app_purchase_code" class="form-label">eShop Purchase Code for App<span class='text-danger text-sm'>*</span></label>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="purchase_code" placeholder="Enter your purchase code here" name="app_purchase_code" value="">
                                    </div>
                                    <div class="col-sm-3">
                                        <button class="btn btn-primary" type="submit" id="de_register_app_purchase_code" value="<?= $doctor_brown; ?>">De-Register App Purchase Code</button>
                                    </div>
                                    <?php $doctor_brown = get_settings('doctor_brown', true);
                                    if (!empty($doctor_brown) && isset($doctor_brown['code_bravo'])) { ?>
                                        <div class="alert alert-success m-2">
                                            Your system is successfully registered with us! Enjoy selling online!
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="form-group row mt-2 pb-3">
                                <label for="purchase_code" class="col-sm-2 col-form-label"></label>
                                <div class="form-group col-sm-10">
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-success"><?= (isset($fetched_data[0]['id'])) ? 'Update Ticket Type' : 'Register Now' ?></button>
                                </div>
                            </div>
                        </form>




                        <!-- De-Register Web Popup -->
                        <div class="modal fade mt-5" id="purchaseCodeModal" tabindex="-1" role="dialog" aria-labelledby="purchaseCodeModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="purchaseCodeModalLabel">Enter Your Purchase Code Here <span class='text-danger text-sm'>*</span></h5>
                                    </div>
                                    <div class="modal-body">
                                        <input type="text" id="modalPurchaseCode" class="form-control" placeholder="Enter your purchase code">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" id="submitPurchaseCode">De-Register</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- De-Register App Popup -->
                        <div class="modal fade mt-5" id="AppPurchaseCodeModal" tabindex="-1" role="dialog" aria-labelledby="AppPurchaseCode" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="AppPurchaseCode">Enter Your Purchase Code Here <span class='text-danger text-sm'>*</span></h5>
                                    </div>
                                    <div class="modal-body">
                                        <input type="text" id="modalAppPurchaseCode" class="form-control" placeholder="Enter your purchase code">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" id="submitAppPurchaseCode">De-Register</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>