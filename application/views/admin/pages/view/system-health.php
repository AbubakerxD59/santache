<div class="content-wrapper">
    <section class="content-header mt-4">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-8 ">
                    <h4>System Health</h4>
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active"><a href="<?= base_url('admin/system_health') ?>">System Health</a></li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-info p-4 system_health_table">
                            <h4 class="">System Analytics</h4>
                            <hr>
                            <div class="d-flex">
                                <h6 class="text-bold"> Current PHP Version : </h6>
                                <p class="mb-0 mx-3"> 8.1</p>
                            </div>
                            <div class="d-flex">
                                <h6 class="text-bold"> Required Minimum PHP Version : </h6>
                                <p class="mb-0 mx-3"> 7.4</p>
                            </div>
                            <div class="d-flex">
                                <h6 class="text-bold"> Required Maximum PHP Version : </h6>
                                <p class="mb-0 mx-3"> 8.3</p>
                            </div>

                            <div class="mt-4">
                            <table class="table table-striped table-bordered">
                                    <tbody>
                                        <tr>
                                            <th scope="col">Number</th>
                                            <th scope="col">Title</th>
                                            <th scope="col">Discription</th>
                                        </tr>
                                        <tr scope="row">
                                            <td>1.</td>
                                            <td>cURL Extension</td>
                                            <td>Needs to enable this extension on your server(cPanel).This is used for payment methods.</td>
                                        </tr>
                                        <tr scope="row">
                                            <td>2.</td>
                                            <td>Zip Extension</td>
                                            <td>Needs to enable this extension on your server(cPanel).This is used for update system using zip files.</td>
                                        </tr>
                                        <tr scope="row">
                                            <td>3.</td>
                                            <td>Upload_tmp_dir </td>
                                            <td>Needs to Set Upload_tmp_dir on your server(cPanel).</td>
                                        </tr>
                                        <tr scope="row">
                                            <td>4.</td>
                                            <td>Open SSL Extension</td>
                                            <td>Needs to enable this extension on your server(cPanel).</td>
                                        </tr>
                                        <tr scope="row">
                                            <td>4.</td>
                                            <td>5.</td>
                                            <td>Notification Settings</td>
                                            <td>To enable Application Push Notifications, please complete these steps: <br>
                                                &emsp;1. Set your Vap ID key from Firebase account.( Firebase → Project Settings → Cloud Messaging → Web Configuration → here you have to generate it ) <br>
                                                &emsp;2. Set your Firebase project ID. ( Firebase → Project Settings → General → Project ID ) <br>
                                                &emsp;3. Upload the service account JSON file associated with your Firebase account. ( Firebase → Project Settings → Service Account → Generate new private key ) <br>
                                                These actions are necessary to ensure proper configuration and functionality of push notifications within the application.</td>
                                        </tr>
                                        <tr scope="row">
                                            <td>5.</td>
                                            <td>6.</td>
                                            <td>Email Settings <a href="https://www.gmass.co/smtp-test" target="_blank"><i class="fa fa-link"></i></a></td>
                                            <td>You need to set SMTP Email Settings for Email Notification.For this setting you need to check your server SMTP Email settings. If that is not working then Ask your support to check your SMTP settings.</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <h4 class="mt-4"> For Payment Settings</h4>
                                <table class="table table-striped table-bordered">
                                    <tbody>
                                        <tr scope="row">
                                            <th scope="col">Number</th>
                                            <th scope="col">Title</th>
                                            <th scope="col">Discription</th>
                                        </tr>
                                        <tr scope="row">
                                            <td>6.</td>
                                            <td>Paypal Payments <a href="https://www.paypal.com/in/business" target="_blank"><i class="fa fa-link"></i></a></td>
                                            <td>You need to create Paypal Payments Account on official bussiness site.</td>
                                        </tr>
                                        <tr scope="row">
                                            <td>7.</td>
                                            <td>Razorpay Payments <a href="https://razorpay.com/" target="_blank"><i class="fa fa-link"></i></a></td>
                                            <td>You need to create Razorpay Payments Account on official bussiness site.</td>
                                        </tr>
                                        <tr scope="row">
                                            <td>8.</td>
                                            <td>Paystack Payments <a href="https://paystack.com/" target="_blank"><i class="fa fa-link"></i></a></td>
                                            <td>You need to create Paystack Payments Account on official bussiness site.</td>
                                        </tr>
                                        <tr scope="row">
                                            <td>9.</td>
                                            <td>Stripe Payments <a href="https://stripe.com/in" target="_blank"><i class="fa fa-link"></i></a></td>
                                            <td>You need to create Stripe Payments Account on official bussiness site.</td>
                                        </tr>
                                        <tr scope="row">
                                            <td>10.</td>
                                            <td>Flutterwave Payments <a href="https://flutterwave.com/us/" target="_blank"><i class="fa fa-link"></i></a></td>
                                            <td>You need to create Flutterwave Payments Account on official bussiness site.</td>
                                        </tr>
                                        <tr scope="row">
                                            <td>11.</td>
                                            <td>Paytm Payments <a href="https://business.paytm.com/" target="_blank"><i class="fa fa-link"></i></a></td>
                                            <td>You need to create Paytm Payments Account on official bussiness site.</td>
                                        </tr>
                                        <tr scope="row">
                                            <td>12.</td>
                                            <td>Midtrans Payments <a href="https://midtrans.com/" target="_blank"><i class="fa fa-link"></i></a></td>
                                            <td>You need to create Midtrans Payments Account on official bussiness site.</td>
                                        </tr>
                                        <tr scope="row">
                                            <td>13.</td>
                                            <td>Myfatoorah Payments <a href="https://www.myfatoorah.com/" target="_blank"><i class="fa fa-link"></i></a></td>
                                            <td>You need to create Myfatoorah Payments Account on official bussiness site.</td>
                                        </tr>
                                        <tr scope="row">
                                            <td>14.</td>
                                            <td>Instamojo Payments <a href="https://www.instamojo.com/" target="_blank"><i class="fa fa-link"></i></a></td>
                                            <td>You need to create Instamojo Payments Account on official bussiness site.</td>
                                        </tr>
                                        <tr scope="row">
                                            <td>15.</td>
                                            <td>Phone pe Payments <a href="https://www.phonepe.com/" target="_blank"><i class="fa fa-link"></i></a></td>
                                            <td>You need to create Phone pe Payments Account on official bussiness site.</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <h4 class="mt-4"> For Shipping Settings</h4>
                                <table class="table table-striped table-bordered">
                                    <tbody>
                                        <tr scope="row">
                                            <th scope="col">Number</th>
                                            <th scope="col">Title</th>
                                            <th scope="col">Discription</th>
                                        </tr>
                                        <tr scope="row">
                                            <td>16.</td>
                                            <td>Local Shipping</td>
                                            <td>To use local shipping , please complete these steps : <br>
                                                &emsp;1. Set delivrability system from admin panel → system → store setting ( enable zipcode wise or city wise ). <br>
                                                &emsp;2. Add cities in admin panel → location → city. <br>
                                                &emsp;3. Add zipcodes in admin panel → location → zipcodes ( for zipcode wise delivrability). <br>
                                                These actions are necessary to ensure proper configuration and functionality of local shipping within the application. <br>
                                            </td>
                                        </tr>
                                        <tr scope="row">
                                            <td>17.</td>
                                            <td>Standard delivery method (Shiprocket) <a href="https://www.shiprocket.in/" target="_blank"><i class="fa fa-link"></i></a></td>
                                            <td>To use Standard shipping , please complete these steps : <br>
                                                &emsp;1. Set your Shiprocket API credentials. <br>
                                                &emsp;2. Set your pick up address. <br>
                                                These actions are necessary to ensure proper configuration and functionality of local shipping within the application.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <h4 class="mt-4"> For Authentication Settings</h4>
                                <table class="table table-striped table-bordered">
                                    <tbody>
                                        <tr scope="row">
                                            <th scope="col">Number</th>
                                            <th scope="col">Title</th>
                                            <th scope="col">Discription</th>
                                        </tr>
                                        <tr scope="row">
                                            <td>18.</td>
                                            <td>Firebase</td>
                                            <td>To use Firebase , please complete these steps : <br>
                                                &emsp;1. Set Firebase settings from admin panel → Web settings → Firebase Settings. <br>
                                                &emsp;2. Add 'test' in databaseURL and measurementId . <br>
                                                These actions are necessary to ensure proper configuration and functionality of Firebase within the application. <br>
                                            </td>
                                        </tr>
                                        <tr scope="row">
                                            <td>19.</td>
                                            <td>Custom SMS Gateway <a href="https://www.twilio.com/en-us" target="_blank"><i class="fa fa-link"></i></a></td>
                                            <td>To use Custom SMS Gateway , please complete these steps : <br>
                                                &emsp;1. Set your custom sms gateway settings from Admin panel → System → SMS Gateway Settings. <br>
                                                &emsp;2. In base url add your sms gateways base url. <br>
                                                &emsp;3. Add authorization token in header. <br>
                                                &emsp;2. Add body data in Body. <br>
                                                These actions are necessary to ensure proper configuration and functionality of SMS Gateway within the application.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>