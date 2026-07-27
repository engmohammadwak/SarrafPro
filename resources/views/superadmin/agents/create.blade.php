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

                {{-- منطقة الملف المرفق + المعاينة --}}
                <div style="grid-column:1/-1">
                    <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">ملف مرفق <span style="font-size:12px;color:var(--text-muted);font-weight:400">(اختياري — PDF, صورة, Word)</span></label>

                    {{-- منطقة الرفع --}}
                    <div id="dropZone" onclick="document.getElementById('attachmentInput').click()"
                        style="border:2px dashed var(--border);border-radius:10px;padding:24px;text-align:center;background:#fafbfc;cursor:pointer;transition:border-color .2s">
                        <i class="fas fa-cloud-upload-alt" style="font-size:32px;color:var(--text-muted);margin-bottom:8px;display:block"></i>
                        <p style="font-size:13px;color:var(--text-muted);margin:0" id="attachmentLabel">اضغط لاختيار ملف • الحجم الأقصى 5MB</p>
                        <input type="file" id="attachmentInput" name="attachment"
                               accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" style="display:none"
                               onchange="handleFilePreview(this)">
                    </div>

                    {{-- منطقة المعاينة (تظهر بعد الاختيار) --}}
                    <div id="previewArea" style="display:none;margin-top:14px;border:1px solid var(--border);border-radius:10px;overflow:hidden;background:#fff">

                        {{-- صورة --}}
                        <div id="previewImage" style="display:none">
                            <img id="previewImg" src="" alt="معاينة"
                                 style="width:100%;max-height:360px;object-fit:contain;display:block;cursor:pointer"
                                 onclick="window.open(this.src,'_blank')">
                        </div>

                        {{-- PDF --}}
                        <div id="previewPdf" style="display:none">
                            <iframe id="previewPdfFrame" src="" style="width:100%;height:400px;border:none;display:block" title="PDF"></iframe>
                        </div>

                        {{-- ملف آخر --}}
                        <div id="previewOther" style="display:none;padding:16px;display:none;align-items:center;gap:12px">
                            <i class="fas fa-file-alt" style="font-size:32px;color:var(--text-muted)"></i>
                            <div>
                                <p id="previewFileName" style="font-weight:600;font-size:14px;margin:0"></p>
                                <p id="previewFileSize" style="font-size:12px;color:var(--text-muted);margin:0"></p>
                            </div>
                        </div>

                        {{-- شريط سفلي --}}
                        <div style="padding:10px 14px;background:#f8f9fc;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
                            <span id="previewFooterName" style="font-size:13px;color:var(--text-muted);font-family:monospace"></span>
                            <button type="button" onclick="clearPreview()"
                                style="background:none;border:none;color:#ef4444;font-size:13px;cursor:pointer;padding:0">
                                <i class="fas fa-times"></i> إلغاء
                            </button>
                        </div>
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

<script>
function handleFilePreview(input) {
    const file = input.files[0];
    if (!file) return;

    const name = file.name;
    const ext  = name.split('.').pop().toLowerCase();
    const size = (file.size / 1024).toFixed(0) + ' KB';
    const isImage = ['jpg','jpeg','png','gif','webp'].includes(ext);
    const isPdf   = ext === 'pdf';

    // تحديث اسم الملف في dropzone
    document.getElementById('attachmentLabel').textContent = name;

    // إخفاء كل الأقسام
    document.getElementById('previewImage').style.display = 'none';
    document.getElementById('previewPdf').style.display   = 'none';
    document.getElementById('previewOther').style.display = 'none';

    // إظهار منطقة المعاينة
    document.getElementById('previewArea').style.display = 'block';
    document.getElementById('previewFooterName').textContent = name;

    const reader = new FileReader();

    if (isImage) {
        reader.onload = e => {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('previewImage').style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else if (isPdf) {
        reader.onload = e => {
            document.getElementById('previewPdfFrame').src = e.target.result;
            document.getElementById('previewPdf').style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById('previewFileName').textContent = name;
        document.getElementById('previewFileSize').textContent = size;
        document.getElementById('previewOther').style.display  = 'flex';
    }
}

function clearPreview() {
    document.getElementById('attachmentInput').value = '';
    document.getElementById('attachmentLabel').textContent = 'اضغط لاختيار ملف • الحجم الأقصى 5MB';
    document.getElementById('previewArea').style.display   = 'none';
    document.getElementById('previewImg').src               = '';
    document.getElementById('previewPdfFrame').src          = '';
}
</script>
@endsection
