<script>
       $(document).ready(function () {

        let deferredPrompt;

        window.addEventListener('beforeinstallprompt', (e) => {
            console.log('beforeinstallprompt fired');
            e.preventDefault();
            deferredPrompt = e;
        });

        $('#installPwaBtn').on('click', function () {
            console.log('Install button clicked');
            
            if (deferredPrompt) {
                deferredPrompt.prompt(); // Show the browser install UI

                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                       $('.pwa-support').addClass('d-none');
                        console.log('PWA installed, sending AJAX');

                        let updateUrl = route("vcard.pwa.status", vcardId); 
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



        $('#sendWhatsAppBtn').on('click', function (e) {
            e.preventDefault();

            const number = $('#wpNumber').val().trim();
            const message = $('#wpMessageInput').val().trim()|| '';
            const receiver = $('#wpReceiver').val().trim();
            const vcardId = $('input[name="vcard_id"]').val();
            const currentUrl = receiver 
                ? `${document.URL}?receiver=${btoa(receiver)}` 
                : document.URL;
            /* const currentUrl = document.URL; */

            if (!number) {
                alert("Please enter a WhatsApp number");
                return;
            }

        $.ajax({
            url: '{{ route("vcard.senderslist.store") }}',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                vcard_id: vcardId,
                senders_name: receiver,
                senders_number: number,
                senders_message: message,
            },
            success: function (res) {

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
                alert("Failed to save sender info");
            }
        });
        });
    });
</script>