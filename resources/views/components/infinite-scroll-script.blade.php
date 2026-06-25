@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const scrollContainer = document.querySelector('.data-page-table-scroll');
    const tableBody = document.getElementById('{{ $tableBodyId ?? "table-body" }}');
    const spinner = document.getElementById('{{ $spinnerId ?? "loading-spinner" }}');
    const sentinel = document.getElementById('{{ $sentinelId ?? "scroll-sentinel" }}');
    const showingCount = document.getElementById('showing-count');
    const totalCount = document.getElementById('total-count');
    
    if (!tableBody || !sentinel || !scrollContainer) return;
    
    // Count actual rows visible on initial load (excluding "no data" row)
    let currentShowing = 0;
    tableBody.querySelectorAll('tr').forEach(tr => {
        const td = tr.querySelector('td');
        if (td && td.getAttribute('colspan')) return; // skip empty/placeholder rows
        currentShowing++;
    });
    if (showingCount) showingCount.textContent = currentShowing;
    
    let nextPageUrl = '{{ $nextPageUrl ?? "" }}';
    let hasMore = {{ ($hasMore ?? false) ? 'true' : 'false' }};
    let isLoading = false;

    // Use IntersectionObserver with the scrollable container as root
    // This ensures detection works when scrolling inside the table container
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting && hasMore && nextPageUrl && !isLoading) {
                loadMore();
            }
        });
    }, { 
        root: scrollContainer,
        rootMargin: '200px' 
    });

    observer.observe(sentinel);

    function loadMore() {
        isLoading = true;
        if (spinner) spinner.classList.remove('d-none');
        
        fetch(nextPageUrl, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.html) {
                const temp = document.createElement('div');
                temp.innerHTML = data.html;
                const newRows = [];
                temp.querySelectorAll('tr').forEach(row => {
                    const td = row.querySelector('td');
                    if (td && td.getAttribute('colspan')) return; // skip empty/placeholder rows
                    newRows.push(row);
                });
                // Insert new rows before the sentinel
                newRows.forEach(row => sentinel.parentNode.insertBefore(row, sentinel));
                currentShowing += newRows.length;
                if (showingCount) showingCount.textContent = currentShowing;
            }
            nextPageUrl = data.next_page;
            hasMore = data.has_more;
            if (data.total && totalCount) {
                totalCount.textContent = data.total;
            }
            isLoading = false;
            if (spinner) spinner.classList.add('d-none');
            
            // Disconnect observer if no more data
            if (!hasMore) {
                observer.unobserve(sentinel);
                sentinel.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            isLoading = false;
            if (spinner) spinner.classList.add('d-none');
        });
    }
});
</script>
@endpush
