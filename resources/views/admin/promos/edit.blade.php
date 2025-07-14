<!-- Promo Modal -->
<div class="modal fade" id="promoModal" tabindex="-1" aria-labelledby="promoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="promoForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="promoModalLabel">Add Promo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="promoId" name="id">

                    <div class="mb-3">
                        <label for="promoTitle" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="promoTitle" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="promoImage" class="form-label">Image</label>
                        <input type="file" name="image" id="promoImage" class="form-control">
                        <div class="mt-2" id="promoImagePreview"></div>
                    </div>

                    <div class="mb-3">
                        <label for="promoButtonText" class="form-label">Button Text</label>
                        <input type="text" name="button_text" id="promoButtonText" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="promoButtonLink" class="form-label">Button Link</label>
                        <input type="url" name="button_link" id="promoButtonLink" class="form-control">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" id="savePromoBtn" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Save
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
