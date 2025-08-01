import moment from 'moment';
import 'moment/min/locales';

listenClick("#superAdminGuideAffiliation", function () {
    $("#superAdminGuideAffiliationModal").modal("show");
});

listenClick("#adminGuideAffiliation", function () {
    $("#adminGuideAffiliationModal").modal("show");
});

listenSubmit("#withdrawAmountForm", function (e) {
    e.preventDefault();

    $.ajax({
        url: route("withdraw-amount"),
        type: "Post",
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                Livewire.dispatch("refresh");
                $("#withdrawAmountModal").modal("hide");
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
});

listen("hidden.bs.modal", "#withdrawAmountModal", function () {
    $("#withdrawAmountForm")[0].reset();
});

listen("hidden.bs.modal", "#rejectWithdrawalModal", function () {
    $("#rejectionNote").val("");
});

listenClick("#rejectWithdrawalBtn", function (e) {
    e.preventDefault();
    let id = $(this).attr("data-id");
    $("#rejectWithdrawalStatus").attr("data-id", id);
    $("#rejectWithdrawalModal").appendTo("body").modal("show");
});

listenClick("#approveWithdrawalBtn", function (e) {
    e.preventDefault();
    let id = $(this).attr("data-id");
    let amount = $(this).attr("data-amount");
    $("#approveWithdrawalStatus").attr("data-id", id);
    $("#withdrawAmount").html(amount);
    $("#approveWithdrawalModal").appendTo("body").modal("show");
});

listenHiddenBsModal("#approveWithdrawalModal", function () {
    $("#withdrawPaymentMethod").val(0).trigger("change");
});

listenClick("#showAffiliationWithdrawBtn", function () {
    let id = $(this).attr("data-id");
    let url = route("sadmin.withdraw-transactions.show", { id: id });

    $.ajax({
        url: url,
        type: "Get",
        success: function (result) {
            if (result.success) {
                let withdrawal = result.data;
                let user = withdrawal.user;
                $("#withdrawalUsername").text(user.full_name);
                $("#withdrawalAmount").text(withdrawal.formattedAmount);
                if (withdrawal.bank_details != null) {
                    $(".bankDetailsDiv").removeClass("d-none");
                    $("#viewBankDetails").html((withdrawal.bank_details).replace(/\n/g, '<br>'));
                } else {
                    $(".bankDetailsDiv").addClass("d-none");
                }
                if (withdrawal.email != null) {
                    $(".paypalEmailDiv").removeClass("d-none");
                    $("#PaypalEmail").text(withdrawal.email);
                } else {
                    $(".paypalEmailDiv").addClass("d-none");
                }
                if (withdrawal.is_approved == 1) {
                    $("#withdrawalIsApproved")
                        .text(Lang.get("js.approved"))
                        .removeClass("bg-danger bg-warning")
                        .addClass("bg-success");
                } else if (withdrawal.is_approved == 2) {
                    $("#withdrawalIsApproved")
                        .text(Lang.get("js.rejected"))
                        .removeClass("bg-success bg-warning")
                        .addClass("bg-danger");
                } else {
                    $("#withdrawalIsApproved")
                        .text(Lang.get("js.in_progress"))
                        .removeClass("bg-success bg-danger")
                        .addClass("bg-warning");
                }
                $("#withdrawalDate").text(
                    moment(withdrawal.created_at)
                        .locale(lang)
                        .format(getFormattedDateTime(userDateFormate, 1)
                        )
                );
                if (withdrawal.rejection_note) {
                    $("#withdrawalRejectionDiv").removeClass("d-none");
                    $("#withdrawalRejectionNote").text(
                        withdrawal.rejection_note
                    );
                } else {
                    $("#withdrawalRejectionDiv").addClass("d-none");
                }
                $("#showAffiliationWithdrawModal")
                    .appendTo("body")
                    .modal("show");
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
});

listenClick("#approveWithdrawalStatus", function () {
    let withdrawalId = $(this).attr("data-id");
    let isApproved = $(this).attr("data-status");
    if (isApproved == 1 && $("#withdrawPaymentMethod").val() == "1") {
        $.ajax({
            type: "GET",
            url: route("paypal.payout"),
            data: {
                withdrawalId: withdrawalId,
            },
            success: function (result) {
                if (result.success) {
                    changeWithdrawalStatus(
                        withdrawalId,
                        isApproved,
                        result.data
                    );
                }
            },
            error: function (error) {
                displayErrorMessage(error.responseJSON.message);
            },
        });
    } else {
        changeWithdrawalStatus(withdrawalId, isApproved);
    }
});

listenClick("#rejectWithdrawalStatus", function () {
    if ($("#rejectionNote").val().trim().length == 0) {
        displayErrorMessage("Rejection note field is required");
        return false;
    }
    let withdrawalId = $(this).attr("data-id");
    let isApproved = $(this).attr("data-status");
    changeWithdrawalStatus(withdrawalId, isApproved);
});

function changeWithdrawalStatus(withdrawalId, isApproved, meta = null) {
    let rejectionNote = $("#rejectionNote").val();
    $.ajax({
        url: route("sadmin.change-withdrawal-status", {
            id: withdrawalId,
            isApproved: isApproved,
        }),
        data: { rejectionNote: rejectionNote, meta: meta },
        type: "post",
        success: function (result) {
            if (result.success) {
                Livewire.dispatch("refresh");
                displaySuccessMessage(result.message);
                $(".modal").modal("hide");
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
}

listenClick(".sendInviteBtn", function () {
    $("#sendRferralMail").modal("show");
});

listenSubmit("#sendReferralForm", function () {
    $("#sendRferralMail").modal("hide");
});

listenHiddenBsModal("#sendRferralMail", function () {
    resetModalForm("#sendReferralForm");
});

listenClick("#copyLinkBtn", function () {
    let value = $("#urlLink").select();
    document.execCommand("copy");
    displaySuccessMessage(Lang.get("js.copied_successfully"));
});

listenClick(".sendmailbtn", function () {
    displaySuccessMessage(Lang.get("js.affiliation_email_send"));
});
