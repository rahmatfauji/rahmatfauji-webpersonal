<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ActivityLogController extends Controller
{
    private const METHODS = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];

    public function index(Request $request)
    {
        $filters = $this->resolveFilters($request);

        return view('admin.activity-logs.index', [
            'logs' => $this->buildFilteredQuery($filters)
                ->latest('visited_at')
                ->paginate(30)
                ->withQueryString(),
            'filters' => $filters,
            'methods' => self::METHODS,
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $filters = $this->resolveFilters($request);
        $filename = 'activity-logs-' . now()->format('Ymd-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ];

        return response()->streamDownload(function () use ($filters) {
            $output = fopen('php://output', 'w');
            if ($output === false) {
                return;
            }

            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, ['Visited At', 'IP Address', 'Country', 'Action', 'Method', 'URL', 'User Agent']);

            $this->buildFilteredQuery($filters)
                ->latest('visited_at')
                ->chunkById(500, function ($logs) use ($output) {
                    foreach ($logs as $log) {
                        fputcsv($output, [
                            optional($log->visited_at)->format('Y-m-d H:i:s'),
                            $log->ip_address,
                            $log->country,
                            $log->action_type,
                            $log->method,
                            $log->url,
                            $log->user_agent,
                        ]);
                    }
                });

            fclose($output);
        }, $filename, $headers);
    }

    private function resolveFilters(Request $request): array
    {
        $preset = $request->string('preset')->toString();

        $filters = [
            'preset' => in_array($preset, ['today', 'last7', 'last30'], true) ? $preset : '',
            'date_from' => $request->string('date_from')->toString(),
            'date_to' => $request->string('date_to')->toString(),
            'ip_address' => $request->string('ip_address')->toString(),
            'country' => $request->string('country')->toString(),
            'method' => strtoupper($request->string('method')->toString()),
            'url' => $request->string('url')->toString(),
        ];

        if ($filters['preset'] === 'today') {
            $filters['date_from'] = now()->toDateString();
            $filters['date_to'] = now()->toDateString();
        }

        if ($filters['preset'] === 'last7') {
            $filters['date_from'] = now()->subDays(6)->toDateString();
            $filters['date_to'] = now()->toDateString();
        }

        if ($filters['preset'] === 'last30') {
            $filters['date_from'] = now()->subDays(29)->toDateString();
            $filters['date_to'] = now()->toDateString();
        }

        return $filters;
    }

    private function buildFilteredQuery(array $filters): Builder
    {
        return ActivityLog::query()
            ->when($filters['date_from'] !== '', function (Builder $query) use ($filters) {
                $query->whereDate('visited_at', '>=', $filters['date_from']);
            })
            ->when($filters['date_to'] !== '', function (Builder $query) use ($filters) {
                $query->whereDate('visited_at', '<=', $filters['date_to']);
            })
            ->when($filters['ip_address'] !== '', function (Builder $query) use ($filters) {
                $query->where('ip_address', 'like', '%' . $filters['ip_address'] . '%');
            })
            ->when($filters['country'] !== '', function (Builder $query) use ($filters) {
                $query->where('country', 'like', '%' . $filters['country'] . '%');
            })
            ->when(in_array($filters['method'], self::METHODS, true), function (Builder $query) use ($filters) {
                $query->where('method', $filters['method']);
            })
            ->when($filters['url'] !== '', function (Builder $query) use ($filters) {
                $query->where('url', 'like', '%' . $filters['url'] . '%');
            });
    }
}
