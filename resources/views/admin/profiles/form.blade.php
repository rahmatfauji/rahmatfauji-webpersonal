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
        <label class="form-label">{{ __('Avatar URL') }}</label>
        <input type="url" name="avatar_url" value="{{ old('avatar_url', optional($profile)->avatar_url) }}" class="form-control @error('avatar_url') is-invalid @enderror">
        @error('avatar_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 d-flex gap-2">
        <button class="btn btn-primary">{{ __('Save') }}</button>
        <a href="{{ route('admin.profiles.index') }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a>
    </div>
</form>
