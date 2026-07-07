@forelse($logs as $log)
    <tr>
        <td>
            <div class="fw-semibold">{{ $log->created_at->format('d M Y') }}</div>
            <div class="text-muted">{{ $log->created_at->format('H:i:s') }}</div>
        </td>
        <td>
            @if($log->user)
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ $log->user->avatar_url }}" class="avatar avatar-xs rounded-circle" alt="">
                    <div>
                        <div class="fw-semibold">{{ $log->user->name }}</div>
                        <div class="text-muted">{{ $log->user->email }}</div>
                    </div>
                </div>
            @else
                <span class="text-muted">System</span>
            @endif
        </td>
        <td>
            @php
                $badgeClass = match($log->action) {
                    'login', 'logout' => 'info',
                    'create' => 'success',
                    'update' => 'warning',
                    'delete' => 'danger',
                    default => 'secondary'
                };
            @endphp
            <span class="badge bg-{{ $badgeClass }}-subtle text-{{ $badgeClass }} border border-{{ $badgeClass }}-subtle text-capitalize">{{ $log->action }}</span>
        </td>
        <td><span class="badge bg-light text-dark border">{{ ucfirst($log->module) }}</span></td>
        <td><div>{{ $log->description }}</div></td>
        <td><code>{{ $log->ip_address }}</code></td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center py-5">
            @include('components.empty-state', [
                'icon' => 'ti-activity-heartbeat',
                'title' => 'No Activity Logs Found',
                'subtitle' => 'Activity logs will appear here as users perform actions in the system.',
            ])
        </td>
    </tr>
@endforelse
