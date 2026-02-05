<script>
       $(document).ready(function () {

        let deferredPrompt;
            renderMsgHistory();


        window.addEventListener('beforeinstallprompt', (e) => {
            console.log('beforeinstallprompt fired');
            e.preventDefault();
            deferredPrompt = e;
        });

        const data = sessionStorage.getItem('wpForm');
        if (data) {
            const v = JSON.parse(data);
            $('#wpNumber').val(v.number);
            $('#wpReceiver').val(v.receiver);
            $('#wpMessageInput').val(v.message);
        }
        else if (!$('#wpNumber').val()) {
            $('#wpNumber').val('+91');
        }

        $('#wpNumber').on('input', function () {
            let value = this.value;
            if (!value.startsWith('+91')) {
                value = '+91';
            }
            let digits = value.replace('+91', '').replace(/\D/g, '');
            digits = digits.slice(0, 10);

            this.value = '+91' + digits;
        });

        $('#installPwaBtn').on('click', function () {
            console.log('Install button clicked');
            
            if (deferredPrompt) {
                deferredPrompt.prompt(); // Show the browser install UI

                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                       $('.pwa-support').addClass('d-none');
                        console.log('PWA installed, sending AJAX');

                        let updateUrl = route("vcard.pwa.status", vcardId) + "?downloadstatus=1"; 
                        $.ajax({
                            type: "get",
                            url: updateUrl,
                            success: function (response) {
                                 console.log('PWA installed');
                            },
                            error: function (error) {
                                 console.log('PWA installation failed');
                            },
                        });
                    } else {
                        console.log('PWA install dismissed');
                    }

                    deferredPrompt = null;
                });
            }
        });

        function downloadVCard(name, number) {
            const vcard = [
            'BEGIN:VCARD',
            'VERSION:3.0',
            `FN:${name}`,
            `TEL;TYPE=CELL:${number}`,
            'END:VCARD'
            ].join('\r\n');

            const blob = new Blob([vcard], { type: 'text/vcard' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `${name || 'contact'}.vcf`;
            document.body.appendChild(a);
            a.click();
            a.remove();
            URL.revokeObjectURL(url);
        }

        $('#sendWhatsAppBtn').on('click', function (e) {
            e.preventDefault();
            sessionStorage.setItem('wpForm', JSON.stringify({
                number: $('#wpNumber').val(),
                receiver: $('#wpReceiver').val(),
                message: $('#wpMessageInput').val()
            }));

            const number = $('#wpNumber').val().trim();
            const message = $('#wpMessageInput').val().trim()|| '';
            const receiver = $('#wpReceiver').val().trim();
            const vcardId = $('input[name="vcard_id"]').val();
            const saveContact = $('#saveContactCheckbox').is(':checked');
            if(message) {
                saveLastMessages(message);
                renderMsgHistory();
            }
            if (saveContact) {
                    downloadVCard(receiver || 'My Contact', number);

            }

            if (!number) {
                alert("Please enter a WhatsApp number");
                return;
            }

        $.ajax({
            url: '{{ route("vcard.senderslist.store") }}',
            method: 'POST',
            data: {
                // _token: $('meta[name="csrf-token"]').attr('content'),
                vcard_id: vcardId,
                senders_name: receiver,
                senders_number: number,
                senders_message: message,
            },
            success: function (res) {

            sessionStorage.removeItem('wpForm');

            let currentUrl = `${document.URL}?sid=${btoa(res.id)}`;

            let greetingmsg = `*Greetings,*\n\nHere's a quick glimpse of my e-profile:\n${currentUrl}\n\nLooking forward to fruitful engagements.`;


                const encodedMsg = encodeURIComponent(greetingmsg);
                const url = `https://wa.me/${number}?text=${encodedMsg}`;
                window.open(url, '_blank');

                    $('#global-whatsappModal').modal('hide');
                    $('#wpNumber').val('');
                    $('#wpReceiver').val('');
                    $('#wpMessageInput').val('');
            },
            error: function (err) {
                console.error('Error saving sender:', err);
                alert("Failed to save sender info.\nClick OK to Reload");
                location.reload();
            }
        });
        });


        $(document).on('change', '.role-check', function () {
            $('.role-check').not(this).prop('checked', false);
        });
        $(document).on('input blur', '#emailField', function () {
            let email = $(this).val();
            if (email.length > 0) {
                $('#vcardSaveBtn').prop('disabled', true);
            }
            else{
                $('#vcardSaveBtn').prop('disabled', false);
            }
         });    

        $(document).on('blur', '#emailField', function () {
        let email = $(this).val();
        let saveBtn = $('#vcardSaveBtn');

        if (email.length > 0) {
            saveBtn.prop('disabled', true);
            $.ajax({
                url: "{{ route('check.validemail', ':email') }}".replace(':email', email),
                type: "GET",
                success: function (response) {
                    if (response.valid) {
                        $('#roleSection').removeClass('d-none');
                        $('.companytype').removeClass('d-none');
                         saveBtn.prop('disabled', false);
                    } else {
                        $('#roleSection').addClass('d-none');
                        $('.companytype').addClass('d-none');
                        saveBtn.prop('disabled', true);

                         if (response.exists) {
                            displayErrorMessage('Email already exists!');
                        } else {
                            displayErrorMessage('Invalid email!');
                        }
                    }
                },
                error: function () {
                    $('#roleSection').addClass('d-none');
                     $('.companytype').addClass('d-none');
                }
            });
        }
        else{
            saveBtn.prop('disabled', false);
        }
    });
    });


    function saveLastMessages(message) {
        let history = JSON.parse(sessionStorage.getItem('wpMsgHistory')) || [];

        history = history.filter(m => m !== message);

        history.unshift(message);

        history = history.slice(0, 3);

        sessionStorage.setItem('wpMsgHistory', JSON.stringify(history));
    }

    function renderMsgHistory() {
        const history = JSON.parse(sessionStorage.getItem('wpMsgHistory')) || [];
        const box = $('#msgHistory');

        box.html('');

        history.forEach((msg, index) => {
            box.append(`
                <div class="msg-pill" data-msg="${msg.replace(/"/g, '&quot;')}">
                    ${msg}
                </div>
            `);
        });
    }

    $(document).on('click', '.msg-pill', function () {
        $(this).toggleClass('active');
        let combined = [];
        $('.msg-pill.active').each(function () {
            combined.push($(this).data('msg'));
        });
        $('#wpMessageInput').val(combined.join('\n\n')).focus();
    });


    // vcard exit 
    // $(document).on('click', '.vcard_exit-btn', function(event) {
    //     event.preventDefault();
    //     let vcardExitId = $(event.currentTarget).attr('data-id');

    //     Swal2.fire({
    //         title: "Exit!",
    //         text: "Transfer and exit?",
    //         input: "text", 
    //         inputLabel: "Type email to transfer or skip to erase all data",
    //         inputPlaceholder: "Enter email",
    //         showCancelButton: true,
    //         confirmButtonText: "Confirm Exit",
    //         cancelButtonText: "No",
    //         confirmButtonColor: "#009ef7",
    //         didOpen: () => {
    //             const confirmBtn = Swal2.getConfirmButton();
    //             const inputElm = Swal2.getInput();

    //             // Enable confirm if blank initially
    //             confirmBtn.disabled = !!inputElm.value;

    //             const validateEmail = (email) => {
    //                 const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    //                 return emailRegex.test(email);
    //             };

    //             $(inputElm).on('input', function() {
    //                 let email = $(this).val().trim();
    //                 Swal2.resetValidationMessage();

    //                 if (email === "") {
    //                     confirmBtn.disabled = false;
    //                     return;
    //                 }

    //                 confirmBtn.disabled = true;

    //                 if (!validateEmail(email)) {
    //                     Swal2.showValidationMessage('Please enter a valid email');
    //                     return;
    //                 }

    //                 $.ajax({
    //                     url: "{{ route('check.validemail', ':email') }}".replace(':email', encodeURIComponent(email)),
    //                     type: "GET",
    //                     success: function(response) {
    //                         if (response.valid) {
    //                             Swal2.resetValidationMessage();
    //                             confirmBtn.disabled = false;
    //                         } else {
    //                             Swal2.showValidationMessage(response.exists ? 'Email already exists!' : 'Invalid email!');
    //                             confirmBtn.disabled = true;
    //                         }
    //                     },
    //                     error: function() {
    //                         Swal2.showValidationMessage('Error validating email. Please try again.');
    //                         confirmBtn.disabled = true;
    //                     }
    //                 });
    //             });
    //         },
    //         preConfirm: (email) => {
    //             // Always allow blank or validated email
    //             return email;
    //         }
    //     }).then(function(result) {
    //         if (result.isConfirmed) {
    //             let url = route("CardUserExit");
    //             $.ajax({
    //             url: url,
    //             type: "POST",
    //             data: { email: result.value ,vcardid: vcardExitId},
    //             success: function(response) {
    //                 console.log(response);
    //                 // return false;
    //                 Swal2.fire("Success", response.message || "Exited!", "success");
    //             },
    //             error: function(xhr) {
    //                 console.log(xhr,'xhr');
    //                 Swal2.fire("Error", "Ajax failed or server error.", "error");
    //             }
    //         });
    //         }
    //     });
    // });
    $(document).on('click', '.vcard_exit-btn', function(event) {
    event.preventDefault();

    // Load SweetAlert2 dynamically only when needed
    if (typeof Swal2 === 'undefined') {
        let script = document.createElement('script');
        script.src = "https://cdn.jsdelivr.net/npm/sweetalert2@11";
        script.onload = () => {
            window.Swal2 = window.Swal;
            Swal2Handler(event); // call the alert once loaded
        };
        document.head.appendChild(script);
    } else {
        Swal2Handler(event); // already loaded
    }
});

function Swal2Handler(event) {
    let vcardExitId = $(event.currentTarget).attr('data-id');

    Swal2.fire({
        title: "Exit!",
        text: "Transfer and exit?",
        input: "text",
        inputLabel: "Type email to transfer or skip to erase all data",
        inputPlaceholder: "Enter email",
        showCancelButton: true,
        confirmButtonText: "Confirm Exit",
        cancelButtonText: "No",
        confirmButtonColor: "#009ef7",
        didOpen: () => {
            const confirmBtn = Swal2.getConfirmButton();
            const inputElm = Swal2.getInput();

            confirmBtn.disabled = !!inputElm.value;

            const validateEmail = (email) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

            $(inputElm).on('input', function() {
                let email = $(this).val().trim();
                Swal2.resetValidationMessage();

                if (email === "") {
                    confirmBtn.disabled = false;
                    return;
                }

                confirmBtn.disabled = true;

                if (!validateEmail(email)) {
                    Swal2.showValidationMessage('Please enter a valid email');
                    return;
                }

                $.ajax({
                    url: "{{ route('check.validemail', ':email') }}".replace(':email', encodeURIComponent(email)),
                    type: "GET",
                    success: function(response) {
                        if (response.valid) {
                            Swal2.resetValidationMessage();
                            confirmBtn.disabled = false;
                        } else {
                            Swal2.showValidationMessage(response.exists ? 'Email already exists!' : 'Invalid email!');
                            confirmBtn.disabled = true;
                        }
                    },
                    error: function() {
                        Swal2.showValidationMessage('Error validating email. Please try again.');
                        confirmBtn.disabled = true;
                    }
                });
            });
        },
        preConfirm: (email) => email
    }).then(function(result) {
        if (result.isConfirmed) {
            let url = route("CardUserExit");
            $.ajax({
                url: url,
                type: "POST",
                data: { email: result.value, vcardid: vcardExitId },
                success: function(response) {
                    Swal2.fire("Success", response.message || "Exited!", "success");
                },
                error: function() {
                    Swal2.fire("Error", "Ajax failed or server error.", "error");
                }
            });
        }
    });
}

</script>