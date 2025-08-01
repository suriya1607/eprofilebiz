document.addEventListener("DOMContentLoaded", function () {
    let storeId = $("#whatsappStoreId").val();
    loadPhoneInput();
    Lang.setLocale(lang);
    productCount(storeId);
});

listenClick(".addToCartBtn", function (event) {
    event.preventDefault();

    let button = $(this);
    let originalContent = button.html();
    button.html(" ✓ ").addClass("animate-btn");
    button.prop("disabled", true);

    setTimeout(function () {
        button.removeClass("animate-btn");
        button.prop("disabled", false);
        button.html(originalContent);
    }, 2000);

    let storeId = $("#whatsappStoreId").val();
    let productId = $(this).attr("data-id");

    let productCard = $(this).closest(
        ".item-card, .product-card, .details, .product-detail-content, .product-box-section"
    );

    let product = {
        id: $(this).data("id"),
        name: productCard.find(".product-name").text().trim(),
        available_stock: productCard.find(".available-stock").val(),
        image_url:
            productCard.find(".product-image").attr("src") ||
            productCard.find(".product-image").val(),
        currency_icon: productCard.find(".currency_icon").text().trim(),
        category_name:
            productCard.find(".product-category").text().trim() ||
            productCard.find(".product-category").val(),
        qty: 1,
        price: productCard.find(".selling_price").text().trim(),
        total_price: productCard.find(".selling_price").text().trim(),
    };
    addToCart(storeId, product);
});

function addToCart(storeId, product) {
    let cart = JSON.parse(localStorage.getItem("cart")) || {};

    if (!cart[`store_${storeId}`]) {
        cart[`store_${storeId}`] = { grand_total: 0 };
    }

    let storeCart = cart[`store_${storeId}`];

    if (
        storeCart[product.id] &&
        storeCart[product.id].qty >= product.available_stock
    ) {
        displayErrorMessage(Lang.get("js.no_more_quantity"));
        return;
    }
    displaySuccessMessage(Lang.get("js.product_added_to_cart"));
    if (storeCart[product.id]) {
        storeCart[product.id].qty += 1;
        storeCart[product.id].total_price =
            storeCart[product.id].price * storeCart[product.id].qty;
    } else {
        storeCart[product.id] = { ...product };
    }

    storeCart.grand_total = Object.values(storeCart)
        .filter((p) => typeof p === "object")
        .reduce((sum, p) => sum + Number(p.total_price), 0);

    localStorage.setItem("cart", JSON.stringify(cart));

    productCount(storeId);
}

listenClick("#addToCartViewBtn", function () {
    let storeId = $("#whatsappStoreId").val();

    let cartData = JSON.parse(localStorage.getItem("cart")) || {};

    let cart = cartData[`store_${storeId}`] || {};

    let grandTotal = cart?.grand_total ?? 0;

    let cartArray = Object.values(cart).filter(
        (item) => item && item.id != null
    );

    let cartItems = $("#cartItems");
    cartItems.html("");
    const locale = Lang.getLocale();
    const rtlClass = locale == "ar" ? "rtl" : "";

    let totalDetails = $("#totalDetails");
    totalDetails.html("");

    let cartItemsCloth = $("#cartItemsCloth");
    cartItemsCloth.html("");

    if (cartArray.length === 0) {
        cartItems.html(`
              <tr>
           <td colspan="5">
            <div class="d-flex py-3 justify-content-center align-items-center w-100" >
                    <h4 class="fs-18  text-muted mb-0">${Lang.get("js.item_not_addded_to_cart")}</h4>
                </div>
           </td>
       </tr>
        `);

        cartItemsCloth.html(`
       <tr>
           <td colspan="5">
            <div class="d-flex py-3 justify-content-center align-items-center w-100" >
                    <h4 class="fs-18  text-muted mb-0">${Lang.get("js.item_not_addded_to_cart")}</h4>
                </div>
           </td>
       </tr>
        `);

        totalDetails.html(`
            <div class="text-center py-3 w-100">
                <h4 class="fs-18 text-muted">${Lang.get("js.item_not_addded_to_cart")}</h4>
            </div>
        `);
    } else {
       
    $.each(cartArray, function (index, item) {
        cartItems.append(`
            <tr class="${rtlClass}">
                <td class="fw-6 fs-14">
                    <div class="d-flex gap-lg-4 gap-3 align-items-center">
                        <div class="product-img">
                            <img  src="${item.image_url}" alt="product" style="width: 50px ; height: 50px;" class=" object-fit-cover rounded"  loading="lazy" />
                        </div>
                        <div>
                            <h5 class="fs-18 fw-6 mb-0">${item.name}</h5>
                            <p class="mb-0 fs-14">${item.category_name}</p>
                        </div>
                    </div>
                </td>
                <td class="fw-6 fs-14">${item.currency_icon} ${item.price}</td>
                <td class="text-center">
                    <div class="btn-group gap-1 justify-content-center">
                        <button type="button" class="btn minus-btn" data-id="${item.id}">-</button>
                        <button type="button" class="btn count-btn bg-white" id="qty_${item.id}">${item.qty}</button>
                        <button type="button" class="btn plus-btn" data-id="${item.id}">+</button>
                    </div>
                </td>
                <td class="fw-6 fs-14 text-end" id="total_${item.id}">${item.currency_icon} ${item.total_price}</td>
                 <td class="text-center">
                <button type="button" class="btn delete-btn" data-id="${item.id}" style="padding:4px 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256">
                              <g fill="#f00808" fill-rule="nonzero">
                    <g transform="scale(8.53333,8.53333)">
                 <path d="M14.98438,2.48633c-0.55152,0.00862 -0.99193,0.46214 -0.98437,1.01367v0.5h-5.5c-0.26757,-0.00363 -0.52543,0.10012 -0.71593,0.28805c-0.1905,0.18793 -0.29774,0.44436 -0.29774,0.71195h-1.48633c-0.36064,-0.0051 -0.69608,0.18438 -0.87789,0.49587c-0.18181,0.3115 -0.18181,0.69676 0,1.00825c0.18181,0.3115 0.51725,0.50097 0.87789,0.49587h18c0.36064,0.0051 0.69608,-0.18438 0.87789,-0.49587c0.18181,-0.3115 0.18181,-0.69676 0,-1.00825c-0.18181,-0.3115 -0.51725,-0.50097 -0.87789,-0.49587h-1.48633c0,-0.26759 -0.10724,-0.52403 -0.29774,-0.71195c-0.1905,-0.18793 -0.44836,-0.29168 -0.71593,-0.28805h-5.5v-0.5c0.0037,-0.2703 -0.10218,-0.53059 -0.29351,-0.72155c-0.19133,-0.19097 -0.45182,-0.29634 -0.72212,-0.29212zM6,9l1.79297,15.23438c0.118,1.007 0.97037,1.76563 1.98438,1.76563h10.44531c1.014,0 1.86538,-0.75862 1.98438,-1.76562l1.79297,-15.23437z"></path>
                     </g>
                 </g>
                     </svg>
                </button>

                </td>
            </tr>
        `);

            cartItemsCloth.append(`
 <tr>
   <td>
      <div class="product-card-box d-flex align-items-center gap-20">
         <div class="product-img">
            <img src="${item.image_url}" alt="images"
               class="h-100 w-100 object-fit-cover" loading="lazy" />
         </div>
         <div>
            <p class="fs-18 fw-5 mb-1 restaurant-white">${item.name}</p>
            <p class="fs-14 text-gray-200 fw-5 mb-0 restaurant-white">${item.category_name}</p>

         </div>
      </div>
   </td>
   <td>
      <div class="d-flex count-btn w-100 mx-auto align-items-center">
         <button type="button" class="btn minus-btn count-modal-btn restaurant-white"  data-id="${item.id}">-</button>
         <p class="fs-14 fw-5 mb-0 text-black w-100 text-center restaurant-white" id="qty_${item.id}">${item.qty}</p>
         <button type="button" class="btn plus-btn count-modal-btn restaurant-white" data-id="${item.id}">+</button>
      </div>
   </td>

    <td class="fs-16 fw-5 text-center text-nowrap restaurant-white">
      ${item.currency_icon} ${item.price}
   </td>

   <td class="text-center">
      <button type="button" class="btn delete-btn" data-id="${item.id}" style="padding:4px 8px;">
         <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256">
            <g fill="#f00808" fill-rule="nonzero">
               <g transform="scale(8.53333,8.53333)">
                  <path d="M14.98438,2.48633c-0.55152,0.00862 -0.99193,0.46214 -0.98437,1.01367v0.5h-5.5c-0.26757,-0.00363 -0.52543,0.10012 -0.71593,0.28805c-0.1905,0.18793 -0.29774,0.44436 -0.29774,0.71195h-1.48633c-0.36064,-0.0051 -0.69608,0.18438 -0.87789,0.49587c-0.18181,0.3115 -0.18181,0.69676 0,1.00825c0.18181,0.3115 0.51725,0.50097 0.87789,0.49587h18c0.36064,0.0051 0.69608,-0.18438 0.87789,-0.49587c0.18181,-0.3115 0.18181,-0.69676 0,-1.00825c-0.18181,-0.3115 -0.51725,-0.50097 -0.87789,-0.49587h-1.48633c0,-0.26759 -0.10724,-0.52403 -0.29774,-0.71195c-0.1905,-0.18793 -0.44836,-0.29168 -0.71593,-0.28805h-5.5v-0.5c0.0037,-0.2703 -0.10218,-0.53059 -0.29351,-0.72155c-0.19133,-0.19097 -0.45182,-0.29634 -0.72212,-0.29212zM6,9l1.79297,15.23438c0.118,1.007 0.97037,1.76563 1.98438,1.76563h10.44531c1.014,0 1.86538,-0.75862 1.98438,-1.76562l1.79297,-15.23437z"></path>
               </g>
            </g>
         </svg>
      </button>
   </td>
</tr>
            `);

            totalDetails.append(`
            <div class="d-flex justify-content-between total-details align-items-center gap-2 my-3" id="product_${item.id}">
                                        <p class="fs-16 fw-5 text-black mb-0 restaurant-white">${item.name}</p>
                                        <p class="fs-16 fw-5 text-black mb-0 restaurant-white text-nowrap">${item.currency_icon} <span id="product_cart_${item.id}">${item.total_price}</span></p>

                                    </div>
            `);
        });
    }
    $("#grandTotal").text(`${grandTotal}`);

    $("#cartModal").modal("show");
});

listenClick(".delete-btn", function () {
    let storeId = $("#whatsappStoreId").val();
    let productId = $(this).attr("data-id");

    $("#product_" + productId).remove();

    displaySuccessMessage(Lang.get("js.product_deleted_from_cart"));

    let cart = JSON.parse(localStorage.getItem("cart")) || {};

    if (cart[`store_${storeId}`] && cart[`store_${storeId}`][productId]) {
        delete cart[`store_${storeId}`][productId];

        cart[`store_${storeId}`].grand_total = Object.values(
            cart[`store_${storeId}`]
        )
            .filter((p) => typeof p === "object")
            .reduce((sum, p) => sum + Number(p.total_price), 0);

        if (Object.keys(cart[`store_${storeId}`]).length === 1) {
            delete cart[`store_${storeId}`];
        }

        localStorage.setItem("cart", JSON.stringify(cart));

        $(this).closest("tr").remove();

        productCount(storeId);

        $("#grandTotal").text(` ${cart[`store_${storeId}`]?.grand_total ?? 0}`);
    }
});

listenClick(".plus-btn", function () {
    let storeId = $("#whatsappStoreId").val();
    let productId = $(this).attr("data-id");

    let cart = JSON.parse(localStorage.getItem("cart")) || {};
    let storeCart = cart[`store_${storeId}`];
    if (storeCart[productId].qty >= storeCart[productId].available_stock) {
        displayErrorMessage(Lang.get('js.no_more_quantity'));
        return;
    }

    if (storeCart && storeCart[productId]) {
        storeCart[productId].qty += 1;
        storeCart[productId].total_price =
            storeCart[productId].qty * storeCart[productId].price;
        storeCart.grand_total = Object.values(storeCart)
            .filter((p) => typeof p === "object")
            .reduce((sum, p) => sum + Number(p.total_price), 0);

        localStorage.setItem("cart", JSON.stringify(cart));

        productCount(storeId);
        $(`#qty_${productId}`).text(storeCart[productId].qty);
        $(`#total_${productId}`).text(
            `${storeCart[productId].currency_icon} ${storeCart[productId].total_price}`
        );
        $("#product_cart_" + productId).text(storeCart[productId].total_price);
        $("#grandTotal").text(`${storeCart.grand_total}`);
    }
});
listenClick(".minus-btn", function () {
    let storeId = $("#whatsappStoreId").val();
    let productId = $(this).attr("data-id");

    let cart = JSON.parse(localStorage.getItem("cart")) || {};
    let storeCart = cart[`store_${storeId}`];

    if (storeCart && storeCart[productId]) {
        if (storeCart[productId].qty === 1) {
            return;
        }

        storeCart[productId].qty -= 1;
        storeCart[productId].total_price =
            storeCart[productId].qty * storeCart[productId].price;

        storeCart.grand_total = Object.values(storeCart)
            .filter((p) => typeof p === "object")
            .reduce((sum, p) => sum + Number(p.total_price), 0);

        localStorage.setItem("cart", JSON.stringify(cart));

        productCount(storeId);
        $(`#qty_${productId}`).text(storeCart[productId].qty);
        $(`#total_${productId}`).text(
            `${storeCart[productId].currency_icon} ${storeCart[productId].total_price}`
        );
        $("#product_cart_" + productId).text(storeCart[productId].total_price);
        $("#grandTotal").text(`${storeCart.grand_total}`);
    }
});

function productCount(storeId) {

    let cart = JSON.parse(localStorage.getItem("cart")) || {};
    let storeCart = cart[`store_${storeId}`] || {};
    let productCount = Object.values(storeCart).filter(
        (item) => item && item.id
    ).length;
    let count = productCount > 0 ? productCount : 0;

    if (count == 0) {

        let cartItems = $("#cartItems");
        cartItems.html("");

        let totalDetails = $("#totalDetails");
        totalDetails.html("");

        let cartItemsCloth = $("#cartItemsCloth");
        cartItemsCloth.html("");

        cartItems.html(`
              <tr>
           <td colspan="5">
            <div class="d-flex py-3 justify-content-center align-items-center w-100" >
                    <h4 class="fs-18  text-muted mb-0">${Lang.get(
                        "js.item_not_addded_to_cart"
                    )}</h4>
                </div>
           </td>
       </tr>
        `);

        cartItemsCloth.html(`
       <tr>
           <td colspan="5">
            <div class="d-flex py-3 justify-content-center align-items-center w-100" >
                    <h4 class="fs-18  text-muted mb-0">${Lang.get(
                        "js.item_not_addded_to_cart"
                    )}</h4>
                </div>
           </td>
       </tr>
        `);

        totalDetails.html(`
            <div class="text-center py-3 w-100">
                <h4 class="fs-18 text-muted">${Lang.get(
                    "js.item_not_addded_to_cart"
                )}</h4>
            </div>
        `);

        $(".order-btn").prop("disabled", true);

    } else {
        $(".order-btn").prop("disabled", false);
    }

    $(".product-count-badge").text(count);
}

function loadPhoneInput() {
    let phoneInput = document.querySelector("#phoneNumber");
    let regionCodeInput = document.querySelector("#prefix_code");

    if (phoneInput) {
        let iti = window.intlTelInput(phoneInput, {
            initialCountry: defaultCountryCodeValue,
            preferredCountries: ["us", "gb", "in"],
            separateDialCode: true,
        });

        phoneInput.addEventListener("countrychange", function () {
            let countryData = iti.getSelectedCountryData();
            regionCodeInput.value = countryData.dialCode;
        });

        // phoneInput.addEventListener("blur", function () {
        //     if (iti.isValidNumber()) {

        //         document.getElementById("valid-msg").classList.remove("d-none");
        //         document.getElementById("error-msg").classList.add("d-none");
        //     } else {
        //         document.getElementById("valid-msg").classList.add("d-none");
        //         document.getElementById("error-msg").classList.remove("d-none");
        //     }
        // });
    }
}

listenSubmit("#orderForm", function (event) {
    event.preventDefault();

    $(this).find(".btn").prop("disabled", true);

    let storeId = $("#whatsappStoreId").val();
    let cart = JSON.parse(localStorage.getItem("cart")) || {};
    let storeCart = cart[`store_${storeId}`];
    let grandTotal = storeCart?.grand_total || 0;
    let products = [];

    if (storeCart) {
        products = Object.values(storeCart)
            .filter((p) => typeof p === "object")
            .filter((item) => item && item.id != null)
            .map((p) => ({
                id: p.id,
                price: p.price,
                qty: p.qty,
                total_price: p.total_price,
            }));
    }

    let orderDetails =
        $(this).serialize() +
        "&wp_store_id=" +
        storeId +
        "&grand_total=" +
        grandTotal +
        "&products=" +
        encodeURIComponent(JSON.stringify(products))+
        "&language="+lang;

    let url = $("#productBuyUrl").val();

    setTimeout(() => {
        $.ajax({
            url: url,
            type: "POST",
            data: orderDetails,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    prepareAndSendWpMessage(response.data);
                    localStorage.removeItem("cart");
                    productCount(storeId);
                    displaySuccessMessage(Lang.get("js.order_placed"));
                    setTimeout(() => {
                        window.location.reload();
                    }, 3000);
                }
            },
            error: function (response) {
                $(this).find(".btn").prop("disabled", false);
                displayErrorMessage(response.responseJSON.message);
                setTimeout(() => {
                    window.location.reload();
                }, 4000);
            },
        });
    }, 3000);
});

function prepareAndSendWpMessage(order) {
    let baseUrl = $("#baseUrl").val();
    let storeAlias = $("#storeAlias").val();
    let wpRegionCode = $("#wpRegionCode").val();
    let whatsappNumber = $("#whatsappNo").val();

    let message = Lang.get("js.customer_details") + `:\n`;
    message += `------------------------------\n`;
    message += Lang.get('js.name') + `: ${order.name}\n`;
    message += Lang.get('js.phone') + `: +${order.region_code} ${order.phone}\n`;
    message += Lang.get('js.address') + `: ${order.address}\n\n`;
    message += Lang.get('js.order_id') + `: ${order.order_id}\n`;
    message += `------------------------------\n`;
    message += Lang.get('js.product_details') + `:\n`;
    message += `------------------------------\n`;

    order.products.forEach((product, index) => {
        let productUrl = `${baseUrl}/whatsapp-store/${storeAlias}/${product.product_id}/product-details`;

        message += `${index + 1}.\n`;
        message += Lang.get('js.product_name') + `: ${product.product ? product.product.name : "Unknown"
            }\n`;
        message += Lang.get('js.product_url') + ` : ${productUrl}\n`;
        message += Lang.get('js.price') + ` : ${product.product.currency.currency_icon} ${product.price}\n`;
        message += Lang.get('js.quantity') + ` : ${product.qty}\n`;
        message += Lang.get('js.total_price') + ` : ${product.product.currency.currency_icon} ${product.total_price}\n`;
        message += `------------------------------\n`;
    });

    message += `\n${Lang.get("js.grand_total")}: ${order.grand_total}\n`;

    let encodedMessage = encodeURIComponent(message);
    let recipientPhone = `+${wpRegionCode}${whatsappNumber}`;
    let whatsappUrl = `https://wa.me/${recipientPhone}?text=${encodedMessage}`;

    window.open(whatsappUrl, "_blank");
}

listenClick("#languageName", function () {
    let languageName = $(this).attr("data-name");
    $.ajax({
        url: languageChange + "/" + languageName + "/" + vcardAlias,
        type: "GET",
        success: function (result) {
            location.reload();
        },
        error: function error(result) {
            alert(result.responseJSON.message);
        },
    });
});
window.displaySuccessMessage = function (message) {
    toastr.options = {
        positionClass: "toast-top-right",
        progressBar: true,
        closeButton: true,
        timeOut: 5000,
        extendedTimeOut: 2000,
    };
    toastr.success(message, Lang.get("js.successful"));
};
window.displayErrorMessage = function (message) {
    toastr.options = {
        positionClass: "toast-top-right",
        progressBar: true,
        closeButton: true,
        timeOut: 5000,
        extendedTimeOut: 2000,
    };
    toastr.error(message, Lang.get("js.error"));
};

listenClick(".drop-item-select", function () {
    $(".drop-item-select").removeClass("active");
    $(this).addClass("active");
});


 listenClick(".custom-select-option", function () {
    $(".custom-select-option").removeClass("active");
    $(this).addClass("active");
 });

listenClick(".pwa-close", function () {
    $(".pwa-support").addClass("d-none");
});
