<div class="modal fade" data-backdrop="static"
     data-keyboard="false" id="deleteModal" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Are you sure?</h5>
                <button type="button" class="close" wire:click="closeConfirmDelete">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer d-block text-center">
                <button type="button" class="btn btn-outline-secondary"
                        wire:click="closeConfirmDelete">Cancel</button>
                <button type="button" class="btn btn-outline-danger"
                        wire:click="destroy" wire:loading.attr="disabled">Delete</button>
            </div>
        </div>
    </div>
</div>
