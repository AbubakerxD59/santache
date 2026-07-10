<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header mt-4">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h4>SMS Gateway Settings</h4>
                </div>
                <div class="col-sm-4 d-flex justify-content-end">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">SMS Gateway Settings</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <?php

    use function PHPSTORM_META\type;

    $sms = json_encode($sms_gateway_settings);

    ?>
    <section class="content">
        <input type="hidden" id="sms_gateway_settings" name="sms_gateway_settings" value='<?= $sms ?>'>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <div class="nav" id="product-tab" role="tablist">
                            <nav class="w-100">
                                <ul class="nav nav-tabs">
                                    <li class="nav-item">
                                        <a class="nav-item nav-link product-nav-tab active" id="sms-gateway-config-tab"
                                            data-toggle="tab" href="#config-tab" role="tab" aria-controls="config-tab"
                                            aria-selected="false"><?= !empty($this->lang->line('sms_gateway_configuration')) ? $this->lang->line('sms_gateway_configuration') : 'SMS Gateway Configuration' ?></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-item nav-link product-nav-tab" id="sms-matrix-tab"
                                            data-toggle="tab" href="#sms-matrix" role="tab" aria-controls="sms-matrix"
                                            aria-selected="false"><?= !empty($this->lang->line('sms_matrix')) ? $this->lang->line('sms_matrix') : 'SMS Matrix' ?></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-item nav-link product-nav-tab" id="sms-templates-tab"
                                            data-toggle="tab" href="#sms-templates" role="tab"
                                            aria-controls="sms-templates"
                                            aria-selected="true"><?= !empty($this->lang->line('sms_tempates')) ? $this->lang->line('sms_tempates') : 'SMS Templates' ?></a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <div class="tab-content w-100" id="nav-tabContent">
                            <!-- sms gateway config -->
                            <div class="tab-pane fade active show" id="config-tab" role="tabpanel"
                                aria-labelledby="sms-gateway-config-tab">
                                <div class="align-items-center">
                                    <div class="">
                                        <div class="align-items-baseline d-flex mx-4">
                                            <p class="fw-bold gap-3">Are you confuse how to do ? </p>
                                            <a type="button" class="text-danger" data-toggle="modal"
                                                data-target="#sms_instuction_modal">
                                                Follow this for reference
                                            </a>
                                        </div>
                                        <form class="form-horizontal form-submit-event smsgateway_setting_form"
                                            action="<?= base_url('admin/Sms_gateway_settings/add_sms_data'); ?>"
                                            method="POST" id="smsgateway_setting_form" enctype="multipart/form-data">
                                            <div class="card-body">
                                                <div class="d-flex gap-4 mb-2">
                                                    <div class="col-md-6">
                                                        <label for="base_url" class="form-label">Base URL : </label>
                                                        </button>
                                                        <input type="text" class="form-control" id="base_url"
                                                            name="base_url"
                                                            value="<?= isset($sms_gateway_settings['base_url']) ? $sms_gateway_settings['base_url'] : '' ?>">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="sms_gateway_method"
                                                            class="form-label">Method</label>
                                                        <select id="sms_gateway_method" name="sms_gateway_method"
                                                            class="form-control col-md-5">
                                                            <option value="POST">POST</option>
                                                            <option value="GET">GET</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="py-3">
                                                    <h4 class="mb-3">Create Authorization Token </h4>
                                                    <hr>
                                                    <div class="d-flex gap-4 mb-2">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="converterInputAccountSID"
                                                                    class="form-label">Account SID</label>
                                                                <input type="text" id="converterInputAccountSID"
                                                                    class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="converterInputAuthToken"
                                                                    class="form-label">Auth Token</label>
                                                                <input type="text" id="converterInputAuthToken"
                                                                    class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <div class="col-md-4">
                                                            <button type="button" onClick="createHeader()"
                                                                class="btn btn-success">Create</button>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <h4 id="basicToken"></h4>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="">
                                                    <ul class="nav nav-tabs mb-4">
                                                        <li class="nav-item">
                                                            <a class="nav-item nav-link product-nav-tab active"
                                                                id="product-header-tab" data-toggle="tab"
                                                                href="#product-header" role="tab"
                                                                aria-controls="product-header"
                                                                aria-selected="false"><?= !empty($this->lang->line('header')) ? $this->lang->line('header') : 'Header' ?></a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-item nav-link product-nav-tab "
                                                                id="product-body-tab" data-toggle="tab"
                                                                href="#product-body" role="tab"
                                                                aria-controls="product-body"
                                                                aria-selected="false"><?= !empty($this->lang->line('body')) ? $this->lang->line('body') : 'Body' ?></a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-item nav-link product-nav-tab "
                                                                id="product-params-tab" data-toggle="tab"
                                                                href="#product-params" role="tab"
                                                                aria-controls="product-params"
                                                                aria-selected="false"><?= !empty($this->lang->line('params')) ? $this->lang->line('params') : 'Params' ?></a>
                                                        </li>
                                                    </ul>
                                                    <div class="tab-content w-100" id="nav-tabContent">
                                                        <!-- header -->
                                                        <div class="tab-pane fade active show" id="product-header"
                                                            role="tabpanel" aria-labelledby="product-header-tab">
                                                            <div>
                                                                <div
                                                                    class="d-flex align-items-center justify-content-between mb-3">
                                                                    <h6 class="modal-titlen  fw-bold">Add Header data
                                                                    </h6>
                                                                    <a href="#" id="add_sms_header"
                                                                        class="btn btn-primary btn-sm mx-5">
                                                                        <i class="fa fa-plus"></i>
                                                                    </a>
                                                                </div>
                                                                <div class="card-body p-0">
                                                                    <div id="formdata_header_section" class="col-md-8">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- product body tab -->
                                                        <div class="tab-pane fade show" id="product-body"
                                                            role="tabpanel" aria-labelledby="product-body-tab">
                                                            <div class="row">
                                                                <ul class="nav nav-tabs">
                                                                    <li class="nav-item">
                                                                        <a class="nav-item nav-link product-nav-tab active"
                                                                            id="product-text-tab" data-toggle="tab"
                                                                            href="#product-text" role="tab"
                                                                            aria-controls="product-text"
                                                                            aria-selected="false"><?= !empty($this->lang->line('text/JSON')) ? $this->lang->line('text/JSON') : 'text/JSON' ?></a>
                                                                    </li>
                                                                    <li class="nav-item">
                                                                        <a class="nav-item nav-link product-nav-tab "
                                                                            id="product-formdata-tab" data-toggle="tab"
                                                                            href="#product-formdata" role="tab"
                                                                            aria-controls="product-formdata"
                                                                            aria-selected="false"><?= !empty($this->lang->line('formdata')) ? $this->lang->line('formdata') : 'Formdata' ?></a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <!-- params -->
                                                        <div class="tab-pane fade" id="product-params" role="tabpanel"
                                                            aria-labelledby="product-params-tab">
                                                            <div>
                                                                <div
                                                                    class="d-flex align-items-center justify-content-between mb-3">
                                                                    <h5 class="modal-title">Add Params </h5>
                                                                    <a href="#" id="add_sms_params"
                                                                        class="btn btn-primary btn-sm mx-5">
                                                                        <i class="fa fa-plus"></i>
                                                                    </a>
                                                                </div>
                                                                <div class="card-body p-0">
                                                                    <div id="formdata_params_section" class="col-md-8">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="tab-content p-3 w-100" id="nav-tabContent">

                                                        <!-- Product Text Tab -->
                                                        <div class="tab-pane fade" id="product-text" role="tabpanel">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-semibold">Product
                                                                            Text</label>
                                                                        <textarea name="text_format_data"
                                                                            class="form-control text_format_data"
                                                                            rows="6"
                                                                            placeholder="Place some text here"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Product Form Data Tab -->
                                                        <div class="tab-pane fade" id="product-formdata" role="tabpanel"
                                                            aria-labelledby="product-formdata-tab">

                                                            <!-- Header -->
                                                            <div
                                                                class="d-flex align-items-center justify-content-between mb-3">
                                                                <h5 class="mb-0 fw-semibold">
                                                                    Add Body Data Parameters
                                                                </h5>

                                                                <button type="button" id="add_sms_body"
                                                                    class="btn btn-primary btn-sm">
                                                                    <i class="fa fa-plus me-1"></i> Add
                                                                </button>
                                                            </div>

                                                            <!-- Body -->
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <div id="formdata_section" class="row g-3">
                                                                        <!-- dynamic form rows will append here -->
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>


                                                    <div class="tab-content p-3 w-100" id="nav-tabContent">
                                                        <div class="tab-pane fade show" id="product-body"
                                                            role="tabpanel" aria-labelledby="product-body-tab">
                                                            <div class="row">
                                                                <ul class="nav nav-tabs">
                                                                    <li class="nav-item">
                                                                        <a class="nav-item nav-link product-nav-tab active"
                                                                            id="product-text-tab" data-toggle="tab"
                                                                            href="#product-text" role="tab"
                                                                            aria-controls="product-text"
                                                                            aria-selected="false"><?= !empty($this->lang->line('text/JSON')) ? $this->lang->line('text/JSON') : 'text/JSON' ?></a>
                                                                    </li>
                                                                    <li class="nav-item">
                                                                        <a class="nav-item nav-link product-nav-tab "
                                                                            id="product-formdata-tab" data-toggle="tab"
                                                                            href="#product-formdata" role="tab"
                                                                            aria-controls="product-formdata"
                                                                            aria-selected="false"><?= !empty($this->lang->line('formdata')) ? $this->lang->line('formdata') : 'Formdata' ?></a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex m-2">
                                                        <pre class="">{only_mobile_number}</pre>
                                                        <pre>{mobile_number_with_country_code}</pre>
                                                        <pre>{country_code}</pre>
                                                        <pre>{message}</pre>
                                                    </div>

                                                    <div class="form-group ml-3">
                                                        <button type="reset" class="btn btn-warning">Reset</button>
                                                        <button class="btn btn-success" id="sms_gateway_submit">Update
                                                            SMS Gateway Settings</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- sms matrix -->
                            <div class="tab-pane fade " id="sms-matrix" role="tabpanel"
                                aria-labelledby="sms-matrix-tab">
                                <div class="align-items-center">
                                    <form class="form-horizontal form-submit-event update_notification_module"
                                        action="<?= base_url('admin/Sms_gateway_settings/update_notification_module'); ?>"
                                        method="POST" id="add_product_form" enctype="multipart/form-data">
                                        <div class=" row">
                                            <div class="table-responsive table-responsive-sm">
                                                <?php
                                                $actions = [
                                                    'customer',
                                                    'admin',
                                                    'delivery_boy',
                                                    'notification_via_sms',
                                                    'notification_via_mail'
                                                ];
                                                ?>
                                                <table class="table permission-table mb-4">
                                                    <tr>
                                                        <th>Module / Permissions</th>

                                                        <?php foreach ($actions as $row) { ?>
                                                            <th><?= ucwords(str_replace('_', ' ', $row)) ?></th>
                                                        <?php } ?>
                                                    </tr>

                                                    <tbody>
                                                        <?php foreach ($notification_modules as $key => $value) { ?>
                                                            <tr>
                                                                <td><?= ucwords(str_replace('_', ' ', $key)) ?></td>

                                                                <?php for ($i = 0; $i < count($actions); $i++) {

                                                                    $index = array_search($actions[$i], $value);

                                                                    if ($index !== false) {

                                                                        $checked = '';

                                                                        if (isset($send_notification_settings)) {
                                                                            $checked = isset($send_notification_settings[$key][$value[$index]]) ? 'checked' : '';
                                                                        } else {
                                                                            $checked = 'checked';
                                                                        }
                                                                        ?>
                                                                        <td>
                                                                            <a class="toggle form-switch" href="javascript:void(0)">
                                                                                <input type="checkbox"
                                                                                    name="<?= 'permissions[' . $key . '][' . $value[$index] . ']' ?>"
                                                                                    class="form-check-input system-users-switch"
                                                                                    role="switch"
                                                                                    <?= $checked ?>>
                                                                            </a>
                                                                        </td>
                                                                    <?php } else { ?>
                                                                        <td></td>
                                                                    <?php } ?>

                                                                <?php } ?>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>

                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-success" id="submit_btn">Update
                                                        Permissions</button>
                                                </div>

                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- sms templates -->
                            <div class="tab-pane fade" id="sms-templates" role="tabpanel"
                                aria-labelledby="sms-templates-tab">
                                <div class="align-items-center">
                                    <div class="">
                                        <div class="card card-info">
                                            <!-- form start -->
                                            <form class="form-horizontal form-submit-event add_sms"
                                                action="<?= base_url('admin/custom_sms/add_sms'); ?>" method="POST"
                                                id="add_product_form" enctype="multipart/form-data">
                                                <?php
                                                if (isset($fetched_data[0]['id'])) {
                                                    ?>
                                                    <input type="hidden" id="edit_custom_sms" name="edit_custom_sms"
                                                        value="<?= @$fetched_data[0]['id'] ?>">
                                                    <input type="hidden" id="update_id" name="update_id" value="1">
                                                    <input type="hidden" id="udt_title"
                                                        value="<?= @$fetched_data[0]['title'] ?>">
                                                    <?php
                                                }
                                                ?>

                                                <div class=" card-body">
                                                    <div class="form-group row mb-3">
                                                        <label for="type" class="col-sm-2 control-label">Types <span
                                                                class='text-danger text-sm'> * </span></label>
                                                        <div class="col-sm-10">
                                                            <select name="type" class="form-control type">
                                                                <option value=" ">Select Types</option>
                                                                <?php foreach ($notification_modules as $key => $value) { ?>
                                                                    <option value="<?= $key ?>"
                                                                        <?= (isset($fetched_data[0]['id']) && $fetched_data[0]['type'] == $key) ? "Selected" : "" ?>><?= ucwords(str_replace('_', ' ', $key)) ?>
                                                                    </option>
                                                                    <?php
                                                                } ?>
                                                            </select>
                                                            <?php ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row mb-3">
                                                        <label for="title" class="col-sm-2 col-form-label">Title <span
                                                                class='text-danger text-sm'>*</span></label>
                                                        <div class="col-sm-10">
                                                            <input type="text" name="title" id="update_title"
                                                                class="form-control update_title"
                                                                placeholder="Title Name"
                                                                value="<?= (isset($fetched_data[0]['title'])) ? $fetched_data[0]['title'] : ""; ?>" />
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="form-group row mb-3 place_order <?= (isset($fetched_data[0]['id']) && $fetched_data[0]['type'] == 'place_order') ? '' : 'd-none' ?>">
                                                        <label for="message"
                                                            class="col-sm-2 col-form-label"></label></label>
                                                        <?php
                                                        $hashtag = get_notification_variables();
                                                        foreach ($hashtag as $row) { ?>
                                                            <div class="col">
                                                                <div class="hashtag_input"><?= $row ?></div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="form-group row mb-3">
                                                        <label for="message"
                                                            class="col-sm-2 col-form-label">Message<span
                                                                class='text-danger text-sm'>*</span></label>
                                                        <div class="col-sm-10">
                                                            <textarea name="message" id="text-box"
                                                                class="form-control text-box"
                                                                placeholder="Place some text here"><?= (isset($fetched_data[0]['id'])) ? $fetched_data[0]['message'] : ''; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="form-group row mb-3 place_order <?= (isset($fetched_data[0]['id']) && $fetched_data[0]['type'] == 'place_order') ? '' : 'd-none' ?>">
                                                        <label for="message"
                                                            class="col-sm-2 col-form-label"></label></label>

                                                        <?php
                                                        $hashtag = get_notification_variables();
                                                        foreach ($hashtag as $row) { ?>
                                                            <div class="col">
                                                                <div class="hashtag"><?= $row ?></div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div
                                                        class="form-group row mb-3 settle_cashback_discount <?= (isset($fetched_data[0]['id']) && $fetched_data[0]['type'] == 'settle_cashback_discount') ? '' : 'd-none' ?>">
                                                        <label for="message"
                                                            class="col-sm-2 col-form-label"></label></label>
                                                        <?php
                                                        $hashtag = get_notification_variables();
                                                        foreach ($hashtag as $row) { ?>
                                                            <div class="col">
                                                                <div class="hashtag"><?= $row ?></div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div
                                                        class="form-group row mb-3 customer_order_received <?= (isset($fetched_data[0]['id']) && $fetched_data[0]['type'] == 'customer_order_received') ? '' : 'd-none' ?>">
                                                        <label for="message"
                                                            class="col-sm-2 col-form-label"></label></label>
                                                        <?php
                                                        $hashtag = get_notification_variables();
                                                        foreach ($hashtag as $row) { ?>
                                                            <div class="col">
                                                                <div class="hashtag"><?= $row ?></div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div
                                                        class="form-group row mb-3 customer_order_processed <?= (isset($fetched_data[0]['id']) && $fetched_data[0]['type'] == 'customer_order_processed') ? '' : 'd-none' ?>">
                                                        <label for="message"
                                                            class="col-sm-2 col-form-label"></label></label>
                                                        <?php
                                                        $hashtag = get_notification_variables();
                                                        foreach ($hashtag as $row) { ?>
                                                            <div class="col">
                                                                <div class="hashtag"><?= $row ?></div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div
                                                        class="form-group row mb-3 customer_order_shipped <?= (isset($fetched_data[0]['id']) && $fetched_data[0]['type'] == 'customer_order_shipped') ? '' : 'd-none' ?>">
                                                        <label for="message"
                                                            class="col-sm-2 col-form-label"></label></label>
                                                        <?php
                                                        $hashtag = get_notification_variables();
                                                        foreach ($hashtag as $row) { ?>
                                                            <div class="col">
                                                                <div class="hashtag"><?= $row ?></div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div
                                                        class="form-group row mb-3 customer_order_delivered <?= (isset($fetched_data[0]['id']) && $fetched_data[0]['type'] == 'customer_order_delivered') ? '' : 'd-none' ?>">
                                                        <label for="message"
                                                            class="col-sm-2 col-form-label"></label></label>
                                                        <?php
                                                        $hashtag = get_notification_variables();
                                                        foreach ($hashtag as $row) { ?>
                                                            <div class="col">
                                                                <div class="hashtag"><?= $row ?></div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div
                                                        class="form-group row mb-3 customer_order_cancelled <?= (isset($fetched_data[0]['id']) && $fetched_data[0]['type'] == 'customer_order_cancelled') ? '' : 'd-none' ?>">
                                                        <label for="message"
                                                            class="col-sm-2 col-form-label"></label></label>
                                                        <?php
                                                        $hashtag = get_notification_variables();
                                                        foreach ($hashtag as $row) { ?>
                                                            <div class="col">
                                                                <div class="hashtag"><?= $row ?></div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div
                                                        class="form-group row mb-3 customer_order_returned <?= (isset($fetched_data[0]['id']) && $fetched_data[0]['type'] == 'customer_order_returned') ? '' : 'd-none' ?>">
                                                        <label for="message"
                                                            class="col-sm-2 col-form-label"></label></label>
                                                        <?php
                                                        $hashtag = get_notification_variables();
                                                        foreach ($hashtag as $row) { ?>
                                                            <div class="col">
                                                                <div class="hashtag"><?= $row ?></div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div
                                                        class="form-group row mb-3 customer_order_returned_request_approved <?= (isset($fetched_data[0]['id']) && $fetched_data[0]['type'] == 'customer_order_returned_request_approved') ? '' : 'd-none' ?>">
                                                        <label for="message"
                                                            class="col-sm-2 col-form-label"></label></label>
                                                        <?php
                                                        $hashtag = get_notification_variables();
                                                        foreach ($hashtag as $row) { ?>
                                                            <div class="col">
                                                                <div class="hashtag"><?= $row ?></div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div
                                                        class="form-group row mb-3 customer_order_returned_request_decline <?= (isset($fetched_data[0]['id']) && $fetched_data[0]['type'] == 'customer_order_returned_request_decline') ? '' : 'd-none' ?>">
                                                        <label for="message"
                                                            class="col-sm-2 col-form-label"></label></label>
                                                        <?php
                                                        $hashtag = get_notification_variables();
                                                        foreach ($hashtag as $row) { ?>
                                                            <div class="col">
                                                                <div class="hashtag"><?= $row ?></div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div
                                                        class="form-group row mb-3 wallet_transaction <?= (isset($fetched_data[0]['id']) && $fetched_data[0]['type'] == 'wallet_transaction') ? '' : 'd-none' ?>">
                                                        <label for="message"
                                                            class="col-sm-2 col-form-label"></label></label>
                                                        <?php
                                                        $hashtag = get_notification_variables();
                                                        foreach ($hashtag as $row) { ?>
                                                            <div class="col">
                                                                <div class="hashtag"><?= $row ?></div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div
                                                        class="form-group row mb-3 ticket_status <?= (isset($fetched_data[0]['id']) && $fetched_data[0]['type'] == 'ticket_status') ? '' : 'd-none' ?>">
                                                        <label for="message"
                                                            class="col-sm-2 col-form-label"></label></label>
                                                        <?php
                                                        $hashtag = ['< application_name >'];
                                                        foreach ($hashtag as $row) { ?>
                                                            <div class="col">
                                                                <div class="hashtag"><?= $row ?></div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div
                                                        class="form-group row mb-3 ticket_message <?= (isset($fetched_data[0]['id']) && $fetched_data[0]['type'] == 'ticket_message') ? '' : 'd-none' ?>">
                                                        <label for="message"
                                                            class="col-sm-2 col-form-label"></label></label>
                                                        <?php
                                                        $hashtag = ['< application_name >'];
                                                        foreach ($hashtag as $row) { ?>
                                                            <div class="col">
                                                                <div class="hashtag"><?= $row ?></div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div
                                                        class="form-group row mb-3 bank_transfer_receipt_status <?= (isset($fetched_data[0]['id']) && $fetched_data[0]['type'] == 'bank_transfer_receipt_status') ? '' : 'd-none' ?>">
                                                        <label for="message"
                                                            class="col-sm-2 col-form-label"></label></label>
                                                        <?php
                                                        $hashtag = get_notification_variables();
                                                        foreach ($hashtag as $row) { ?>
                                                            <div class="col">
                                                                <div class="hashtag"><?= $row ?></div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div
                                                        class="form-group row mb-3 bank_transfer_proof <?= (isset($fetched_data[0]['id']) && $fetched_data[0]['type'] == 'bank_transfer_proof') ? '' : 'd-none' ?>">
                                                        <label for="message"
                                                            class="col-sm-2 col-form-label"></label></label>
                                                        <?php
                                                        $hashtag = get_notification_variables();
                                                        foreach ($hashtag as $row) { ?>
                                                            <div class="col">
                                                                <div class="hashtag"><?= $row ?></div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <button type="reset" class="btn btn-warning">Reset</button>
                                                        <button type="submit" class="btn btn-success"
                                                            id="submit_btn"><?= (isset($fetched_data[0]['id'])) ? 'Update Custom message ' : 'Add Custom message ' ?></button>`
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="main-content mt-4">
                                        <div class="card content-area p-4">
                                            <div class="card-head">
                                                <h4 class="card-title">Custom message List</h4>
                                            </div>
                                            <div class="card-innr">
                                                <div class="gaps-1-5x"></div>
                                                <table class='table-striped' data-toggle="table"
                                                    data-url="<?= base_url('admin/custom_sms/view_sms') ?>"
                                                    data-click-to-select="true" data-side-pagination="server"
                                                    data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]"
                                                    data-search="true" data-show-columns="true" data-show-refresh="true"
                                                    data-trim-on-search="false" data-sort-name="id"
                                                    data-sort-order="desc" data-mobile-responsive="true" data-toolbar=""
                                                    data-show-export="true" data-maintain-selected="true"
                                                    data-export-types='["txt","excel"]'
                                                    data-export-options='{ "fileName": "custom-sms-list","ignoreColumn": ["operate"] }'
                                                    data-query-params="queryParams">
                                                    <thead>
                                                        <tr>
                                                            <th data-field="id" data-sortable="true">ID</th>
                                                            <th data-field="title" data-sortable="false">Title</th>
                                                            <th data-field="type" data-sortable="true">Type</th>
                                                            <th data-field="message" data-sortable="true">Message</th>
                                                            <th data-field="operate" data-sortable="false">Action</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div><!-- .card-innr -->
                                        </div><!-- .card -->
                                    </div>
                                </div>
                            </div>



                            <!-- Modal -->
                            <div class="modal fade sms-modal" id="sms-gateway-modal" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">Custom message </h5>
                                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- form start -->

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal -->
                            <div class="modal fade bd-example-modal-lg" id="sms_instuction_modal" tabindex="-1"
                                role="dialog" aria-labelledby="sms_instuction_modal_Label" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="sms_instuction_modal_Label">Sms Gateway
                                                Configuration</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <ul>
                                                <li class="my-4">Read and follow instructions carefully while
                                                    configuration sms gateway setting </li>

                                                <li class="my-4">Firstly open your sms gateway account . You can find
                                                    api keys in your account -> API keys & credentials -> create api key
                                                </li>
                                                <li class="my-4">After create key you can see here Account sid and auth
                                                    token </li>
                                                <div class="simplelightbox-gallery">
                                                    <a href="<?= base_url('assets/admin_old/images/base_url_and_params.png') ?>"
                                                        target="_blank">
                                                        <img src="<?= base_url('assets/admin/images/base_url_and_params.png') ?>"
                                                            class="w-100">
                                                    </a>
                                                </div>
                                                <li class="my-4">For Base url Messaging -> Send an SMS</li>
                                                <div class="simplelightbox-gallery">
                                                    <a href="<?= base_url('assets/admin_old/images/api_key_and_token.png') ?>"
                                                        target="_blank">
                                                        <img src="<?= base_url('assets/admin/images/api_key_and_token.png') ?>"
                                                            class="w-100">
                                                    </a>
                                                </div>
                                                <li class="my-4">check this for admin panel settings</li>
                                                <div class="simplelightbox-gallery">
                                                    <a href="<?= base_url('assets/admin_old/images/sms_gateway_1.png') ?>"
                                                        target="_blank">
                                                        <img src="<?= base_url('assets/admin/images/sms_gateway_1.png') ?>"
                                                            class="w-100">
                                                    </a>
                                                </div>
                                                <div class="simplelightbox-gallery">
                                                    <a href="<?= base_url('assets/admin_old/images/sms_gateway_2.png') ?>"
                                                        target="_blank">
                                                        <img src="<?= base_url('assets/admin/images/sms_gateway_2.png') ?>"
                                                            class="w-100">
                                                    </a>
                                                </div>
                                                <li class="my-4"><b>Make sure you entered valid data as per instructions
                                                        before proceed</b></li>
                                            </ul>
                                        </div>
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
