@php
    $shareUrl = urlencode($url);
    $shareId = 'share-' . md5(($title ?? 'share') . '|' . $url);
@endphp

<div class="dropdown share-dropdown">
    <button class="btn btn-sm btn-outline-secondary share-trigger dropdown-toggle" type="button" id="{{ $shareId }}" data-bs-toggle="dropdown" aria-expanded="false">
        {{ __('Share') }}
    </button>
    <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="{{ $shareId }}">
        <li><a class="dropdown-item" href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" target="_blank" rel="noreferrer">LinkedIn</a></li>
        <li><button type="button" class="dropdown-item" data-copy-url="{{ urldecode($shareUrl) }}">{{ __('Copy Link') }}</button></li>
    </ul>
</div>
