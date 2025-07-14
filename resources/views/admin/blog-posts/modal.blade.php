<div class="modal fade" id="blogModal" tabindex="-1" aria-labelledby="blogModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="blogForm">
      <input type="hidden" name="id" id="blogId">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="blogModalLabel">Add Blog Post</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body row g-3">
          <div class="col-md-6">
            <label for="title" class="form-label">Title</label>
            <input type="text" id="title" name="title" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label for="author" class="form-label">Author</label>
            <input type="text" id="author" name="author" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label for="date" class="form-label">Date</label>
            <input type="date" id="date" name="date" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label for="status" class="form-label">Status</label>
            <select id="status" name="status" class="form-select" required>
              <option value="">Select Status</option>
              <option value="draft">Draft</option>
              <option value="published">Published</option>
            </select>
          </div>
          <div class="col-12">
            <label for="content" class="form-label">Content</label>
            <textarea id="content" name="content" class="form-control" rows="4" required></textarea>
          </div>
          <div class="col-12">
            <label for="image" class="form-label">Image</label>
            <input type="file" id="image" name="image" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" id="saveBlogBtn">Save Blog Post</button>
        </div>
      </div>
    </form>
  </div>
</div>
