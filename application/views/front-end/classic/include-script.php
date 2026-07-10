<script src="<?= THEME_ASSETS_URL . 'js/eshop-bundle-js.js' ?>"></script>

<!-- Firebase.js -->
<script src="<?= THEME_ASSETS_URL . 'js/firebase-app.js' ?>"></script>
<script src="<?= THEME_ASSETS_URL . 'js/firebase-auth.js' ?>"></script>
<script src="<?= base_url('firebase-config.js') ?>"></script>
<script>
    (function () {
        function showAuthError(message) {
            if (typeof Toast !== 'undefined' && Toast.fire) {
                Toast.fire({
                    icon: 'error',
                    title: message || 'Google login failed. Please try again.'
                });
            } else {
                alert(message || 'Google login failed. Please try again.');
            }
        }

        function socialLoginByGoogle() {
            if (typeof firebase === 'undefined' || !firebase.auth) {
                showAuthError('Firebase auth is not initialized.');
                return;
            }

            var provider = new firebase.auth.GoogleAuthProvider();
            provider.addScope('email');

            firebase.auth().signInWithPopup(provider).then(function (authResult) {
                var email = authResult.user && authResult.user.email ? authResult.user.email : '';
                if (!email && authResult.user && authResult.user.providerData && authResult.user.providerData[0]) {
                    email = authResult.user.providerData[0].email || '';
                }
                if (!email && authResult.additionalUserInfo && authResult.additionalUserInfo.profile) {
                    email = authResult.additionalUserInfo.profile.email || '';
                }
                var payload = {
                    type: 'google',
                    name: (authResult.user && authResult.user.displayName) ? authResult.user.displayName : '',
                    email: email,
                    password: (authResult.user && authResult.user.uid) ? authResult.user.uid : ''
                };

                if (typeof csrfName !== 'undefined' && typeof csrfHash !== 'undefined') {
                    payload[csrfName] = csrfHash;
                }

                $.post(base_url + 'home/verifyUser', {
                    email: payload.email,
                    type: payload.type,
                    [csrfName]: csrfHash
                }, function (verifyRes) {
                    if (verifyRes.csrfName) csrfName = verifyRes.csrfName;
                    if (verifyRes.csrfHash) csrfHash = verifyRes.csrfHash;

                    var loginNow = function () {
                        var loginData = {
                            identity: payload.email,
                            type: payload.type,
                            password: payload.password
                        };
                        loginData[csrfName] = csrfHash;
                        $.post(base_url + 'home/login', loginData, function (loginRes) {
                            if (loginRes.csrfName) csrfName = loginRes.csrfName;
                            if (loginRes.csrfHash) csrfHash = loginRes.csrfHash;
                            if (loginRes.error === false) {
                                location.reload();
                            } else {
                                showAuthError(loginRes.message);
                            }
                        }, 'json');
                    };

                    if (verifyRes.error === true) {
                        var registerData = {
                            type: payload.type,
                            name: payload.name,
                            email: payload.email,
                            password: payload.password
                        };
                        registerData[csrfName] = csrfHash;
                        $.post(base_url + 'auth/register_user', registerData, function (registerRes) {
                            if (registerRes.csrfName) csrfName = registerRes.csrfName;
                            if (registerRes.csrfHash) csrfHash = registerRes.csrfHash;
                            if (registerRes.error === false) {
                                loginNow();
                            } else {
                                showAuthError(registerRes.message);
                            }
                        }, 'json');
                    } else {
                        loginNow();
                    }
                }, 'json');
            }).catch(function (error) {
                showAuthError((error && error.message) ? error.message : null);
            });
        }

        document.addEventListener('click', function (event) {
            var trigger = event.target.closest('#googleLogin');
            if (!trigger) return;
            event.preventDefault();
            event.stopImmediatePropagation();
            socialLoginByGoogle();
        }, true);
    })();
</script>

<?php if ($this->session->flashdata('message')) { ?>
    <script>
        Toast.fire({
            icon: '<?= $this->session->flashdata('message_type'); ?>',
            title: "<?= $this->session->flashdata('message'); ?>"
        });
    </script>
<?php } ?>
