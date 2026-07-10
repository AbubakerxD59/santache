<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header mt-4">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h4>Languages</h4>
                </div>
                <div class="col-sm-4 d-flex justify-content-end">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Languages</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-right m-2">
                        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#language-modal">Add Language</a>
                    </div>
                    <div class="card card-info">
                        <!-- form start -->
                        <div class="card-body">
                            <div class="col-md-12">
                                <div class="msg_error p-3 mb-3">Select the Language to add labels.</div>
                                <div class="form-group">
                                    <label class="mb-2" for="">Languages</label>
                                    <select name="selected_language" id="selected_language" class="form-control mb-2">
                                        <?php foreach ($languages as $row) { ?>
                                            <option value="<?= $row['id'] ?>" <?= (isset($_GET['id']) && $_GET['id'] == $row['id']) ? 'selected' : '' ?>><?= $row['language'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <form class="form-horizontal" id="update-language-form" action="<?= base_url('admin/language/save'); ?>" method="POST">
                                <input type="hidden" id="id" name="language_id" value="<?= $language['id'] ?>">
                                <div class="row">
                                    <hr class="w-100">
                                    <div class="col-md-12 text-center mb-2">
                                        <h4 class="h4">Labels</h4>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="checkbox" name="is_rtl" class="form-checkbox" id="is_rtl" value="<?= $language['is_rtl'] ?>" <?= ($language['is_rtl']) ? 'checked' : '' ?> />
                                            <label class="mb-2" for="is_rtl" class="control-checkbox">Enable RTL</label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="menu" class="control-checkbox">Menu</label>
                                            <input type="text" name="menu" class="form-control mb-2" value="<?= (isset($lang_labels['menu']) && !empty($lang_labels['menu'])) ? $lang_labels['menu'] : 'Menu'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="products" class="control-checkbox">Products</label>
                                            <input type="text" name="products" class="form-control mb-2" value="<?= (isset($lang_labels['products']) && !empty($lang_labels['products'])) ? $lang_labels['products'] : 'Products'; ?>" />
                                        </div>
                                    </div>
                                      <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="id" class="control-checkbox">id</label>
                                            <input type="text" name="id" class="form-control mb-2" value="<?= (isset($lang_labels['id']) && !empty($lang_labels['id'])) ? $lang_labels['id'] : 'id'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="product" class="control-checkbox">Product</label>
                                            <input type="text" name="product" class="form-control mb-2" value="<?= (isset($lang_labels['product']) && !empty($lang_labels['product'])) ? $lang_labels['product'] : 'Product'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="my_account" class="control-checkbox">My Account</label>
                                            <input type="text" name="my_account" class="form-control mb-2" value="<?= (isset($lang_labels['my_account']) && !empty($lang_labels['my_account'])) ? $lang_labels['my_account'] : 'My Account'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="my_orders" class="control-checkbox">My Orders</label>
                                            <input type="text" name="my_orders" class="form-control mb-2" value="<?= (isset($lang_labels['my_orders']) && !empty($lang_labels['my_orders'])) ? $lang_labels['my_orders'] : 'My Orders'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="order_tracking" class="control-checkbox">Order Tracking</label>
                                            <input type="text" name="order_tracking" class="form-control mb-2" value="<?= (isset($lang_labels['order_tracking']) && !empty($lang_labels['order_tracking'])) ? $lang_labels['order_tracking'] : 'Order Tracking'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="favorite" class="control-checkbox">Favorite</label>
                                            <input type="text" name="favorite" class="form-control mb-2" value="<?= (isset($lang_labels['favorite']) && !empty($lang_labels['favorite'])) ? $lang_labels['favorite'] : 'Favorite'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="login" class="control-checkbox">Login</label>
                                            <input type="text" name="login" class="form-control mb-2" value="<?= (isset($lang_labels['login']) && !empty($lang_labels['login'])) ? $lang_labels['login'] : 'Login'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="register" class="control-checkbox">Register</label>
                                            <input type="text" name="register" class="form-control mb-2" value="<?= (isset($lang_labels['register']) && !empty($lang_labels['register'])) ? $lang_labels['register'] : 'Register'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="about_us" class="control-checkbox">About Us</label>
                                            <input type="text" name="about_us" class="form-control mb-2" value="<?= (isset($lang_labels['about_us']) && !empty($lang_labels['about_us'])) ? $lang_labels['about_us'] : 'About Us'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="contact_us" class="control-checkbox">Contact Us</label>
                                            <input type="text" name="contact_us" class="form-control mb-2" value="<?= (isset($lang_labels['contact_us']) && !empty($lang_labels['contact_us'])) ? $lang_labels['contact_us'] : 'Contact Us'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="logout" class="control-checkbox">Logout</label>
                                            <input type="text" name="logout" class="form-control mb-2" value="<?= (isset($lang_labels['logout']) && !empty($lang_labels['logout'])) ? $lang_labels['logout'] : 'Logout'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="language" class="control-checkbox">Language</label>
                                            <input type="text" name="language" class="form-control mb-2" value="<?= (isset($lang_labels['language']) && !empty($lang_labels['language'])) ? $lang_labels['language'] : 'Language'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="shopping_cart" class="control-checkbox">Shopping Cart</label>
                                            <input type="text" name="shopping_cart" class="form-control mb-2" value="<?= (isset($lang_labels['shopping_cart']) && !empty($lang_labels['shopping_cart'])) ? $lang_labels['shopping_cart'] : 'Shopping Cart'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="close" class="control-checkbox">Close</label>
                                            <input type="text" name="close" class="form-control mb-2" value="<?= (isset($lang_labels['close']) && !empty($lang_labels['close'])) ? $lang_labels['close'] : 'Close'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="return_to_shop" class="control-checkbox">Return To Shop</label>
                                            <input type="text" name="return_to_shop" class="form-control mb-2" value="<?= (isset($lang_labels['return_to_shop']) && !empty($lang_labels['return_to_shop'])) ? $lang_labels['return_to_shop'] : 'Return To Shop'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="empty_cart_message" class="control-checkbox">Your Cart Is Empty</label>
                                            <input type="text" name="empty_cart_message" class="form-control mb-2" value="<?= (isset($lang_labels['empty_cart_message']) && !empty($lang_labels['empty_cart_message'])) ? $lang_labels['empty_cart_message'] : 'Your Cart Is Empty'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="view_cart" class="control-checkbox">View Cart</label>
                                            <input type="text" name="view_cart" class="form-control mb-2" value="<?= (isset($lang_labels['view_cart']) && !empty($lang_labels['view_cart'])) ? $lang_labels['view_cart'] : 'View Cart'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="faq" class="control-checkbox">FAQ'S</label>
                                            <input type="text" name="faq" class="form-control mb-2" value="<?= (isset($lang_labels['faq']) && !empty($lang_labels['faq'])) ? $lang_labels['faq'] : 'FAQs'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="compare" class="control-checkbox">Compare</label>
                                            <input type="text" name="compare" class="form-control mb-2" value="<?= (isset($lang_labels['compare']) && !empty($lang_labels['compare'])) ? $lang_labels['compare'] : 'Compare'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="pages" class="control-checkbox">Pages</label>
                                            <input type="text" name="pages" class="form-control mb-2" value="<?= (isset($lang_labels['pages']) && !empty($lang_labels['pages'])) ? $lang_labels['pages'] : 'Pages'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="social_media" class="control-checkbox">Social Media</label>
                                            <input type="text" name="social_media" class="form-control mb-2" value="<?= (isset($lang_labels['social_media']) && !empty($lang_labels['social_media'])) ? $lang_labels['social_media'] : 'Social Media'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="terms_and_condition" class="control-checkbox">Terms & Condition</label>
                                            <input type="text" name="terms_and_condition" class="form-control mb-2" value="<?= (isset($lang_labels['terms_and_condition']) && !empty($lang_labels['terms_and_condition'])) ? $lang_labels['terms_and_condition'] : 'Terms & Condition'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="privacy_policy" class="control-checkbox">Privacy Policy</label>
                                            <input type="text" name="privacy_policy" class="form-control mb-2" value="<?= (isset($lang_labels['privacy_policy']) && !empty($lang_labels['privacy_policy'])) ? $lang_labels['privacy_policy'] : 'Privacy Policy'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="reviews" class="control-checkbox">Reviews</label>
                                            <input type="text" name="reviews" class="form-control mb-2" value="<?= (isset($lang_labels['reviews']) && !empty($lang_labels['reviews'])) ? $lang_labels['reviews'] : 'Reviews'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="add_to_cart" class="control-checkbox">Add to Cart</label>
                                            <input type="text" name="add_to_cart" class="form-control mb-2" value="<?= (isset($lang_labels['add_to_cart']) && !empty($lang_labels['add_to_cart'])) ? $lang_labels['add_to_cart'] : 'Add to Cart'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="add_to_favorite" class="control-checkbox">Add to Favorite</label>
                                            <input type="text" name="add_to_favorite" class="form-control mb-2" value="<?= (isset($lang_labels['add_to_favorite']) && !empty($lang_labels['add_to_favorite'])) ? $lang_labels['add_to_favorite'] : 'Add to Favorite'; ?>" />
                                        </div>
                                    </div>
                                     <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="No Favorite" class="control-checkbox">No Favorite Products Found.</label>
                                            <input type="text" name="no_favorite" class="form-control mb-2" value="<?= (isset($lang_labels['no_favorite']) && !empty($lang_labels['no_favorite'])) ? $lang_labels['no_favorite'] : 'No Favorite Products Found.'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="cancel" class="control-checkbox">Cancel</label>
                                            <input type="text" name="cancel" class="form-control mb-2" value="<?= (isset($lang_labels['cancel']) && !empty($lang_labels['cancel'])) ? $lang_labels['cancel'] : 'Cancel'; ?>" />
                                        </div>
                                    </div>
                                     <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="send" class="control-checkbox">Send</label>
                                            <input type="text" name="send" class="form-control mb-2" value="<?= (isset($lang_labels['send']) && !empty($lang_labels['send'])) ? $lang_labels['send'] : 'Send'; ?>" />
                                        </div>
                                    </div>
                                      <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="send_bank_payment_receipt" class="control-checkbox">Send Bank Payment Receipt</label>
                                            <input type="text" name="send_bank_payment_receipt" class="form-control mb-2" value="<?= (isset($lang_labels['send_bank_payment_receipt']) && !empty($lang_labels['send_bank_payment_receipt'])) ? $lang_labels['send_bank_payment_receipt'] : 'Send Bank Payment Receipt'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="send_otp" class="control-checkbox">Send OTP</label>
                                            <input type="text" name="send_otp" class="form-control mb-2" value="<?= (isset($lang_labels['send_otp']) && !empty($lang_labels['send_otp'])) ? $lang_labels['send_otp'] : 'Send OTP'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="submit" class="control-checkbox">Submit</label>
                                            <input type="text" name="submit" class="form-control mb-2" value="<?= (isset($lang_labels['submit']) && !empty($lang_labels['submit'])) ? $lang_labels['submit'] : 'Submit'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="home" class="control-checkbox">Home</label>
                                            <input type="text" name="home" class="form-control mb-2" value="<?= (isset($lang_labels['home']) && !empty($lang_labels['home'])) ? $lang_labels['home'] : 'Home'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="name" class="control-checkbox">Name</label>
                                            <input type="text" name="name" class="form-control mb-2" value="<?= (isset($lang_labels['name']) && !empty($lang_labels['name'])) ? $lang_labels['name'] : 'Name'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="mobile_number" class="control-checkbox">Mobile Number</label>
                                            <input type="text" name="mobile_number" class="form-control mb-2" value="<?= (isset($lang_labels['mobile_number']) && !empty($lang_labels['mobile_number'])) ? $lang_labels['mobile_number'] : 'Mobile Number'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="address" class="control-checkbox">Address</label>
                                            <input type="text" name="address" class="form-control mb-2" value="<?= (isset($lang_labels['address']) && !empty($lang_labels['address'])) ? $lang_labels['address'] : 'Address'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="city" class="control-checkbox">City</label>
                                            <input type="text" name="city" class="form-control mb-2" value="<?= (isset($lang_labels['city']) && !empty($lang_labels['city'])) ? $lang_labels['city'] : 'City'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="select_city" class="control-checkbox">Select City</label>
                                            <input type="text" name="select_city" class="form-control mb-2" value="<?= (isset($lang_labels['select_city']) && !empty($lang_labels['select_city'])) ? $lang_labels['select_city'] : 'Select City'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="select_area" class="control-checkbox">Select Area</label>
                                            <input type="text" name="select_area" class="form-control mb-2" value="<?= (isset($lang_labels['select_area']) && !empty($lang_labels['select_area'])) ? $lang_labels['select_area'] : 'Select Area'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="area" class="control-checkbox">Area</label>
                                            <input type="text" name="area" class="form-control mb-2" value="<?= (isset($lang_labels['area']) && !empty($lang_labels['area'])) ? $lang_labels['area'] : 'Area'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="pincode" class="control-checkbox">Pincode</label>
                                            <input type="text" name="pincode" class="form-control mb-2" value="<?= (isset($lang_labels['pincode']) && !empty($lang_labels['pincode'])) ? $lang_labels['pincode'] : 'Pincode'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="state" class="control-checkbox">State</label>
                                            <input type="text" name="state" class="form-control mb-2" value="<?= (isset($lang_labels['state']) && !empty($lang_labels['state'])) ? $lang_labels['state'] : 'State'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="country" class="control-checkbox">Country</label>
                                            <input type="text" name="country" class="form-control mb-2" value="<?= (isset($lang_labels['country']) && !empty($lang_labels['country'])) ? $lang_labels['country'] : 'Country'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="type" class="control-checkbox">Type</label>
                                            <input type="text" name="type" class="form-control mb-2" value="<?= (isset($lang_labels['type']) && !empty($lang_labels['type'])) ? $lang_labels['type'] : 'Type'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="office" class="control-checkbox">Office</label>
                                            <input type="text" name="office" class="form-control mb-2" value="<?= (isset($lang_labels['office']) && !empty($lang_labels['office'])) ? $lang_labels['office'] : 'Office'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="other" class="control-checkbox">Other</label>
                                            <input type="text" name="other" class="form-control mb-2" value="<?= (isset($lang_labels['other']) && !empty($lang_labels['other'])) ? $lang_labels['other'] : 'Other'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="alternate_mobile" class="control-checkbox">Alternate Mobile</label>
                                            <input type="text" name="alternate_mobile" class="form-control mb-2" value="<?= (isset($lang_labels['alternate_mobile']) && !empty($lang_labels['alternate_mobile'])) ? $lang_labels['alternate_mobile'] : 'Alternate Mobile'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="landmark" class="control-checkbox">Landmark</label>
                                            <input type="text" name="landmark" class="form-control mb-2" value="<?= (isset($lang_labels['landmark']) && !empty($lang_labels['landmark'])) ? $lang_labels['landmark'] : 'Landmark'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="action" class="control-checkbox">Action</label>
                                            <input type="text" name="action" class="form-control mb-2" value="<?= (isset($lang_labels['action']) && !empty($lang_labels['action'])) ? $lang_labels['action'] : 'Action'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="edit_address" class="control-checkbox">Edit Address</label>
                                            <input type="text" name="edit_address" class="form-control mb-2" value="<?= (isset($lang_labels['edit_address']) && !empty($lang_labels['edit_address'])) ? $lang_labels['edit_address'] : 'Edit Address'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="image" class="control-checkbox">Image</label>
                                            <input type="text" name="image" class="form-control mb-2" value="<?= (isset($lang_labels['image']) && !empty($lang_labels['image'])) ? $lang_labels['image'] : 'Image'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="price" class="control-checkbox">Price</label>
                                            <input type="text" name="price" class="form-control mb-2" value="<?= (isset($lang_labels['price']) && !empty($lang_labels['price'])) ? $lang_labels['price'] : 'Price'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="quantity" class="control-checkbox">Quantity</label>
                                            <input type="text" name="quantity" class="form-control mb-2" value="<?= (isset($lang_labels['quantity']) && !empty($lang_labels['quantity'])) ? $lang_labels['quantity'] : 'Quantity'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="total" class="control-checkbox">Total</label>
                                            <input type="text" name="total" class="form-control mb-2" value="<?= (isset($lang_labels['total']) && !empty($lang_labels['total'])) ? $lang_labels['total'] : 'Total'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="save_for_later" class="control-checkbox">Save For Later</label>
                                            <input type="text" name="save_for_later" class="form-control mb-2" value="<?= (isset($lang_labels['save_for_later']) && !empty($lang_labels['save_for_later'])) ? $lang_labels['save_for_later'] : 'Save For Later'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="remove" class="control-checkbox">Remove</label>
                                            <input type="text" name="remove" class="form-control mb-2" value="<?= (isset($lang_labels['remove']) && !empty($lang_labels['remove'])) ? $lang_labels['remove'] : 'Remove'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="subtotal" class="control-checkbox">Subtotal</label>
                                            <input type="text" name="subtotal" class="form-control mb-2" value="<?= (isset($lang_labels['subtotal']) && !empty($lang_labels['subtotal'])) ? $lang_labels['subtotal'] : 'Subtotal'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="tax" class="control-checkbox">Tax</label>
                                            <input type="text" name="tax" class="form-control mb-2" value="<?= (isset($lang_labels['tax']) && !empty($lang_labels['tax'])) ? $lang_labels['tax'] : 'Tax'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="delivery_charge" class="control-checkbox">Delivery Charge</label>
                                            <input type="text" name="delivery_charge" class="form-control mb-2" value="<?= (isset($lang_labels['delivery_charge']) && !empty($lang_labels['delivery_charge'])) ? $lang_labels['delivery_charge'] : 'Delivery Charge'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="grand_total" class="control-checkbox">Grand Total</label>
                                            <input type="text" name="grand_total" class="form-control mb-2" value="<?= (isset($lang_labels['grand_total']) && !empty($lang_labels['grand_total'])) ? $lang_labels['grand_total'] : 'Grand Total'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="checkout" class="control-checkbox">Checkout</label>
                                            <input type="text" name="checkout" class="form-control mb-2" value="<?= (isset($lang_labels['checkout']) && !empty($lang_labels['checkout'])) ? $lang_labels['checkout'] : 'Checkout'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="move_to_cart" class="control-checkbox">Move to cart</label>
                                            <input type="text" name="move_to_cart" class="form-control mb-2" value="<?= (isset($lang_labels['move_to_cart']) && !empty($lang_labels['move_to_cart'])) ? $lang_labels['move_to_cart'] : 'Move to cart'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="category" class="control-checkbox">Category</label>
                                            <input type="text" name="category" class="form-control mb-2" value="<?= (isset($lang_labels['category']) && !empty($lang_labels['category'])) ? $lang_labels['category'] : 'Category'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="cart" class="control-checkbox">Cart</label>
                                            <input type="text" name="cart" class="form-control mb-2" value="<?= (isset($lang_labels['cart']) && !empty($lang_labels['cart'])) ? $lang_labels['cart'] : 'Cart'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="your_cart" class="control-checkbox">Your cart</label>
                                            <input type="text" name="your_cart" class="form-control mb-2" value="<?= (isset($lang_labels['your_cart']) && !empty($lang_labels['your_cart'])) ? $lang_labels['your_cart'] : 'Your cart'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="promo_code" class="control-checkbox">Promo code</label>
                                            <input type="text" name="promo_code" class="form-control mb-2" value="<?= (isset($lang_labels['promo_code']) && !empty($lang_labels['promo_code'])) ? $lang_labels['promo_code'] : 'Promo code'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="redeem" class="control-checkbox">Redeem</label>
                                            <input type="text" name="redeem" class="form-control mb-2" value="<?= (isset($lang_labels['redeem']) && !empty($lang_labels['redeem'])) ? $lang_labels['redeem'] : 'Redeem'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="clear" class="control-checkbox">Clear</label>
                                            <input type="text" name="clear" class="form-control mb-2" value="<?= (isset($lang_labels['clear']) && !empty($lang_labels['clear'])) ? $lang_labels['clear'] : 'Clear'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="billing_address" class="control-checkbox">Billing address</label>
                                            <input type="text" name="billing_address" class="form-control mb-2" value="<?= (isset($lang_labels['billing_address']) && !empty($lang_labels['billing_address'])) ? $lang_labels['billing_address'] : 'Billing address'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="preferred_delivery_date_time" class="control-checkbox">Preferred Delivery Date / Time</label>
                                            <input type="text" name="preferred_delivery_date_time" class="form-control mb-2" value="<?= (isset($lang_labels['preferred_delivery_date_time']) && !empty($lang_labels['preferred_delivery_date_time'])) ? $lang_labels['preferred_delivery_date_time'] : 'Preferred Delivery Date / Time'; ?>" />
                                        </div>
                                    </div>
                                     
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="select_payment_method" class="control-checkbox">Select Payment Method</label>
                                            <input type="text" name="select_payment_method" class="form-control mb-2" value="<?= (isset($lang_labels['select_payment_method']) && !empty($lang_labels['select_payment_method'])) ? $lang_labels['select_payment_method'] : 'Select Payment Method'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="cash_on_delivery" class="control-checkbox">Cash On Delivery</label>
                                            <input type="text" name="cash_on_delivery" class="form-control mb-2" value="<?= (isset($lang_labels['cash_on_delivery']) && !empty($lang_labels['cash_on_delivery'])) ? $lang_labels['cash_on_delivery'] : 'Cash On Delivery'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="create_a_new_address" class="control-checkbox">Create a New Address</label>
                                            <input type="text" name="create_a_new_address" class="form-control mb-2" value="<?= (isset($lang_labels['create_a_new_address']) && !empty($lang_labels['create_a_new_address'])) ? $lang_labels['create_a_new_address'] : 'Create a New Address'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="shipping_address" class="control-checkbox">Shipping Address</label>
                                            <input type="text" name="shipping_address" class="form-control mb-2" value="<?= (isset($lang_labels['shipping_address']) && !empty($lang_labels['shipping_address'])) ? $lang_labels['shipping_address'] : 'Shipping Address'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="save" class="control-checkbox">Save</label>
                                            <input type="text" name="save" class="form-control mb-2" value="<?= (isset($lang_labels['save']) && !empty($lang_labels['save'])) ? $lang_labels['save'] : 'Save'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="username" class="control-checkbox">Username</label>
                                            <input type="text" name="username" class="form-control mb-2" value="<?= (isset($lang_labels['username']) && !empty($lang_labels['username'])) ? $lang_labels['username'] : 'Username'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="email" class="control-checkbox">Email</label>
                                            <input type="text" name="email" class="form-control mb-2" value="<?= (isset($lang_labels['email']) && !empty($lang_labels['email'])) ? $lang_labels['email'] : 'Email'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="subject" class="control-checkbox">Subject</label>
                                            <input type="text" name="subject" class="form-control mb-2" value="<?= (isset($lang_labels['subject']) && !empty($lang_labels['subject'])) ? $lang_labels['subject'] : 'Subject'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="message" class="control-checkbox">Message</label>
                                            <input type="text" name="message" class="form-control mb-2" value="<?= (isset($lang_labels['message']) && !empty($lang_labels['message'])) ? $lang_labels['message'] : 'Message'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="send_message" class="control-checkbox">Send Message</label>
                                            <input type="text" name="send_message" class="form-control mb-2" value="<?= (isset($lang_labels['send_message']) && !empty($lang_labels['send_message'])) ? $lang_labels['send_message'] : 'Send Message'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="dashboard" class="control-checkbox">Dashboard</label>
                                            <input type="text" name="dashboard" class="form-control mb-2" value="<?= (isset($lang_labels['dashboard']) && !empty($lang_labels['dashboard'])) ? $lang_labels['dashboard'] : 'Dashboard'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="profile" class="control-checkbox">Profile</label>
                                            <input type="text" name="profile" class="form-control mb-2" value="<?= (isset($lang_labels['profile']) && !empty($lang_labels['profile'])) ? $lang_labels['profile'] : 'Profile'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="orders" class="control-checkbox">Orders</label>
                                            <input type="text" name="orders" class="form-control mb-2" value="<?= (isset($lang_labels['orders']) && !empty($lang_labels['orders'])) ? $lang_labels['orders'] : 'Orders'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="notification" class="control-checkbox">Notification</label>
                                            <input type="text" name="notification" class="form-control mb-2" value="<?= (isset($lang_labels['notification']) && !empty($lang_labels['notification'])) ? $lang_labels['notification'] : 'Notification'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="wallet" class="control-checkbox">Wallet</label>
                                            <input type="text" name="wallet" class="form-control mb-2" value="<?= (isset($lang_labels['wallet']) && !empty($lang_labels['wallet'])) ? $lang_labels['wallet'] : 'Wallet'; ?>" />
                                        </div>
                                    </div>
                                      <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="Add money to wallet" class="control-checkbox">Add money to wallet</label>
                                            <input type="text" name="add_money_to_wallet" class="form-control mb-2" value="<?= (isset($lang_labels['add_money_to_wallet']) && !empty($lang_labels['add_money_to_wallet'])) ? $lang_labels['add_money_to_wallet'] : 'Add money to wallet'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="transaction" class="control-checkbox">Transaction</label>
                                            <input type="text" name="transaction" class="form-control mb-2" value="<?= (isset($lang_labels['transaction']) && !empty($lang_labels['transaction'])) ? $lang_labels['transaction'] : 'Transaction'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="no_favorite_product_message" class="control-checkbox">No Favorite Products Found</label>
                                            <input type="text" name="no_favorite_product_message" class="form-control mb-2" value="<?= (isset($lang_labels['no_favorite_product_message']) && !empty($lang_labels['no_favorite_product_message'])) ? $lang_labels['no_favorite_product_message'] : 'No Favorite Products Found'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="amazing_categories" class="control-checkbox">Amazing Categories</label>
                                            <input type="text" name="amazing_categories" class="form-control mb-2" value="<?= (isset($lang_labels['amazing_categories']) && !empty($lang_labels['amazing_categories'])) ? $lang_labels['amazing_categories'] : 'Amazing Categories'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-grouplabel_create_new
                                            <label class=" mb-2">View More</label>
                                            <input type="text" name="view_more" class="form-control mb-2" value="<?= (isset($lang_labels['view_more']) && !empty($lang_labels['view_more'])) ? $lang_labels['view_more'] : 'View More'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="mobile_app" class="control-checkbox">Mobile App</label>
                                            <input type="text" name="mobile_app" class="form-control mb-2" value="<?= (isset($lang_labels['mobile_app']) && !empty($lang_labels['mobile_app'])) ? $lang_labels['mobile_app'] : 'Mobile App'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="order_id" class="control-checkbox">Order ID</label>
                                            <input type="text" name="order_id" class="form-control mb-2" value="<?= (isset($lang_labels['order_id']) && !empty($lang_labels['order_id'])) ? $lang_labels['order_id'] : 'Order ID'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="place_on" class="control-checkbox">Place On</label>
                                            <input type="text" name="place_on" class="form-control mb-2" value="<?= (isset($lang_labels['place_on']) && !empty($lang_labels['place_on'])) ? $lang_labels['place_on'] : 'Place On'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="invoice" class="control-checkbox">Invoice</label>
                                            <input type="text" name="invoice" class="form-control mb-2" value="<?= (isset($lang_labels['invoice']) && !empty($lang_labels['invoice'])) ? $lang_labels['invoice'] : 'Invoice'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="back_to_list" class="control-checkbox">Back to List</label>
                                            <input type="text" name="back_to_list" class="form-control mb-2" value="<?= (isset($lang_labels['back_to_list']) && !empty($lang_labels['back_to_list'])) ? $lang_labels['back_to_list'] : 'Back to List'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="return" class="control-checkbox">Return</label>
                                            <input type="text" name="return" class="form-control mb-2" value="<?= (isset($lang_labels['return']) && !empty($lang_labels['return'])) ? $lang_labels['return'] : 'Return'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="shipping_details" class="control-checkbox">Shipping Details</label>
                                            <input type="text" name="shipping_details" class="form-control mb-2" value="<?= (isset($lang_labels['shipping_details']) && !empty($lang_labels['shipping_details'])) ? $lang_labels['shipping_details'] : 'Shipping Details'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="total_order_price" class="control-checkbox">Total Order Price</label>
                                            <input type="text" name="total_order_price" class="form-control mb-2" value="<?= (isset($lang_labels['total_order_price']) && !empty($lang_labels['total_order_price'])) ? $lang_labels['total_order_price'] : 'Total Order Price'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="promocode_discount" class="control-checkbox">Promocode Discount</label>
                                            <input type="text" name="promocode_discount" class="form-control mb-2" value="<?= (isset($lang_labels['promocode_discount']) && !empty($lang_labels['promocode_discount'])) ? $lang_labels['promocode_discount'] : 'Promocode Discount'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="bulk_discount" class="control-checkbox">Bulk Discount</label>
                                            <input type="text" name="bulk_discount" class="form-control mb-2" value="<?= (isset($lang_labels['bulk_discount']) && !empty($lang_labels['bulk_discount'])) ? $lang_labels['bulk_discount'] : 'Bulk Discount'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="platform_fees" class="control-checkbox">Platform Fees</label>
                                            <input type="text" name="platform_fees" class="form-control mb-2" value="<?= (isset($lang_labels['platform_fees']) && !empty($lang_labels['platform_fees'])) ? $lang_labels['platform_fees'] : 'Platform Fees'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="bulk_order_discount" class="control-checkbox">Bulk Order Discount</label>
                                            <input type="text" name="bulk_order_discount" class="form-control mb-2" value="<?= (isset($lang_labels['bulk_order_discount']) && !empty($lang_labels['bulk_order_discount'])) ? $lang_labels['bulk_order_discount'] : 'Bulk Order Discount'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="off_when_you_order" class="control-checkbox">Off When You Order</label>
                                            <input type="text" name="off_when_you_order" class="form-control mb-2" value="<?= (isset($lang_labels['off_when_you_order']) && !empty($lang_labels['off_when_you_order'])) ? $lang_labels['off_when_you_order'] : 'off when you order'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="or_more" class="control-checkbox">Or More</label>
                                            <input type="text" name="or_more" class="form-control mb-2" value="<?= (isset($lang_labels['or_more']) && !empty($lang_labels['or_more'])) ? $lang_labels['or_more'] : 'or more'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="register_with_email" class="control-checkbox">Register with Email?</label>
                                            <input type="text" name="register_with_email" class="form-control mb-2" value="<?= (isset($lang_labels['register_with_email']) && !empty($lang_labels['register_with_email'])) ? $lang_labels['register_with_email'] : 'Register with Email?'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="register_with_phone" class="control-checkbox">Register with Phone?</label>
                                            <input type="text" name="register_with_phone" class="form-control mb-2" value="<?= (isset($lang_labels['register_with_phone']) && !empty($lang_labels['register_with_phone'])) ? $lang_labels['register_with_phone'] : 'Register with Phone?'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="enter_mobile_number" class="control-checkbox">Enter Mobile Number</label>
                                            <input type="text" name="enter_mobile_number" class="form-control mb-2" value="<?= (isset($lang_labels['enter_mobile_number']) && !empty($lang_labels['enter_mobile_number'])) ? $lang_labels['enter_mobile_number'] : 'Enter Mobile Number'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="enter_mobile_number_optional" class="control-checkbox">Enter Mobile Number (Optional)</label>
                                            <input type="text" name="enter_mobile_number_optional" class="form-control mb-2" value="<?= (isset($lang_labels['enter_mobile_number_optional']) && !empty($lang_labels['enter_mobile_number_optional'])) ? $lang_labels['enter_mobile_number_optional'] : 'Enter Mobile Number (Optional)'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="login_with_phone" class="control-checkbox">Login with Phone?</label>
                                            <input type="text" name="login_with_phone" class="form-control mb-2" value="<?= (isset($lang_labels['login_with_phone']) && !empty($lang_labels['login_with_phone'])) ? $lang_labels['login_with_phone'] : 'Login with Phone?'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="email_address" class="control-checkbox">Email address</label>
                                            <input type="text" name="email_address" class="form-control mb-2" value="<?= (isset($lang_labels['email_address']) && !empty($lang_labels['email_address'])) ? $lang_labels['email_address'] : 'Email address'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="login_description" class="control-checkbox">Login Description</label>
                                            <input type="text" name="login_description" class="form-control mb-2" value="<?= (isset($lang_labels['login_description']) && !empty($lang_labels['login_description'])) ? $lang_labels['login_description'] : 'To access your account and enjoy a seamless shopping experience, simply enter your registered Mobile Number and password in the designated fields. Once logged in, you\'ll have access to your personalized dashboard, order history, saved payment methods, and more.'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="register_description" class="control-checkbox">Register Description</label>
                                            <input type="text" name="register_description" class="form-control mb-2" value="<?= (isset($lang_labels['register_description']) && !empty($lang_labels['register_description'])) ? $lang_labels['register_description'] : 'Registering for this site allows you to access your order status and history. Just fill in the fields below, and we\'ll get a new account set up for you in no time. We will only ask you for information necessary to make the purchase process faster and easier.'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="forgot_password_with_email" class="control-checkbox">Forgot password with Email?</label>
                                            <input type="text" name="forgot_password_with_email" class="form-control mb-2" value="<?= (isset($lang_labels['forgot_password_with_email']) && !empty($lang_labels['forgot_password_with_email'])) ? $lang_labels['forgot_password_with_email'] : 'Forgot password with Email?'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="forgot_password_with_phone" class="control-checkbox">Forgot password with Phone Number?</label>
                                            <input type="text" name="forgot_password_with_phone" class="form-control mb-2" value="<?= (isset($lang_labels['forgot_password_with_phone']) && !empty($lang_labels['forgot_password_with_phone'])) ? $lang_labels['forgot_password_with_phone'] : 'Forgot password with Phone Number?'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="new_password" class="control-checkbox">New Password</label>
                                            <input type="text" name="new_password" class="form-control mb-2" value="<?= (isset($lang_labels['new_password']) && !empty($lang_labels['new_password'])) ? $lang_labels['new_password'] : 'New Password'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="send_reset_email" class="control-checkbox">Send Reset Email</label>
                                            <input type="text" name="send_reset_email" class="form-control mb-2" value="<?= (isset($lang_labels['send_reset_email']) && !empty($lang_labels['send_reset_email'])) ? $lang_labels['send_reset_email'] : 'Send Reset Email'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="reset_password" class="control-checkbox">Reset Password</label>
                                            <input type="text" name="reset_password" class="form-control mb-2" value="<?= (isset($lang_labels['reset_password']) && !empty($lang_labels['reset_password'])) ? $lang_labels['reset_password'] : 'Reset Password'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="reset_password_description" class="control-checkbox">Reset Password Description</label>
                                            <input type="text" name="reset_password_description" class="form-control mb-2" value="<?= (isset($lang_labels['reset_password_description']) && !empty($lang_labels['reset_password_description'])) ? $lang_labels['reset_password_description'] : 'Enter your new password below to reset your account access.'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="enter_new_password" class="control-checkbox">Enter new password</label>
                                            <input type="text" name="enter_new_password" class="form-control mb-2" value="<?= (isset($lang_labels['enter_new_password']) && !empty($lang_labels['enter_new_password'])) ? $lang_labels['enter_new_password'] : 'Enter new password'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="no_account_yet" class="control-checkbox">No account Yet?</label>
                                            <input type="text" name="no_account_yet" class="form-control mb-2" value="<?= (isset($lang_labels['no_account_yet']) && !empty($lang_labels['no_account_yet'])) ? $lang_labels['no_account_yet'] : 'No account Yet?'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="create_new_account" class="control-checkbox">Create New Account</label>
                                            <input type="text" name="create_new_account" class="form-control mb-2" value="<?= (isset($lang_labels['create_new_account']) && !empty($lang_labels['create_new_account'])) ? $lang_labels['create_new_account'] : 'Create New Account'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="wallet_used" class="control-checkbox">Wallet Used</label>
                                            <input type="text" name="wallet_used" class="form-control mb-2" value="<?= (isset($lang_labels['wallet_used']) && !empty($lang_labels['wallet_used'])) ? $lang_labels['wallet_used'] : 'Wallet Used'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="final_total" class="control-checkbox">Final Total</label>
                                            <input type="text" name="final_total" class="form-control mb-2" value="<?= (isset($lang_labels['final_total']) && !empty($lang_labels['final_total'])) ? $lang_labels['final_total'] : 'Final Total'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="view_details" class="control-checkbox">View Details</label>
                                            <input type="text" name="view_details" class="form-control mb-2" value="<?= (isset($lang_labels['view_details']) && !empty($lang_labels['view_details'])) ? $lang_labels['view_details'] : 'View Details'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="payment_cancelled" class="control-checkbox">Payment Cancelled / Failed</label>
                                            <input type="text" name="payment_cancelled" class="form-control mb-2" value="<?= (isset($lang_labels['payment_cancelled']) && !empty($lang_labels['payment_cancelled'])) ? $lang_labels['payment_cancelled'] : 'Payment Cancelled / Failed'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="payment_cancelled_message" class="control-checkbox">It seems like payment process is failed or cancelled.Please Try again.</label>
                                            <input type="text" name="payment_cancelled_message" class="form-control mb-2" value="<?= (isset($lang_labels['payment_cancelled_message']) && !empty($lang_labels['payment_cancelled_message'])) ? $lang_labels['payment_cancelled_message'] : 'It seems like payment process is fai                                        </div>led or cancelled.Please Try again.'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="payment_completed" class="control-checkbox">Payment Complete</label>
                                            <input type="text" name="payment_completed" class="form-control mb-2" value="<?= (isset($lang_labels['payment_completed']) && !empty($lang_labels['payment_completed'])) ? $lang_labels['payment_completed'] : 'Payment Complete'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="payment_completed_message" class="control-checkbox">Payment Completed Successfully</label>
                                            <input type="text" name="payment_completed_message" class="form-control mb-2" value="<?= (isset($lang_labels['payment_completed_message']) && !empty($lang_labels['payment_completed_message'])) ? $lang_labels['payment_completed_message'] : 'Payment Completed Successfully'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="thank_you_for_shopping_with_us" class="control-checkbox">Thank you for Shopping with Us</label>
                                            <input type="text" name="thank_you_for_shopping_with_us" class="form-control mb-2" value="<?= (isset($lang_labels['thank_you_for_shopping_with_us']) && !empty($lang_labels['thank_you_for_shopping_with_us'])) ? $lang_labels['thank_you_for_shopping_with_us'] : 'Thank you for Shopping with Us'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="filter" class="control-checkbox">Filter</label>
                                            <input type="text" name="filter" class="form-control mb-2" value="<?= (isset($lang_labels['filter']) && !empty($lang_labels['filter'])) ? $lang_labels['filter'] : 'Filter'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="color" class="control-checkbox">Color</label>
                                            <input type="text" name="color" class="form-control mb-2" value="<?= (isset($lang_labels['color']) && !empty($lang_labels['color'])) ? $lang_labels['color'] : 'Color'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="top_rated" class="control-checkbox">Top Rated</label>
                                            <input type="text" name="top_rated" class="form-control mb-2" value="<?= (isset($lang_labels['top_rated']) && !empty($lang_labels['top_rated'])) ? $lang_labels['top_rated'] : 'Top Rated'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="newest_first" class="control-checkbox">Newest First</label>
                                            <input type="text" name="newest_first" class="form-control mb-2" value="<?= (isset($lang_labels['newest_first']) && !empty($lang_labels['newest_first'])) ? $lang_labels['newest_first'] : 'Newest First'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="oldest_first" class="control-checkbox">Oldest First</label>
                                            <input type="text" name="oldest_first" class="form-control mb-2" value="<?= (isset($lang_labels['oldest_first']) && !empty($lang_labels['oldest_first'])) ? $lang_labels['oldest_first'] : 'Oldest First'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="price_low_to_high" class="control-checkbox">Price - Low To High</label>
                                            <input type="text" name="price_low_to_high" class="form-control mb-2" value="<?= (isset($lang_labels['price_low_to_high']) && !empty($lang_labels['price_low_to_high'])) ? $lang_labels['price_low_to_high'] : 'Price - Low To High'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="price_high_to_low" class="control-checkbox">Price - High To Low</label>
                                            <input type="text" name="price_high_to_low" class="form-control mb-2" value="<?= (isset($lang_labels['price_high_to_low']) && !empty($lang_labels['price_high_to_low'])) ? $lang_labels['price_high_to_low'] : 'Price - High To Low'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="relevance" class="control-checkbox">Relevance</label>
                                            <input type="text" name="relevance" class="form-control mb-2" value="<?= (isset($lang_labels['relevance']) && !empty($lang_labels['relevance'])) ? $lang_labels['relevance'] : 'Relevance'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sale" class="control-checkbox">Sale</label>
                                            <input type="text" name="sale" class="form-control mb-2" value="<?= (isset($lang_labels['sale']) && !empty($lang_labels['sale'])) ? $lang_labels['sale'] : 'Sale'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="view" class="control-checkbox">View</label>
                                            <input type="text" name="view" class="form-control mb-2" value="<?= (isset($lang_labels['view']) && !empty($lang_labels['view'])) ? $lang_labels['view'] : 'View'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="back_to_top" class="control-checkbox">Back to top</label>
                                            <input type="text" name="back_to_top" class="form-control mb-2" value="<?= (isset($lang_labels['back_to_top']) && !empty($lang_labels['back_to_top'])) ? $lang_labels['back_to_top'] : 'Back to top'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="go_to_shop" class="control-checkbox">Go to Shop</label>
                                            <input type="text" name="go_to_shop" class="form-control mb-2" value="<?= (isset($lang_labels['go_to_shop']) && !empty($lang_labels['go_to_shop'])) ? $lang_labels['go_to_shop'] : 'Go to Shop'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="go_to_cart" class="control-checkbox">Go to Cart</label>
                                            <input type="text" name="go_to_cart" class="form-control mb-2" value="<?= (isset($lang_labels['go_to_cart']) && !empty($lang_labels['go_to_cart'])) ? $lang_labels['go_to_cart'] : 'Go to Cart'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="details" class="control-checkbox">Details</label>
                                            <input type="text" name="details" class="form-control mb-2" value="<?= (isset($lang_labels['details']) && !empty($lang_labels['details'])) ? $lang_labels['details'] : 'Details'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="remove_from_favorite" class="control-checkbox">Remove from Favorite</label>
                                            <input type="text" name="remove_from_favorite" class="form-control mb-2" value="<?= (isset($lang_labels['remove_from_favorite']) && !empty($lang_labels['remove_from_favorite'])) ? $lang_labels['remove_from_favorite'] : 'Remove from Favorite'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="specification" class="control-checkbox">Specifications</label>
                                            <input type="text" name="specification" class="form-control mb-2" value="<?= (isset($lang_labels['specification']) && !empty($lang_labels['specification'])) ? $lang_labels['specification'] : 'Specifications'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="rating" class="control-checkbox">Rating</label>
                                            <input type="text" name="rating" class="form-control mb-2" value="<?= (isset($lang_labels['rating']) && !empty($lang_labels['rating'])) ? $lang_labels['rating'] : 'Rating'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="related_products" class="control-checkbox">Related Products</label>
                                            <input type="text" name="related_products" class="form-control mb-2" value="<?= (isset($lang_labels['related_products']) && !empty($lang_labels['related_products'])) ? $lang_labels['related_products'] : 'Related Products'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="old_password" class="control-checkbox">Old Password</label>
                                            <input type="text" name="old_password" class="form-control mb-2" value="<?= (isset($lang_labels['old_password']) && !empty($lang_labels['old_password'])) ? $lang_labels['old_password'] : 'Old Password'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="new_password" class="control-checkbox">New Password</label>
                                            <input type="text" name="new_password" class="form-control mb-2" value="<?= (isset($lang_labels['new_password']) && !empty($lang_labels['new_password'])) ? $lang_labels['new_password'] : 'New Password'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="confirm_new_password" class="control-checkbox">Confirm New Password</label>
                                            <input type="text" name="confirm_new_password" class="form-control mb-2" value="<?= (isset($lang_labels['confirm_new_password']) && !empty($lang_labels['confirm_new_password'])) ? $lang_labels['confirm_new_password'] : 'Confirm New Password'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="reset" class="control-checkbox">Reset</label>
                                            <input type="text" name="reset" class="form-control mb-2" value="<?= (isset($lang_labels['reset']) && !empty($lang_labels['reset'])) ? $lang_labels['reset'] : 'Reset'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="update_profile" class="control-checkbox">Update Profile</label>
                                            <input type="text" name="update_profile" class="form-control mb-2" value="<?= (isset($lang_labels['update_profile']) && !empty($lang_labels['update_profile'])) ? $lang_labels['update_profile'] : 'Update Profile'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="transactions" class="control-checkbox">Transactions</label>
                                            <input type="text" name="transactions" class="form-control mb-2" value="<?= (isset($lang_labels['transactions']) && !empty($lang_labels['transactions'])) ? $lang_labels['transactions'] : 'Transactions'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="wallet" class="control-checkbox">Wallet</label>
                                            <input type="text" name="wallet" class="form-control mb-2" value="<?= (isset($lang_labels['wallet']) && !empty($lang_labels['wallet'])) ? $lang_labels['wallet'] : 'Wallet'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="wallet" class="control-checkbox">Newsletter</label>
                                            <input type="text" name="newsletter" class="form-control mb-2" value="<?= (isset($lang_labels['newsletter']) && !empty($lang_labels['newsletter'])) ? $lang_labels['newsletter'] : 'Newsletter'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="wallet" class="control-checkbox">Useful Links</label>
                                            <input type="text" name="useful_links" class="form-control mb-2" value="<?= (isset($lang_labels['useful_links']) && !empty($lang_labels['useful_links'])) ? $lang_labels['useful_links'] : 'Useful Links'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="wallet" class="control-checkbox">Subscribe</label>
                                            <input type="text" name="subscribe" class="form-control mb-2" value="<?= (isset($lang_labels['subscribe']) && !empty($lang_labels['subscribe'])) ? $lang_labels['subscribe'] : 'Subscribe'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="wallet" class="control-checkbox">Follow us</label>
                                            <input type="text" name="follow_us" class="form-control mb-2" value="<?= (isset($lang_labels['follow_us']) && !empty($lang_labels['follow_us'])) ? $lang_labels['follow_us'] : 'Follow us'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="wallet" class="control-checkbox">Find Us</label>
                                            <input type="text" name="find_us" class="form-control mb-2" value="<?= (isset($lang_labels['find_us']) && !empty($lang_labels['find_us'])) ? $lang_labels['find_us'] : 'Find Us'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="wallet" class="control-checkbox">Call Us</label>
                                            <input type="text" name="call_us" class="form-control mb-2" value="<?= (isset($lang_labels['call_us']) && !empty($lang_labels['call_us'])) ? $lang_labels['call_us'] : 'Call Us'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="wallet" class="control-checkbox">Mail Us</label>
                                            <input type="text" name="mail_us" class="form-control mb-2" value="<?= (isset($lang_labels['mail_us']) && !empty($lang_labels['mail_us'])) ? $lang_labels['mail_us'] : 'Mail Us'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="wallet" class="control-checkbox">Forgot Password</label>
                                            <input type="text" name="forgot_password" class="form-control mb-2" value="<?= (isset($lang_labels['forgot_password']) && !empty($lang_labels['forgot_password'])) ? $lang_labels['forgot_password'] : 'Forgot Password'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="see_all" class="control-checkbox">See All</label>
                                            <input type="text" name="see_all" class="form-control mb-2" value="<?= (isset($lang_labels['see_all']) && !empty($lang_labels['see_all'])) ? $lang_labels['see_all'] : 'See All'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="see_all" class="control-checkbox">Sort By</label>
                                            <input type="text" name="sort_by" class="form-control mb-2" value="<?= (isset($lang_labels['sort_by']) && !empty($lang_labels['sort_by'])) ? $lang_labels['sort_by'] : 'Sort By'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="see_all" class="control-checkbox">Show</label>
                                            <input type="text" name="show" class="form-control mb-2" value="<?= (isset($lang_labels['show']) && !empty($lang_labels['show'])) ? $lang_labels['show'] : 'Show'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="see_all" class="control-checkbox">Product Listing</label>
                                            <input type="text" name="product_listing" class="form-control mb-2" value="<?= (isset($lang_labels['product_listing']) && !empty($lang_labels['product_listing'])) ? $lang_labels['product_listing'] : 'Product Listing'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="go_to_checkout" class="control-checkbox">Go To Checkout</label>
                                            <input type="text" name="go_to_checkout" class="form-control mb-2" value="<?= (isset($lang_labels['go_to_checkout']) && !empty($lang_labels['go_to_checkout'])) ? $lang_labels['go_to_checkout'] : 'Go To Checkout'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="Available_balance" class="control-checkbox">Available balance</label>
                                            <input type="text" name="Available_balance" class="form-control mb-2" value="<?= (isset($lang_labels['available_balance']) && !empty($lang_labels['available_balance'])) ? $lang_labels['available_balance'] : 'Available Balance'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="balance" class="control-checkbox">Balance</label>
                                            <input type="text" name="balance" class="form-control mb-2" value="<?= (isset($lang_labels['balance']) && !empty($lang_labels['balance'])) ? $lang_labels['balance'] : 'Balance'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="billing_details" class="control-checkbox">Billing Details</label>
                                            <input type="text" name="billing_details" class="form-control mb-2" value="<?= (isset($lang_labels['billing_details']) && !empty($lang_labels['billing_details'])) ? $lang_labels['billing_details'] : 'Billing Details'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="allow_order_attachments" class="control-checkbox">Allow Order Attachments</label>
                                            <input type="text" name="allow_order_attachments" class="form-control mb-2" value="<?= (isset($lang_labels['allow_order_attachments']) && !empty($lang_labels['allow_order_attachments'])) ? $lang_labels['allow_order_attachments'] : 'Allow Order Attachments'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="wallet_balance" class="control-checkbox">Wallet Balance</label>
                                            <input type="text" name="wallet_balance" class="form-control mb-2" value="<?= (isset($lang_labels['wallet_balance']) && !empty($lang_labels['wallet_balance'])) ? $lang_labels['wallet_balance'] : 'Wallet Balance'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="payment_method" class="control-checkbox">Payment Method</label>
                                            <input type="text" name="payment_method" class="form-control mb-2" value="<?= (isset($lang_labels['payment_method']) && !empty($lang_labels['payment_method'])) ? $lang_labels['payment_method'] : 'Payment Method'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="place_order" class="control-checkbox">Place Order</label>
                                            <input type="text" name="place_order" class="form-control mb-2" value="<?= (isset($lang_labels['place_order']) && !empty($lang_labels['place_order'])) ? $lang_labels['place_order'] : 'Place Order'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="promocode" class="control-checkbox">Promo Code</label>
                                            <input type="text" name="promocode" class="form-control mb-2" value="<?= (isset($lang_labels['promocode']) && !empty($lang_labels['promocode'])) ? $lang_labels['promocode'] : 'Promo Code'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="order_summary" class="control-checkbox">Order Summary</label>
                                            <input type="text" name="order_summary" class="form-control mb-2" value="<?= (isset($lang_labels['order_summary']) && !empty($lang_labels['order_summary'])) ? $lang_labels['order_summary'] : 'Order Summary'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="top_offer" class="control-checkbox">Top Offer</label>
                                            <input type="text" name="top_offer" class="form-control mb-2" value="<?= (isset($lang_labels['top_offer']) && !empty($lang_labels['top_offer'])) ? $lang_labels['top_offer'] : 'Top Offer'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="support" class="control-checkbox">Support</label>
                                            <input type="text" name="support" class="form-control mb-2" value="<?= (isset($lang_labels['support']) && !empty($lang_labels['support'])) ? $lang_labels['support'] : 'Support'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="return_policy" class="control-checkbox">Return Policy</label>
                                            <input type="text" name="return_policy" class="form-control mb-2" value="<?= (isset($lang_labels['return_policy']) && !empty($lang_labels['return_policy'])) ? $lang_labels['return_policy'] : 'Return Policy'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="shipping_policy" class="control-checkbox">Shipping Policy</label>
                                            <input type="text" name="shipping_policy" class="form-control mb-2" value="<?= (isset($lang_labels['shipping_policy']) && !empty($lang_labels['shipping_policy'])) ? $lang_labels['shipping_policy'] : 'Shipping Policy'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="android_app" class="control-checkbox">Android App</label>
                                            <input type="text" name="android_app" class="form-control mb-2" value="<?= (isset($lang_labels['android_app']) && !empty($lang_labels['android_app'])) ? $lang_labels['android_app'] : 'Android App'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="ios_app" class="control-checkbox">IOS App</label>
                                            <input type="text" name="ios_app" class="form-control mb-2" value="<?= (isset($lang_labels['ios_app']) && !empty($lang_labels['ios_app'])) ? $lang_labels['ios_app'] : 'IOS App'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="about_this_item" class="control-checkbox">About This Item</label>
                                            <input type="text" name="about_this_item" class="form-control mb-2" value="<?= (isset($lang_labels['about_this_item']) && !empty($lang_labels['about_this_item'])) ? $lang_labels['about_this_item'] : 'About This Item'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="customer_reviews" class="control-checkbox">Customer Reviews</label>
                                            <input type="text" name="customer_reviews" class="form-control mb-2" value="<?= (isset($lang_labels['customer_reviews']) && !empty($lang_labels['customer_reviews'])) ? $lang_labels['customer_reviews'] : 'Customer Reviews'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="review_of_product" class="control-checkbox">Review Of Product</label>
                                            <input type="text" name="review_of_product" class="form-control mb-2" value="<?= (isset($lang_labels['review_of_product']) && !empty($lang_labels['review_of_product'])) ? $lang_labels['review_of_product'] : 'Review Of Product'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="order_from_whatsapp" class="control-checkbox">Order From Whatsapp</label>
                                            <input type="text" name="order_from_whatsapp" class="form-control mb-2" value="<?= (isset($lang_labels['order_from_whatsapp']) && !empty($lang_labels['order_from_whatsapp'])) ? $lang_labels['order_from_whatsapp'] : 'Order From Whatsapp'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="add_in_cart" class="control-checkbox">Add in Cart</label>
                                            <input type="text" name="add_in_cart" class="form-control mb-2" value="<?= (isset($lang_labels['add_in_cart']) && !empty($lang_labels['add_in_cart'])) ? $lang_labels['add_in_cart'] : 'Add in Cart'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="check_availability" class="control-checkbox">Check Availability</label>
                                            <input type="text" name="check_availability" class="form-control mb-2" value="<?= (isset($lang_labels['check_availability']) && !empty($lang_labels['check_availability'])) ? $lang_labels['check_availability'] : 'Check Availability'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="share" class="control-checkbox">Share</label>
                                            <input type="text" name="share" class="form-control mb-2" value="<?= (isset($lang_labels['share']) && !empty($lang_labels['share'])) ? $lang_labels['share'] : 'Share'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="inclusive_of_all_taxes" class="control-checkbox">Inclusive of all taxes</label>
                                            <input type="text" name="inclusive_of_all_taxes" class="form-control mb-2" value="<?= (isset($lang_labels['inclusive_of_all_taxes']) && !empty($lang_labels['inclusive_of_all_taxes'])) ? $lang_labels['inclusive_of_all_taxes'] : 'Inclusive of all taxes'; ?>" />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="account_detail" class="control-checkbox">Account Detail</label>
                                            <input type="text" name="account_detail" class="form-control mb-2" value="<?= (isset($lang_labels['account_detail']) && !empty($lang_labels['account_detail'])) ? $lang_labels['account_detail'] : 'Account Detail'; ?>" />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="wishlist" class="control-checkbox">Wishlist</label>
                                            <input type="text" name="wishlist" class="form-control mb-2" value="<?= (isset($lang_labels['wishlist']) && !empty($lang_labels['wishlist'])) ? $lang_labels['wishlist'] : 'Wishlist'; ?>" />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="customer_support" class="control-checkbox">Customer Support</label>
                                            <input type="text" name="customer_support" class="form-control mb-2" value="<?= (isset($lang_labels['customer_support']) && !empty($lang_labels['customer_support'])) ? $lang_labels['customer_support'] : 'Customer Support'; ?>" />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="refer_and_earn" class="control-checkbox">Refer and Earn</label>
                                            <input type="text" name="refer_and_earn" class="form-control mb-2" value="<?= (isset($lang_labels['refer_and_earn']) && !empty($lang_labels['refer_and_earn'])) ? $lang_labels['refer_and_earn'] : 'Refer and Earn'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="delete_account" class="control-checkbox">Delete Account</label>
                                            <input type="text" name="delete_account" class="form-control mb-2" value="<?= (isset($lang_labels['delete_account']) && !empty($lang_labels['delete_account'])) ? $lang_labels['delete_account'] : 'Delete Account'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="reorder" class="control-checkbox">Reorder</label>
                                            <input type="text" name="reorder" class="form-control mb-2" value="<?= (isset($lang_labels['reorder']) && !empty($lang_labels['reorder'])) ? $lang_labels['reorder'] : 'Reorder'; ?>" />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="title" class="control-checkbox">Title</label>
                                            <input type="text" name="title" class="form-control mb-2" value="<?= (isset($lang_labels['title']) && !empty($lang_labels['title'])) ? $lang_labels['title'] : 'Title'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="zipcode" class="control-checkbox">Zipcode</label>
                                            <input type="text" name="zipcode" class="form-control mb-2" value="<?= (isset($lang_labels['zipcode']) && !empty($lang_labels['zipcode'])) ? $lang_labels['zipcode'] : 'Zipcode'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="password_change" class="control-checkbox">Password Change</label>
                                            <input type="text" name="password_change" class="form-control mb-2" value="<?= (isset($lang_labels['password_change']) && !empty($lang_labels['password_change'])) ? $lang_labels['password_change'] : 'Password Change'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="current_password" class="control-checkbox">Current Password</label>
                                            <input type="text" name="current_password" class="form-control mb-2" value="<?= (isset($lang_labels['current_password']) && !empty($lang_labels['current_password'])) ? $lang_labels['current_password'] : 'Current Password'; ?>" />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="mobile" class="control-checkbox">Mobile</label>
                                            <input type="text" name="mobile" class="form-control mb-2" value="<?= (isset($lang_labels['mobile']) && !empty($lang_labels['mobile'])) ? $lang_labels['mobile'] : 'Mobile'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="user_name" class="control-checkbox">User Name</label>
                                            <input type="text" name="user_name" class="form-control mb-2" value="<?= (isset($lang_labels['user_name']) && !empty($lang_labels['user_name'])) ? $lang_labels['user_name'] : 'User Name'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="amount" class="control-checkbox">Amount</label>
                                            <input type="text" name="amount" class="form-control mb-2" value="<?= (isset($lang_labels['amount']) && !empty($lang_labels['amount'])) ? $lang_labels['amount'] : 'Amount'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="status" class="control-checkbox">Status</label>
                                            <input type="text" name="status" class="form-control mb-2" value="<?= (isset($lang_labels['status']) && !empty($lang_labels['status'])) ? $lang_labels['status'] : 'Status'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="date" class="control-checkbox">Date</label>
                                            <input type="text" name="date" class="form-control mb-2" value="<?= (isset($lang_labels['date']) && !empty($lang_labels['date'])) ? $lang_labels['date'] : 'Date'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="payment_address" class="control-checkbox">Payment Address</label>
                                            <input type="text" name="payment_address" class="form-control mb-2" value="<?= (isset($lang_labels['payment_address']) && !empty($lang_labels['payment_address'])) ? $lang_labels['payment_address'] : 'Payment Address'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="wallet_requests" class="control-checkbox">Wallet Requests</label>
                                            <input type="text" name="wallet_requests" class="form-control mb-2" value="<?= (isset($lang_labels['wallet_requests']) && !empty($lang_labels['wallet_requests'])) ? $lang_labels['wallet_requests'] : 'Wallet Requests'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="wallet_transaction" class="control-checkbox">Wallet Transaction</label>
                                            <input type="text" name="wallet_transaction" class="form-control mb-2" value="<?= (isset($lang_labels['wallet_transaction']) && !empty($lang_labels['wallet_transaction'])) ? $lang_labels['wallet_transaction'] : 'Wallet Transaction'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="add_money" class="control-checkbox">Add Money</label>
                                            <input type="text" name="add_money" class="form-control mb-2" value="<?= (isset($lang_labels['add_money']) && !empty($lang_labels['add_money'])) ? $lang_labels['add_money'] : 'Add Money'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="password" class="control-checkbox">Password</label>
                                            <input type="text" name="password" class="form-control mb-2" value="<?= (isset($lang_labels['password']) && !empty($lang_labels['password'])) ? $lang_labels['password'] : 'Password'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="withdrawal" class="control-checkbox">Withdrawal</label>
                                            <input type="text" name="withdrawal" class="form-control mb-2" value="<?= (isset($lang_labels['withdrawal']) && !empty($lang_labels['withdrawal'])) ? $lang_labels['withdrawal'] : 'Withdrawal'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="save_address" class="control-checkbox">Save Address</label>
                                            <input type="text" name="save_address" class="form-control mb-2" value="<?= (isset($lang_labels['save_address']) && !empty($lang_labels['save_address'])) ? $lang_labels['save_address'] : 'Save Address'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="your_wishlist" class="control-checkbox">Your Wishlist</label>
                                            <input type="text" name="your_wishlist" class="form-control mb-2" value="<?= (isset($lang_labels['your_wishlist']) && !empty($lang_labels['your_wishlist'])) ? $lang_labels['your_wishlist'] : 'Your Wishlist'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="hello" class="control-checkbox">Hello</label>
                                            <input type="text" name="hello" class="form-control mb-2" value="<?= (isset($lang_labels['hello']) && !empty($lang_labels['hello'])) ? $lang_labels['hello'] : 'Hello'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="from_your_account_dashboard_you_can_view_your" class="control-checkbox">From your account dashboard you can view your</label>
                                            <input type="text" name="from_your_account_dashboard_you_can_view_your" class="form-control mb-2" value="<?= (isset($lang_labels['from_your_account_dashboard_you_can_view_your']) && !empty($lang_labels['from_your_account_dashboard_you_can_view_your'])) ? $lang_labels['from_your_account_dashboard_you_can_view_your'] : 'From your account dashboard you can view your'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="recent_orders" class="control-checkbox">Recent Orders</label>
                                            <input type="text" name="recent_orders" class="form-control mb-2" value="<?= (isset($lang_labels['recent_orders']) && !empty($lang_labels['recent_orders'])) ? $lang_labels['recent_orders'] : 'Recent Orders'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="manage_your" class="control-checkbox">Manage your</label>
                                            <input type="text" name="manage_your" class="form-control mb-2" value="<?= (isset($lang_labels['manage_your']) && !empty($lang_labels['manage_your'])) ? $lang_labels['manage_your'] : 'Manage your'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="shipping_and_billing_addresses" class="control-checkbox">Shipping and Billing Addresses</label>
                                            <input type="text" name="shipping_and_billing_addresses" class="form-control mb-2" value="<?= (isset($lang_labels['shipping_and_billing_addresses']) && !empty($lang_labels['shipping_and_billing_addresses'])) ? $lang_labels['shipping_and_billing_addresses'] : 'Shipping and Billing Addresses'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="and" class="control-checkbox">And</label>
                                            <input type="text" name="and" class="form-control mb-2" value="<?= (isset($lang_labels['and']) && !empty($lang_labels['and'])) ? $lang_labels['and'] : 'And'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="edit_your_password_and_account_details" class="control-checkbox">Edit Your Password and Account Details.</label>
                                            <input type="text" name="edit_your_password_and_account_details" class="form-control mb-2" value="<?= (isset($lang_labels['edit_your_password_and_account_details']) && !empty($lang_labels['edit_your_password_and_account_details'])) ? $lang_labels['edit_your_password_and_account_details'] : 'Edit Your Password and Account Details.'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="order_complete" class="control-checkbox">Order Complete</label>
                                            <input type="text" name="order_complete" class="form-control mb-2" value="<?= (isset($lang_labels['order_complete']) && !empty($lang_labels['order_complete'])) ? $lang_labels['order_complete'] : 'Order Complete'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="delivery_charge_with_cod" class="control-checkbox">Delivery charge with COD</label>
                                            <input type="text" name="delivery_charge_with_cod" class="form-control mb-2" value="<?= (isset($lang_labels['delivery_charge_with_cod']) && !empty($lang_labels['delivery_charge_with_cod'])) ? $lang_labels['delivery_charge_with_cod'] : 'Delivery charge with COD'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="delivery_charge_without_cod" class="control-checkbox">Delivery charge without COD</label>
                                            <input type="text" name="delivery_charge_without_cod" class="form-control mb-2" value="<?= (isset($lang_labels['delivery_charge_without_cod']) && !empty($lang_labels['delivery_charge_without_cod'])) ? $lang_labels['delivery_charge_without_cod'] : 'Delivery charge without COD'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="estimate_delivery_date" class="control-checkbox">Estimate delivery date</label>
                                            <input type="text" name="estimate_delivery_date" class="form-control mb-2" value="<?= (isset($lang_labels['estimate_delivery_date']) && !empty($lang_labels['estimate_delivery_date'])) ? $lang_labels['estimate_delivery_date'] : 'Estimate delivery date'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="total_with_cod" class="control-checkbox">Total</label>
                                            <input type="text" name="total_with_cod" class="form-control mb-2" value="<?= (isset($lang_labels['total_with_cod']) && !empty($lang_labels['total_with_cod'])) ? $lang_labels['total_with_cod'] : 'Total'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="apply_coupon" class="control-checkbox">Apply Coupon</label>
                                            <input type="text" name="apply_coupon" class="form-control mb-2" value="<?= (isset($lang_labels['apply_coupon']) && !empty($lang_labels['apply_coupon'])) ? $lang_labels['apply_coupon'] : 'Apply Coupon'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="see_all_offers" class="control-checkbox">See All Offers(%)</label>
                                            <input type="text" name="see_all_offers" class="form-control mb-2" value="<?= (isset($lang_labels['see_all_offers']) && !empty($lang_labels['see_all_offers'])) ? $lang_labels['see_all_offers'] : 'See All Offers(%)'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="door_step_delivery" class="control-checkbox">Door Step Delivery</label>
                                            <input type="text" name="door_step_delivery" class="form-control mb-2" value="<?= (isset($lang_labels['door_step_delivery']) && !empty($lang_labels['door_step_delivery'])) ? $lang_labels['door_step_delivery'] : 'Door Step Delivery'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="pickup_from_store" class="control-checkbox">Pickup From Store</label>
                                            <input type="text" name="pickup_from_store" class="form-control mb-2" value="<?= (isset($lang_labels['pickup_from_store']) && !empty($lang_labels['pickup_from_store'])) ? $lang_labels['pickup_from_store'] : 'Pickup From Store'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="cart_totals" class="control-checkbox">Cart Totals</label>
                                            <input type="text" name="cart_totals" class="form-control mb-2" value="<?= (isset($lang_labels['cart_totals']) && !empty($lang_labels['cart_totals'])) ? $lang_labels['cart_totals'] : 'Cart Totals'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="proceed_to_checkout" class="control-checkbox">Proceed to checkout</label>
                                            <input type="text" name="proceed_to_checkout" class="form-control mb-2" value="<?= (isset($lang_labels['proceed_to_checkout']) && !empty($lang_labels['proceed_to_checkout'])) ? $lang_labels['proceed_to_checkout'] : 'Proceed to checkout'; ?>" />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="you_may_be_interested_in" class="control-checkbox">You May Be Interested In…</label>
                                            <input type="text" name="you_may_be_interested_in" class="form-control mb-2" value="<?= (isset($lang_labels['you_may_be_interested_in']) && !empty($lang_labels['you_may_be_interested_in'])) ? $lang_labels['you_may_be_interested_in'] : 'You May Be Interested In…'; ?>" />
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="company" class="control-checkbox">Company</label>
                                            <input type="text" name="company" class="form-control mb-2" value="<?= (isset($lang_labels['company']) && !empty($lang_labels['company'])) ? $lang_labels['company'] : 'Company'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="legal" class="control-checkbox">Legal</label>
                                            <input type="text" name="legal" class="form-control mb-2" value="<?= (isset($lang_labels['legal']) && !empty($lang_labels['legal'])) ? $lang_labels['legal'] : 'Legal'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="resources" class="control-checkbox">Resources</label>
                                            <input type="text" name="resources" class="form-control mb-2" value="<?= (isset($lang_labels['resources']) && !empty($lang_labels['resources'])) ? $lang_labels['resources'] : 'Resources'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="popular_categories" class="control-checkbox">Popular Categories</label>
                                            <input type="text" name="popular_categories" class="form-control mb-2" value="<?= (isset($lang_labels['popular_categories']) && !empty($lang_labels['popular_categories'])) ? $lang_labels['popular_categories'] : 'Popular Categories'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="brands" class="control-checkbox">Brands</label>
                                            <input type="text" name="brands" class="form-control mb-2" value="<?= (isset($lang_labels['brands']) && !empty($lang_labels['brands'])) ? $lang_labels['brands'] : 'Brands'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="your_referral_code" class="control-checkbox">Your Referral Code</label>
                                            <input type="text" name="your_referral_code" class="form-control mb-2" value="<?= (isset($lang_labels['your_referral_code']) && !empty($lang_labels['your_referral_code'])) ? $lang_labels['your_referral_code'] : 'Your Referral Code'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="tap_to_copy" class="control-checkbox">Tap to copy</label>
                                            <input type="text" name="tap_to_copy" class="form-control mb-2" value="<?= (isset($lang_labels['tap_to_copy']) && !empty($lang_labels['tap_to_copy'])) ? $lang_labels['tap_to_copy'] : 'Tap to copy'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="compare_list_is_empty" class="control-checkbox">Compare list is empty.</label>
                                            <input type="text" name="compare_list_is_empty" class="form-control mb-2" value="<?= (isset($lang_labels['compare_list_is_empty']) && !empty($lang_labels['compare_list_is_empty'])) ? $lang_labels['compare_list_is_empty'] : 'Compare list is empty.'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="sub_title" class="control-checkbox">Invite your friends to join and get the reward as soon as your friend places his first order</label>
                                            <input type="text" name="sub_title" class="form-control mb-2" value="<?= (isset($lang_labels['sub_title']) && !empty($lang_labels['sub_title'])) ? $lang_labels['sub_title'] : 'Invite your friends to join and get the reward as soon as your friend places his first order'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="compare_subtitle" class="control-checkbox">No products added in the compare list. You must add some products to compare them.
                                                You will find a lot of interesting products on our "Shop" page.</label>
                                            <input type="text" name="compare_subtitle" class="form-control mb-2" value="<?= (isset($lang_labels['compare_subtitle']) && !empty($lang_labels['compare_subtitle'])) ? $lang_labels['compare_subtitle'] : 'No products added in the compare list. You must add some products to compare them.
                                                     You will find a lot of interesting products on our "Shop" page.'; ?>" />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="flash_sale" class="control-checkbox">Flash Sale</label>
                                            <input type="text" name="flash_sale" class="form-control mb-2" value="<?= (isset($lang_labels['flash_sale']) && !empty($lang_labels['flash_sale'])) ? $lang_labels['flash_sale'] : 'Flash Sale'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="current_balance" class="control-checkbox">Current Balance</label>
                                            <input type="text" name="current_balance" class="form-control mb-2" value="<?= (isset($lang_labels['current_balance']) && !empty($lang_labels['current_balance'])) ? $lang_labels['current_balance'] : 'Current Balance'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="withdraw_money" class="control-checkbox">Withdraw Money</label>
                                            <input type="text" name="withdraw_money" class="form-control mb-2" value="<?= (isset($lang_labels['withdraw_money']) && !empty($lang_labels['withdraw_money'])) ? $lang_labels['withdraw_money'] : 'Withdraw Money'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="clear_cart" class="control-checkbox">Clear Cart</label>
                                            <input type="text" name="clear_cart" class="form-control mb-2" value="<?= (isset($lang_labels['clear_cart']) && !empty($lang_labels['clear_cart'])) ? $lang_labels['clear_cart'] : 'Clear Cart'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="item_in_cart" class="control-checkbox">Item(s) in Cart</label>
                                            <input type="text" name="item_in_cart" class="form-control mb-2" value="<?= (isset($lang_labels['item_in_cart']) && !empty($lang_labels['item_in_cart'])) ? $lang_labels['item_in_cart'] : 'Item(s) in Cart'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mb-2" for="save_changes" class="control-checkbox">Save Changes</label>
                                            <input type="text" name="save_changes" class="form-control mb-2" value="<?= (isset($lang_labels['save_changes']) && !empty($lang_labels['save_changes'])) ? $lang_labels['save_changes'] : 'Save Changes'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="profile_image" class="control-checkbox">Profile Image</label>
                                            <input type="text" name="profile_image" class="form-control" value="<?= (isset($lang_labels['profile_image']) && !empty($lang_labels['profile_image'])) ? $lang_labels['profile_image'] : 'Profile Image'; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-4">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success" id="update_btn">Update</button>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center form-group">
                                        <div id="update-result" class="p-3"></div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!--/.card-->
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<div class="modal fade" id="language-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Language</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">

                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="add-new-language-form" action="<?= base_url('admin/language/create'); ?>" method="POST">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Name <small>(Language name should be in english)<small> </label>
                                    <input type="text" name="language" id="language" class="form-control" placeholder="Ex. English , Hindi" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Code</label>
                                    <input type="text" name="code" id="code" class="form-control" placeholder="Ex. EN , हिन्दी" />
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <input type="checkbox" name="is_rtl" class="form-checkbox" id="is_rtl_create" value="1" />
                            <label for="is_rtl_create" class="control-checkbox">Enable RTL</label>
                        </div>
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-success" id="submit_btn">Save</button>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center form-group">
                        <div id="result" class="p-3"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>