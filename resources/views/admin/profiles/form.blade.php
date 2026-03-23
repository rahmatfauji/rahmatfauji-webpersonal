<form action="{{ $action }}" method="POST" class="row g-3">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif
    @php($uploadToken = old('upload_token', (string) \Illuminate\Support\Str::uuid()))
    @php($chartItems = old('expertise_chart', optional($profile)->expertise_chart ?? [
        ['label' => 'Data Modeling', 'value' => 28, 'color' => '#0F4C81'],
        ['label' => 'Power BI', 'value' => 22, 'color' => '#2A72D6'],
        ['label' => 'Dashboard Design', 'value' => 16, 'color' => '#35A7FF'],
        ['label' => 'Business Insight', 'value' => 14, 'color' => '#3AAFA9'],
        ['label' => 'Automation', 'value' => 12, 'color' => '#F4A261'],
        ['label' => 'Governance', 'value' => 8, 'color' => '#E76F51'],
    ]))
    <input type="hidden" id="upload-token" name="upload_token" value="{{ $uploadToken }}">

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
        <label class="form-label">{{ __('LinkedIn URL') }}</label>
        <input type="url" name="linkedin_url" value="{{ old('linkedin_url', optional($profile)->linkedin_url) }}" class="form-control @error('linkedin_url') is-invalid @enderror" placeholder="https://www.linkedin.com/in/username">
        @error('linkedin_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">{{ __('GitHub URL') }}</label>
        <input type="url" name="github_url" value="{{ old('github_url', optional($profile)->github_url) }}" class="form-control @error('github_url') is-invalid @enderror" placeholder="https://github.com/username">
        @error('github_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
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

    <div class="col-12 mt-2">
        <hr>
        <h3 class="section-title h5 mb-3">{{ __('Expertise Chart Management') }}</h3>
        <p class="text-muted small mb-3">{{ __('Set between 3 and 8 expertise categories. Total percentage must equal 100.') }}</p>
    </div>

    <div class="col-12">
        @error('expertise_chart')
            <div class="alert alert-danger py-2">{{ $message }}</div>
        @enderror
        <div class="row g-4 align-items-start">
            <div class="col-lg-7">
                <div id="chart-items" class="d-grid gap-3"></div>
                <button type="button" id="add-chart-item" class="btn btn-outline-primary btn-sm mt-3">{{ __('Add Chart Item') }}</button>
                <div class="small text-muted mt-2">{{ __('Choose strong labels and distinct colors so the chart remains readable on the public page.') }}</div>
            </div>
            <div class="col-lg-5">
                <div class="soft-panel p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h4 class="section-title h6 mb-0">{{ __('Live Preview') }}</h4>
                        <span id="chart-total-indicator" class="badge text-bg-secondary">0%</span>
                    </div>
                    <div style="height: 280px;">
                        <canvas id="profile-chart-preview" aria-label="Profile chart preview"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 d-flex gap-2">
        <button class="btn btn-primary">{{ __('Save') }}</button>
        <a href="{{ route('admin.profiles.index') }}" class="btn btn-outline-secondary">{{ __('Cancel') }}</a>
    </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    const csrfToken = document.querySelector('input[name="_token"]').value;
    const uploadToken = document.getElementById('upload-token').value;
    const avatarInput = document.getElementById('avatar-input');
    const avatarValue = document.getElementById('avatar-value');
    const initialChartItems = @json(array_values($chartItems));
    const chartItemsContainer = document.getElementById('chart-items');
    const addChartItemButton = document.getElementById('add-chart-item');
    const chartTotalIndicator = document.getElementById('chart-total-indicator');
    const previewCanvas = document.getElementById('profile-chart-preview');
    let previewChart;

    const getAvatarDisplayNode = () => document.getElementById('avatar-preview') || document.getElementById('avatar-placeholder');

    const getChartRows = () => [...chartItemsContainer.querySelectorAll('[data-chart-item]')];

    const getChartData = () => getChartRows().map((row) => ({
        label: row.querySelector('[data-field="label"]').value || 'Item',
        value: Number(row.querySelector('[data-field="value"]').value || 0),
        color: row.querySelector('[data-field="color"]').value || '#0F4C81',
    }));

    const updateChartPreview = () => {
        if (!previewCanvas || typeof Chart === 'undefined') {
            return;
        }

        const chartData = getChartData();
        const total = chartData.reduce((sum, item) => sum + item.value, 0);
        chartTotalIndicator.textContent = `${total}%`;
        chartTotalIndicator.className = `badge ${total === 100 ? 'text-bg-success' : 'text-bg-danger'}`;

        if (!previewChart) {
            previewChart = new Chart(previewCanvas, {
                type: 'doughnut',
                data: {
                    labels: chartData.map((item) => item.label),
                    datasets: [{
                        data: chartData.map((item) => item.value),
                        backgroundColor: chartData.map((item) => item.color),
                        borderColor: '#ffffff',
                        borderWidth: 3,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 8,
                            },
                        },
                    },
                },
            });
            return;
        }

        previewChart.data.labels = chartData.map((item) => item.label);
        previewChart.data.datasets[0].data = chartData.map((item) => item.value);
        previewChart.data.datasets[0].backgroundColor = chartData.map((item) => item.color);
        previewChart.update();
    };

    const syncChartRowNames = () => {
        const rows = getChartRows();

        rows.forEach((row, index) => {
            row.querySelectorAll('[data-field]').forEach((field) => {
                field.name = `expertise_chart[${index}][${field.dataset.field}]`;
            });

            row.querySelector('[data-remove-chart-item]').disabled = rows.length <= 3;
        });

        addChartItemButton.disabled = rows.length >= 8;
        updateChartPreview();
    };

    const createChartItemRow = (item = {}) => {
        const row = document.createElement('div');
        row.dataset.chartItem = '1';
        row.className = 'border rounded-3 p-3';
        row.innerHTML = `
            <div class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label">{{ __('Label') }}</label>
                    <input type="text" class="form-control" data-field="label" value="${item.label ?? ''}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('Value (%)') }}</label>
                    <input type="number" min="0" max="100" class="form-control" data-field="value" value="${item.value ?? 0}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">{{ __('Color') }}</label>
                    <input type="color" class="form-control form-control-color w-100" data-field="color" value="${item.color ?? '#0F4C81'}" required>
                </div>
                <div class="col-md-2 d-grid">
                    <button type="button" class="btn btn-outline-danger" data-remove-chart-item>{{ __('Remove') }}</button>
                </div>
            </div>
        `;

        row.querySelectorAll('[data-field]').forEach((field) => {
            field.addEventListener('input', updateChartPreview);
        });

        row.querySelector('[data-remove-chart-item]').addEventListener('click', () => {
            row.remove();
            syncChartRowNames();
        });

        chartItemsContainer.appendChild(row);
        syncChartRowNames();
    };

    initialChartItems.forEach((item) => createChartItemRow(item));

    addChartItemButton.addEventListener('click', () => {
        if (getChartRows().length >= 8) {
            return;
        }

        createChartItemRow({ label: 'New Skill', value: 0, color: '#577590' });
    });

    // Handle avatar upload
    avatarInput.addEventListener('change', () => {
        const file = avatarInput.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('image', file);
        formData.append('type', 'profile');
        formData.append('temp_token', uploadToken);

        const loadingText = document.createElement('div');
        loadingText.style.cssText = 'width: 100px; height: 100px; background: #e9ecef; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid #dee2e6;';
        loadingText.className = 'text-muted small';
        loadingText.textContent = '{{ __('Uploading...') }}';
        const currentAvatarNode = getAvatarDisplayNode();
        if (currentAvatarNode) {
            currentAvatarNode.replaceWith(loadingText);
        }

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
                if (loadingText.parentNode) {
                    const placeholder = document.createElement('div');
                    placeholder.id = 'avatar-placeholder';
                    placeholder.style.cssText = 'width: 100px; height: 100px; background: #e9ecef; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid #dee2e6;';
                    placeholder.className = 'text-muted small';
                    placeholder.textContent = '{{ __('No avatar') }}';
                    loadingText.replaceWith(placeholder);
                }
            }
        })
        .catch(() => {
            alert('{{ __('Image upload failed.') }}');
            if (loadingText.parentNode) {
                const placeholder = document.createElement('div');
                placeholder.id = 'avatar-placeholder';
                placeholder.style.cssText = 'width: 100px; height: 100px; background: #e9ecef; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid #dee2e6;';
                placeholder.className = 'text-muted small';
                placeholder.textContent = '{{ __('No avatar') }}';
                loadingText.replaceWith(placeholder);
            }
        });
    });
</script>
