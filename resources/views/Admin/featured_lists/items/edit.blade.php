@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">

    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Edit Featured Item</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.featured-list-items.index') }}">Featured Items</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit #{{ $item->id }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.featured-list-items.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <i class="fas fa-trash me-2"></i>Delete
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <!-- Main Form Card -->
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-edit me-2"></i>Edit Item Details
                    </h6>
                    <span class="badge bg-light text-dark">
                        <i class="fas fa-hashtag me-1"></i>ID: {{ $item->id }}
                    </span>
                </div>
                <div class="card-body">
                    <form method="POST"
                          action="{{ route('admin.featured-list-items.update', $item) }}"
                          id="editForm">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <!-- Featured List -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Featured List *</label>
                                <select name="featured_list_id"
                                        id="featuredList"
                                        class="form-select @error('featured_list_id') is-invalid @enderror"
                                        required
                                        onchange="updateAvailablePositions()">
                                    @foreach($featuredLists as $list)
                                        <option value="{{ $list->id }}"
                                            data-size="{{ $list->list_size }}"
                                            data-positions="{{ json_encode($list->items->where('id', '!=', $item->id)->pluck('position')->toArray()) }}"
                                            {{ old('featured_list_id', $item->featured_list_id) == $list->id ? 'selected' : '' }}>
                                            {{ $list->title }}
                                            <span class="text-muted">(Top {{ $list->list_size }})</span>
                                        </option>
                                    @endforeach
                                </select>
                                @error('featured_list_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Catalog Item -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Catalog Item *</label>
                                <select name="catalog_item_id"
                                        class="form-select @error('catalog_item_id') is-invalid @enderror"
                                        id="catalogItem"
                                        required>
                                    @foreach($catalogItems as $catalog)
                                        <option value="{{ $catalog->id }}"
                                            data-category="{{ $catalog->category->name ?? 'N/A' }}"
                                            data-price="{{ $catalog->price ?? 'N/A' }}"
                                            data-rating="{{ $catalog->rating ?? 'N/A' }}"
                                            {{ old('catalog_item_id', $item->catalog_item_id) == $catalog->id ? 'selected' : '' }}>
                                            {{ $catalog->name }}
                                            @if($catalog->category)
                                                <span class="text-muted">({{ $catalog->category->name }})</span>
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('catalog_item_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Position -->
                           <div class="col-md-4">
                                <label class="form-label fw-semibold">Position *</label>

                                <input type="number"
                                    name="position"
                                    id="positionInput"
                                    class="form-control text-center @error('position') is-invalid @enderror"
                                    min="1"
                                    required>

                                <small class="text-muted d-block mt-1" id="positionHelp"></small>

                                @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Current Status -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>Current Position:</strong>
                                            <span class="badge bg-primary ms-2">{{ $item->position }}</span>
                                        </div>
                                        <div>
                                            <strong>List:</strong>
                                            <span class="ms-2">{{ $item->featuredList->title ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top">
                            <div>
                                <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='{{ route('admin.featured-list-items.index') }}'">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </button>
                            </div>
                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Save Changes
                                </button>
                                {{-- <button type="button" class="btn btn-success" onclick="previewChanges()">
                                    <i class="fas fa-eye me-2"></i>Preview Changes
                                </button> --}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>Delete Featured Item
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this featured item?</p>
                <div class="alert alert-warning">
                    <strong>Item:</strong> {{ $item->catalogItem->name ?? 'N/A' }}<br>
                    <strong>List:</strong> {{ $item->featuredList->title ?? 'N/A' }}<br>
                    <strong>Position:</strong> {{ $item->position }}
                </div>
                <p class="text-muted">This will remove the item from the list and cannot be undone.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('admin.featured-list-items.destroy', $item) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Item</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Update available positions based on selected list
    function updateAvailablePositions() {
        const listSelect = document.getElementById('featuredList');
        const positionSelect = document.getElementById('positionSelect');
        const selectedOption = listSelect.options[listSelect.selectedIndex];
        const currentPosition = {{ $item->position }};

        // Clear current options
        positionSelect.innerHTML = '';

        if (selectedOption.value) {
            const listSize = parseInt(selectedOption.dataset.size) || 10;
            const takenPositions = JSON.parse(selectedOption.dataset.positions || '[]');

            // Populate positions
            for (let i = 1; i <= listSize; i++) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = `Position ${i}`;

                if (takenPositions.includes(i) && i !== currentPosition) {
                    option.textContent += ' (Taken)';
                    option.disabled = true;
                }

                if (i == currentPosition) {
                    option.selected = true;
                }

                positionSelect.appendChild(option);
            }
        }
    }

    // Change position with buttons
    function changePosition(delta) {
        const select = document.getElementById('positionSelect');
        if (!select.value) return;

        const currentIndex = Array.from(select.options).findIndex(opt => opt.value === select.value);
        const newIndex = currentIndex + delta;

        if (newIndex >= 0 && newIndex < select.options.length) {
            const newOption = select.options[newIndex];
            if (!newOption.disabled) {
                select.value = newOption.value;
            }
        }
    }

    // Preview changes
    function previewChanges() {
        const listSelect = document.getElementById('featuredList');
        const itemSelect = document.getElementById('catalogItem');
        const positionSelect = document.getElementById('positionSelect');

        const listText = listSelect.options[listSelect.selectedIndex].text.split('(')[0].trim();
        const itemText = itemSelect.options[itemSelect.selectedIndex].text.split('(')[0].trim();

        Swal.fire({
            title: 'Preview Changes',
            html: `
                <div class="text-start">
                    <div class="alert alert-info">
                        <strong>Summary of Changes:</strong>
                    </div>
                    <table class="table table-sm">
                        <tr>
                            <th>Field</th>
                            <th>Current</th>
                            <th>New</th>
                        </tr>
                        <tr>
                            <td>List</td>
                            <td>{{ $item->featuredList->title ?? 'N/A' }}</td>
                            <td>${listText}</td>
                        </tr>
                        <tr>
                            <td>Item</td>
                            <td>{{ $item->catalogItem->name ?? 'N/A' }}</td>
                            <td>${itemText}</td>
                        </tr>
                        <tr>
                            <td>Position</td>
                            <td>{{ $item->position }}</td>
                            <td>${positionSelect.value}</td>
                        </tr>
                    </table>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Save Changes',
            cancelButtonText: 'Continue Editing',
            icon: 'info'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('editForm').submit();
            }
        });
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateAvailablePositions();
    });

    const CURRENT_POSITION = {{ $item->position }};
    const CURRENT_LIST_ID = {{ $item->featured_list_id }};

    function updateAvailablePositions() {
        const listSelect = document.getElementById('featuredList');
        const positionInput = document.getElementById('positionInput');
        const helpText = document.getElementById('positionHelp');

        const selectedOption = listSelect.options[listSelect.selectedIndex];
        const maxSize = parseInt(selectedOption.dataset.size);
        const usedPositions = JSON.parse(selectedOption.dataset.positions || '[]');

        positionInput.max = maxSize;

        // Default value handling
        if (
            parseInt(listSelect.value) === CURRENT_LIST_ID &&
            (!positionInput.value || positionInput.value == CURRENT_POSITION)
        ) {
            positionInput.value = CURRENT_POSITION;
        }

        // helpText.innerHTML = `
        //     Allowed: 1 – ${maxSize}<br>
        //     Occupied: ${usedPositions.length ? usedPositions.join(', ') : 'None'}
        // `;

        positionInput.oninput = function () {
            const value = parseInt(this.value);

            if (!value) return;

            if (value < 1 || value > maxSize) {
                this.setCustomValidity(`Position must be between 1 and ${maxSize}`);
            } else if (
                usedPositions.includes(value) &&
                !(value === CURRENT_POSITION && parseInt(listSelect.value) === CURRENT_LIST_ID)
            ) {
                this.setCustomValidity(`Position ${value} is already occupied`);
            } else {
                this.setCustomValidity('');
            }
        };
    }

    document.addEventListener('DOMContentLoaded', function () {
        updateAvailablePositions();
        document.getElementById('featuredList')
            .addEventListener('change', updateAvailablePositions);
    });


</script>

@endsection
