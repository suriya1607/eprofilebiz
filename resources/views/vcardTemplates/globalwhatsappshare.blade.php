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
                <input type="text" id="wpReceiver" class="form-control mb-2 py-3" placeholder="{{ __('messages.setting.wp_reciever') }}">
                <input type="text" id="wpNumber" class="form-control mb-2" placeholder="{{ __('messages.setting.wp_number') }}" value ="+91">
            <div class="position-relative">
                    <textarea id="wpMessageInput" class="form-control"
                        rows="3" placeholder="Type message..."></textarea>
                        <small class="text-muted">
                Press <b>Enter</b> to save new chat • Click suggestion to insert <a href="#" id="manageChatsLink">Manage chats</a>
                </small>

                    <div id="quickSuggestBox" class="quick-suggest-box"></div>
            </div>




                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="" id="saveContactCheckbox">
                    <label class="form-check-label" for="saveContactCheckbox">
                        Save this contact
                    </label>
                </div>
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
<style>
 .quick-suggest-box{
  position:absolute;
  left:0;
  right:0;
  top:100%;
  background:#fff;
  border:1px solid #ddd;
  border-radius:10px;
  max-height:180px;
  overflow-y:auto;
  display:none;
  z-index:999;
}

.quick-item{
  padding:8px 12px;
  cursor:pointer;
  font-size:13px;
}

.quick-item:hover{
  background:#f1f1f1;
}
.quick-item{
  padding:8px 12px;
  cursor:pointer;
  font-size:13px;
  display:flex;
  justify-content:space-between;
  align-items:center;
}

.quick-item span mark{
  background:#ffe58f;
  padding:0;
}

.remove-btn{
  color:#999;
  font-size:12px;
  cursor:pointer;
}

.remove-btn:hover{color:red;}

</style>



