<!-- previous working code.... -->
<!-- <div class="icon-search-container mb-3" data-ic-class="search-trigger">
        <div class="wp-btn">
            <i class="fab text-light  fa-whatsapp fa-2x" id="wpIcon"></i>
        </div>
        <input type="number" class="search-input" id="wpNumber" data-ic-class="search-input"
            placeholder="{{ __('messages.setting.wp_number') }}" />
        <div class="share-wp-btn-div">
            <a href="javascript:void(0)"
                class="vcard37-sticky-btn vcard37-btn-group d-flex justify-content-center text-primary align-items-center rounded-0 text-decoration-none py-1 rounded-pill justify-content share-wp-btn">
                <i class="fa-solid fa-paper-plane"></i> </a>
        </div>
</div> -->

<!-- update working code.... -->

<!-- WhatsApp Share Modal Trigger Button -->
<!-- WhatsApp Share Button -->
<button type="button"
    class="wp-btn mb-3 px-2 py-1"
    data-bs-toggle="modal" data-bs-target="#global-whatsappModal">
    <i class="fab text-light  fa-whatsapp fa-2x" id="wpIcon"></i>
</button>

<!-- WhatsApp Share Modal -->
<div id="global-whatsappModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" @if (getLanguage($vcard->default_language) == 'Arabic') dir="rtl" @endif>
            <div class="modal-header">
                <h5 class="modal-title">Share via WhatsApp</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="number" id="wpNumber" class="form-control mb-2" placeholder="{{ __('messages.setting.wp_number') }}">
                <input type="text" id="wpReceiver" class="form-control mb-2" placeholder="{{ __('messages.setting.wp_reciever') }}">
                <textarea id="wpMessageInput" class="form-control mb-3" rows="2"
                    placeholder="{{ __('messages.setting.wp_description') }}"></textarea>
                <div class="text-center">
                   <a href="javascript:void(0)" 
                        class="btn btn-success d-flex justify-content-center align-items-center rounded-pill py-1"
                        id="sendWhatsAppBtn"
                        style="color: white;">
                            <i class="fab fa-whatsapp me-2"></i> Send
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>



