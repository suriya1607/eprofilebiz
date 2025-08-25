<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Database\Eloquent\Builder;
use App\Models\VcardSendersList;

class VcardSenders extends LivewireTableComponent
{
    protected $model = VcardSendersList::class;

    public bool $showButtonOnHeader = false;

    protected $listeners = ['refresh' => '$refresh', 'resetPageTable'];

    public $vcardId;

    public function mount($vcardId)
    {
        $this->vcardId = $vcardId;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setPageName('vcard-senders-table');
        $this->setDefaultSort('created_at', 'desc');
        $this->setColumnSelectStatus(false);
        $this->setQueryStringStatus(false);
        $this->resetPage('vcard-senders-table');
    }

    public function columns(): array
    {
        return [
            Column::make(__('Sender Name'), "senders_name")
                ->sortable()
                ->searchable()
                ->format(fn($value) => $value ?? '-'),

            Column::make(__('Sender Number'), "senders_number")
                ->sortable()
                ->searchable()
                ->format(fn($value) => $value ?? '-'),

            Column::make(__('Sender Message'), "senders_message")
                ->sortable()
                ->searchable()
                ->format(fn($value) => $value ?? '-'),
            Column::make(__('Sent At'), "created_at")
                ->sortable(),
        ];
    }

    public function builder(): Builder
    {
        return VcardSendersList::where('vcard_id', '=', $this->vcardId);
    }

    public function placeholder()
    {
        return view('lazy_loading/without-listing-skelecton');
    }
}
