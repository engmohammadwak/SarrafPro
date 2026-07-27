@extends('layouts.superadmin')
@section('title', 'إضافة مندوب')
@section('page-title', 'إضافة مندوب جديد')
@section('content')
<div style="max-width:620px">

    @if($errors->any())
    <div style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);color:#dc2626;padding:12px 16px;border-radius:10px;margin-bottom:20px;font-size:14px">
        @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('superadmin.agents.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="card" style="margin-bottom:20px">
        <div class="card-header"><h3><i class="fas fa-id-badge" style="color:var(--accent);margin-left:8px"></i> بيانات المندوب</h3></div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">

                <div>
                    <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">الاسم الكامل <span style="color:#ef4444">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;box-sizing:border-box">
                </div>

                <div>
                    <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" placeholder="agent_name"
                        style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:monospace;font-size:14px;direction:ltr;box-sizing:border-box"
                        autocomplete="off">
                </div>

                <div>
                    <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">البريد الإلكتروني <span style="color:#ef4444">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;direction:ltr;box-sizing:border-box">
                </div>

                <div>
                    <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">كلمة المرور <span style="color:#ef4444">*</span></label>
                    <input type="password" name="password" required
                        style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;box-sizing:border-box">
                </div>

                <div style="grid-column:1/-1">
                    <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">ملف مرفق <span style="font-size:12px;color:var(--text-muted);font-weight:400">(اختياري — PDF, صورة, Word)</span></label>
                    <div style="border:2px dashed var(--border);border-radius:10px;padding:20px;text-align:center;background:#fafbfc;cursor:pointer" onclick="document.getElementById('attachmentInput').click()">
                        <i class="fas fa-cloud-upload-alt" style="font-size:28px;color:var(--text-muted);margin-bottom:8px;display:block"></i>
                        <p style="font-size:13px;color:var(--text-muted);margin:0" id="attachmentLabel">اضغط لاختيار ملف • الحجم الأقصى 5MB</p>
                        <input type="file" id="attachmentInput" name="attachment" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" style="display:none"
                            onchange="document.getElementById('attachmentLabel').textContent = this.files[0]?.name || 'اضغط لاختيار ملف'">
                    </div>
                </div>

                <div style="grid-column:1/-1">
                    <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">ملاحظة</label>
                    <textarea name="notes" rows="3" placeholder="أي معلومات إضافية..."
                        style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;resize:vertical;box-sizing:border-box">{{ old('notes') }}</textarea>
                </div>

            </div>
        </div>
    </div>

    <div style="display:flex;gap:12px">
        <button type="submit" class="btn btn-gold"><i class="fas fa-user-plus"></i> إضافة</button>
        <a href="{{ route('superadmin.agents.index') }}" class="btn" style="background:#f3f4f6;color:#374151">رجوع</a>
    </div>
    </form>
</div>
@endsection
