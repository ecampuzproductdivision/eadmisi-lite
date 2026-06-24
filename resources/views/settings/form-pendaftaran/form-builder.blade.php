@extends('layouts.app')

@section('content')
<main class="p-6">
  <div class="row justify-content-center">
    <div class="col-lg-10">

      <!-- Header -->
      <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
          <h2 class="fw-bold mb-1">
            <i class="ti ti-file-text me-2 text-primary"></i>Form Builder
            <span class="badge bg-primary-subtle text-primary fs-6 align-middle ms-2">Drag & Drop</span>
          </h2>
          <p class="text-muted mb-0">Buat formulir pendaftaran dengan drag & drop layaknya Google Forms.</p>
        </div>
        <div class="d-flex gap-2">
          <button type="button" class="btn btn-success" onclick="saveFieldOrder()">
            <i class="ti ti-device-floppy me-1"></i> Simpan
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
        <!-- Left Column: Field Palette -->
        <div class="col-lg-3">
          <div class="card border-1 shadow-sm sticky-top" style="top: 80px;">
            <div class="card-header bg-light py-3">
              <h6 class="fw-bold mb-0"><i class="ti ti-box me-2"></i>Tambah Field</h6>
            </div>
            <div class="card-body p-3">
              <p class="text-muted small mb-3">Klik tipe field untuk menambahkan ke formulir:</p>
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
              <hr>
              <div class="d-flex align-items-center gap-2">
                <i class="ti ti-info-circle text-muted"></i>
                <small class="text-muted">Drag & drop untuk mengubah urutan</small>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column: Form Canvas (Google Forms Style) -->
        <div class="col-lg-9">
          <!-- Form Title Card -->
          <div class="card border-1 shadow-sm mb-4" style="border-top: 8px solid #f63a4c !important; border-radius: 12px;">
            <div class="card-body p-4">
              <div class="d-flex align-items-start gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                  <i class="ti ti-file-text text-primary fs-4"></i>
                </div>
                <div>
                  <h4 class="fw-bold mb-1">Formulir Pendaftaran Mahasiswa Baru</h4>
                  <p class="text-muted mb-0">Formulir ini digunakan untuk pendaftaran mahasiswa baru. Field-field di bawah dapat diatur sesuai kebutuhan.</p>
                  <small class="text-muted">
                    <i class="ti ti-drag-drop me-1"></i>
                    Drag & drop field untuk mengubah urutan
                  </small>
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
            <h5 class="fw-bold text-muted mb-2">Formulir Masih Kosong</h5>
            <p class="text-muted mb-0">Klik tipe field di samping untuk mulai menambahkan field.</p>
            <p class="text-muted">Atur urutan field dengan drag & drop.</p>
          </div>

          <!-- Form Canvas -->
          <div id="formCanvas" class="{{ $fields->isEmpty() ? 'd-none' : '' }}">
            <div id="fieldList">
              @forelse($fields as $sectionName => $sectionFields)
              <div class="mb-3 section-container">
                @if($sectionName)
                <div class="d-flex align-items-center gap-2 mb-2 section-header">
                  <div class="bg-light px-3 py-1 rounded-3 d-inline-flex align-items-center gap-2">
                    <i class="ti ti-menu-2 text-muted" style="font-size: 0.8rem;"></i>
                    <span class="fw-semibold small text-uppercase text-muted">{{ $sectionName }}</span>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary" style="font-size: 0.65rem;">{{ count($sectionFields) }}</span>
                  </div>
                </div>
                @endif
                <div class="sortable-fields" data-section="{{ $sectionName }}">
                  @foreach($sectionFields as $field)
                  <div class="field-item card border-1 shadow-sm mb-2" data-field-id="{{ $field->id }}" data-sort="{{ $field->sort_order }}" style="border-radius: 8px; {{ !$field->is_active ? 'opacity: 0.6;' : '' }}">
                    <div class="card-body py-3 px-4">
                      <div class="d-flex align-items-center gap-3">
                        <!-- Drag Handle -->
                        <div class="drag-handle text-muted" title="Drag to reorder">
                          <i class="ti ti-grip-vertical fs-5"></i>
                        </div>

                        <!-- Field Preview (Google Forms style) -->
                        <div class="flex-grow-1">
                          <div class="row align-items-center g-2">
                            <div class="col-md-8">
                              <div class="d-flex align-items-center gap-2">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 24px; height: 24px; background: {{ $fieldTypes[$field->field_type]['color'] ?? '#6c757d' }}15;">
                                  <i class="{{ $fieldTypes[$field->field_type]['icon'] ?? 'ti-input' }}" style="font-size: 0.75rem; color: {{ $fieldTypes[$field->field_type]['color'] ?? '#6c757d' }}"></i>
                                </span>
                                <span class="fw-semibold" style="font-size: 0.9rem;">
                                  {{ $field->field_label }}
                                  @if($field->is_required)
                                    <span class="text-danger">*</span>
                                  @endif
                                </span>
                              </div>
                              <div class="d-flex align-items-center gap-2 mt-1 ms-4">
                                <code class="small text-muted" style="font-size: 0.7rem;">{{ $field->field_name }}</code>
                                <span class="badge bg-light text-muted" style="font-size: 0.6rem;">{{ $field->field_type }}</span>
                                @if(in_array($field->field_type, ['select','radio','checkbox']))
                                  <span class="text-muted" style="font-size: 0.7rem;">{{ count($field->options ?? []) }} options</span>
                                @endif
                              </div>
                            </div>
                            <div class="col-md-4">
                              <!-- Mini preview based on field type -->
                              <div class="bg-light rounded p-2" style="min-height: 32px;">
                                @if($field->field_type === 'text')
                                  <div class="text-muted" style="font-size: 0.75rem; border-bottom: 1px dashed #ccc;">{{ $field->placeholder ?: 'Short answer text' }}</div>
                                @elseif($field->field_type === 'textarea')
                                  <div class="text-muted" style="font-size: 0.7rem;">{{ $field->placeholder ?: 'Long answer text...' }}</div>
                                @elseif($field->field_type === 'select')
                                  <div class="text-muted" style="font-size: 0.7rem;">
                                    <i class="ti ti-select me-1"></i>Dropdown
                                  </div>
                                @elseif($field->field_type === 'radio')
                                  <div class="d-flex gap-2" style="font-size: 0.65rem;">
                                    @foreach(array_slice($field->options ?? ['Option 1'], 0, 2) as $opt)
                                      <span><i class="ti ti-circle text-muted me-1"></i>{{ $opt }}</span>
                                    @endforeach
                                  </div>
                                @elseif($field->field_type === 'checkbox')
                                  <div class="d-flex gap-2" style="font-size: 0.65rem;">
                                    @foreach(array_slice($field->options ?? ['Option 1'], 0, 2) as $opt)
                                      <span><i class="ti ti-square text-muted me-1"></i>{{ $opt }}</span>
                                    @endforeach
                                  </div>
                                @elseif($field->field_type === 'date')
                                  <div style="font-size: 0.7rem;"><i class="ti ti-calendar me-1"></i>Date picker</div>
                                @elseif($field->field_type === 'file')
                                  <div style="font-size: 0.7rem;"><i class="ti ti-upload me-1"></i>File upload</div>
                                @else
                                  <div class="text-muted" style="font-size: 0.7rem;">{{ $field->field_type }}</div>
                                @endif
                              </div>
                            </div>
                          </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-1 flex-shrink-0">
                          <button type="button" class="btn btn-sm btn-outline-info border-0" title="Edit Field" onclick="openEditModal({{ $field->id }})">
                            <i class="ti ti-edit"></i>
                          </button>
                          <button type="button" class="btn btn-sm btn-outline-success border-0" title="Duplikat" onclick="duplicateField({{ $field->id }})">
                            <i class="ti ti-copy"></i>
                          </button>
                          <button type="button" class="btn btn-sm btn-outline-{{ $field->is_active ? 'warning' : 'success' }} border-0" title="{{ $field->is_active ? 'Nonaktifkan' : 'Aktifkan' }}" onclick="toggleField({{ $field->id }})">
                            <i class="ti ti-{{ $field->is_active ? 'eye-off' : 'eye' }}"></i>
                          </button>
                          <button type="button" class="btn btn-sm btn-outline-danger border-0" title="Hapus" onclick="deleteField({{ $field->id }})">
                            <i class="ti ti-trash"></i>
                          </button>
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
                </div>
              </div>
              @empty
              <div class="text-center py-4 empty-section">
                <i class="ti ti-plus-circle text-muted fs-2"></i>
                <p class="text-muted mt-2">Klik tipe field di samping untuk menambahkan</p>
              </div>
              @endforelse
            </div>
          </div>

          <!-- Footer note -->
          <div class="text-center mt-3">
            <small class="text-muted">
              <i class="ti ti-arrows-move me-1"></i>Drag & drop field untuk mengatur urutan
              <span class="mx-2">|</span>
              <span id="fieldCount"><strong>{{ array_sum(array_map('count', $fields->toArray())) }}</strong> field terpasang</span>
            </small>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- Add / Edit Field Modal -->
<div class="modal fade" id="fieldModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <h5 class="modal-title fw-bold text-white" id="fieldModalLabel">
          <i class="ti ti-plus me-2"></i>Tambah Field
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="fieldForm" method="POST">
        @csrf
        <input type="hidden" name="field_type" id="field_type_input" value="text">
        <input type="hidden" name="_method" id="field_method" value="POST">
        <input type="hidden" name="field_id" id="field_id" value="">

        <div class="modal-body p-4">
          <div class="row g-3">
            <!-- Field Type Badge -->
            <div class="col-12">
              <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3">
                <div id="fieldTypeIconDisplay" class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #4f46e515;">
                  <i class="ti ti-input fs-4" style="color: #4f46e5;"></i>
                </div>
                <div>
                  <span class="fw-semibold" id="fieldTypeLabelDisplay">Text (Short)</span>
                  <br>
                  <small class="text-muted">Type: <span id="fieldTypeNameDisplay">text</span></small>
                </div>
              </div>
            </div>

            <!-- Field Label -->
            <div class="col-md-8">
              <label class="form-label fw-semibold">Judul Field <span class="text-danger">*</span></label>
              <input type="text" name="field_label" id="field_label" class="form-control form-control-lg" placeholder="Masukkan judul pertanyaan" required>
            </div>

            <!-- Field Name (auto) -->
            <div class="col-md-4">
              <label class="form-label fw-semibold">Field Name</label>
              <input type="text" name="field_name" id="field_name" class="form-control font-monospace" placeholder="snake_case" required>
              <small class="text-muted">Auto dari judul</small>
            </div>

            <!-- Placeholder -->
            <div class="col-md-6">
              <label class="form-label fw-semibold">Placeholder</label>
              <input type="text" name="placeholder" id="field_placeholder" class="form-control" placeholder="Text petunjuk di dalam input">
            </div>

            <!-- Default Value -->
            <div class="col-md-6">
              <label class="form-label fw-semibold">Nilai Default</label>
              <input type="text" name="default_value" id="field_default_value" class="form-control" placeholder="Nilai awal (opsional)">
            </div>

            <!-- Section -->
            <div class="col-md-6">
              <label class="form-label fw-semibold">Bagian / Grup</label>
              <select name="section" id="field_section" class="form-select">
                <option value="">Tanpa Grup</option>
                @foreach($sections as $section)
                  <option value="{{ $section }}">{{ $section }}</option>
                @endforeach
              </select>
            </div>

            <!-- Width -->
            <div class="col-md-6">
              <label class="form-label fw-semibold">Lebar Kolom</label>
              <select name="width" id="field_width" class="form-select">
                @foreach($widthOptions as $val => $label)
                  <option value="{{ $val }}" {{ $val === 'col-12' ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
              </select>
            </div>

            <!-- Options (for select/radio/checkbox) -->
            <div class="col-12 d-none" id="optionsContainer">
              <label class="form-label fw-semibold">Pilihan Opsi <span class="text-danger">*</span></label>
              <div id="optionsList">
                <div class="input-group mb-2 option-item">
                  <span class="input-group-text bg-white"><i class="ti ti-grip-vertical text-muted"></i></span>
                  <input type="text" name="options[]" class="form-control" placeholder="Opsi 1">
                  <button type="button" class="btn btn-outline-danger" onclick="removeOption(this)">
                    <i class="ti ti-x"></i>
                  </button>
                </div>
              </div>
              <button type="button" class="btn btn-sm btn-outline-primary mt-1" onclick="addOption()">
                <i class="ti ti-plus me-1"></i> Tambah Opsi
              </button>
            </div>

            <!-- Help Text -->
            <div class="col-12">
              <label class="form-label fw-semibold">Teks Bantuan</label>
              <textarea name="help_text" id="field_help_text" class="form-control" rows="2" placeholder="Teks bantuan yang muncul di bawah field"></textarea>
            </div>

            <!-- Required Toggle -->
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
          <button type="submit" class="btn btn-primary fw-semibold px-4" id="fieldSubmitBtn">
            <i class="ti ti-device-floppy me-1"></i> Simpan Field
          </button>
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
        <h5 class="modal-title fw-bold text-white">
          <i class="ti ti-eye me-2"></i>Preview Formulir Pendaftaran
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4" id="previewBody">
        <div class="text-center py-5">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <p class="mt-2 text-muted">Memuat preview...</p>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.field-item {
  transition: all 0.2s ease;
  cursor: default;
}
.field-item:hover {
  box-shadow: 0 2px 12px rgba(0,0,0,0.08) !important;
}
.field-item .drag-handle {
  cursor: grab;
  opacity: 0.3;
  transition: opacity 0.2s;
}
.field-item:hover .drag-handle {
  opacity: 0.8;
}
.field-item .drag-handle:active {
  cursor: grabbing;
}
.field-item.sortable-chosen {
  box-shadow: 0 4px 20px rgba(102, 126, 234, 0.2) !important;
  border-left: 3px solid #667eea !important;
}
.field-item.sortable-ghost {
  opacity: 0.3;
  background: #f0f4ff;
}
.sortable-fields {
  min-height: 40px;
}
.section-header {
  user-select: none;
}
.field-type-btn {
  transition: all 0.2s;
}
.field-type-btn:hover {
  border-color: #667eea !important;
  background: #f0f4ff !important;
}
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
const fieldTypes = @json($fieldTypes);

// ====== INIT SORTABLE ======
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.sortable-fields').forEach(el => {
    new Sortable(el, {
      group: 'fields',
      animation: 200,
      handle: '.drag-handle',
      ghostClass: 'sortable-ghost',
      chosenClass: 'sortable-chosen',
      onEnd: function() {
        updateFieldCount();
      }
    });
  });
  updateFieldCount();
});

function updateFieldCount() {
  const count = document.querySelectorAll('.field-item').length;
  document.getElementById('fieldCount').innerHTML = `<strong>${count}</strong> field terpasang`;
  if (count === 0) {
    document.getElementById('noFields').classList.remove('d-none');
    document.getElementById('formCanvas').classList.add('d-none');
  } else {
    document.getElementById('noFields').classList.add('d-none');
    document.getElementById('formCanvas').classList.remove('d-none');
  }
}

function getFieldOrder() {
  const fields = [];
  let sortOrder = 1;
  document.querySelectorAll('.sortable-fields').forEach(sectionEl => {
    sectionEl.querySelectorAll('.field-item').forEach(item => {
      fields.push({ id: item.dataset.fieldId, sort_order: sortOrder++ });
    });
  });
  return fields;
}

function saveFieldOrder() {
  const fields = getFieldOrder();
  if (fields.length === 0) return;

  const btn = event.currentTarget;
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';

  fetch('{{ route("settings.form-builder.reorder") }}', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}',
      'Accept': 'application/json',
    },
    body: JSON.stringify({ fields })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) showToast('success', 'Urutan field berhasil disimpan!');
    else showToast('danger', 'Gagal menyimpan urutan');
  })
  .catch(() => showToast('danger', 'Gagal menyimpan urutan'))
  .finally(() => {
    btn.disabled = false;
    btn.innerHTML = '<i class="ti ti-device-floppy me-1"></i> Simpan';
  });
}

// ====== MODAL ======
function openAddModal(type) {
  document.getElementById('field_type_input').value = type;
  document.getElementById('field_method').value = 'POST';
  document.getElementById('field_id').value = '';
  document.getElementById('fieldForm').action = '{{ route("settings.form-builder.store") }}';
  document.getElementById('fieldModalLabel').innerHTML = '<i class="ti ti-plus me-2"></i>Tambah Field';
  document.getElementById('fieldSubmitBtn').innerHTML = '<i class="ti ti-device-floppy me-1"></i> Simpan Field';
  
  // Reset form
  document.getElementById('fieldForm').reset();
  document.getElementById('field_name').value = '';
  delete document.getElementById('field_name').dataset.manual;

  updateFieldTypeUI(type);
  toggleOptions(type);
  
  new bootstrap.Modal(document.getElementById('fieldModal')).show();
}

function openEditModal(id) {
  // For now, we'll just reload with edit functionality
  showToast('info', 'Edit field akan diimplementasikan dengan AJAX.');
}

function updateFieldTypeUI(type) {
  const config = fieldTypes[type] || fieldTypes['text'];
  const display = document.getElementById('fieldTypeIconDisplay');
  display.style.background = config.color + '15';
  display.querySelector('i').className = config.icon + ' fs-4';
  display.querySelector('i').style.color = config.color;
  document.getElementById('fieldTypeLabelDisplay').textContent = config.label;
  document.getElementById('fieldTypeNameDisplay').textContent = type;
}

function toggleOptions(type) {
  document.getElementById('optionsContainer').classList.toggle('d-none', !['select','radio','checkbox'].includes(type));
}

function addOption(value = '') {
  const list = document.getElementById('optionsList');
  const div = document.createElement('div');
  div.className = 'input-group mb-2 option-item';
  div.innerHTML = `
    <span class="input-group-text bg-white"><i class="ti ti-grip-vertical text-muted"></i></span>
    <input type="text" name="options[]" class="form-control" placeholder="Opsi ${list.children.length + 1}" value="${value}">
    <button type="button" class="btn btn-outline-danger" onclick="removeOption(this)"><i class="ti ti-x"></i></button>
  `;
  list.appendChild(div);
}

function removeOption(btn) {
  if (document.querySelectorAll('.option-item').length > 1) {
    btn.closest('.option-item').remove();
  }
}

// Auto-generate field name from label
document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('field_label').addEventListener('input', function() {
    const nameInput = document.getElementById('field_name');
    if (!nameInput.dataset.manual) {
      nameInput.value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '_').replace(/^_|_$/g, '').substring(0, 100);
    }
  });
  document.getElementById('field_name').addEventListener('input', function() {
    this.dataset.manual = this.value.length > 0 ? '1' : '';
  });
});

// ====== AJAX ACTIONS ======
function duplicateField(id) {
  if (!confirm('Duplikat field ini?')) return;
  fetch(`/settings/form-builder/${id}/duplicate`, {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) { showToast('success', 'Field berhasil diduplikasi!'); setTimeout(() => location.reload(), 800); }
  })
  .catch(() => showToast('danger', 'Gagal menduplikasi'));
}

function toggleField(id) {
  fetch(`/settings/form-builder/${id}/toggle-status`, {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) { showToast('success', 'Status field berhasil diubah!'); setTimeout(() => location.reload(), 500); }
  })
  .catch(() => showToast('danger', 'Gagal mengubah status'));
}

function deleteField(id) {
  if (!confirm('Hapus field ini? Aksi ini tidak bisa dibatalkan.')) return;
  fetch(`/settings/form-builder/${id}`, {
    method: 'DELETE',
    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) { showToast('success', 'Field berhasil dihapus!'); setTimeout(() => location.reload(), 500); }
  })
  .catch(() => showToast('danger', 'Gagal menghapus'));
}

// ====== PREVIEW ======
function previewForm() {
  const modal = new bootstrap.Modal(document.getElementById('previewModal'));
  modal.show();
  const body = document.getElementById('previewBody');

  fetch('{{ route("settings.form-builder.get-fields") }}')
    .then(res => res.json())
    .then(data => {
      if (data.success) renderPreview(data.fields);
      else body.innerHTML = '<div class="alert alert-danger">Gagal memuat preview</div>';
    })
    .catch(() => body.innerHTML = '<div class="alert alert-danger">Gagal memuat preview</div>');
}

function renderPreview(fields) {
  const body = document.getElementById('previewBody');
  const sectionKeys = Object.keys(fields);
  
  if (sectionKeys.length === 0 || sectionKeys.every(k => fields[k].length === 0)) {
    body.innerHTML = '<div class="text-center py-5"><i class="ti ti-file-off text-muted fs-1"></i><p class="mt-2 text-muted">Belum ada field</p></div>';
    return;
  }

  let html = `
    <div class="card border-1 shadow-sm" style="border-top: 8px solid #f63a4c;">
      <div class="card-body p-4">
        <div class="d-flex align-items-center gap-3 mb-4">
          <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i class="ti ti-file-text text-primary"></i>
          </div>
          <div>
            <h4 class="fw-bold mb-0">Formulir Pendaftaran</h4>
            <small class="text-muted">Preview form pendaftaran mahasiswa baru</small>
          </div>
        </div>
        <form><div class="row g-3">`;

  sectionKeys.forEach(section => {
    const sectionFields = fields[section];
    if (!sectionFields || sectionFields.length === 0) return;

    if (section) {
      html += `<div class="col-12"><hr class="my-2"><h6 class="fw-bold text-uppercase small text-muted">${section}</h6></div>`;
    }

    sectionFields.forEach(f => {
      if (!f.is_active) return;
      const required = f.is_required ? '<span class="text-danger">*</span>' : '';
      const label = `<label class="form-label fw-semibold small">${f.field_label} ${required}</label>`;
      html += `<div class="${f.width || 'col-12'} mb-2">${label}`;

      switch (f.field_type) {
        case 'textarea':
          html += `<textarea class="form-control" rows="3" placeholder="${f.placeholder || ''}"></textarea>`; break;
        case 'select':
          html += `<select class="form-select"><option value="">${f.placeholder || 'Pilih...'}</option>`;
          (f.options || []).forEach(o => { html += `<option>${o}</option>`; });
          html += `</select>`; break;
        case 'radio':
          (f.options || []).forEach((o, i) => {
            html += `<div class="form-check"><input class="form-check-input" type="radio" name="${f.field_name}" id="${f.field_name}_${i}"><label class="form-check-label" for="${f.field_name}_${i}">${o}</label></div>`;
          }); break;
        case 'checkbox':
          (f.options || []).forEach((o, i) => {
            html += `<div class="form-check"><input class="form-check-input" type="checkbox" id="${f.field_name}_${i}"><label class="form-check-label" for="${f.field_name}_${i}">${o}</label></div>`;
          }); break;
        case 'date': html += `<input type="date" class="form-control">`; break;
        case 'number': html += `<input type="number" class="form-control" placeholder="${f.placeholder || ''}">`; break;
        case 'file': html += `<input type="file" class="form-control">`; break;
        case 'color': html += `<input type="color" class="form-control form-control-color" style="max-width: 60px;">`; break;
        default:
          html += `<input type="${f.field_type === 'email' ? 'email' : f.field_type === 'tel' ? 'tel' : 'text'}" class="form-control" placeholder="${f.placeholder || ''}">`;
      }
      if (f.help_text) html += `<small class="text-muted">${f.help_text}</small>`;
      html += `</div>`;
    });
  });

  html += `</div></form></div></div>`;
  body.innerHTML = html;
}

// ====== TOAST ======
function showToast(type, message) {
  const container = document.createElement('div');
  container.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;';
  const icon = type === 'success' ? 'circle-check' : type === 'danger' ? 'alert-triangle' : 'info-circle';
  container.innerHTML = `
    <div class="alert alert-${type} alert-dismissible fade show shadow-lg border-0" role="alert">
      <i class="ti ti-${icon} me-2"></i>${message}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>`;
  document.body.appendChild(container);
  setTimeout(() => { container.querySelector('.alert').classList.remove('show'); setTimeout(() => container.remove(), 300); }, 3000);
}
</script>
@endpush
@endsection