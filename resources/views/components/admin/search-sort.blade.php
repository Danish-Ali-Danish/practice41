@props([
    'searchId' => 'searchInput',
    'sortId' => 'sortSelect',
    'modalId' => 'defaultModal',
    'addBtnId' => 'addButton',
    'addLabel' => 'Add New',
    'placeholder' => 'Search...'
])
    



<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <input type="text" id="{{ $searchId }}" class="form-control" placeholder="{{ $placeholder }}">
    </div>
    <div>
        <label for="{{ $sortId }}" class="form-label me-2 fw-bold">Sort by:</label>
        <select id="{{ $sortId }}" class="form-select w-auto d-inline-block">
            <option value="newest">Newest First</option>
            <option value="oldest">Oldest First</option>
            <option value="az">Name A–Z</option>
            <option value="za">Name Z–A</option>
        </select>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#{{ $modalId }}" id="{{ $addBtnId }}">
        <i class="fas fa-plus-circle"></i> {{ $addLabel }}
    </button>
</div>
