@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1" style="font-size:1.5rem;font-weight:800">Edit Interest</h1>
            <p class="text-muted mb-0">Update interest details and icon</p>
        </div>
        <a href="{{ route('admin.interest.index') }}" class="btn btn-outline-secondary">
            Back to List
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <!-- Preview -->
            <div class="text-center mb-4">
                <div class="interest-preview mb-3">
                    <div class="preview-icon-wrapper d-inline-flex align-items-center justify-content-center rounded-circle bg-light"
                         style="width:80px;height:80px;margin:0 auto;">
                        @if($interest->icon)
                            <img id="imagePreview"
                                 src="{{ asset('storage/'.$interest->icon) }}"
                                 style="width:40px;height:40px;">
                        @else
                            <span id="iconPlaceholder" class="text-muted fs-3">+</span>
                            <img id="imagePreview" style="display:none;width:40px;height:40px;">
                        @endif
                    </div>
                    <h5 class="mt-3 mb-0" id="namePreview">{{ $interest->name }}</h5>
                    <small class="text-muted">Preview</small>
                </div>
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

            <!-- Form -->
            <form action="{{ route('admin.interest.update',$interest) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  onsubmit="return validateForm()">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">Interest Name *</label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name',$interest->name) }}"
                           class="form-control form-control-lg"
                           required>
                </div>

                <!-- Upload Icon -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">Upload Icon</label>
                    <input type="file"
                           name="icon_image"
                           id="iconImage"
                           class="form-control"
                           accept="image/png,image/svg+xml,image/webp">
                    <small class="text-muted">
                        PNG / SVG / WEBP • Max 1MB • 128×128 recommended
                    </small>
                </div>

                <!-- Status -->
                <div class="mb-4 form-check form-switch">
                    <input type="checkbox"
                           name="is_active"
                           value="1"
                           class="form-check-input"
                           {{ $interest->is_active ? 'checked' : '' }}>
                    <label class="form-check-label">Active</label>
                </div>

                <!-- Actions -->
                <div class="d-flex justify-content-end gap-2 border-top pt-4">
                    <a href="{{ route('admin.interest.index') }}"
                       class="btn btn-outline-secondary">Cancel</a>
                    <button class="btn btn-primary">
                        Update Interest
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<style>
.card{border-radius:12px}
.interest-preview{
    padding:2rem;
    background:linear-gradient(135deg,#f8f9fa,#e9ecef);
    border-radius:12px;
    border:2px dashed #dee2e6
}
.preview-icon-wrapper{
    box-shadow:0 4px 12px rgba(0,0,0,.08);
    transition:.3s
}
.preview-icon-wrapper:hover{transform:scale(1.1)}
</style>

<script>
const nameInput = document.getElementById('name');
const namePreview = document.getElementById('namePreview');
const imageInput = document.getElementById('iconImage');
const imagePreview = document.getElementById('imagePreview');
const placeholder = document.getElementById('iconPlaceholder');

nameInput.addEventListener('input',()=>{
    namePreview.textContent = nameInput.value || 'Interest';
});

imageInput.addEventListener('change',e=>{
    const file = e.target.files[0];
    if(!file) return;

    const reader = new FileReader();
    reader.onload = ev=>{
        imagePreview.src = ev.target.result;
        imagePreview.style.display = 'block';
        if(placeholder) placeholder.style.display = 'none';
    };
    reader.readAsDataURL(file);
});

function validateForm(){
    if(!nameInput.value.trim()){
        alert('Interest name is required');
        return false;
    }
    return true;
}
</script>
@endsection