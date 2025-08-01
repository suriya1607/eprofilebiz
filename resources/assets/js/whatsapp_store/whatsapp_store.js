//delete whatsapp store
listenClick(".whatsapp-store-delete-btn", function (event) {
    let recordId = $(event.currentTarget).attr("data-id");
    deleteItem(
        route("whatsapp.stores.destroy", recordId),
        Lang.get("js.whatsapp_store")
    );
});

//save or update wp template
listenClick(".wp-template-save", function () {
    let template_id = $("#themeInput").val();

    if (isEmpty(template_id) || template_id == 0) {
        displayErrorMessage(Lang.get("js.choose_one_template"));
        return false;
    }
    let whatsappStore = $("#whatsappStoreId").val();

    $.ajax({
        url: route("wp.template.update", whatsappStore),
        type: "POST",
        data: { template_id: template_id },
        success: function (response) {
            displaySuccessMessage(response.message);
        },
        error: function (response) {
            displayErrorMessage(response.responseJSON.message);
        },
    });
});

//save or update wp template seo
listenClick(".wp-template-seo-save", function (e) {
    e.preventDefault();

    let whatsappStore = $("#whatsappStoreId").val();

    let formData = new FormData();
    formData.append('site_title', $('input[name="site_title"]').val());
    formData.append('home_title', $('input[name="home_title"]').val());
    formData.append('meta_keyword', $('input[name="meta_keyword"]').val());
    formData.append('meta_description', $('input[name="meta_description"]').val());
    formData.append('google_analytics', $('textarea[name="google_analytics"]').val());

    $.ajax({
        url: route("wp.template.seo.update", whatsappStore),
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            displaySuccessMessage(response.message);
        },
        error: function (response) {
            displayErrorMessage(response.responseJSON.message);
        },
    });
});
