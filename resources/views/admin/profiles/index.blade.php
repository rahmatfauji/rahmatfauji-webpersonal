@extends('layouts.app')

@section('title', __('Admin - Profiles'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="section-title mb-0">{{ __('Profile Management') }}</h1>
    <a href="{{ route('admin.profiles.create') }}" class="btn btn-primary">{{ __('Add Profile') }}</a>
</div>

<div class="clean-card p-3">
    <div class="table-responsive">
        <table class="table table-striped align-middle mb-0">
            <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Headline') }}</th>
                    <th>{{ __('Email') }}</th>
                    <th>{{ __('Location') }}</th>
                    <th class="text-end">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
            @forelse($profiles as $profile)
                <tr>
                    <td>{{ $profile->full_name }}</td>
                    <td>{{ $profile->title }}</td>
                    <td>{{ $profile->email }}</td>
                    <td>{{ $profile->location }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.profiles.edit', $profile) }}" class="btn btn-sm btn-outline-primary">{{ __('Edit') }}</a>
                        <form action="{{ route('admin.profiles.destroy', $profile) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Delete this profile?') }}')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">{{ __('Delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">{{ __('No profiles yet.') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3 pagination-shell">{{ $profiles->links() }}</div>
@endsection
