<?php

namespace App\Livewire;

use App\Models\Vcard;
use Livewire\Component;
use App\Models\CustomDomain;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class VcardLists extends Component
{
    public $search;
    protected $listeners = ['refresh' => '$refresh', 'resetPageTable', 'deleteVcard'];
    protected $queryString = ['search'];

    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public function placeholder()
    {
        return view('lazy_loading.user_vcards');
    }

    public function render()
    {
        $vcards = Vcard::with(['tenant.user', 'template'])
            ->where('name', 'like', '%' . $this->search . '%')
            ->where('tenant_id', getLogInTenantId())
            ->orderBy('created_at', 'desc')
            ->paginate(9);

            $customDomain = CustomDomain::where('user_id', Auth::id())->first();
            $isCustomDomainUse = $customDomain ? $customDomain->is_use_vcard : false;

        return view('livewire.vcard-lists', compact('vcards','isCustomDomainUse','customDomain'));
    }
}
