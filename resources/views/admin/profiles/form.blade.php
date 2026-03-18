<form action="{{ $action }}" method="POST" class="row g-3">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="col-md-6">
        <label class="form-label">{{ __('Full Name') }}</label>
        <input type="text" name="full_name" value="{{ old('full_name', optional($profile)->full_name) }}" class="form-control @error('full_name') is-invalid @enderror" required>
        @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">{{ __('Title') }}</label>
        <input type="text" name="title" value="{{ old('title', optional($profile)->title) }}" class="form-control @error('title') is-invalid @enderror" required>
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label">{{ __('Bio') }}</label>
        <textarea name="bio" rows="4" class="form-control @error('bio') is-invalid @enderror" required>{{ old('bio', optional($profile)->bio) }}</textarea>
        @error('bio')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">{{ __('Email') }}</label>
        <input type="email" name="email" value="{{ old('email', optional($profile)->email) }}" class="form-control @error('email') is-invalid @enderror">
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">{{ __('Phone Number') }}</label>
        <input type="text" name="phone" value="{{ old('phone', optional($profile)->phone) }}" class="form-control @error('phone') is-invalid @enderror">
        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">{{ __('Location') }}</label>
        <input type="text" name="location" value="{{ old('location', optional($profile)->location) }}" class="form-control @error('location') is-invalid @enderror">
        @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">{{ __('Avatar') }}</label>
        <div class="d-flex gap-2 align-items-start">
            <div class="flex-grow-1">
                <input type="file" id="avatar-input" accept="image/*" class="form-control @error('avatar_url') is-invalid @enderror">
                @error('avatar_url')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                <input type="hidden" id="avatar-value" name="avatar_url" value="{{ old('avatar_url', optional($profile)->avatar_url) }}">
                <div class="form-text mt-2">{{ __('Supported formats: JPG, PNG, WebP (max 5MB)') }}</div>
            </div>
            @if(optional($profile)->avatar_url)
            <img id="avatar-preview" src="{{ optional($profile)->avatar_url }}" alt="Avatar" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 2px solid #dee2e6;">
            @else
            <div id="avatar-placeholder" style="width: 100px; height: 100px; background: #e9ecef; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid #dee2e6;" class="text-muted small">{{ __('No avatar') }}</div>
            @endif
        </div>
    </div>

    <div class="col-12 d-flex gap-2">
        <button class="btn btn-primary">{{ __('Save') }}</button>
        <a href="{{ route('admin.profiles.index') }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a>
    </div>
</form>

<script>
    const csrfToken = document.querySelector('input[name="_token"]').value;
    const avatarInput = document.getElementById('avatar-input');
    const avatarValue = document.getElementById('avatar-value');
    const avatarPreview = document.getElementById('avatar-preview');
    const avatarPlaceholder = document.getElementById('avatar-placeholder');

    // Handle avatar upload
    avatarInput.addEventListener('change', () => {
        const file = avatarInput.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('image', file);
        formData.append('type', 'profile');

        const loadingText = document.createElement('div');
        loadingText.style.cssText = 'width: 100px; height: 100px; background: #e9ecef; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid #dee2e6;';
        loadingText.className = 'text-muted small';
        loadingText.textContent = '{{ __('Uploading...') }}';
        if (avatarPlaceholder) avatarPlaceholder.replaceWith(loadingText);
        if (avatarPreview) avatarPreview.parentNode.removeChild(avatarPreview);

        fetch('{{ route('admin.upload-image') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                avatarValue.value = data.url;
                const img = document.createElement('img');
                img.id = 'avatar-preview';
                img.src = data.url;
                img.alt = 'Avatar';
                img.style.cssText = 'width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 2px solid #dee2e6;';
                loadingText.replaceWith(img);
            } else {
                alert('{{ __('Image upload failed.') }}');
                loadingText.remove();
                if (!avatarPlaceholder) {
                    const placeholder = document.createElement('div');
                    placeholder.id = 'avatar-placeholder';
                    placeholder.style.cssText = 'width: 100px; height: 100px; background: #e9ecef; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid #dee2e6;';
                    placeholder.className = 'text-muted small';
                    placeholder.textContent = '{{ __('No avatar') }}';
                    loadingText.parentNode.appendChild(placeholder);
                }
            }
        })
        .catch(() => alert('{{ __('Image upload failed.') }}'));
    });
</script>
</form>
