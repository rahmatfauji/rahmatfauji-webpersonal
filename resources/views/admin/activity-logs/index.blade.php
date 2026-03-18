@extends('layouts.app')

@section('title', __('Admin - Activity Log'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="section-title mb-0">{{ __('Visitor Activity Log') }}</h1>
</div>

<div class="clean-card p-3 mb-3">
    <div class="d-flex flex-wrap gap-2 mb-3">
        <a href="{{ route('admin.activity-logs.index', array_merge(request()->except('page', 'preset'), ['preset' => 'today'])) }}" class="btn btn-sm {{ $filters['preset'] === 'today' ? 'btn-primary' : 'btn-outline-primary' }}">{{ __('Today') }}</a>
        <a href="{{ route('admin.activity-logs.index', array_merge(request()->except('page', 'preset'), ['preset' => 'last7'])) }}" class="btn btn-sm {{ $filters['preset'] === 'last7' ? 'btn-primary' : 'btn-outline-primary' }}">{{ __('Last 7 Days') }}</a>
        <a href="{{ route('admin.activity-logs.index', array_merge(request()->except('page', 'preset'), ['preset' => 'last30'])) }}" class="btn btn-sm {{ $filters['preset'] === 'last30' ? 'btn-primary' : 'btn-outline-primary' }}">{{ __('Last 30 Days') }}</a>
        <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-sm btn-outline-secondary">{{ __('All') }}</a>
    </div>

    <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="row g-2 align-items-end">
        <input type="hidden" name="preset" value="{{ $filters['preset'] }}">
        <div class="col-md-2">
            <label class="form-label">{{ __('Date From') }}</label>
            <input type="date" name="date_from" class="form-control" value="{{ $filters['date_from'] }}">
        </div>
        <div class="col-md-2">
            <label class="form-label">{{ __('Date To') }}</label>
            <input type="date" name="date_to" class="form-control" value="{{ $filters['date_to'] }}">
        </div>
        <div class="col-md-2">
            <label class="form-label">{{ __('IP Address') }}</label>
            <input type="text" name="ip_address" class="form-control" value="{{ $filters['ip_address'] }}" placeholder="192.168.1.1">
        </div>
        <div class="col-md-2">
            <label class="form-label">{{ __('Country') }}</label>
            <input type="text" name="country" class="form-control" value="{{ $filters['country'] }}" placeholder="Indonesia">
        </div>
        <div class="col-md-2">
            <label class="form-label">{{ __('Method') }}</label>
            <select name="method" class="form-select">
                <option value="">{{ __('All') }}</option>
                @foreach($methods as $method)
                    <option value="{{ $method }}" @selected($filters['method'] === $method)>{{ $method }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">{{ __('URL') }}</label>
            <input type="text" name="url" class="form-control" value="{{ $filters['url'] }}" placeholder="/blog">
        </div>
        <div class="col-12 d-flex gap-2 mt-3">
            <button type="submit" class="btn btn-primary">{{ __('Apply Filter') }}</button>
            <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline-secondary">{{ __('Reset') }}</a>
            <a href="{{ route('admin.activity-logs.export', request()->except('page')) }}" class="btn btn-outline-success">{{ __('Export CSV') }}</a>
        </div>
    </form>
</div>

<div class="clean-card p-3">
    <div class="table-responsive">
        <table class="table table-striped align-middle mb-0">
            <thead>
                <tr>
                    <th>{{ __('Visited At') }}</th>
                    <th>{{ __('IP Address') }}</th>
                    <th>{{ __('Country') }}</th>
                    <th>{{ __('Action') }}</th>
                    <th>{{ __('Method') }}</th>
                    <th>{{ __('URL') }}</th>
                </tr>
            </thead>
            <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ optional($log->visited_at)->format('Y-m-d H:i:s') }}</td>
                    <td>{{ $log->ip_address }}</td>
                    <td>{{ $log->country ?? '-' }}</td>
                    <td><span class="badge {{ $log->action_type === 'page_visit' ? 'bg-info' : ($log->action_type === 'user_login' ? 'bg-success' : ($log->action_type === 'user_logout' ? 'bg-warning' : 'bg-primary')) }}">{{ __(ucfirst(str_replace('_', ' ', $log->action_type))) }}</span></td>
                    <td>{{ $log->method }}</td>
                    <td class="text-break" style="max-width: 320px;">{{ $log->url }}</td>
                </tr>
            @empty
                <tr><td colspan="6">{{ __('No activity yet.') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3 pagination-shell">{{ $logs->links() }}</div>
@endsection
