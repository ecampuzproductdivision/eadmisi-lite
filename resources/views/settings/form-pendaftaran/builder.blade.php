@extends('layouts.app')

@section('content')
<main class="p-6">
  <div class="row justify-content-center">
    <div class="col-lg-12">

      <!-- Header -->
      <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
          <h2 class="fw-bold mb-1">
            <i class="ti ti-layout-board me-2 text-primary"></i>Atur Field Form
            <span class="badge bg-primary-subtle text-primary fs-6 align-middle ms-2">{{ $form->nama }}</span>
          </h2>
          <p class="text-muted mb-0">Atur field-form secara fleksibel dengan drag & drop.</p>
        </div>
        <div class="d-flex gap-2">
          <a href="{{ route('settings.form-pendaftaran.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i> Kembali
          </a>
          <button type="button" class="btn btn-success" onclick="saveFieldOrder()">
            <i class="ti ti-device-floppy me-1"></i> Simpan Urutan
          </button>
          <button type="button" class="btn btn-outline-info" onclick="previewForm()">
            <i class="ti ti-eye me-1"></i> Preview
          </button>
        </div>
      </div>

      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="ti ti-circle-check fs-4 me-2"></i>
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <div class="row g-4">
        <!-- Left: Field Palette with Accordion -->
        <div class="col-lg-3">
          <div class="card border-1 shadow-sm sticky-top" style="top: 80px;">
            <div class="card-header bg-light py-3">
              <h6 class="fw-bold mb-0"><i class="ti ti-box me-2"></i>Tambah Field</h6>
            </div>
            <div class="card-body p-3">
              <div class="accordion" id="fieldAccordion">
                <!-- Card 1: Generic Fields -->
                <div class="accordion-item border-0 mb-2">
                  <h2 class="accordion-header">
                    <button class="accordion-button fw-semibold py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#genericFields" aria-expanded="true">
                      <i class="ti ti-settings me-2"></i> Komponen Dasar (Generic)
                    </button>
                  </h2>
                  <div id="genericFields" class="accordion-collapse collapse show" data-bs-parent="#fieldAccordion">
                    <div class="accordion-body p-2">
                      <div class="d-grid gap-2">
                        @foreach($fieldTypes as $type => $config)
                        <button type="button" class="btn btn-outline-secondary text-start d-flex align-items-center gap-2 py-2 field-type-btn"
                          style="border-style: dashed; font-size: 0.82rem;"
                          onclick="openAddModal('{{ $type }}')">
                          <span class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px; background: {{ $config['color'] }}15;">
                            <i class="{{ $config['icon'] }} small" style="color: {{ $config['color'] }}"></i>
                          </span>
                          <span>{{ $config['label'] }}</span>
                        </button>
                        @endforeach
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Card 2: PDDIKTI Standard Fields -->
                <div class="accordion-item border-0">
                  <h2 class="accordion-header">
                    <button class="accordion-button fw-semibold py-2 px-3 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pddiktiFields" aria-expanded="false">
                      <i class="ti ti-gift me-2" style="color: #dc3545;"></i> Standar PDDIKTI (Siap Pakai)
                    </button>
                  </h2>
                  <div id="pddiktiFields" class="accordion-collapse collapse" data-bs-parent="#fieldAccordion">
                    <div class="accordion-body p-2">
                      <p class="text-muted small mb-2"><i class="ti ti-info-circle me-1"></i>Klik untuk menambahkan field standar PDDIKTI:</p>
                      <div class="d-grid gap-2">
                        @foreach(\App\Models\FormField::PDDIKTI_STANDARD_FIELDS as $pddiktiKey => $pddiktiConfig)
                        <button type="button" class="btn btn-outline-danger text-start d-flex align-items-center gap-2 py-2 field-type-btn pddikti-btn"
                          style="border-style: dashed; font-size: 0.82rem; border-color: #dc3545 !important;"
                          onclick="addPddiktiField('{{ $pddiktiKey }}')">
                          <span class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px; background: #dc354515;">
                            <i class="ti ti-shield-check small" style="color: #dc3545;"></i>
                          </span>
                          <span>{{ $pddiktiConfig['field_label'] }}</span>
                        </button>
                        @endforeach
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <hr>
              <small class="text-muted"><i class="ti ti-arrows-move me-1"></i>Drag & drop untuk mengubah urutan</small>
            </div>
          </div>
        </div>

        <!-- Right: Form Canvas -->
        <div class="col-lg-9">
          <!-- Form Title Card -->
          <div class="card border-1 shadow-sm mb-4" style="border-top: 8px solid #f63a4c !important; border-radius: 12px;">
            <div class="card-body p-4">
              <div class="d-flex align-items-start gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                  <i class="ti ti-file-text text-primary fs-4"></i>
                </div>
                <div class="flex-grow-1">
                  <h4 class="fw-bold mb-1">{{ $form->nama }}</h4>
                  <p class="text-muted mb-0">{{ $form->deskripsi ?: 'Formulir pendaftaran mahasiswa baru.' }}</p>
                  <small class="text-muted"><i class="ti ti-drag-drop me-1"></i>Drag & drop field untuk mengubah urutan</small>
                </div>
                <div class="flex-shrink-0 text-end">
                  <div class="badge bg-info-subtle text-info px-3 py-2" id="fieldCount">{{ $form->fields->count() }} field</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Empty State -->
          <div id="noFields" class="text-center py-5 {{ $fields->isNotEmpty() ? 'd-none' : '' }}">
            <div class="mb-4">
              <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                <i class="ti ti-file-plus text-muted" style="font-size: 2.5rem;"></i>
              </div>
            </div>
            <h5 class="fw-bold text-muted mb-2">Form Masih Kosong</h5>
            <p class="text-muted mb-0">Klik tipe field di samping untuk mulai menambahkan field.</p>
          </div>

          <!-- Canvas -->
          <div id="formCanvas" class="{{ $fields->isEmpty() ? 'd-none' : '' }}">
            @php
              $pddiktiNames = \App\Models\FormField::pddiktiFieldNames();
              $coreNames = \App\Models\FormField::coreFieldNames();
              // Fields that exist in BOTH core AND PDDIKTI (double-badge candidates)
              $intersectNames = array_intersect($coreNames, $pddiktiNames);
            @endphp
            <div class="sortable-fields" id="masterSortable">
              @forelse($fields as $sectionName => $sectionFields)
                @foreach($sectionFields as $field)
                @php
                  $isPddikti = in_array($field->field_name, $pddiktiNames);
                  $isCore = in_array($field->field_name, $coreNames);
                  $isSystemLocked = $field->is_system;
                  $isDualBadge = $isSystemLocked && $isPddikti;
                @endphp
                <div class="field-item card border-1 shadow-sm mb-2" data-field-id="{{ $field->id }}" data-sort="{{ $field->sort_order }}" style="border-radius: 8px; {{ !$field->is_active ? 'opacity: 0.5;' : '' }}">
                  <div class="card-body py-3 px-4">
                    <div class="d-flex align-items-center gap-3">
                      <div class="drag-handle text-muted" title="Drag to reorder"><i class="ti ti-grip-vertical fs-5"></i></div>
                      <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2">
                          <span class="d-inline-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 24px; height: 24px; background: {{ $fieldTypes[$field->field_type]['color'] ?? '#6c757d' }}15;">
                            <i class="{{ $fieldTypes[$field->field_type]['icon'] ?? 'ti-input' }}" style="font-size: 0.75rem; color: {{ $fieldTypes[$field->field_type]['color'] ?? '#6c757d' }}"></i>
                          </span>
                          <span class="fw-semibold" style="font-size: 0.9rem;">
                            {{ $field->field_label }}
                            @if($field->is_required)<span class="text-danger">*</span>@endif
                          </span>
                          @if($isDualBadge)
                            <span class="badge bg-primary-subtle text-primary px-2" style="font-size: 0.6rem;"><i class="ti ti-shield-check me-1"></i>Sistem</span>
                            <span class="badge" style="font-size: 0.6rem; background: #fce4ec; color: #c62828; border: 1px solid #ef9a9a;"><i class="ti ti-file-text me-1"></i>PDDIKTI</span>
                          @elseif($isSystemLocked && !$isPddikti)
                            <span class="badge bg-primary-subtle text-primary px-2" style="font-size: 0.6rem;"><i class="ti ti-shield-check me-1"></i>Sistem</span>
                          @elseif(!$isSystemLocked && $isPddikti)
                            <span class="badge" style="font-size: 0.6rem; background: #fff3e0; color: #e65100; border: 1px solid #ffcc80;"><i class="ti ti-gift me-1"></i>PDDIKTI</span>
                          @endif
                          @if(!$field->is_active)<span class="badge bg-warning-subtle text-warning" style="font-size: 0.6rem;">Disabled</span>@endif
                        </div>
                        <div class="d-flex align-items-center gap-2 mt-1 ms-4">
                          <code class="small text-muted" style="font-size: 0.65rem;">{{ $field->field_name }}</code>
                          <span class="badge bg-light text-muted" style="font-size: 0.6rem;">{{ $field->field_type }}</span>
                          @if(in_array($field->field_type, ['select','radio','checkbox']))
                            <span class="text-muted" style="font-size: 0.65rem;">{{ count($field->options ?? []) }} ops</span>
                          @endif
                        </div>
                      </div>
                      <div class="d-flex gap-1 flex-shrink-0">
                        @if($isSystemLocked)
                          <span class="badge bg-primary-subtle text-primary px-3 py-2" title="Field sistem tidak dapat diubah"><i class="ti ti-lock me-1"></i>System</span>
                        @elseif($isPddikti && !$isSystemLocked)
                          {{-- PDDIKTI non-locked: only trash button --}}
                          <button type="button" class="btn btn-sm btn-outline-danger border-0" title="Hapus" onclick="deleteField({{ $field->id }})"><i class="ti ti-trash"></i></button>
                        @else
                          <button type="button" class="btn btn-sm btn-outline-info border-0" title="Edit" onclick="openEditModal({{ $field->id }})"><i class="ti ti-edit"></i></button>
                          <button type="button" class="btn btn-sm btn-outline-success border-0" title="Duplikat" onclick="duplicateField({{ $field->id }})"><i class="ti ti-copy"></i></button>
                          <button type="button" class="btn btn-sm btn-outline-{{ $field->is_active ? 'warning' : 'success' }} border-0" onclick="toggleField({{ $field->id }})"><i class="ti ti-{{ $field->is_active ? 'eye-off' : 'eye' }}"></i></button>
                          <button type="button" class="btn btn-sm btn-outline-danger border-0" title="Hapus" onclick="deleteField({{ $field->id }})"><i class="ti ti-trash"></i></button>
                        @endif
                      </div>
                    </div>
                  </div>
                  @if($field->help_text)
                  <div class="card-footer bg-white border-top-0 pt-0 px-4 pb-2">
                    <small class="text-muted"><i class="ti ti-info-circle me-1"></i>{{ $field->help_text }}</small>
                  </div>
                  @endif
                </div>
                @endforeach
              @empty
              @endforelse
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- Add/Edit Field Modal -->
<div class="modal fade" id="fieldModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <h5 class="modal-title fw-bold text-white" id="fieldModalLabel"><i class="ti ti-plus me-2"></i>Tambah Field</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="fieldForm">
        @csrf
        <input type="hidden" name="form_id" value="{{ $form->id }}">
        <input type="hidden" name="field_type" id="field_type_input" value="text">
        <input type="hidden" name="_method" id="field_method" value="POST">
        <input type="hidden" name="field_id" id="field_id" value="">
        <input type="hidden" name="is_system" id="field_is_system" value="0">

        <div class="modal-body p-4" style="max-height: 60vh; overflow-y: auto;">
          <div class="row g-3">
            <div class="col-12">
              <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3">
                <div id="fieldTypeIconDisplay" class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #4f46e515;">
                  <i class="ti ti-input fs-4" style="color: #4f46e5;"></i>
                </div>
                <div>
                  <span class="fw-semibold" id="fieldTypeLabelDisplay">Text (Short)</span><br>
                  <small class="text-muted">Type: <span id="fieldTypeNameDisplay">text</span></small>
                </div>
              </div>
            </div>
            <div class="col-md-8">
              <label class="form-label fw-semibold">Judul Field <span class="text-danger">*</span></label>
              <input type="text" name="field_label" id="field_label" class="form-control form-control-lg" placeholder="Masukkan judul pertanyaan" required>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Field Name</label>
              <input type="text" name="field_name" id="field_name" class="form-control font-monospace" placeholder="snake_case" required>
              <small class="text-muted">Auto dari judul</small>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Placeholder</label>
              <input type="text" name="placeholder" id="field_placeholder" class="form-control" placeholder="Text petunjuk">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Nilai Default</label>
              <input type="text" name="default_value" id="field_default_value" class="form-control" placeholder="Nilai awal">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Bagian / Grup</label>
              <select name="section" id="field_section" class="form-select">
                <option value="">Tanpa Grup</option>
                @foreach($sections as $section)
                  <option value="{{ $section }}">{{ $section }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Lebar Kolom</label>
              <select name="width" id="field_width" class="form-select">
                @foreach($widthOptions as $val => $label)
                  <option value="{{ $val }}" {{ $val === 'col-12' ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-12 d-none" id="optionsContainer">
              <label class="form-label fw-semibold">Pilihan Opsi <span class="text-danger">*</span></label>
              <div id="optionsList">
                <div class="input-group mb-2 option-item">
                  <span class="input-group-text bg-white"><i class="ti ti-grip-vertical text-muted"></i></span>
                  <input type="text" name="options[]" class="form-control" placeholder="Opsi 1">
                  <button type="button" class="btn btn-outline-danger" onclick="removeOption(this)"><i class="ti ti-x"></i></button>
                </div>
              </div>
              <button type="button" class="btn btn-sm btn-outline-primary mt-1" onclick="addOption()"><i class="ti ti-plus me-1"></i> Tambah Opsi</button>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Teks Bantuan</label>
              <textarea name="help_text" id="field_help_text" class="form-control" rows="2" placeholder="Teks bantuan"></textarea>
            </div>
            <div class="col-12">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_required" id="field_required" value="1" checked>
                <label class="form-check-label fw-semibold" for="field_required">Wajib diisi</label>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary fw-semibold px-4" id="fieldSubmitBtn"><i class="ti ti-device-floppy me-1"></i> Simpan Field</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <h5 class="modal-title fw-bold text-white"><i class="ti ti-eye me-2"></i>Preview: {{ $form->nama }}</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4" id="previewBody">
        <div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-2 text-muted">Memuat preview...</p></div>
      </div>
    </div>
  </div>
</div>

<style>
.field-item { transition: all 0.2s ease; cursor: default; }
.field-item:hover { box-shadow: 0 2px 12px rgba(0,0,0,0.08) !important; }
.field-item .drag-handle { cursor: grab; opacity: 0.3; transition: opacity 0.2s; }
.field-item:hover .drag-handle { opacity: 0.8; }
.field-item.sortable-chosen { box-shadow: 0 4px 20px rgba(102,126,234,0.2) !important; border-left: 3px solid #667eea !important; }
.field-item.sortable-ghost { opacity: 0.3; background: #f0f4ff; }
.sortable-fields { min-height: 40px; }
.field-type-btn:hover { border-color: #667eea !important; background: #f0f4ff !important; }
.field-type-btn.pddikti-btn:hover { border-color: #dc3545 !important; background: #fff5f5 !important; }
.accordion-button:not(.collapsed) { background: transparent; box-shadow: none; }
.accordion-button:focus { box-shadow: none; }
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
const fieldTypes = @json($fieldTypes);
const formId = {{ $form->id }};
let editingFieldId = null;

document.addEventListener('DOMContentLoaded', function() {
  // Single unified sortable - all fields mix freely
  new Sortable(document.getElementById('masterSortable'), {
    group: 'fields',
    animation: 200,
    handle: '.drag-handle',
    ghostClass: 'sortable-ghost',
    chosenClass: 'sortable-chosen',
    onEnd: updateFieldCount
  });
  updateFieldCount();

  document.getElementById('field_label').addEventListener('input', function() {
    const n = document.getElementById('field_name');
    if (!n.dataset.manual) n.value = this.value.toLowerCase().replace(/[^a-z0-9]+/g,'_').replace(/^_|_$/g,'').substring(0,100);
  });
  document.getElementById('field_name').addEventListener('input', function() { this.dataset.manual = this.value.length > 0 ? '1' : ''; });
});

function updateFieldCount() {
  const c = document.querySelectorAll('.field-item').length;
  document.getElementById('fieldCount').textContent = c + ' field';
  document.getElementById('noFields')?.classList.toggle('d-none', c > 0);
  document.getElementById('formCanvas')?.classList.toggle('d-none', c === 0);
}

function getFieldOrder() {
  const fields = []; let o = 1;
  document.querySelectorAll('#masterSortable > .field-item').forEach(i => fields.push({id:i.dataset.fieldId, sort_order:o++}));
  return fields;
}

function saveFieldOrder() {
  const fields = getFieldOrder();
  if (!fields.length) return showToast('warning', 'Belum ada field');
  fetch('{{ route("settings.form-builder.reorder") }}', {
    method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'},
    body: JSON.stringify({fields})
  }).then(r=>r.json()).then(d=>{if(d.success)showToast('success','Urutan field berhasil disimpan!');else showToast('danger','Gagal')}).catch(()=>showToast('danger','Gagal'));
}

function openAddModal(type) {
  editingFieldId = null;
  document.getElementById('field_type_input').value = type;
  document.getElementById('field_method').value = 'POST';
  document.getElementById('field_is_system').value = '0';
  document.getElementById('fieldForm').reset();
  document.getElementById('field_name').value = '';
  delete document.getElementById('field_name').dataset.manual;
  document.getElementById('fieldModalLabel').innerHTML = '<i class="ti ti-plus me-2"></i>Tambah Field';
  document.getElementById('fieldSubmitBtn').innerHTML = '<i class="ti ti-device-floppy me-1"></i> Simpan Field';
  updateFieldTypeUI(type); toggleOptions(type);
  new bootstrap.Modal(document.getElementById('fieldModal')).show();
}

function openEditModal(id) { showToast('info', 'Edit field akan segera hadir.'); }

function addPddiktiField(key) {
  // Add a PDDIKTI standard field via AJAX - marked as system
  const pddiktiFields = @json(\App\Models\FormField::PDDIKTI_STANDARD_FIELDS);
  const config = pddiktiFields[key];
  if (!config) return showToast('danger', 'Field PDDIKTI tidak ditemukan');

  const formData = new FormData();
  formData.append('form_id', formId);
  formData.append('field_type', config.field_type);
  formData.append('field_name', key);
  formData.append('field_label', config.field_label);
  formData.append('placeholder', config.placeholder || '');
  formData.append('help_text', config.help_text || '');
  formData.append('section', config.section || '');
  formData.append('width', config.width || 'col-12');
  formData.append('is_required', config.is_required ? '1' : '0');
  formData.append('is_system', '1');
  if (config.options) {
    config.options.forEach(function(opt) { formData.append('options[]', opt); });
  }

  fetch('{{ route("settings.form-builder.store") }}', {
    method:'POST',
    headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json', 'X-Requested-With':'XMLHttpRequest'},
    body: formData
  }).then(r=>r.json()).then(d=>{
    if(d.success) { showToast('success','Field PDDIKTI "'+config.field_label+'" ditambahkan!'); setTimeout(()=>location.reload(),500); }
    else showToast('danger', d.message||'Gagal');
  }).catch(()=>showToast('danger','Gagal menambahkan field PDDIKTI'));
}

function updateFieldTypeUI(type) {
  const c=fieldTypes[type]||fieldTypes.text, d=document.getElementById('fieldTypeIconDisplay');
  d.style.background=c.color+'15'; d.querySelector('i').className=c.icon+' fs-4'; d.querySelector('i').style.color=c.color;
  document.getElementById('fieldTypeLabelDisplay').textContent=c.label;
  document.getElementById('fieldTypeNameDisplay').textContent=type;
}

function toggleOptions(type) { document.getElementById('optionsContainer').classList.toggle('d-none',!['select','radio','checkbox'].includes(type)); }

function addOption(v='') {
  const l=document.getElementById('optionsList'), d=document.createElement('div');
  d.className='input-group mb-2 option-item';
  d.innerHTML=`<span class="input-group-text bg-white"><i class="ti ti-grip-vertical text-muted"></i></span><input type="text" name="options[]" class="form-control" placeholder="Opsi ${l.children.length+1}" value="${v}"><button type="button" class="btn btn-outline-danger" onclick="removeOption(this)"><i class="ti ti-x"></i></button>`;
  l.appendChild(d);
}
function removeOption(b){if(document.querySelectorAll('.option-item').length>1)b.closest('.option-item').remove();}

document.getElementById('fieldForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  formData.append('form_id', formId);
  fetch('{{ route("settings.form-builder.store") }}', { method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json', 'X-Requested-With':'XMLHttpRequest'}, body:formData })
    .then(r=>r.json()).then(d=>{if(d.success){showToast('success','Field berhasil ditambahkan!');bootstrap.Modal.getInstance(document.getElementById('fieldModal')).hide();setTimeout(()=>location.reload(),500);}else{showToast('danger',d.message||'Gagal menyimpan');console.error(d);}})
    .catch((err)=>{showToast('danger','Gagal menyimpan field');console.error(err);});
});

function duplicateField(id) {
  if(!confirm('Duplikat field ini?'))return;
  fetch(`/settings/form-builder/${id}/duplicate`,{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}})
    .then(r=>r.json()).then(d=>{if(d.success){showToast('success','Field diduplikasi!');setTimeout(()=>location.reload(),500);}}).catch(()=>showToast('danger','Gagal'));
}

function toggleField(id) {
  fetch(`/settings/form-builder/${id}/toggle-status`,{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}})
    .then(r=>r.json()).then(d=>{if(d.success){showToast('success','Status diubah!');setTimeout(()=>location.reload(),300);}}).catch(()=>showToast('danger','Gagal'));
}

function deleteField(id) {
  if(!confirm('Hapus field ini?'))return;
  fetch(`/settings/form-builder/${id}`,{method:'DELETE',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}})
    .then(r=>r.json()).then(d=>{if(d.success){showToast('success','Field dihapus!');setTimeout(()=>location.reload(),300);}}).catch(()=>showToast('danger','Gagal'));
}

function previewForm() {
  const m=new bootstrap.Modal(document.getElementById('previewModal'));m.show();
  fetch(`/settings/form-builder/fields/${formId}`).then(r=>r.json()).then(d=>{if(d.success)renderPreview(d.fields);else document.getElementById('previewBody').innerHTML='<div class="alert alert-danger">Gagal</div>';})
    .catch(()=>document.getElementById('previewBody').innerHTML='<div class="alert alert-danger">Gagal</div>');
}

function renderPreview(fields) {
  const body=document.getElementById('previewBody');
  const keys=Object.keys(fields);
  if(!keys.length||keys.every(k=>!fields[k].length)){body.innerHTML='<div class="text-center py-5"><i class="ti ti-file-off text-muted fs-1"></i><p class="mt-2 text-muted">Belum ada field</p></div>';return;}
  let html=`<div class="card border-1 shadow-sm" style="border-top:8px solid #f63a4c;"><div class="card-body p-4"><div class="d-flex align-items-center gap-3 mb-4"><div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:40px;height:40px;"><i class="ti ti-file-text text-primary"></i></div><div><h4 class="fw-bold mb-0">{{ $form->nama }}</h4></div></div><form><div class="row g-3">`;
  keys.forEach(s=>{
    const sf=fields[s]; if(!sf||!sf.length)return;
    if(s) html+=`<div class="col-12"><hr><h6 class="fw-bold text-uppercase small text-muted">${s}</h6></div>`;
    sf.forEach(f=>{
      if(!f.is_active)return;
      const r=f.is_required?'<span class="text-danger">*</span>':'';
      html+=`<div class="${f.width||'col-12'} mb-2"><label class="form-label fw-semibold small">${f.field_label} ${r}</label>`;
      switch(f.field_type){
        case'textarea':html+=`<textarea class="form-control" rows="3" placeholder="${f.placeholder||''}"></textarea>`;break;
        case'select':html+=`<select class="form-select"><option value="">${f.placeholder||'Pilih...'}</option>`;(f.options||[]).forEach(o=>html+=`<option>${o}</option>`);html+=`</select>`;break;
        case'radio':(f.options||[]).forEach((o,i)=>{html+=`<div class="form-check"><input class="form-check-input" type="radio" name="${f.field_name}" id="${f.field_name}_${i}"><label class="form-check-label" for="${f.field_name}_${i}">${o}</label></div>`;});break;
        case'checkbox':(f.options||[]).forEach((o,i)=>{html+=`<div class="form-check"><input class="form-check-input" type="checkbox" id="${f.field_name}_${i}"><label class="form-check-label" for="${f.field_name}_${i}">${o}</label></div>`;});break;
        case'date':html+=`<input type="date" class="form-control">`;break;
        case'number':html+=`<input type="number" class="form-control" placeholder="${f.placeholder||''}">`;break;
        case'file':html+=`<input type="file" class="form-control">`;break;
        case'color':html+=`<input type="color" class="form-control form-control-color" style="max-width:60px;">`;break;
        default:html+=`<input type="${f.field_type==='email'?'email':f.field_type==='tel'?'tel':'text'}" class="form-control" placeholder="${f.placeholder||''}">`;
      }
      if(f.help_text)html+=`<small class="text-muted">${f.help_text}</small>`;
      html+=`</div>`;
    });
  });
  html+=`</div></form></div></div>`;body.innerHTML=html;
}

function showToast(type,msg){
  const c=document.createElement('div');c.style.cssText='position:fixed;top:20px;right:20px;z-index:9999;max-width:400px;';
  const icon=type==='success'?'circle-check':type==='danger'?'alert-triangle':'info-circle';
  c.innerHTML=`<div class="alert alert-${type} alert-dismissible fade show shadow-lg border-0" role="alert"><i class="ti ti-${icon} me-2"></i>${msg}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`;
  document.body.appendChild(c);setTimeout(()=>{c.querySelector('.alert').classList.remove('show');setTimeout(()=>c.remove(),300);},3000);
}
</script>
@endpush
@endsection