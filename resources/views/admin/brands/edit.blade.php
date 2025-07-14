<!-- Brand Add/Edit Modal -->
<div class="modal fade" id="brandModal" tabindex="-1" aria-labelledby="brandModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="brandModalLabel">Add New Brand</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="brandForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="brandId" name="id">

                    <div class="mb-3">
                        <label for="brandName" class="form-label">Brand Name</label>
                        <input type="text" class="form-control" id="brandName" name="name" placeholder="Enter brand name">
                    </div>

                    <div class="mb-3">
                        <label for="brandCategory" class="form-label">Category</label>
                        <select id="brandCategory" name="category_id" class="form-select">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="brandFile" class="form-label">Brand Image</label>
                        <input type="file" class="form-control" id="brandFile" name="file" accept="image/*">
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="saveBrandBtn">Save</button>
            </div>
        </div>
    </div>
</div>
