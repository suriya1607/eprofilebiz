<script>
       $(document).ready(function () {

        $('#sendWhatsAppBtn').on('click', function (e) {
            e.preventDefault();

            const number = $('#wpNumber').val().trim();
            const message = $('#wpMessageInput').val().trim()|| '';
            const receiver = $('#wpReceiver').val().trim();
            const vcardId = $('input[name="vcard_id"]').val();
            const currentUrl = `${document.URL}?receiver=${encodeURIComponent(receiver)}`;
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
            },
            success: function (res) {

                let greetingmsg = `Greetings,

                Here's a quick glimpse of my e-profile:
                ${currentUrl}

                Looking forward to fruitful engagements.

                ${message}`;

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