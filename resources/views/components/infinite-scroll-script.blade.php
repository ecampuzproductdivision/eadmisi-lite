@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const scrollContainer = document.querySelector('.data-page-table-scroll');
    const tableBody = document.getElementById('{{ $tableBodyId ?? "table-body" }}');
    const spinner = document.getElementById('{{ $spinnerId ?? "loading-spinner" }}');
    
    if (!scrollContainer || !tableBody) return;
    
    let nextPageUrl = '{{ $nextPageUrl ?? "" }}';
    let hasMore = {{ ($hasMore ?? false) ? 'true' : 'false' }};
    let isLoading = false;

    scrollContainer.addEventListener('scroll', function() {
        if (isLoading || !hasMore || !nextPageUrl) return;
        if (scrollContainer.scrollTop + scrollContainer.clientHeight >= scrollContainer.scrollHeight - 50) {
            loadMore();
        }
    });

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
                temp.querySelectorAll('tr').forEach(row => tableBody.appendChild(row));
            }
            nextPageUrl = data.next_page;
            hasMore = data.has_more;
            isLoading = false;
            if (spinner) spinner.classList.add('d-none');
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