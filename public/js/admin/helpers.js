export function showAlert(containerSelector, message, type = 'success') {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    $(containerSelector).html(alertHtml);
    setTimeout(() => {
        $(containerSelector).find('.alert').alert('close');
    }, 4000);
}

export function setupFilePreview(triggerSelector, modalSelector, imageSelector) {
    $(document).on('click', triggerSelector, function () {
        const src = $(this).data('src');
        $(imageSelector).attr('src', src);
        new bootstrap.Modal($(modalSelector)).show();
    });
}
