{{--
  Reusable AJAX Sort Script Component
  Handles sorting table columns without full page refresh.
  Preserves scroll position and updates URL in address bar.
  
  Usage:
  @include('components.ajax-sort-script', ['tableBodyId' => 'country-table-body'])
--}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.getElementById('{{ $tableBodyId }}');
    if (!tableBody) return;

    const table = tableBody.closest('table');
    if (!table) return;

    const tableWrapper = table.closest('.table-responsive') || table.parentElement;

    // Listen for custom event to re-bind after infinite scroll
    document.addEventListener('sort-updated', function(e) {
        if (e.detail && e.detail.tableBodyId === '{{ $tableBodyId }}') {
            // Re-register status toggles if any
            const tb = document.getElementById('{{ $tableBodyId }}');
            if (tb) {
                tb.querySelectorAll('.status-toggle').forEach(function(toggle) {
                    if (!toggle.hasAttribute('data-registered')) {
                        toggle.setAttribute('data-registered', 'true');
                        toggle.addEventListener('change', function() {
                            const url = this.getAttribute('data-url');
                            const label = this.parentElement.querySelector('.status-label');
                            fetch(url, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    if (label) {
                                        label.textContent = data.status === 'active' ? 'Aktif' : 'Nonaktif';
                                        label.className = 'form-check-label status-label text-' + (data.status === 'active' ? 'success' : 'danger') + ' fw-semibold small ms-1';
                                    }
                                } else {
                                    this.checked = !this.checked;
                                }
                            })
                            .catch(() => { this.checked = !this.checked; });
                        });
                    }
                });
            }
        }
    });

    // Intercept clicks on sortable headers
    table.addEventListener('click', function(e) {
        const sortTh = e.target.closest('.sortable-th');
        if (!sortTh) return;

        // Use AJAX URL for fetch, regular URL for fallback
        const sortUrl = sortTh.getAttribute('data-sort-ajax-url') || sortTh.getAttribute('data-sort-url');
        if (!sortUrl) return;

        e.preventDefault();

        // Show loading state
        const tbody = table.querySelector('tbody');
        if (tbody) tbody.style.opacity = '0.5';

        // Fetch sorted page via AJAX (WITHOUT X-Requested-With header to get full page response)
        fetch(sortUrl)
        .then(response => response.text())
        .then(html => {
            // Parse the response HTML to extract new table content
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            // Find the new tbody with the same ID
            const newTbody = doc.getElementById('{{ $tableBodyId }}');
            if (!newTbody) {
                // Fallback: just navigate to the URL
                window.location.href = sortUrl;
                return;
            }

            // Find the new thead (first thead in the same table context)
            const oldTable = tableBody.closest('table');
            const newTable = newTbody.closest('table');
            
            if (newTable) {
                // Replace thead with sorted headers (updated sort icons)
                const newThead = newTable.querySelector('thead');
                const oldThead = oldTable.querySelector('thead');
                if (newThead && oldThead) {
                    oldThead.innerHTML = newThead.innerHTML;
                }
            }

            // Replace tbody content
            tableBody.innerHTML = newTbody.innerHTML;

            // Update URL in address bar without page refresh
            window.history.pushState({ path: sortUrl }, '', sortUrl);

            // Dispatch custom event so other scripts can reinitialize
            const event = new CustomEvent('sort-updated', {
                detail: { tableBodyId: '{{ $tableBodyId }}', url: sortUrl }
            });
            document.dispatchEvent(event);
        })
        .catch(error => {
            console.error('Sort AJAX error:', error);
            // Fallback to regular navigation
            window.location.href = sortUrl;
        })
        .finally(() => {
            if (tbody) tbody.style.opacity = '1';
        });
    });
});
</script>
