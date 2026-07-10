var android_app_store_link = $('#android_app_store_link').val();
var ios_app_store_link = $('#ios_app_store_link').val();
var host = $('#host').val();
var scheme = $('#scheme').val();
var app_name = $('#app_name').val();
var doctor_brown = $('#doctor_brown').val();

if (doctor_brown != '') {

    $(document).ready(function () {
        // Function to check if the device is mobile or tablet
        function isMobileOrTablet() {
            return true;
            return window.matchMedia("(max-width: 1024px)").matches;
        }
        function openInApp(pathName) {
            // var appScheme = 'eshop://vendoreshop.wrteam.co.in'+pathName;
            // var androidAppStoreLink = 'https://play.google.com/store/apps/details?id=eShop.multivendor.customer';
            // var iosAppStoreLink = 'https://apps.apple.com/app/eshop/idYOUR_APP_ID';
            var appScheme = scheme + '://' + host + pathName;
            var androidAppStoreLink = android_app_store_link;
            var iosAppStoreLink = ios_app_store_link;
            var userAgent = navigator.userAgent || navigator.vendor || window.opera;
            var isAndroid = /android/i.test(userAgent);
            var isIOS = /iPad|iPhone|iPod/.test(userAgent) && !window.MSStream;
            var appStoreLink = isAndroid ? androidAppStoreLink : (isIOS ? iosAppStoreLink : androidAppStoreLink);
            // Attempt to open the app
            window.location.href = appScheme;
            // Set a timeout to check if app opened
            setTimeout(function () {
                if (document.hidden || document.webkitHidden) {
                    // App opened successfully
                } else {
                    // App is not installed, ask user if they want to go to app store
                    if (confirm(app_name + "app is not installed. Would you like to download it from the app store?")) {
                        window.location.href = appStoreLink;
                    }
                }
            }, 1000);
        }
        // Only add the bottom sheet and its functionality if it's a mobile or tablet device
        if (document.getElementById("share_slug") !== null) {
            if (isMobileOrTablet()) {
                // Add the bottom sheet HTML
                const pathName = window.location.pathname;
                let deeplinkWrapper = document.getElementsByClassName("deeplink_wrapper")[0];
                
                if (!deeplinkWrapper) {
                    deeplinkWrapper = document.createElement('div');
                    deeplinkWrapper.className = 'deeplink_wrapper';
                    document.body.appendChild(deeplinkWrapper);
                }
                
                if (deeplinkWrapper) {
                    deeplinkWrapper.innerHTML = `
            <div class="bottom-sheet p-4" id="bottomSheet">
                <h5>Open in App</h5>
                <p>Get a better experience by using our mobile app!</p>
                <button class="btn btn-outline-secondary w-100 mb-2" onclick="openApp()">Open in APP</button>
                <button class="btn btn-outline-danger w-100" onclick="hideBottomSheet()">Close</button>
            </div>
            ` + deeplinkWrapper.innerHTML;
                //  <button class="btn btn-outline-secondary w-100" onclick="hideBottomSheet()">Close</button>
                // <button class="btn btn-primary w-100 mb-2" onclick="openApp()>Open in App</button>
                // Add the CSS for the bottom sheet
                const style = document.createElement('style');
                style.textContent = `
                .bottom-sheet {
                    position: fixed;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    background-color: #fff;
                    border-top-left-radius: 15px;
                    border-top-right-radius: 15px;
                    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
                    transform: translateY(100%);
                    transition: transform 0.3s ease-out;
                    z-index: 1050;
                }
                .bottom-sheet.show {
                    transform: translateY(0);
                }
                @media (min-width: 1025px) {
                    .bottom-sheet {
                        display: none;
                    }
                }
            `;
                    document.head.appendChild(style);
                    // Define the toggle and hide functions
                    window.toggleBottomSheet = function (show = true) {
                        const bottomSheet = document.getElementById('bottomSheet');
                        if (show) {
                            bottomSheet.classList.add('show');
                        } else {
                            bottomSheet.classList.remove('show');
                        }
                    }
                    window.hideBottomSheet = function () {
                        toggleBottomSheet(false);
                        sessionStorage.setItem('bottomSheetShown', 'true');
                    }
                    window.openApp = function () {
                        openInApp(pathName);
                    }
                    // Check if we should show the bottom sheet when the page loads
                    if (!sessionStorage.getItem('bottomSheetShown')) {
                        toggleBottomSheet(true);
                    }
                }
            }
        }
    });
}

//Landing Page Email Verification & Password Reset
if (window.location.href === base_url + '/landing') {
$(document).ready(function () {
    // Toggle password visibility
    $(document).on('click', '.togglePassword', function () {
        const input = $(this).siblings('input');
        const icon = $(this).find('i');

        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Check for Firebase action parameters (email verification or password reset)
    let paramQueries = new URLSearchParams(window.location.search);
    let mode = paramQueries.get("mode");
    let oobCode = paramQueries.get("oobCode");

    let resetForm = document.getElementById("resetForm");

    if (mode === "verifyEmail" && oobCode) {
        // Handle email verification
        // Hide the main content
        $('.content-wrapper > .justify-center').hide();
        if (resetForm) resetForm.style.display = "none";

        // Apply the email verification code
        firebase.auth().applyActionCode(oobCode)
            .then(() => {
                console.log("Email verification successful.");
                Toast.fire({
                    title: 'Email Verified!',
                    icon: 'success',
                }).then(() => {
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                });
            })
            .catch((error) => {
                console.log("Error verifying email:", error);
                let errorMessage = "Invalid or expired verification link";

                // Handle specific error codes
                switch (error.code) {
                    case 'auth/expired-action-code':
                        errorMessage = "This verification link has expired. Please request a new one.";
                        break;
                    case 'auth/invalid-action-code':
                        errorMessage = "This verification link is invalid or has already been used.";
                        break;
                    case 'auth/user-disabled':
                        errorMessage = "This user account has been disabled.";
                        break;
                    case 'auth/user-not-found':
                        errorMessage = "No user found for this verification link.";
                        break;
                }

                Swal.fire({
                    title: 'Verification Failed',
                    text: errorMessage,
                    icon: 'error',
                    confirmButtonText: 'Go to Homepage',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then(() => {
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                });
            });
    } else if (mode === "resetPassword" && oobCode) {
        // Show reset form
        if (resetForm) {
            resetForm.style.display = "block";
        }

        // Hide the main content
        $('.content-wrapper > .justify-center').hide();

        // Verify reset code
        firebase.auth().verifyPasswordResetCode(oobCode)
            .then((email) => {
                console.log("Reset code is valid.");
                Toast.fire({
                    icon: "success",
                    title: "Enter your new Password"
                });
                window.resetEmail = email;
            })
            .catch((error) => {
                console.log("Error verifying reset code:", error);
                Toast.fire({
                    icon: "error",
                    title: "Invalid or expired link"
                });
                resetForm.style.display = "none";
                delete window.resetEmail;
                setTimeout(() => {
                    location.reload();
                }, 1000);
            });

        // Handle reset form submit
        resetForm.addEventListener("submit", function (e) {
            e.preventDefault();
            const newPassword = document.getElementById("newPassword").value;
            const email = window.resetEmail;
            var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

        if (!passwordRegex.test(newPassword)) {
            Toast.fire({
                icon: 'error',
                title: "Password must be at least 8 characters, with one uppercase letter, one lowercase letter, one number, and one special character."
            });
            return;
        }
            if (!email) {
                Toast.fire({
                    icon: "error",
                    title: "Session expired. Please try again."
                });
                return;
            }

            $('#reset_pass_btn').attr('disabled', true).text('Resetting...');

            firebase.auth().confirmPasswordReset(oobCode, newPassword)
                .then(() => {
                    console.log("Firebase password reset successful.");
                    // Update password in Database
                    $.ajax({
                        url: base_url + 'home/reset-password',
                        type: 'POST',
                        data: {
                            email: email,
                            new_password: newPassword
                        },
                        beforeSend: function () {
                            console.log("Sending request to reset MySQL password..." + email);
                        },
                        success: function (response) {
                            try {
                                const res = JSON.parse(response);
                                if (res.error) {
                                    console.log("MySQL password reset failed:", res.message);
                                    Toast.fire({
                                        icon: "error",
                                        title: res.message
                                    });
                                    $('#reset_pass_btn').attr('disabled', false).text('Reset Password');
                                } else {
                                    console.log("MySQL password reset successful.");
                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'Password has been reset successfully! Open App',
                                        icon: 'success',
                                    }).then(() => {
                                        resetForm.style.display = "none";
                                        setTimeout(() => {
                                            location.reload();
                                        }, 1000);
                                    });
                                }
                            } catch (e) {
                                console.error("Error parsing response:", e);
                                Toast.fire({
                                    icon: "error",
                                    title: "An error occurred. Please try again."
                                });
                                $('#reset_pass_btn').attr('disabled', false).text('Reset Password');
                            }
                        },
                        error: function (xhr, status, error) {
                            console.log("Error calling reset_password API:", error);
                            Toast.fire({
                                icon: "error",
                                title: "Failed to update password in database"
                            });
                            $('#reset_pass_btn').attr('disabled', false).text('Reset Password');
                            delete window.resetEmail;
                        }
                    });
                })
                .catch((error) => {
                    console.log("Error resetting Firebase password:", error);
                    let errorMessage = "Failed to reset password";

                    switch (error.code) {
                        case 'auth/expired-action-code':
                            errorMessage = "This reset link has expired. Please request a new one.";
                            break;
                        case 'auth/invalid-action-code':
                            errorMessage = "This reset link is invalid or has already been used.";
                            break;
                        case 'auth/weak-password':
                            errorMessage = "Password is too weak. Please use a stronger password.";
                            break;
                    }

                    Swal.fire({
                        title: 'Reset Failed',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'Go to Homepage'
                    }).then(() => {
                        delete window.resetEmail;
                        location.href = base_url;
                    });
                });
        });
    }
});
}