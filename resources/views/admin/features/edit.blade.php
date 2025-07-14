<div class="modal fade" id="featureModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="featureForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="featureModalLabel">Add Feature</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="featureId" name="id">
                    <div class="mb-3">
                        <label for="featureTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="featureTitle" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="featureIcon" class="form-label">Icon (class or URL)</label>
                        <input type="text" class="form-control" id="featureIcon" name="icon" required>
                    </div>
                    <div class="mb-3">
                        <label for="featureDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="featureDescription" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="saveFeatureBtn" class="btn btn-success">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
