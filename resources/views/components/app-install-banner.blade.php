<style>
#installBanner {
    display: none;
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    max-width: 420px;
    width: 90%;
    background: #ffffff;
    border-radius: 15px;
    padding: 18px;
    box-shadow: 0px 5px 25px rgba(0,0,0,0.18);
    z-index: 99999;
}

#installBanner .close-btn {
    position: absolute;
    right: 12px;
    top: 10px;
    font-size: 20px;
    color: #777;
    cursor: pointer;
}

#installBanner .banner-title {
    font-weight: 700;
    font-size: 17px;
    color: #333;
}

#installBanner .banner-sub {
    font-size: 14px;
    color: #777;
    margin-top: 5px;
}

#installBanner .actions {
    margin-top: 14px;
    text-align: right;
}

#installBanner .install-btn {
    background: #1e64ff;
    color: #fff;
    padding: 8px 18px;
    border-radius: 8px;
    font-weight: 600;
    border: none;
    cursor: pointer;
}

</style>

<div id="installBanner">
    <div class="close-btn" id="bannerClose">×</div>
    <div class="banner-title">Get our Mobile App</div>
    <div class="banner-sub">Fast, secure, and optimized for your device.</div>

    <div class="actions">
        <button class="install-btn" id="appLinkBtn">Install</button>
    </div>
</div>
<script>
function getDeviceType() {
    const ua = navigator.userAgent.toLowerCase();
    if (ua.includes("android")) return "android";
    if (ua.includes("iphone") || ua.includes("ipad")) return "ios";
    return "web"; // desktop/laptop
}

function initInstallBanner() {
    // If user closed the banner in this session → don't show again
    if (sessionStorage.getItem("hideInstallBanner") === "yes") {
        return;
    }

    const device = getDeviceType();

    // Set correct store link
    if (device === "android") {
        $("#appLinkBtn").attr("onclick",
            "window.location='https://play.google.com/store/apps/details?id=com.digitalwall'");
    }
    else if (device === "ios") {
        $("#appLinkBtn").attr("onclick",
            "window.location='https://apps.apple.com/in/app/digital-wall/id6749620161'");
    }
    else {
        // Desktop → show banner with iOS store or your website page
        $("#appLinkBtn").attr("onclick",
            "window.location='https://apps.apple.com/in/app/digital-wall/id6749620161'");
    }

    // Mobile deep link check
    if (device === "android" || device === "ios") {
        const deepLink = "digitalwall://home"; // replace with real deep link

        window.location = deepLink;

        setTimeout(function () {
            $("#installBanner").fadeIn(300);
        }, 1200);

    } else {
        // Desktop always show
        $("#installBanner").fadeIn(300);
    }
}

// Close button event
$("#bannerClose").on("click", function () {
    $("#installBanner").fadeOut(200);
    sessionStorage.setItem("hideInstallBanner", "yes");
});

// Run after login page loads
$(document).ready(function () {
    initInstallBanner();
});
</script>