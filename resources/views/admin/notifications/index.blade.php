@extends('layouts.admin')
@section('title', 'الإشعارات')
@section('page-title', 'الإشعارات')
@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-bell" style="color:var(--accent);margin-left:8px"></i> الإشعارات</h3>
        @if(auth()->user()->unreadNotifications->count())
        <form action="{{ route('admin.notifications.read-all') }}" method="POST">
            @csrf
            <button class="btn btn-sm btn-gold"><i class="fas fa-check-double"></i> تعليم الكل كمقروء</button>
        </form>
        @endif
    </div>
    <div class="card-body" style="padding:0">
        @forelse($notifications as $n)
        @php
            $data = is_array($n->data) ? $n->data : json_decode($n->data, true);
            $type = $data['type'] ?? 'info';
            $colors = [
                'success' => ['bg'=>'rgba(16,185,129,0.08)','border'=>'rgba(16,185,129,0.25)','icon'=>'#10b981','fa'=>'fa-check-circle'],
                'warning' => ['bg'=>'rgba(245,158,11,0.08)','border'=>'rgba(245,158,11,0.25)','icon'=>'#f59e0b','fa'=>'fa-exclamation-triangle'],
                'danger'  => ['bg'=>'rgba(239,68,68,0.08)', 'border'=>'rgba(239,68,68,0.25)', 'icon'=>'#ef4444','fa'=>'fa-times-circle'],
                'info'    => ['bg'=>'rgba(59,130,246,0.08)','border'=>'rgba(59,130,246,0.25)','icon'=>'#3b82f6','fa'=>'fa-info-circle'],
            ];
            $c = $colors[$type] ?? $colors['info'];
        @endphp
        <a href="{{ route('admin.notifications.read', $n->id) }}"
           style="display:flex;align-items:flex-start;gap:14px;padding:18px 24px;border-bottom:1px solid var(--border);text-decoration:none;transition:background .15s;
                  background:{{ $n->read_at ? '#fff' : $c['bg'] }};">
            <div style="width:40px;height:40px;border-radius:12px;background:{{ $c['bg'] }};border:1.5px solid {{ $c['border'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="fas {{ $c['fa'] }}" style="color:{{ $c['icon'] }};font-size:16px"></i>
            </div>
            <div style="flex:1;min-width:0">
                <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;margin-bottom:3px">
                    <span style="font-weight:{{ $n->read_at ? '600' : '700' }};font-size:14.5px;color:var(--text-dark)">{{ $data['title'] ?? '' }}</span>
                    <span style="font-size:11px;color:var(--text-muted);white-space:nowrap">{{ $n->created_at->diffForHumans() }}</span>
                </div>
                <p style="font-size:13px;color:var(--text-muted);margin:0;line-height:1.5">{{ $data['message'] ?? '' }}</p>
            </div>
            @if(!$n->read_at)
            <div style="width:8px;height:8px;background:var(--accent);border-radius:50%;flex-shrink:0;margin-top:6px"></div>
            @endif
        </a>
        @empty
        <div style="text-align:center;padding:60px;color:var(--text-muted)">
            <i class="fas fa-bell-slash" style="font-size:40px;opacity:.3;margin-bottom:12px;display:block"></i>
            لا توجد إشعارات
        </div>
        @endforelse
    </div>
    @if($notifications->hasPages())
    <div style="padding:16px">{{ $notifications->links() }}</div>
    @endif
</div>
@endsection
