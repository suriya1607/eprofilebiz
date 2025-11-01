<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Repositories\UserRepository;

class UserImport extends Component
{
    use WithFileUploads;

    public $file;
    public $showImportModal = false;

    protected $userRepo;

    // Inject UserRepository here
        public function mount(UserRepository $userRepository)
    {
        $this->userRepo = $userRepository;
    }

    public function render()
    {
        return view('livewire.user-import');
    }
}
