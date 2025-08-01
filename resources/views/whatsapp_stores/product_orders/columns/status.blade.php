@if ($row->status == App\Models\WpOrder::PENDING || $row->status == App\Models\WpOrder::DISPATCHED)
    <div wire:ignore>
        {{ Form::select('status', App\Models\WpOrder::STATUS_ARR, $row->status, ['class' => 'form-control form-select product-order-status', 'data-id' => $row->id]) }}
    </div>
@elseif($row->status == App\Models\WpOrder::DELIVERED)
    <span class="text-success">Delivered</span>
@elseif($row->status == App\Models\WpOrder::CANCELLED)
    <span class="text-danger">Cancelled</span>
@endif
