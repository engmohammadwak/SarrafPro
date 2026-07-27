@extends('layouts.admin')
@section('title', 'تعديل مندوب')
@section('page-title', 'تعديل بيانات المندوب')
@section('content')
<div style="max-width:600px">

    @if($errors->any())
    <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#ef4444;padding:14px;border-radius:12px;margin-bottom:20px">
        <ul style="margin:0;padding-right:18px">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form action="{{ route('admin.agents.update',$agent) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="card">
            <div class="card-header"><h3><i class="fas fa-edit" style="color:var(--accent);margin-left:8px"></i> {{ $agent->name }}</h3></div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">

                    <div><label class="e-lbl">الاسم</label>
                    <input type="text" name="name" value="{{ old('name',$agent->name) }}" required class="e-inp"></div>

                    <div><label class="e-lbl">الهاتف</label>
                    <input type="text" name="phone" value="{{ old('phone',$agent->phone) }}" class="e-inp"></div>

                    <div><label class="e-lbl">هاتف ثاني</label>
                    <input type="text" name="phone2" value="{{ old('phone2',$agent->phone2) }}" class="e-inp"></div>

                    <div><label class="e-lbl">الدولة</label>
                    <input type="text" name="country" value="{{ old('country',$agent->country) }}" class="e-inp"></div>

                    <div><label class="e-lbl">الشركة</label>
                    <input type="text" name="company" value="{{ old('company',$agent->company) }}" class="e-inp"></div>

                    {{-- ملف مرفق --}}
                    <div style="grid-column:1/-1">
                        <label class="e-lbl">
                            <i class="fas fa-paperclip" style="color:var(--info);font-size:12px;margin-left:4px"></i>
                            ملف مرفق <span style="font-size:12px;color:var(--text-muted)">اختياري &mdash; PDF, صورة, Word (5MB كحد أقصى)</span>
                        </label>

                        @if($agent->attachment)
                        {{-- عرض الملف الحالي --}}
                        <div id="currentFile" style="display:flex;align-items:center;justify-content:space-between;gap:10px;padding:12px 14px;background:rgba(59,130,246,0.07);border:1.5px solid rgba(59,130,246,0.25);border-radius:10px;margin-bottom:10px">
                            <div style="display:flex;align-items:center;gap:10px">
                                @php
                                    $ext = strtolower(pathinfo($agent->attachment, PATHINFO_EXTENSION));
                                    $icon = in_array($ext, ['jpg','jpeg','png','gif','webp']) ? 'fa-file-image' :
                                            ($ext === 'pdf' ? 'fa-file-pdf' :
                                            (in_array($ext, ['doc','docx']) ? 'fa-file-word' : 'fa-file'));
                                    $color = in_array($ext, ['jpg','jpeg','png','gif','webp']) ? 'var(--success)' :
                                             ($ext === 'pdf' ? '#ef4444' :
                                             (in_array($ext, ['doc','docx']) ? 'var(--info)' : 'var(--text-muted)'));
                                @endphp
                                <i class="fas {{ $icon }}" style="font-size:24px;color:{{ $color }}"></i>
                                <div>
                                    <div style="font-size:14px;font-weight:600;color:var(--text-dark)">{{ basename($agent->attachment) }}</div>
                                    <a href="{{ asset('storage/'.$agent->attachment) }}" target="_blank" style="font-size:12px;color:var(--info);text-decoration:none">
                                        <i class="fas fa-external-link-alt" style="font-size:10px"></i> فتح / تحميل
                                    </a>
                                </div>
                            </div>
                            <button type="button" onclick="confirmDeleteFile()" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#ef4444;padding:6px 12px;border-radius:8px;cursor:pointer;font-size:13px;font-family:Tajawal,sans-serif">
                                <i class="fas fa-trash-alt"></i> حذف
                            </button>
                        </div>
                        <input type="hidden" name="delete_attachment" id="deleteAttachment" value="0">
                        @endif

                        {{-- رفع ملف جديد --}}
                        <label id="fileDropArea" style="display:flex;align-items:center;gap:12px;padding:12px 16px;border:2px dashed var(--border);border-radius:10px;cursor:pointer;transition:border-color .2s;background:#fafafa">
                            <input type="file" name="attachment" id="attachmentInput" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" style="display:none" onchange="onFileChange(this)">
                            <i class="fas fa-cloud-upload-alt" style="font-size:20px;color:var(--text-muted)"></i>
                            <div>
                                <div id="fileLabel" style="font-size:13px;color:var(--text-dark);font-weight:600">{{ $agent->attachment ? 'استبدال الملف بآخر' : 'اضغط لاختيار ملف أو اسحبه هنا' }}</div>
                                <div style="font-size:12px;color:var(--text-muted)">يدعم PDF و JPG و PNG و Word</div>
                            </div>
                        </label>
                    </div>

                </div>
            </div>
        </div>

        <div style="margin-top:16px;display:flex;gap:12px">
            <button type="submit" class="btn btn-gold"><i class="fas fa-save"></i> حفظ</button>
            <a href="{{ route('admin.agents.index') }}" class="btn" style="background:var(--border);color:var(--text-dark)"><i class="fas fa-times"></i> إلغاء</a>
        </div>
    </form>
</div>

<style>
.e-lbl{display:block;margin-bottom:6px;font-size:14px;font-weight:600;color:var(--text-muted)}
.e-inp{width:100%;padding:10px 14px;background:#f8f9fc;border:1.5px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;color:var(--text-dark);transition:border-color .2s;box-sizing:border-box}
.e-inp:focus{outline:none;border-color:var(--accent);background:#fff}
#fileDropArea:hover{border-color:var(--accent)}
</style>

@push('scripts')
<script>
function onFileChange(input){
    const label=document.getElementById('fileLabel');
    if(input.files&&input.files[0]){
        const f=input.files[0];
        const size=(f.size/1024/1024).toFixed(2);
        label.innerHTML=`<i class="fas fa-file" style="color:var(--info)"></i> ${f.name} <span style="color:var(--text-muted);font-size:12px">(${size} MB)</span>`;
    } else {
        label.textContent='اضغط لاختيار ملف أو اسحبه هنا';
    }
}

function confirmDeleteFile(){
    if(!confirm('هل أنت متأكد من حذف الملف المرفق؟')) return;
    document.getElementById('deleteAttachment').value='1';
    const cf=document.getElementById('currentFile');
    if(cf) cf.style.display='none';
    document.getElementById('fileLabel').textContent='تم تحديد حذف الملف — احفظ لتأكيد';
    document.getElementById('fileDropArea').style.borderColor='#ef4444';
}
</script>
@endpush
@endsection
