<div class="modal fade" id="testimonialModal" tabindex="-1" aria-labelledby="testimonialModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="testimonialForm" enctype="multipart/form-data">
      @csrf
      <input type="hidden" id="testimonialId">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="testimonialModalLabel">Add Testimonial</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="testimonialName" class="form-label">Name</label>
            <input type="text" name="name" class="form-control" id="testimonialName" required>
          </div>
          <div class="mb-3">
            <label for="testimonialDesignation" class="form-label">Designation</label>
            <input type="text" name="designation" class="form-control" id="testimonialDesignation">
          </div>
          <div class="mb-3">
            <label for="testimonialMessage" class="form-label">Message</label>
            <textarea name="message" class="form-control" id="testimonialMessage" rows="3" required></textarea>
          </div>
          <div class="mb-3">
            <label for="testimonialImage" class="form-label">Image</label>
            <input type="file" name="image" class="form-control" id="testimonialImage">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" id="saveTestimonialBtn" class="btn btn-success">Save</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>
