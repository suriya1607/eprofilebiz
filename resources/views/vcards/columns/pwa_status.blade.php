<label class="form-check form-switch d-flex justify-content-center">
    <input name="pwa_status" data-id="{{$row->id}}" class="form-check-input vcardPwaStatus"
           type="checkbox" value="1" {{ $row->pwa_status == 1 ? 'checked': ''}}>
</label>
