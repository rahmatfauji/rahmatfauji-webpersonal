<form action="{{ $action }}" method="POST" class="row g-3">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif
    @php
        $uploadToken = old('upload_token', (string) \Illuminate\Support\Str::uuid());
    @endphp
    <input type="hidden" id="upload-token" name="upload_token" value="{{ $uploadToken }}">

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

    <div class="col-md-6">
        <label class="form-label">{{ __('Category') }}</label>
        <input type="text" name="category" value="{{ old('category', optional($post)->category) }}" class="form-control @error('category') is-invalid @enderror" placeholder="{{ __('Data Analytics') }}">
        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    @php
        $tagsValue = old('tags');

        if ($tagsValue === null) {
            $tagsValue = implode(', ', optional($post)->tags ?? []);
        }
    @endphp

    <div class="col-md-6">
        <label class="form-label">{{ __('Tags') }}</label>
        <input type="text" name="tags" value="{{ $tagsValue }}" class="form-control @error('tags') is-invalid @enderror" placeholder="{{ __('Power BI, Dashboard, SQL') }}">
        <div class="form-text">{{ __('Separate tags with commas.') }}</div>
        @error('tags')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label">{{ __('Excerpt') }}</label>
        <textarea name="excerpt" rows="2" class="form-control @error('excerpt') is-invalid @enderror">{{ old('excerpt', optional($post)->excerpt) }}</textarea>
        @error('excerpt')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label">{{ __('Content') }}</label>
        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.0/dist/quill.snow.css" rel="stylesheet">
        <div id="content-editor" style="height: 400px;" class="@error('content') border-danger @enderror"></div>
        <textarea id="content-value" name="content" style="display:none;">{{ old('content', optional($post)->content) }}</textarea>
        @error('content')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">{{ __('Featured Image') }}</label>
        <div class="d-flex gap-2 align-items-start">
            <div class="flex-grow-1">
                <input type="file" id="featured-image-input" accept="image/*" class="form-control @error('featured_image') is-invalid @enderror">
                @error('featured_image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                <input type="hidden" id="featured-image-value" name="featured_image" value="{{ old('featured_image', optional($post)->featured_image) }}">
                <div class="form-text mt-2">{{ __('Supported formats: JPG, PNG, WebP (max 5MB)') }}</div>
            </div>
            @if(optional($post)->featured_image)
            <img id="featured-image-preview" src="{{ optional($post)->featured_image }}" alt="Featured" style="width: 100px; height: 80px; object-fit: cover; border-radius: 4px;">
            @else
            <div id="featured-image-placeholder" style="width: 100px; height: 80px; background: #e9ecef; border-radius: 4px; display: flex; align-items: center; justify-content: center;" class="text-muted small">{{ __('No image') }}</div>
            @endif
        </div>
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

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.0/dist/quill.js"></script>
<script>
    const uploadImageUrl = @json(route('admin.upload-image'));
    const messageContentEmpty = @json(__('Content cannot be empty.'));
    const messageImageUploadFailed = @json(__('Image upload failed.'));
    const messageUploading = @json(__('Uploading...'));
    const messageNoImage = @json(__('No image'));

    // Wait for DOM to be fully ready
    document.addEventListener('DOMContentLoaded', function() {
        const titleInput = document.getElementById('blog-title');
        const slugInput = document.getElementById('blog-slug');
        const csrfToken = document.querySelector('input[name="_token"]').value;
        const uploadToken = document.getElementById('upload-token').value;
        const contentValue = document.getElementById('content-value');
        const featuredImageInput = document.getElementById('featured-image-input');
        const featuredImageValue = document.getElementById('featured-image-value');
        const featuredImagePreview = document.getElementById('featured-image-preview');
        const featuredImagePlaceholder = document.getElementById('featured-image-placeholder');

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

        // Initialize Quill editor
        const quill = new Quill('#content-editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'script': 'sub'}, { 'script': 'super' }],
                    ['blockquote', 'code-block'],
                    ['link', 'image'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'align': [] }],
                    ['clean']
                ]
            }
        });

        // Set initial content
        if (contentValue.value.trim()) {
            quill.root.innerHTML = contentValue.value;
        }

        // Sync Quill content to textarea before submit
        const forms = document.querySelectorAll('form');
        const form = forms[forms.length - 1]; // Get the last form (our blog form)
        
        form.addEventListener('submit', function(e) {
            // Get content from Quill editor
            const content = quill.root.innerHTML.trim();
            
            // Check if content is empty (various empty HTML patterns)
            if (!content || 
                content === '<p><br></p>' || 
                content === '<p></p>' ||
                content === '<p>&nbsp;</p>' ||
                content === '<br>' ||
                content === '') {
                e.preventDefault();
                alert(messageContentEmpty);
                return false;
            }
            
            // Sync content to hidden textarea BEFORE form submit
            contentValue.value = content;
        });

        // Handle image uploads in Quill
        const imageHandler = () => {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.click();

            input.onchange = () => {
                const file = input.files[0];
                if (!file) return;

                const formData = new FormData();
                formData.append('image', file);
                formData.append('type', 'blog');
                formData.append('temp_token', uploadToken);

                fetch(uploadImageUrl, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const range = quill.getSelection();
                        quill.insertEmbed(range.index, 'image', data.url);
                    } else {
                        alert(messageImageUploadFailed);
                    }
                })
                .catch(() => alert(messageImageUploadFailed));
            };
        };

        // Update toolbar image button handler
        quill.getModule('toolbar').addHandler('image', imageHandler);

        // Handle featured image upload
        featuredImageInput.addEventListener('change', () => {
            const file = featuredImageInput.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('image', file);
            formData.append('type', 'blog');
            formData.append('temp_token', uploadToken);

            const loadingText = document.createElement('div');
            loadingText.className = 'text-muted small';
            loadingText.textContent = messageUploading;
            if (featuredImagePlaceholder) featuredImagePlaceholder.replaceWith(loadingText);

            fetch(uploadImageUrl, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    featuredImageValue.value = data.url;
                    const img = document.createElement('img');
                    img.id = 'featured-image-preview';
                    img.src = data.url;
                    img.alt = 'Featured';
                    img.style.cssText = 'width: 100px; height: 80px; object-fit: cover; border-radius: 4px;';
                    loadingText.replaceWith(img);
                } else {
                    alert(messageImageUploadFailed);
                    loadingText.remove();
                    if (!featuredImagePlaceholder) {
                        const placeholder = document.createElement('div');
                        placeholder.id = 'featured-image-placeholder';
                        placeholder.style.cssText = 'width: 100px; height: 80px; background: #e9ecef; border-radius: 4px; display: flex; align-items: center; justify-content: center;';
                        placeholder.className = 'text-muted small';
                        placeholder.textContent = messageNoImage;
                        loadingText.parentNode.appendChild(placeholder);
                    }
                }
            })
            .catch(() => alert(messageImageUploadFailed));
        });
    });
</script>
