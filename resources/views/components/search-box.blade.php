<form class="position-relative w-100" autocomplete="off">
    <div class="input-group input-group-lg">
        <input type="text" id="global-search" class="form-control rounded-start-pill shadow-sm"
               placeholder="Search products, categories...">
        <button class="btn btn-primary rounded-end-pill" type="submit">
            <i class="fas fa-search"></i>
        </button>
    </div>
    <div id="search-results"
         class="dropdown-menu w-100 mt-2 shadow rounded show"
         style="display: none; max-height: 350px; overflow-y: auto; z-index: 1000;">
    </div>
</form>

@push('scripts')
<script>
$(document).ready(function () {
    $('#global-search').on('keyup', function () {
        let query = $(this).val().trim();

        if (query.length < 2) {
            $('#search-results').hide().html('');
            return;
        }

        $.ajax({
            url: "{{ route('search.suggestions') }}",
            type: "GET",
            data: { q: query },
            success: function (res) {
                let html = '';
                if (res.length > 0) {
                    res.forEach(item => {
                        html += `
                            <a href="${item.url}" class="dropdown-item">
                                <div class="fw-semibold">${item.name}</div>
                                <small class="text-muted">${item.type}</small>
                            </a>
                        `;
                    });
                } else {
                    html = '<div class="dropdown-item text-muted">No results found</div>';
                }

                $('#search-results').html(html).show();
            }
        });
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('#global-search, #search-results').length) {
            $('#search-results').hide();
        }
    });
});
</script>
@endpush
