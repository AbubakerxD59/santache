<script src="<?= base_url("assets/front_end/modern/js/eshop-bundle-js.js") ?>"></script>

<script src="<?= base_url("assets/front_end/modern/js/eshop-bundle-top-js.js") ?>" type="module"></script>
<!-- lazy-load js -->
<script src="<?= base_url('assets/front_end/modern/js/lazyload.min.js') ?>"></script>

<!-- Firebase.js -->
<script src="<?= base_url('assets/front_end/modern/js/firebase-app.js') ?>"></script>
<script src="<?= base_url('assets/front_end/modern/js/firebase-auth.js') ?>"></script>
<script src="<?= base_url('assets/front_end/modern/js/firebase-firestore.js') ?>"></script>
<script src="<?= base_url('firebase-config.js') ?>"></script>

<style>
#google-auth-loader {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 99999;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, 0.45);
}
#google-auth-loader.is-visible {
    display: flex;
}
#google-auth-loader .google-auth-loader-card {
    background: #fff;
    border-radius: 10px;
    padding: 28px 32px;
    text-align: center;
    max-width: 320px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}
#google-auth-loader .google-auth-spinner {
    width: 36px;
    height: 36px;
    margin: 0 auto 14px;
    border: 3px solid #e5e5e5;
    border-top-color: #4285f4;
    border-radius: 50%;
    animation: google-auth-spin 0.7s linear infinite;
}
#google-auth-loader .google-auth-loader-title {
    margin: 0 0 6px;
    font-size: 16px;
    font-weight: 600;
    color: #222;
}
#google-auth-loader .google-auth-loader-text {
    margin: 0;
    font-size: 13px;
    color: #666;
    line-height: 1.4;
}
@keyframes google-auth-spin {
    to { transform: rotate(360deg); }
}
</style>
<script>
(() => {
  let authInProgress = false;

  function ensureLoader() {
    let el = document.getElementById("google-auth-loader");
    if (el) return el;
    el = document.createElement("div");
    el.id = "google-auth-loader";
    el.setAttribute("role", "status");
    el.setAttribute("aria-live", "polite");
    el.innerHTML =
      '<div class="google-auth-loader-card">' +
        '<div class="google-auth-spinner" aria-hidden="true"></div>' +
        '<p class="google-auth-loader-title">Connecting to Google…</p>' +
        '<p class="google-auth-loader-text">Please complete sign-in in the popup window.</p>' +
      "</div>";
    document.body.appendChild(el);
    return el;
  }

  function showGoogleAuthLoader(title, text) {
    const el = ensureLoader();
    if (title) el.querySelector(".google-auth-loader-title").textContent = title;
    if (text) el.querySelector(".google-auth-loader-text").textContent = text;
    el.classList.add("is-visible");
  }

  function hideGoogleAuthLoader() {
    const el = document.getElementById("google-auth-loader");
    if (el) el.classList.remove("is-visible");
    authInProgress = false;
  }

  function showAuthError(message) {
    hideGoogleAuthLoader();
    if (typeof Toast !== "undefined" && Toast.fire) {
      Toast.fire({
        icon: "error",
        title: message || "Google login failed. Please try again.",
      });
    } else {
      alert(message || "Google login failed. Please try again.");
    }
  }

  function warmFirebaseAuth() {
    if (typeof firebase === "undefined" || !firebase.auth) return;
    try {
      const auth = firebase.auth();
      auth.onAuthStateChanged(() => {});
      auth.getRedirectResult().catch(() => {});

      const authDomain = firebase.app().options.authDomain;
      if (!authDomain || document.getElementById("firebase-auth-preconnect")) return;

      [
        "https://" + authDomain,
        "https://accounts.google.com",
        "https://apis.google.com",
        "https://www.googleapis.com",
      ].forEach((origin, index) => {
        const link = document.createElement("link");
        if (index === 0) link.id = "firebase-auth-preconnect";
        link.rel = "preconnect";
        link.href = origin;
        link.crossOrigin = "anonymous";
        document.head.appendChild(link);

        const dns = document.createElement("link");
        dns.rel = "dns-prefetch";
        dns.href = origin;
        document.head.appendChild(dns);
      });

      const prefetch = document.createElement("link");
      prefetch.rel = "prefetch";
      prefetch.href = "https://" + authDomain + "/__/auth/handler";
      document.head.appendChild(prefetch);
    } catch (e) {
      // Ignore warmup failures
    }
  }

  function socialLoginByGoogle() {
    if (authInProgress) return;
    if (typeof firebase === "undefined" || !firebase.auth) {
      showAuthError("Firebase auth is not initialized.");
      return;
    }

    authInProgress = true;
    showGoogleAuthLoader(
      "Connecting to Google…",
      "Please complete sign-in in the popup window.",
    );

    const provider = new firebase.auth.GoogleAuthProvider();
    provider.addScope("email");

    firebase
      .auth()
      .signInWithPopup(provider)
      .then((authResult) => {
        showGoogleAuthLoader("Signing you in…", "Almost done, please wait.");

        let email = authResult.user && authResult.user.email ? authResult.user.email : "";
        if (!email && authResult.user && authResult.user.providerData && authResult.user.providerData[0]) {
          email = authResult.user.providerData[0].email || "";
        }
        if (!email && authResult.additionalUserInfo && authResult.additionalUserInfo.profile) {
          email = authResult.additionalUserInfo.profile.email || "";
        }

        const payload = {
          type: "google",
          name: authResult.user && authResult.user.displayName ? authResult.user.displayName : "",
          email: email,
          password: authResult.user && authResult.user.uid ? authResult.user.uid : "",
        };

        $.post(
          base_url + "home/verifyUser",
          {
            email: payload.email,
            type: payload.type,
            [csrfName]: csrfHash,
          },
          (verifyRes) => {
            if (verifyRes.csrfName) csrfName = verifyRes.csrfName;
            if (verifyRes.csrfHash) csrfHash = verifyRes.csrfHash;

            const loginNow = () => {
              const loginData = {
                identity: payload.email,
                type: payload.type,
                password: payload.password,
                [csrfName]: csrfHash,
              };

              $.post(base_url + "home/login", loginData, (loginRes) => {
                if (loginRes.csrfName) csrfName = loginRes.csrfName;
                if (loginRes.csrfHash) csrfHash = loginRes.csrfHash;
                if (loginRes.error === false) {
                  location.reload();
                } else {
                  showAuthError(loginRes.message);
                }
              }, "json").fail(() => {
                showAuthError("Login failed. Please try again.");
              });
            };

            if (verifyRes.error === true) {
              const registerData = {
                type: payload.type,
                name: payload.name,
                email: payload.email,
                password: payload.password,
                [csrfName]: csrfHash,
              };

              $.post(base_url + "auth/register_user", registerData, (registerRes) => {
                if (registerRes.csrfName) csrfName = registerRes.csrfName;
                if (registerRes.csrfHash) csrfHash = registerRes.csrfHash;
                if (registerRes.error === false) {
                  loginNow();
                } else {
                  showAuthError(registerRes.message);
                }
              }, "json").fail(() => {
                showAuthError("Registration failed. Please try again.");
              });
            } else {
              loginNow();
            }
          },
          "json",
        ).fail(() => {
          showAuthError("Login failed. Please try again.");
        });
      })
      .catch((error) => {
        if (error && (error.code === "auth/popup-closed-by-user" || error.code === "auth/cancelled-popup-request")) {
          hideGoogleAuthLoader();
          return;
        }
        showAuthError(error && error.message ? error.message : null);
      });
  }

  document.addEventListener(
    "click",
    (event) => {
      const trigger = event.target.closest("#googleLogin");
      if (!trigger) return;
      event.preventDefault();
      event.stopImmediatePropagation();
      socialLoginByGoogle();
    },
    true,
  );

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", warmFirebaseAuth);
  } else {
    warmFirebaseAuth();
  }
})();
</script>

<!-- intlTelInput -->


<!-- lottie animation js -->
<script
    src="<?= base_url('assets/front_end/modern/js/unpkg.com_@lottiefiles_lottie-player@2.0.2_dist_lottie-player.js') ?>">
</script>

<!-- Custom Js -->
<!-- <script type="module" src="<?//= base_url('assets/front_end/modern/js/custom.js') ?>"></script> -->
<script>
const Toast = Swal.mixin({
    toast: true,
    position: 'top-right',
    iconColor: 'white',
    customClass: {
        popup: 'colored-toast'
    },
    showConfirmButton: false,
    timer: 1500,
    timerProgressBar: true
})
</script>

<?php if ($this->session->flashdata('message')) { ?>
<script>
Toast.fire({
    icon: '<?= $this->session->flashdata('message_type'); ?>',
    title: "<?= $this->session->flashdata('message'); ?>"
});
</script>
<?php } ?>
