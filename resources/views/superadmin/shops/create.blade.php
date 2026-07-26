@extends('layouts.superadmin')
@section('title', 'إضافة محل جديد - صراف برو')
@section('page-title', 'إضافة محل جديد')
@section('content')
<div style="max-width:700px;">

    @if($errors->any())
    <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#ef4444;padding:14px 20px;border-radius:12px;margin-bottom:20px;">
        <ul style="margin:0;padding-right:18px;">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('superadmin.shops.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- بيانات المحل --}}
        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <h3><i class="fas fa-store" style="color:var(--accent);margin-left:8px;"></i> بيانات المحل</h3>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

                    <div>
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted);">اسم المحل (عربي) <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;color:var(--text-dark);box-sizing:border-box">
                    </div>

                    <div>
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted);">اسم المحل (إنجليزي)</label>
                        <input type="text" name="name_en" value="{{ old('name_en') }}"
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;color:var(--text-dark);box-sizing:border-box">
                    </div>

                    <div>
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted);">Username</label>
                        <div style="position:relative">
                            <span style="position:absolute;right:12px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:15px;">&#64;</span>
                            <input type="text" name="username" value="{{ old('username') }}" placeholder="shop_username"
                                style="width:100%;padding:10px 14px;padding-right:32px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:monospace;font-size:14px;direction:ltr;color:var(--text-dark);box-sizing:border-box">
                        </div>
                        <p style="font-size:12px;color:var(--text-muted);margin-top:4px">اختياري — حروف وأرقام وشرطة سفلية فقط</p>
                    </div>

                    <div>
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted);">رقم الترخيص</label>
                        <input type="text" name="license_number" value="{{ old('license_number') }}"
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;color:var(--text-dark);box-sizing:border-box">
                    </div>

                    <div>
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted);">رقم الهاتف</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;color:var(--text-dark);box-sizing:border-box">
                    </div>

                    <div>
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted);">البريد الإلكتروني</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;color:var(--text-dark);box-sizing:border-box">
                    </div>

                    <div>
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted);">المدينة</label>
                        <input type="text" name="city" value="{{ old('city') }}"
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;color:var(--text-dark);box-sizing:border-box">
                    </div>

                    {{-- منطقة رفع الملف مع بريفيو للصور --}}
                    <div style="grid-column:1/-1">
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted);">ملف مرفق <span style="font-size:12px;color:var(--text-muted);font-weight:400">(اختياري — PDF, صورة, Word)</span></label>

                        {{-- زر الرفع --}}
                        <div id="uploadZone"
                             style="border:2px dashed var(--border);border-radius:10px;padding:20px;text-align:center;background:#fafbfc;cursor:pointer;transition:border-color .2s"
                             onclick="document.getElementById('shopAttachment').click()"
                             ondragover="event.preventDefault();this.style.borderColor='var(--accent)'"
                             ondragleave="this.style.borderColor='var(--border)'"
                             ondrop="handleDrop(event)">
                            <i class="fas fa-cloud-upload-alt" style="font-size:28px;color:var(--text-muted);margin-bottom:8px;display:block"></i>
                            <p style="font-size:13px;color:var(--text-muted);margin:0" id="shopAttachmentLabel">اضغط أو اسحب وأفلت ملف • الحجم الأقصى 5MB</p>
                            <input type="file" id="shopAttachment" name="attachment"
                                   accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                   style="display:none"
                                   onchange="handleFile(this.files[0])">
                        </div>

                        {{-- بريفيو الصورة --}}
                        <div id="imgPreviewWrap" style="display:none;margin-top:12px;position:relative;display:none">
                            <img id="imgPreview"
                                 src=""
                                 alt="معاينة مسبقة"
                                 style="max-width:100%;max-height:300px;border-radius:10px;border:1px solid var(--border);display:block">
                            <button type="button" onclick="clearFile()"
                                    style="position:absolute;top:8px;left:8px;background:rgba(239,68,68,.85);color:#fff;border:none;border-radius:50%;width:30px;height:30px;font-size:16px;cursor:pointer;display:flex;align-items:center;justify-content:center;">
                                &times;
                            </button>
                        </div>

                        {{-- بريفيو ملف غير صورة --}}
                        <div id="filePreviewWrap" style="display:none;margin-top:12px;background:#f8f9fc;border:1px solid var(--border);border-radius:10px;padding:12px 16px;display:none;align-items:center;gap:12px">
                            <i class="fas fa-file-alt" style="font-size:24px;color:var(--accent)"></i>
                            <div style="flex:1">
                                <p id="fileName" style="font-weight:600;font-size:14px;margin:0"></p>
                                <p id="fileSize" style="font-size:12px;color:var(--text-muted);margin:0"></p>
                            </div>
                            <button type="button" onclick="clearFile()"
                                    style="background:rgba(239,68,68,.1);color:#ef4444;border:1px solid rgba(239,68,68,.3);border-radius:8px;padding:6px 12px;cursor:pointer;font-family:Tajawal,sans-serif;font-size:13px">
                                <i class="fas fa-times"></i> حذف
                            </button>
                        </div>

                    </div>

                    <div style="grid-column:1/-1">
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted);">ملاحظة</label>
                        <textarea name="notes" rows="3" placeholder="أي معلومات إضافية..."
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;color:var(--text-dark);resize:vertical;box-sizing:border-box">{{ old('notes') }}</textarea>
                    </div>

                </div>
            </div>
        </div>

        {{-- حساب مدير المحل --}}
        <div class="card" style="margin-bottom:24px;">
            <div class="card-header">
                <h3><i class="fas fa-user-tie" style="color:var(--accent);margin-left:8px;"></i> حساب مدير المحل</h3>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

                    <div>
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted);">اسم المدير <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="admin_name" value="{{ old('admin_name') }}" required
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;color:var(--text-dark);box-sizing:border-box">
                    </div>

                    <div>
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted);">إيميل المدير <span style="color:#ef4444;">*</span></label>
                        <input type="email" name="admin_email" value="{{ old('admin_email') }}" required
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;color:var(--text-dark);box-sizing:border-box">
                    </div>

                    <div style="grid-column:1/-1">
                        <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted);">كلمة المرور <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="admin_password" value="{{ old('admin_password') }}" required
                            style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;color:var(--text-dark);box-sizing:border-box">
                        <p style="font-size:12px;color:var(--text-muted);margin-top:6px;"><i class="fas fa-info-circle"></i> سيستخدم المدير هذه البيانات لتسجيل الدخول.</p>
                    </div>

                </div>
            </div>
        </div>

        <div style="display:flex;gap:12px;">
            <button type="submit" class="btn btn-gold"><i class="fas fa-plus"></i> إنشاء المحل</button>
            <a href="{{ route('superadmin.shops.index') }}" class="btn" style="background:var(--border);color:var(--text-dark);"><i class="fas fa-times"></i> إلغاء</a>
        </div>

    </form>
</div>

<script>
const imageExts = ['jpg','jpeg','png','gif','webp'];

function handleFile(file) {
    if (!file) return;
    const ext = file.name.split('.').pop().toLowerCase();
    document.getElementById('shopAttachmentLabel').textContent = file.name;
    if (imageExts.includes(ext)) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('imgPreview').src = e.target.result;
            document.getElementById('imgPreviewWrap').style.display = 'block';
            document.getElementById('filePreviewWrap').style.display = 'none';
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById('imgPreviewWrap').style.display = 'none';
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('fileSize').textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
        document.getElementById('filePreviewWrap').style.display = 'flex';
    }
}

function clearFile() {
    document.getElementById('shopAttachment').value = '';
    document.getElementById('shopAttachmentLabel').textContent = 'اضغط أو اسحب وأفلت ملف • الحجم الأقصى 5MB';
    document.getElementById('imgPreviewWrap').style.display = 'none';
    document.getElementById('filePreviewWrap').style.display = 'none';
}

function handleDrop(e) {
    e.preventDefault();
    document.getElementById('uploadZone').style.borderColor = 'var(--border)';
    const file = e.dataTransfer.files[0];
    if (!file) return;
    const dt = new DataTransfer();
    dt.items.add(file);
    document.getElementById('shopAttachment').files = dt.files;
    handleFile(file);
}
</script>
@endsection
