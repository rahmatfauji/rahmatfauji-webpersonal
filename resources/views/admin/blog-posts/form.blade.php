<form action="{{ $action }}" method="POST" class="row g-3">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="col-md-8">
        <label class="form-label">{{ __('Title') }}</label>
        <input type="text" id="blog-title" name="title" value="{{ old('title', optional($post)->title) }}" class="form-control @error('title') is-invalid @enderror" required>
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">{{ __('Slug') }}</label>
        <input type="text" id="blog-slug" name="slug" value="{{ old('slug', optional($post)->slug) }}" class="form-control @error('slug') is-invalid @enderror" placeholder="{{ __('optional-auto') }}" pattern="[a-z0-9-]+">
        <div class="form-text">{{ __('Use lowercase letters, numbers, and hyphens only.') }}</div>
        @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label">{{ __('Excerpt') }}</label>
        <textarea name="excerpt" rows="2" class="form-control @error('excerpt') is-invalid @enderror">{{ old('excerpt', optional($post)->excerpt) }}</textarea>
        @error('excerpt')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label">{{ __('Content') }}</label>
        <textarea id="content-editor" name="content" rows="6" class="form-control @error('content') is-invalid @enderror" required>{{ old('content', optional($post)->content) }}</textarea>
        @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">{{ __('Featured Image URL') }}</label>
        <input type="url" name="featured_image" value="{{ old('featured_image', optional($post)->featured_image) }}" class="form-control @error('featured_image') is-invalid @enderror">
        @error('featured_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">{{ __('Publish Date') }}</label>
        <input type="datetime-local" name="published_at" value="{{ old('published_at', optional(optional($post)->published_at)->format('Y-m-d\\TH:i')) }}" class="form-control @error('published_at') is-invalid @enderror">
        @error('published_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3 d-flex align-items-end">
        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="is_published" value="1" {{ old('is_published', optional($post)->is_published ?? true) ? 'checked' : '' }}>
            <label class="form-check-label">{{ __('Publish') }}</label>
        </div>
    </div>

    <div class="col-12 d-flex gap-2">
        <button class="btn btn-primary">{{ __('Save') }}</button>
        <a href="{{ route('admin.blog-posts.index') }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a>
    </div>
</form>

<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    const titleInput = document.getElementById('blog-title');
    const slugInput = document.getElementById('blog-slug');
    const csrfToken = document.querySelector('input[name="_token"]').value;

    const sanitizeSlug = (value) => value
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-');

    titleInput.addEventListener('input', () => {
        if (!slugInput.dataset.userEdited || slugInput.value.trim() === '') {
            slugInput.value = sanitizeSlug(titleInput.value);
        }
    });

    slugInput.addEventListener('input', () => {
        slugInput.dataset.userEdited = '1';
        slugInput.value = sanitizeSlug(slugInput.value);
    });

    tinymce.init({
        selector: '#content-editor',
        menubar: 'file edit view insert format tools table help',
        plugins: 'image media link lists table code codesample fullscreen preview autoresize',
        toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image media codesample | code preview fullscreen',
        height: 520,
        object_resizing: true,
        image_caption: true,
        image_dimensions: true,
        image_advtab: true,
        automatic_uploads: true,
        images_upload_handler: (blobInfo, progress) => new Promise((resolve, reject) => {
            const formData = new FormData();
            formData.append('image', blobInfo.blob(), blobInfo.filename());

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route('admin.blog-posts.upload-image') }}');
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
            xhr.setRequestHeader('Accept', 'application/json');

            xhr.upload.onprogress = (event) => {
                if (event.lengthComputable) {
                    progress((event.loaded / event.total) * 100);
                }
            };

            xhr.onload = () => {
                if (xhr.status < 200 || xhr.status >= 300) {
                    reject('HTTP Error: ' + xhr.status);
                    return;
                }

                const json = JSON.parse(xhr.responseText);
                if (!json.location) {
                    reject('Invalid upload response');
                    return;
                }

                resolve(json.location);
            };

            xhr.onerror = () => reject('{{ __('Image upload failed.') }}');
            xhr.send(formData);
        }),
        media_live_embeds: true,
        media_alt_source: false,
        media_poster: false,
        extended_valid_elements: 'iframe[src|frameborder|style|scrolling|class|width|height|name|align|allowfullscreen|allow|title]',
        codesample_languages: [
            { text: 'HTML/XML', value: 'markup' },
            { text: 'CSS', value: 'css' },
            { text: 'JavaScript', value: 'javascript' },
            { text: 'PHP', value: 'php' },
            { text: 'JSON', value: 'json' },
            { text: 'SQL', value: 'sql' },
            { text: 'Bash', value: 'bash' }
        ]
    });
</script>
