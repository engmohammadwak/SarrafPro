@extends('layouts.admin')
@section('title', 'تعديل مندوب')
@section('page-title', 'تعديل بيانات المندوب')
@section('content')
<div style="max-width:700px">

    @if($errors->any())
    <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#ef4444;padding:14px;border-radius:12px;margin-bottom:20px">
        <ul style="margin:0;padding-right:18px">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form action="{{ route('admin.agents.update',$agent) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        {{-- ======================================================
             قسم ربط الحساب — يظهر فقط لو user_id فاضي
        ======================================================= --}}
        @if(!$agent->user_id)
        <div class="card" style="margin-bottom:20px;border:2px dashed #c9a84c55">
            <div class="card-header" style="background:rgba(201,168,76,0.06)">
                <h3><i class="fas fa-user-plus" style="color:var(--accent);margin-left:8px"></i> ربط حساب بهذا المندوب</h3>
                <span style="font-size:12px;color:var(--text-muted)">المندوب حالياً بدون حساب مرتبط</span>
            </div>
            <div class="card-body">

                {{-- تبويبات --}}
                <div style="display:flex;gap:0;margin-bottom:20px;border:1.5px solid var(--border);border-radius:10px;overflow:hidden">
                    <button type="button" id="tab-existing" onclick="switchLinkTab('existing')"
                        style="flex:1;padding:10px;font-family:Tajawal,sans-serif;font-size:14px;font-weight:700;cursor:pointer;border:none;background:#c9a84c;color:#1a1f3c;transition:all .2s">
                        <i class="fas fa-search"></i> ربط حساب موجود
                    </button>
                    <button type="button" id="tab-create" onclick="switchLinkTab('create')"
                        style="flex:1;padding:10px;font-family:Tajawal,sans-serif;font-size:14px;font-weight:700;cursor:pointer;border:none;background:#f4f6fb;color:var(--text-muted);transition:all .2s">
                        <i class="fas fa-user-plus"></i> إنشاء حساب جديد
                    </button>
                </div>

                {{-- تبويب: حساب موجود --}}
                <div id="panel-existing">
                    <div style="display:flex;gap:10px;margin-bottom:12px">
                        <input type="text" id="searchUsername" placeholder="ابحث بالاسم أو الإيميل أو اسم المستخدم..."
                            style="flex:1;padding:10px 14px;background:#f8f9fc;border:1.5px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px">
                        <button type="button" onclick="doSearch()"
                            style="padding:10px 20px;background:var(--accent);color:var(--primary);border:none;border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;font-weight:700;cursor:pointer">
                            <i class="fas fa-search"></i> بحث
                        </button>
                    </div>
                    <div id="searchResult" style="display:none;padding:14px;background:#f0fdf4;border:1.5px solid #10b98133;border-radius:10px">
                        <div style="display:flex;align-items:center;justify-content:space-between">
                            <div>
                                <p style="font-weight:700;font-size:15px;margin-bottom:2px" id="res-name"></p>
                                <p style="font-size:12px;color:var(--text-muted)" id="res-email"></p>
                            </div>
                            <button type="button" onclick="selectUser()"
                                style="padding:8px 18px;background:#10b981;color:#fff;border:none;border-radius:8px;font-family:Tajawal,sans-serif;font-size:13px;font-weight:700;cursor:pointer">
                                <i class="fas fa-check"></i> اختر
                            </button>
                        </div>
                    </div>
                    <div id="searchError" style="display:none;padding:10px 14px;background:#fef2f2;border:1.5px solid #ef444433;border-radius:10px;color:#ef4444;font-size:13px"></div>
                    <div id="selectedUser" style="display:none;padding:12px 16px;background:#ecfdf5;border:1.5px solid #10b98133;border-radius:10px;margin-top:8px">
                        <i class="fas fa-user-check" style="color:#10b981;margin-left:8px"></i>
                        <strong id="sel-name"></strong>
                        <span style="font-size:12px;color:var(--text-muted)" id="sel-email"></span>
                        <button type="button" onclick="clearSelection()" style="float:left;background:none;border:none;color:#ef4444;cursor:pointer;font-size:12px">
                            <i class="fas fa-times"></i> إلغاء
                        </button>
                    </div>
                    <input type="hidden" name="assign_user_id" id="assign_user_id" value="">
                </div>

                {{-- تبويب: إنشاء حساب جديد --}}
                <div id="panel-create" style="display:none">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                        <div>
                            <label class="e-lbl">البريد الإلكتروني <span style="color:#ef4444">*</span></label>
                            <input type="email" name="new_email" value="{{ old('new_email') }}" class="e-inp" placeholder="example@mail.com">
                        </div>
                        <div>
                            <label class="e-lbl">كلمة المرور <span style="color:#ef4444">*</span></label>
                            <input type="password" name="new_password" class="e-inp" placeholder="8 أحرف على الأقل">
                        </div>
                    </div>
                    <p style="font-size:12px;color:var(--text-muted);margin-top:10px">
                        <i class="fas fa-info-circle" style="color:var(--info)"></i>
                        سيُنشأ حساب جديد باسم المندوب ويُربط تلقائياً بحالة <strong>موافق عليه</strong>.
                    </p>
                </div>

                <input type="hidden" name="link_type" id="link_type_field" value="none">

            </div>
        </div>
        @else
        {{-- لو في حساب مرتبط — عرض فقط --}}
        <div class="card" style="margin-bottom:20px">
            <div class="card-header">
                <h3><i class="fas fa-user-check" style="color:var(--success);margin-left:8px"></i> الحساب المرتبط</h3>
                @if($agent->link_status === 'unlink_pending')
                    <span style="font-size:12px;background:#fef3c7;color:#b45309;padding:4px 10px;border-radius:20px;font-weight:700">
                        <i class="fas fa-hourglass-half"></i> بانتظار تسوية الرصيد
                    </span>
                @elseif($agent->link_status === 'approved')
                    <span class="badge badge-success"><i class="fas fa-link"></i> مرتبط ونشط</span>
                @elseif($agent->link_status === 'pending')
                    <span class="badge badge-warning"><i class="fas fa-clock"></i> بانتظار الموافقة</span>
                @endif
            </div>
            <div class="card-body" style="padding:16px 24px">
                <div style="display:flex;align-items:center;gap:14px">
                    <div style="width:44px;height:44px;background:rgba(16,185,129,0.12);border-radius:12px;display:flex;align-items:center;justify-content:center">
                        <i class="fas fa-user" style="color:#10b981;font-size:18px"></i>
                    </div>
                    <div>
                        <p style="font-weight:700;font-size:15px;margin-bottom:2px">{{ $agent->user->name ?? '-' }}</p>
                        <p style="font-size:13px;color:var(--text-muted)">{{ $agent->user->email ?? '' }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- ====================================================
             بيانات المندوب الأساسية
        ===================================================== --}}
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

                    {{-- حالة التفعيل --}}
                    <div style="grid-column:1/-1">
                        <label class="e-lbl">حالة المندوب</label>
                        <div style="display:flex;align-items:center;gap:14px;padding:12px 16px;background:#f8f9fc;border:1.5px solid var(--border);border-radius:8px">
                            <label class="toggle-switch" style="position:relative;display:inline-block;width:46px;height:26px;flex-shrink:0">
                                <input type="checkbox" name="is_active" value="1" id="isActiveToggle"
                                    {{ old('is_active', $agent->is_active) ? 'checked' : '' }}
                                    onchange="updateToggleLabel(this)">
                                <span class="toggle-slider"></span>
                            </label>
                            <span id="toggleLabel" style="font-size:14px;font-weight:600"
                                  style="color:{{ $agent->is_active ? '#15803d' : '#6b7280' }}">
                                {{ old('is_active', $agent->is_active) ? 'شغال' : 'معطل' }}
                            </span>
                        </div>
                    </div>

                    {{-- ملف مرفق --}}
                    <div style="grid-column:1/-1">
                        <label class="e-lbl">
                            <i class="fas fa-paperclip" style="color:var(--info);font-size:12px;margin-left:4px"></i>
                            ملف مرفق <span style="font-size:12px;color:var(--text-muted)">اختياري &mdash; PDF, صورة, Word (5MB كحد أقصى)</span>
                        </label>

                        @if($agent->attachment)
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
.toggle-switch input{opacity:0;width:0;height:0}
.toggle-slider{
    position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;
    background:#d1d5db;border-radius:26px;transition:.3s;
}
.toggle-slider:before{
    position:absolute;content:"";height:20px;width:20px;left:3px;bottom:3px;
    background:white;border-radius:50%;transition:.3s;
}
.toggle-switch input:checked + .toggle-slider{background:#16a34a}
.toggle-switch input:checked + .toggle-slider:before{transform:translateX(20px)}
</style>

@push('scripts')
<script>
// ============ Toggle ============
function updateToggleLabel(cb){
    const lbl=document.getElementById('toggleLabel');
    lbl.textContent=cb.checked?'شغال':'معطل';
    lbl.style.color=cb.checked?'#15803d':'#6b7280';
}
function onFileChange(input){
    const label=document.getElementById('fileLabel');
    if(input.files&&input.files[0]){
        const f=input.files[0];
        label.innerHTML=`<i class="fas fa-file" style="color:var(--info)"></i> ${f.name} <span style="color:var(--text-muted);font-size:12px">(${(f.size/1024/1024).toFixed(2)} MB)</span>`;
    } else { label.textContent='اضغط لاختيار ملف أو اسحبه هنا'; }
}
function confirmDeleteFile(){
    if(!confirm('هل أنت متأكد من حذف الملف المرفق؟')) return;
    document.getElementById('deleteAttachment').value='1';
    const cf=document.getElementById('currentFile');
    if(cf) cf.style.display='none';
    document.getElementById('fileLabel').textContent='تم تحديد حذف الملف — احفظ لتأكيد';
    document.getElementById('fileDropArea').style.borderColor='#ef4444';
}

// ============ Link Tab ============
@if(!$agent->user_id)
let _foundUser = null;

function switchLinkTab(tab) {
    const isExisting = tab === 'existing';
    document.getElementById('panel-existing').style.display = isExisting ? 'block' : 'none';
    document.getElementById('panel-create').style.display  = isExisting ? 'none'  : 'block';
    document.getElementById('tab-existing').style.background = isExisting ? '#c9a84c' : '#f4f6fb';
    document.getElementById('tab-existing').style.color      = isExisting ? '#1a1f3c' : 'var(--text-muted)';
    document.getElementById('tab-create').style.background   = isExisting ? '#f4f6fb' : '#c9a84c';
    document.getElementById('tab-create').style.color        = isExisting ? 'var(--text-muted)' : '#1a1f3c';
    // إعادة ضبط link_type
    document.getElementById('link_type_field').value = tab === 'existing' ? 'none' : 'create';
}

function doSearch() {
    const q = document.getElementById('searchUsername').value.trim();
    if (!q) return;
    const resBox = document.getElementById('searchResult');
    const errBox = document.getElementById('searchError');
    resBox.style.display = 'none';
    errBox.style.display = 'none';
    _foundUser = null;

    fetch('{{ route("admin.agents.check-user") }}?username=' + encodeURIComponent(q), {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.found) {
            _foundUser = data;
            document.getElementById('res-name').textContent  = data.name + ' (@' + data.username + ')';
            document.getElementById('res-email').textContent = data.email;
            resBox.style.display = 'block';
        } else {
            errBox.textContent   = data.message || 'لم يتم العثور على الحساب';
            errBox.style.display = 'block';
        }
    })
    .catch(() => { errBox.textContent = 'حدث خطأ أثناء البحث'; errBox.style.display='block'; });
}

function selectUser() {
    if (!_foundUser) return;
    document.getElementById('assign_user_id').value = _foundUser.user_id;
    document.getElementById('link_type_field').value = 'existing';
    document.getElementById('sel-name').textContent  = _foundUser.name + ' (@' + _foundUser.username + ')';
    document.getElementById('sel-email').textContent = ' — ' + _foundUser.email;
    document.getElementById('searchResult').style.display  = 'none';
    document.getElementById('selectedUser').style.display  = 'block';
}

function clearSelection() {
    document.getElementById('assign_user_id').value = '';
    document.getElementById('link_type_field').value = 'none';
    document.getElementById('selectedUser').style.display = 'none';
}

// البحث بالضغط على Enter
document.getElementById('searchUsername').addEventListener('keydown', function(e){
    if (e.key === 'Enter') { e.preventDefault(); doSearch(); }
});
@endif
</script>
@endpush
@endsection
