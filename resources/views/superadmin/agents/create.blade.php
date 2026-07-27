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

    {{-- بيانات الحساب --}}
    <div class="card" style="margin-bottom:20px">
        <div class="card-header"><h3><i class="fas fa-user-circle" style="color:var(--accent);margin-left:8px"></i> بيانات الحساب</h3></div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">

                <div>
                    <label class="sa-label">الاسم الكامل <span style="color:#ef4444">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="sa-input">
                </div>

                <div>
                    <label class="sa-label">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" placeholder="agent_name"
                        class="sa-input" style="font-family:monospace;direction:ltr" autocomplete="off">
                </div>

                <div>
                    <label class="sa-label">البريد الإلكتروني <span style="color:#ef4444">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="sa-input" style="direction:ltr">
                </div>

                <div>
                    <label class="sa-label">كلمة المرور <span style="color:#ef4444">*</span></label>
                    <input type="password" name="password" required class="sa-input">
                </div>

            </div>
        </div>
    </div>

    {{-- بيانات إضافية --}}
    <div class="card" style="margin-bottom:20px">
        <div class="card-header"><h3><i class="fas fa-id-badge" style="color:var(--accent);margin-left:8px"></i> بيانات إضافية <span style="font-size:12px;font-weight:400;color:var(--text-muted)">اختيارية</span></h3></div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">

                <div>
                    <label class="sa-label">رقم الهاتف</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+968 XXXX XXXX" class="sa-input">
                </div>

                <div>
                    <label class="sa-label">الدولة</label>
                    <input type="text" name="country" value="{{ old('country') }}" placeholder="عمان" class="sa-input">
                </div>

                <div style="grid-column:1/-1">
                    <label class="sa-label">اسم الشركة</label>
                    <input type="text" name="company" value="{{ old('company') }}" placeholder="اسم الشركة أو المؤسسة" class="sa-input">
                </div>

                {{-- ملف مرفق --}}
                <div style="grid-column:1/-1">
                    <label class="sa-label">ملف مرفق <span style="font-size:12px;font-weight:400;color:var(--text-muted)">(PDF, صورة, Word)</span></label>
                    <div id="dropZone" onclick="document.getElementById('attachmentInput').click()"
                        style="border:2px dashed var(--border);border-radius:10px;padding:20px;text-align:center;background:#fafbfc;cursor:pointer;transition:border-color .2s">
                        <i class="fas fa-cloud-upload-alt" style="font-size:28px;color:var(--text-muted);margin-bottom:6px;display:block"></i>
                        <p style="font-size:13px;color:var(--text-muted);margin:0" id="attachmentLabel">اضغط لاختيار ملف &bull; الحجم الأقصى 5MB</p>
                        <input type="file" id="attachmentInput" name="attachment"
                               accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" style="display:none"
                               onchange="handleFilePreview(this)">
                    </div>
                    <div id="previewArea" style="display:none;margin-top:14px;border:1px solid var(--border);border-radius:10px;overflow:hidden;background:#fff">
                        <div id="previewImage" style="display:none">
                            <img id="previewImg" src="" alt="معاينة" style="width:100%;max-height:320px;object-fit:contain;display:block;cursor:pointer" onclick="window.open(this.src,'_blank')">
                        </div>
                        <div id="previewPdf" style="display:none">
                            <iframe id="previewPdfFrame" src="" style="width:100%;height:380px;border:none;display:block"></iframe>
                        </div>
                        <div id="previewOther" style="display:none;padding:16px;align-items:center;gap:12px">
                            <i class="fas fa-file-alt" style="font-size:28px;color:var(--text-muted)"></i>
                            <div>
                                <p id="previewFileName" style="font-weight:600;font-size:14px;margin:0"></p>
                                <p id="previewFileSize" style="font-size:12px;color:var(--text-muted);margin:0"></p>
                            </div>
                        </div>
                        <div style="padding:10px 14px;background:#f8f9fc;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
                            <span id="previewFooterName" style="font-size:13px;color:var(--text-muted);font-family:monospace"></span>
                            <button type="button" onclick="clearPreview()" style="background:none;border:none;color:#ef4444;font-size:13px;cursor:pointer">
                                <i class="fas fa-times"></i> إلغاء
                            </button>
                        </div>
                    </div>
                </div>

                <div style="grid-column:1/-1">
                    <label class="sa-label">ملاحظة</label>
                    <textarea name="notes" rows="3" placeholder="أي معلومات إضافية..."
                        class="sa-input" style="resize:vertical">{{ old('notes') }}</textarea>
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

<style>
.sa-label { display:block; margin-bottom:6px; font-size:14px; font-weight:600; color:var(--text-muted); }
.sa-input  { width:100%; padding:10px 14px; background:#f8f9fc; border:1.5px solid var(--border); border-radius:8px; font-family:Tajawal,sans-serif; font-size:14px; box-sizing:border-box; transition:border-color .2s; }
.sa-input:focus { outline:none; border-color:var(--accent); background:#fff; }
</style>

<script>
function handleFilePreview(input) {
    const file = input.files[0];
    if (!file) return;
    const name = file.name;
    const ext  = name.split('.').pop().toLowerCase();
    const size = (file.size / 1024).toFixed(0) + ' KB';
    const isImage = ['jpg','jpeg','png','gif','webp'].includes(ext);
    const isPdf   = ext === 'pdf';
    document.getElementById('attachmentLabel').textContent = name;
    document.getElementById('previewImage').style.display = 'none';
    document.getElementById('previewPdf').style.display   = 'none';
    document.getElementById('previewOther').style.display = 'none';
    document.getElementById('previewArea').style.display  = 'block';
    document.getElementById('previewFooterName').textContent = name;
    const reader = new FileReader();
    if (isImage) {
        reader.onload = e => { document.getElementById('previewImg').src = e.target.result; document.getElementById('previewImage').style.display = 'block'; };
        reader.readAsDataURL(file);
    } else if (isPdf) {
        reader.onload = e => { document.getElementById('previewPdfFrame').src = e.target.result; document.getElementById('previewPdf').style.display = 'block'; };
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
    document.getElementById('previewArea').style.display = 'none';
    document.getElementById('previewImg').src = '';
    document.getElementById('previewPdfFrame').src = '';
}
</script>
@endsection
