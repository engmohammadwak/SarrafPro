@extends('layouts.admin')
@section('title', 'الإشعارات')
@section('page-title', 'الإشعارات')
@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-bell" style="color:var(--accent);margin-left:8px"></i> سجل الإشعارات</h3>
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
            $palette = [
                'success' => ['bg'=>'rgba(16,185,129,0.08)','border'=>'rgba(16,185,129,0.3)','icon'=>'#10b981','fa'=>'fa-check-circle',  'label_bg'=>'rgba(16,185,129,0.12)', 'label_color'=>'#065f46'],
                'warning' => ['bg'=>'rgba(245,158,11,0.08)','border'=>'rgba(245,158,11,0.3)','icon'=>'#f59e0b','fa'=>'fa-exclamation-triangle','label_bg'=>'rgba(245,158,11,0.12)','label_color'=>'#92400e'],
                'danger'  => ['bg'=>'rgba(239,68,68,0.08)', 'border'=>'rgba(239,68,68,0.3)', 'icon'=>'#ef4444','fa'=>'fa-times-circle',   'label_bg'=>'rgba(239,68,68,0.12)',  'label_color'=>'#991b1b'],
                'info'    => ['bg'=>'rgba(59,130,246,0.08)','border'=>'rgba(59,130,246,0.3)','icon'=>'#3b82f6','fa'=>'fa-info-circle',    'label_bg'=>'rgba(59,130,246,0.12)', 'label_color'=>'#1e40af'],
            ];
            $c = $palette[$type] ?? $palette['info'];
        @endphp
        <a href="{{ route('admin.notifications.read', $n->id) }}"
           style="display:flex;align-items:flex-start;gap:16px;padding:20px 24px;border-bottom:1px solid var(--border);text-decoration:none;transition:background .15s;background:{{ $n->read_at ? '#fff' : $c['bg'] }}">

            {{-- أيقونة --}}
            <div style="width:44px;height:44px;border-radius:12px;background:{{ $c['bg'] }};border:1.5px solid {{ $c['border'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px">
                <i class="fas {{ $c['fa'] }}" style="color:{{ $c['icon'] }};font-size:18px"></i>
            </div>

            {{-- المحتوى --}}
            <div style="flex:1;min-width:0">

                {{-- العنوان + الوقت --}}
                <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;margin-bottom:6px">
                    <span style="font-weight:{{ $n->read_at ? '600':'700' }};font-size:15px;color:var(--text-dark)">
                        {{ $data['title'] ?? '' }}
                    </span>
                    <span style="font-size:11px;color:var(--text-muted);white-space:nowrap;flex-shrink:0">
                        <i class="fas fa-clock" style="font-size:10px"></i> {{ $n->created_at->diffForHumans() }}
                    </span>
                </div>

                {{-- بطاقة المندوب + نوع الحدث --}}
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;flex-wrap:wrap">
                    @if(!empty($data['agent_name']))
                    <span style="display:inline-flex;align-items:center;gap:5px;background:#f0f4ff;border:1px solid #dbe4ff;color:#3b5bdb;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:700">
                        <i class="fas fa-user" style="font-size:10px"></i> {{ $data['agent_name'] }}
                    </span>
                    @endif
                    @if(!empty($data['action_label']))
                    <span style="display:inline-flex;align-items:center;gap:5px;background:{{ $c['label_bg'] }};color:{{ $c['label_color'] }};padding:3px 10px;border-radius:20px;font-size:12px;font-weight:700">
                        {{ $data['action_label'] }}
                    </span>
                    @endif
                    @if(!empty($data['balance']))
                    <span style="display:inline-flex;align-items:center;gap:5px;background:rgba(245,158,11,0.1);color:#92400e;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:700">
                        <i class="fas fa-coins" style="font-size:10px"></i> رصيد: {{ $data['balance'] }}
                    </span>
                    @endif
                </div>

                {{-- الرسالة --}}
                <p style="font-size:13.5px;color:var(--text-muted);margin:0;line-height:1.6">{{ $data['message'] ?? '' }}</p>
            </div>

            {{-- نقطة غير مقروء --}}
            @if(!$n->read_at)
            <div style="width:9px;height:9px;background:var(--accent);border-radius:50%;flex-shrink:0;margin-top:8px"></div>
            @endif
        </a>
        @empty
        <div style="text-align:center;padding:70px;color:var(--text-muted)">
            <i class="fas fa-bell-slash" style="font-size:48px;opacity:.2;margin-bottom:14px;display:block"></i>
            <p style="font-size:15px">لا توجد إشعارات حتى الآن</p>
        </div>
        @endforelse
    </div>
    @if($notifications->hasPages())
    <div style="padding:16px">{{ $notifications->links() }}</div>
    @endif
</div>
@endsection
