@extends('layouts.app')

@section('title', __('Profile'))
@section('meta_description', 'Professional profile of Rahmat Fauji, focused on data analytics, Power BI dashboard development, and Power Apps implementation.')
@section('meta_type', 'profile')
@section('meta_canonical', route('profile'))

@section('content')
<div class="row g-4 align-items-start">
    <div class="col-lg-4 fade-in-up" data-fade-in>
        <div class="clean-card p-4 text-center">
            @if(optional($profile)->avatar_url)
                <div class="image-skeleton rounded-circle mx-auto mb-3" style="width: 180px; height: 180px;">
                    <img src="{{ $profile->avatar_url }}" alt="{{ $profile->full_name }}" class="img-fluid rounded-circle js-skeleton-image" style="width: 180px; height: 180px; object-fit: cover;">
                </div>
            @endif
            <h3 class="section-title mb-1">{{ optional($profile)->full_name ?? __('Profile is not available yet') }}</h3>
            <p class="text-muted">{{ optional($profile)->title }}</p>
            <div class="small">
                <div>{{ optional($profile)->email }}</div>
                <div>{{ optional($profile)->phone }}</div>
                <div>{{ optional($profile)->location }}</div>
            </div>
        </div>
    </div>
    <div class="col-lg-8 fade-in-up fade-delay-1" data-fade-in>
        <div class="clean-card p-4">
            <h4 class="section-title">{{ __('About Me') }}</h4>
            <p class="mb-0">{{ optional($profile)->bio ?? __('Please complete profile data from the admin panel.') }}</p>
        </div>
    </div>
</div>
@endsection
