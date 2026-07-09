{{-- Komponen Biaya Repeater --}}
<div class="col-12">
    <h6 class="text-secondary fw-bold pb-2 mb-3 mt-2" style="border-bottom: 1px dashed #dee2e6;">
        <i class="ti ti-currency-dollar me-1"></i> Komponen Biaya Registrasi Ulang (ePembayaran)
    </h6>

    <div id="komponen-biaya-wrapper">
        <table class="table table-bordered" id="komponen-biaya-table">
            <thead class="table-light">
                <tr>
                    <th width="40%">Komponen Biaya</th>
                    <th width="40%">Nominal (Rp)</th>
                    <th width="20%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="komponen-biaya-rows">
                @if(isset($registrationPath) && $registrationPath->relationLoaded('komponenBiayas'))
                    @foreach($registrationPath->komponenBiayas as $kb)
                        <tr class="komponen-row">
                            <td>
                                <select name="komponen_id[]" class="form-select form-select-sm komponen-select">
                                    <option value="">Pilih komponen...</option>
                                    @foreach($listMasterKomponen as $mk)
                                        <option value="{{ $mk->id }}" {{ $kb->id == $mk->id ? 'selected' : '' }}>{{ $mk->kode_komponen }} - {{ $mk->nama_komponen }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" name="komponen_nominal[]" class="form-control form-control-sm komponen-nominal" value="{{ $kb->pivot->nominal ?? 0 }}" min="0" placeholder="0">
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-komponen-row" title="Hapus">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <div class="d-flex align-items-center justify-content-between mt-2">
        <button type="button" class="btn btn-sm btn-outline-primary" id="tambah-komponen-btn">
            <i class="ti ti-plus me-1"></i> Tambah Komponen Biaya
        </button>
        <div class="fw-bold">
            Total: <span id="total-komponen-nominal" class="text-primary">Rp 0</span>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let rowIndex = 1000;

    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.komponen-nominal').forEach(function(input) {
            let val = parseFloat(input.value) || 0;
            total += val;
        });
        document.getElementById('total-komponen-nominal').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    function addKomponenRow(selectedId, nominal) {
        rowIndex++;
        const selectHtml = `{!! str_replace("'", "\\'", '<select name="komponen_id[]" class="form-select form-select-sm komponen-select"><option value="">Pilih komponen...</option>') !!}`;
        
        let options = '';
        @foreach($listMasterKomponen as $mk)
            options += `<option value="{{ $mk->id }}" ${selectedId == {{ $mk->id }} ? 'selected' : ''}>{{ $mk->kode_komponen }} - {{ $mk->nama_komponen }}</option>`;
        @endforeach

        const tr = document.createElement('tr');
        tr.className = 'komponen-row';
        tr.innerHTML = `
            <td>
                <select name="komponen_id[]" class="form-select form-select-sm komponen-select">
                    <option value="">Pilih komponen...</option>
                    ${options}
                </select>
            </td>
            <td>
                <input type="number" name="komponen_nominal[]" class="form-control form-control-sm komponen-nominal" value="${nominal || 0}" min="0" placeholder="0">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger remove-komponen-row" title="Hapus">
                    <i class="ti ti-trash"></i>
                </button>
            </td>
        `;
        document.getElementById('komponen-biaya-rows').appendChild(tr);

        tr.querySelector('.remove-komponen-row').addEventListener('click', function() {
            tr.remove();
            updateTotal();
        });
        tr.querySelector('.komponen-nominal').addEventListener('input', updateTotal);
        updateTotal();
    }

    // Add row button
    document.getElementById('tambah-komponen-btn').addEventListener('click', function() {
        addKomponenRow('', 0);
    });

    // Remove row buttons
    document.querySelectorAll('.remove-komponen-row').forEach(function(btn) {
        btn.addEventListener('click', function() {
            this.closest('.komponen-row').remove();
            updateTotal();
        });
    });

    // Nominal input changes
    document.querySelectorAll('.komponen-nominal').forEach(function(input) {
        input.addEventListener('input', updateTotal);
    });

    updateTotal();
});
</script>
@endpush