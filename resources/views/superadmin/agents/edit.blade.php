@extends('layouts.superadmin')
@section('title', 'تعديل مندوب')
@section('page-title', 'تعديل بيانات المندوب')
@section('content')
<div style="max-width:620px">

    @if($errors->any())
    <div style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);color:#dc2626;padding:12px 16px;border-radius:10px;margin-bottom:20px;font-size:14px">
        @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('superadmin.agents.update', $agent) }}" enctype="multipart/form-data">
    @csrf @method('PUT')

    {{-- بيانات الحساب --}}
    <div class="card" style="margin-bottom:20px">
        <div class="card-header"><h3><i class="fas fa-user-circle" style="color:var(--accent);margin-left:8px"></i> بيانات الحساب</h3></div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div>
                    <label class="sa-label">الاسم الكامل <span style="color:#ef4444">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $agent->name) }}" required class="sa-input">
                </div>
                <div>
                    <label class="sa-label">Username</label>
                    <input type="text" name="username" value="{{ old('username', $agent->username) }}"
                        class="sa-input" style="font-family:monospace;direction:ltr" autocomplete="off">
                </div>
                <div>
                    <label class="sa-label">البريد الإلكتروني <span style="color:#ef4444">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $agent->email) }}" required
                        class="sa-input" style="direction:ltr">
                </div>
                <div>
                    <label class="sa-label">كلمة المرور <span style="font-size:12px;font-weight:400;color:var(--text-muted)">فارغة = بدون تغيير</span></label>
                    <input type="password" name="password" class="sa-input" autocomplete="new-password">
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
                    <input type="text" name="phone" value="{{ old('phone', $agent->phone) }}" placeholder="+968 XXXX XXXX" class="sa-input">
                </div>
                <div>
                    <label class="sa-label">الدولة</label>
                    <input type="text" name="country" value="{{ old('country', $agent->country) }}" placeholder="عمان" class="sa-input">
                </div>
                <div style="grid-column:1/-1">
                    <label class="sa-label">اسم الشركة</label>
                    <input type="text" name="company" value="{{ old('company', $agent->company) }}" placeholder="اسم الشركة أو المؤسسة" class="sa-input">
                </div>

                {{-- ملف مرفق --}}
                <div style="grid-column:1/-1">
                    <label class="sa-label">ملف مرفق <span style="font-size:12px;font-weight:400;color:var(--text-muted)">(PDF, صورة, Word)</span></label>

                    @if(!empty($agent->attachment))
                    @php
                        $ext     = strtolower(pathinfo($agent->attachment, PATHINFO_EXTENSION));
                        $isImg   = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                        $isPdf   = $ext === 'pdf';
                        $fileUrl = asset('storage/' . $agent->attachment);
                        $fileName = basename($agent->attachment);
                    @endphp

                    <div style="border:1px solid var(--border);border-radius:10px;overflow:hidden;background:#f8f9fc">

                        {{-- عرض الصورة مصغرة قابلة للتكبير --}}
                        @if($isImg)
                        <div style="background:#000;text-align:center;cursor:zoom-in" onclick="openLightbox('{{ $fileUrl }}')"
                            title="اضغط للتكبير">
                            <img src="{{ $fileUrl }}" alt="مرفق"
                                style="max-height:160px;width:auto;max-width:100%;object-fit:contain;display:inline-block;opacity:.95;transition:opacity .2s"
                                onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=.95">
                        </div>

                        {{-- PDF inline --}}
                        @elseif($isPdf)
                        <div style="height:200px">
                            <iframe src="{{ $fileUrl }}" style="width:100%;height:100%;border:none;display:block"></iframe>
                        </div>

                        {{-- ملف آخر --}}
                        @else
                        <div style="padding:16px;display:flex;align-items:center;gap:12px">
                            <i class="fas fa-file-alt" style="font-size:30px;color:var(--text-muted)"></i>
                            <div>
                                <div style="font-size:13px;font-weight:600">{{ $fileName }}</div>
                                <div style="font-size:12px;color:var(--text-muted)">ملف مرفق</div>
                            </div>
                        </div>
                        @endif

                        {{-- شريط سفلي --}}
                        <div style="padding:8px 14px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
                            <span style="font-size:12px;color:var(--text-muted);font-family:monospace">{{ $fileName }}</span>
                            <div style="display:flex;gap:12px;align-items:center">
                                @if($isImg)
                                <button type="button" onclick="openLightbox('{{ $fileUrl }}')"
                                    style="background:none;border:none;color:var(--info);font-size:12px;cursor:pointer;padding:0">
                                    <i class="fas fa-expand-alt"></i> تكبير
                                </button>
                                @endif
                                <a href="{{ $fileUrl }}" target="_blank"
                                    style="font-size:12px;color:var(--text-muted);text-decoration:none">
                                    <i class="fas fa-external-link-alt"></i> فتح
                                </a>
                                <label style="font-size:12px;color:var(--accent);cursor:pointer;margin:0">
                                    <i class="fas fa-exchange-alt"></i> تغيير
                                    <input type="file" id="attachmentInput" name="attachment"
                                        accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" style="display:none"
                                        onchange="handleFilePreview(this)">
                                </label>
                            </div>
                        </div>
                    </div>

                    @else
                    {{-- ما في ملف: منطقة رفع --}}
                    <div id="dropZone" onclick="document.getElementById('attachmentInput').click()"
                        style="border:2px dashed var(--border);border-radius:10px;padding:20px;text-align:center;background:#fafbfc;cursor:pointer;transition:border-color .2s">
                        <i class="fas fa-cloud-upload-alt" style="font-size:28px;color:var(--text-muted);margin-bottom:6px;display:block"></i>
                        <p style="font-size:13px;color:var(--text-muted);margin:0" id="attachmentLabel">اضغط لاختيار ملف &bull; الحجم الأقصى 5MB</p>
                        <input type="file" id="attachmentInput" name="attachment"
                            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" style="display:none"
                            onchange="handleFilePreview(this)">
                    </div>
                    @endif

                    {{-- معاينة الملف الجديد --}}
                    <div id="previewArea" style="display:none;margin-top:12px;border:1px solid var(--border);border-radius:10px;overflow:hidden;background:#fff">
                        <div id="previewImage" style="display:none;background:#000;text-align:center">
                            <img id="previewImg" src="" alt="معاينة"
                                style="max-height:200px;width:auto;max-width:100%;object-fit:contain;display:inline-block;cursor:zoom-in"
                                onclick="openLightbox(this.src)">
                        </div>
                        <div id="previewPdf" style="display:none">
                            <iframe id="previewPdfFrame" src="" style="width:100%;height:340px;border:none;display:block"></iframe>
                        </div>
                        <div id="previewOther" style="display:none;padding:14px;align-items:center;gap:12px">
                            <i class="fas fa-file-alt" style="font-size:26px;color:var(--text-muted)"></i>
                            <div>
                                <p id="previewFileName" style="font-weight:600;font-size:14px;margin:0"></p>
                                <p id="previewFileSize" style="font-size:12px;color:var(--text-muted);margin:0"></p>
                            </div>
                        </div>
                        <div style="padding:8px 14px;background:#f8f9fc;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
                            <span id="previewFooterName" style="font-size:12px;color:var(--text-muted);font-family:monospace"></span>
                            <button type="button" onclick="clearPreview()" style="background:none;border:none;color:#ef4444;font-size:12px;cursor:pointer">
                                <i class="fas fa-times"></i> إلغاء
                            </button>
                        </div>
                    </div>
                </div>

                <div style="grid-column:1/-1">
                    <label class="sa-label">ملاحظة</label>
                    <textarea name="notes" rows="3" placeholder="أي معلومات إضافية..."
                        class="sa-input" style="resize:vertical">{{ old('notes', $agent->notes) }}</textarea>
                </div>

            </div>
        </div>
    </div>

    <div style="display:flex;gap:12px">
        <button type="submit" class="btn btn-gold"><i class="fas fa-save"></i> حفظ</button>
        <a href="{{ route('superadmin.agents.index') }}" class="btn" style="background:#f3f4f6;color:#374151">رجوع</a>
    </div>
    </form>
</div>

{{-- Lightbox --}}
<div id="lightbox" onclick="closeLightbox()"
    style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.88);z-index:9999;align-items:center;justify-content:center;cursor:zoom-out">
    <img id="lightboxImg" src="" alt="صورة كاملة"
        style="max-width:92vw;max-height:92vh;object-fit:contain;border-radius:8px;box-shadow:0 8px 40px rgba(0,0,0,.6)">
    <button onclick="closeLightbox()" style="position:fixed;top:18px;left:18px;background:rgba(255,255,255,.15);border:none;color:#fff;width:38px;height:38px;border-radius:50%;font-size:18px;cursor:pointer;backdrop-filter:blur(4px)">
        &times;
    </button>
</div>

<style>
.sa-label { display:block; margin-bottom:6px; font-size:14px; font-weight:600; color:var(--text-muted); }
.sa-input  { width:100%; padding:10px 14px; background:#f8f9fc; border:1.5px solid var(--border); border-radius:8px; font-family:Tajawal,sans-serif; font-size:14px; box-sizing:border-box; transition:border-color .2s; }
.sa-input:focus { outline:none; border-color:var(--accent); background:#fff; }
#lightbox { display:none; }
#lightbox.open { display:flex; }
</style>

<script>
function openLightbox(src) {
    document.getElementById('lightboxImg').src = src;
    document.getElementById('lightbox').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    document.getElementById('lightbox').classList.remove('open');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });

function handleFilePreview(input) {
    const file = input.files[0];
    if (!file) return;
    const name = file.name;
    const ext  = name.split('.').pop().toLowerCase();
    const size = (file.size / 1024).toFixed(0) + ' KB';
    const isImage = ['jpg','jpeg','png','gif','webp'].includes(ext);
    const isPdf   = ext === 'pdf';
    const lbl = document.getElementById('attachmentLabel');
    if (lbl) lbl.textContent = name;
    document.getElementById('previewImage').style.display = 'none';
    document.getElementById('previewPdf').style.display   = 'none';
    document.getElementById('previewOther').style.display = 'none';
    document.getElementById('previewArea').style.display  = 'block';
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
    const lbl = document.getElementById('attachmentLabel');
    if (lbl) lbl.textContent = 'اضغط لاختيار ملف • الحجم الأقصى 5MB';
    document.getElementById('previewArea').style.display = 'none';
    document.getElementById('previewImg').src = '';
    document.getElementById('previewPdfFrame').src = '';
}
</script>
@endsection
