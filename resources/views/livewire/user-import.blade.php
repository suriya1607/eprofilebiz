<div>
    <!-- Button trigger modal -->
    <button class="btn btn-primary" wire:click="$set('showImportModal', true)">Import Users</button>

    <!-- Modal -->
    @if ($showImportModal)
    <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5); z-index: 1050;" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Import Users</h5>
                        <button type="button" class="btn-close" aria-label="Close" wire:click="$set('showImportModal', false)"></button>
                    </div>

                    <div class="modal-body">
                        <a href="{{ route('users.samplecsv') }}" class="btn btn-link">Download Sample CSV</a>

                        <div class="form-group mt-3">
                            <input type="file" name="file" accept=".csv" class="form-control" required>
                            @error('file') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success mt-2">{{ session('success') }}</div>
                        @endif
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Import</button>
                        <button type="button" class="btn btn-secondary" wire:click="$set('showImportModal', false)">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
