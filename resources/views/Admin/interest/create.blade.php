@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold">Create New Interest</h1>
            <p class="text-muted mb-0">Add a new interest category for users</p>
        </div>
        <a href="{{ route('admin.interest.index') }}" class="btn btn-outline-secondary">
            ← Back
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">

            <!-- Live Preview -->
            <div class="text-center mb-5">
                <div class="preview-icon-wrapper rounded-circle bg-light mx-auto d-flex align-items-center justify-content-center"
                    style="width:90px;height:90px">

                    <img id="imagePreview" style="display:none;width:48px;height:48px;">
                    <span id="iconPlaceholder" class="fs-3 text-muted">+</span>
                </div>

                <h4 class="mt-3" id="namePreview">New Interest</h4>
                <small class="text-muted">Live Preview</small>
            </div>

            <!-- Errors -->
            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- FORM -->
            <form action="{{ route('admin.interest.store') }}" method="POST" enctype="multipart/form-data"
                onsubmit="return validateForm()">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <label class="fw-semibold">Interest Name *</label>
                    <input type="text" name="name" id="name" class="form-control form-control-lg" maxlength="50"
                        required>
                    <small class="text-muted">
                        <span id="charCount">0</span>/50
                    </small>
                </div>

                <!-- Upload Icon -->
                <div class="mb-4">
                    <label class="fw-semibold">Upload Icon *</label>
                    <input type="file" name="icon_image" id="iconImage" class="form-control"
                        accept="image/png,image/svg+xml,image/webp" required>
                    <small class="text-muted">
                        PNG / SVG / WEBP • Max 1MB • 128×128 recommended
                    </small>
                </div>

                <!-- Status -->
                <div class="mb-4 form-check form-switch">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" checked>
                    <label class="form-check-label">Active</label>
                </div>

                <!-- Actions -->
                <div class="text-end">
                    <a href="{{ route('admin.interest.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">
                        Create Interest
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- Styles -->
<style>
.preview-icon-wrapper {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 rgba(13, 110, 253, 0);
    }

    50% {
        box-shadow: 0 0 20px rgba(13, 110, 253, .3);
    }

    100% {
        box-shadow: 0 0 0 rgba(13, 110, 253, 0);
    }
}
</style>

<!-- Scripts -->
<script>
const nameInput = document.getElementById('name');
const namePreview = document.getElementById('namePreview');
const imagePreview = document.getElementById('imagePreview');
const placeholder = document.getElementById('iconPlaceholder');
const charCount = document.getElementById('charCount');

nameInput.addEventListener('input', () => {
    namePreview.textContent = nameInput.value || 'New Interest';
    charCount.textContent = nameInput.value.length;
});

document.getElementById('iconImage').addEventListener('change', e => {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = ev => {
        imagePreview.src = ev.target.result;
        imagePreview.style.display = 'block';
        placeholder.style.display = 'none';
    };
    reader.readAsDataURL(file);
});

function validateForm() {
    if (!nameInput.value.trim()) {
        alert('Interest name is required');
        return false;
    }
    return true;
}
</script>
@endsection