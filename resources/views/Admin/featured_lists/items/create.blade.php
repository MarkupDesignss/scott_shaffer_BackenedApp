@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">

    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Add Featured Item</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.featured-list-items.index') }}">Featured Items</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add New</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.featured-list-items.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Items
        </a>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <!-- Main Form Card -->
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-plus-circle me-2"></i>Item Details
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.featured-list-items.store') }}" id="createForm">
                        @csrf

                        <div class="row g-4">
                            <!-- Featured List Selection -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Featured List *</label>
                                <select name="featured_list_id"
                                        id="featuredList"
                                        class="form-select @error('featured_list_id') is-invalid @enderror"
                                        required
                                        onchange="updateAvailablePositions()">
                                    <option value="">Select a featured list</option>
                                    @foreach($featuredLists as $list)
                                        <option value="{{ $list->id }}"
                                            data-size="{{ $list->list_size }}"
                                            data-positions="{{ json_encode($list->items->pluck('position')->toArray()) }}"
                                            {{ old('featured_list_id') == $list->id ? 'selected' : '' }}>
                                            {{ $list->title }}
                                            <span class="text-muted">(Top {{ $list->list_size }})</span>
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">Select which featured list this item belongs to.</div>
                                @error('featured_list_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Catalog Item Selection -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Catalog Item *</label>
                                <select name="catalog_item_id"
                                        class="form-select @error('catalog_item_id') is-invalid @enderror"
                                        id="catalogItem"
                                        required>
                                    <option value="">Select a catalog item</option>
                                    @foreach($catalogItems as $item)
                                        <option value="{{ $item->id }}"
                                            data-category="{{ $item->category->name ?? 'N/A' }}"
                                            data-price="{{ $item->price ?? 'N/A' }}"
                                            data-rating="{{ $item->rating ?? 'N/A' }}"
                                            {{ old('catalog_item_id') == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}
                                            @if($item->category)
                                                <span class="text-muted">({{ $item->category->name }})</span>
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">Search and select from available catalog items.</div>
                                @error('catalog_item_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Position Selection -->
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Position *</label>
                                <div class="input-group">
                                    {{-- <button type="button" class="btn btn-outline-secondary" onclick="changePosition(-1)">
                                        <i class="fas fa-chevron-left"></i>
                                    </button> --}}
                                    <input name="position" type="number"
                                            id="positionSelect" placeholder="0"
                                            class="form-select text-center @error('position') is-invalid @enderror"
                                            required>
                                        <!-- Positions will be dynamically populated -->
                                    {{-- </input> --}}
                                    {{-- <button type="button" class="btn btn-outline-secondary" onclick="changePosition(1)">
                                        <i class="fas fa-chevron-right"></i>
                                    </button> --}}
                                </div>
                                <div class="form-text">Position in the list (1 is highest).</div>
                                @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Preview Section -->

                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top">
                            <div>
                                <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='{{ route('admin.featured-list-items.index') }}'">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </button>
                            </div>
                            <div class="d-flex gap-3">
                                <button type="submit" name="action" value="save" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Save Item
                                </button>
                                {{-- <button type="submit" name="action" value="save_and_add" class="btn btn-success">
                                    <i class="fas fa-plus-circle me-2"></i>Save & Add Another
                                </button> --}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        {{-- <div class="col-lg-4">
            <!-- Selected List Info -->
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Selected List Info
                    </h6>
                </div>
                <div class="card-body">
                    <div id="listInfo" class="text-center text-muted">
                        <i class="fas fa-list fa-2x mb-3"></i>
                        <p>No list selected</p>
                    </div>
                </div>
            </div>

            <!-- Selected Item Info -->
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cube me-2"></i>Selected Item Info
                    </h6>
                </div>
                <div class="card-body">
                    <div id="itemInfo" class="text-center text-muted">
                        <i class="fas fa-cube fa-2x mb-3"></i>
                        <p>No item selected</p>
                    </div>
                </div>
            </div>

            <!-- Tips Card -->
            <div class="card shadow border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-lightbulb me-2"></i>Tips
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <strong>Position Matters:</strong> Position 1 appears at the top
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <strong>List Size:</strong> Position cannot exceed list size
                        </li>
                        <li>
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <strong>Unique Items:</strong> Items should be unique per list
                        </li>
                    </ul>
                </div>
            </div>
        </div> --}}
    </div>

</div>

@push('scripts')
<script>
    // Update available positions based on selected list
    function updateAvailablePositions() {
        const listSelect = document.getElementById('featuredList');
        const positionSelect = document.getElementById('positionSelect');
        const selectedOption = listSelect.options[listSelect.selectedIndex];

        // Clear current options
        positionSelect.innerHTML = '<option value="">Select position</option>';

        if (selectedOption.value) {
            const listSize = parseInt(selectedOption.dataset.size) || 10;
            const takenPositions = JSON.parse(selectedOption.dataset.positions || '[]');

            // Populate positions
            for (let i = 1; i <= listSize; i++) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = `Position ${i}`;

                if (takenPositions.includes(i)) {
                    option.textContent += ' (Taken)';
                    option.disabled = true;
                }

                positionSelect.appendChild(option);
            }

            // Update list info
            updateListInfo(selectedOption);
        } else {
            updateListInfo(null);
        }

        updatePreview();
    }

    // Update list info sidebar
    function updateListInfo(option) {
        const listInfo = document.getElementById('listInfo');

        if (option) {
            listInfo.innerHTML = `
                <div class="text-start">
                    <h6 class="fw-bold">${option.text.split('(')[0].trim()}</h6>
                    <div class="mb-2">
                        <span class="badge bg-primary">Top ${option.dataset.size}</span>
                        <span class="badge bg-secondary ms-1">${option.dataset.positions ? JSON.parse(option.dataset.positions).length : 0} items</span>
                    </div>
                    <p class="text-muted small">
                        <i class="fas fa-hashtag me-1"></i>ID: ${option.value}
                    </p>
                </div>
            `;
        } else {
            listInfo.innerHTML = `
                <i class="fas fa-list fa-2x mb-3"></i>
                <p>No list selected</p>
            `;
        }
    }

    // Update item info sidebar
    document.getElementById('catalogItem').addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        const itemInfo = document.getElementById('itemInfo');

        if (option.value) {
            itemInfo.innerHTML = `
                <div class="text-start">
                    <h6 class="fw-bold">${option.text.split('(')[0].trim()}</h6>
                    <div class="row small text-muted mt-3">
                        <div class="col-6">
                            <strong>Category:</strong><br>
                            ${option.dataset.category}
                        </div>
                        <div class="col-6">
                            <strong>Price:</strong><br>
                            ${option.dataset.price}
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="badge bg-info">Rating: ${option.dataset.rating}/5</span>
                    </div>
                </div>
            `;
        } else {
            itemInfo.innerHTML = `
                <i class="fas fa-cube fa-2x mb-3"></i>
                <p>No item selected</p>
            `;
        }

        updatePreview();
    });

    // Change position with buttons
    function changePosition(delta) {
        const select = document.getElementById('positionSelect');
        if (!select.value) return;

        const currentIndex = Array.from(select.options).findIndex(opt => opt.value === select.value);
        const newIndex = currentIndex + delta;

        if (newIndex >= 1 && newIndex < select.options.length) {
            const newOption = select.options[newIndex];
            if (!newOption.disabled) {
                select.value = newOption.value;
            }
        }
    }

    // Update preview
    function updatePreview() {
        const listSelect = document.getElementById('featuredList');
        const itemSelect = document.getElementById('catalogItem');
        const positionSelect = document.getElementById('positionSelect');
        const preview = document.getElementById('previewContent');

        if (listSelect.value && itemSelect.value && positionSelect.value) {
            const listText = listSelect.options[listSelect.selectedIndex].text.split('(')[0].trim();
            const itemText = itemSelect.options[itemSelect.selectedIndex].text.split('(')[0].trim();

            preview.innerHTML = `
                <div class="text-start">
                    <h6 class="fw-bold mb-3">${listText}</h6>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                             style="width: 36px; height: 36px">
                            ${positionSelect.value}
                        </div>
                        <div>
                            <h6 class="mb-0">${itemText}</h6>
                            <small class="text-muted">Catalog Item</small>
                        </div>
                    </div>
                    <div class="alert alert-info small mb-0">
                        This item will appear at position ${positionSelect.value} in the list.
                    </div>
                </div>
            `;
        } else {
            preview.innerHTML = `
                <i class="fas fa-cube fa-2x mb-3"></i>
                <p>Select a list and item to see preview</p>
            `;
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateAvailablePositions();
        updatePreview();
    });
</script>
@endpush

@endsection
