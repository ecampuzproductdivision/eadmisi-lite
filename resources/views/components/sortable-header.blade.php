@props(['field', 'label', 'width' => 'auto', 'class' => '', 'align' => 'left'])

@php
    $state = \App\Helpers\SortHelper::state($field);
    $url = $state['url'];
    $icon = $state['icon'];
    $isSorted = $state['is_sorted'];
    $direction = $state['direction'];
@endphp

<th scope="col" class="py-3 sortable-th {{ $class }}" 
    style="width: {{ $width }}; cursor: pointer; text-align: {{ $align }};" 
    data-sort-field="{{ $field }}"
    data-sort-url="{{ $url }}"
    data-sort-ajax-url="{{ $url }}&sort_ajax=1">
    <a href="{{ $url }}" class="text-decoration-none d-inline-flex align-items-center gap-1 sortable-header" style="justify-content: {{ $align === 'right' ? 'flex-end' : 'flex-start' }};" onclick="return false;">
        <span>{{ $label }}</span>
        <i class="ti {{ $icon }} {{ $isSorted ? 'sort-icon-active' : 'sort-icon-muted' }} fs-6 sort-icon"></i>
    </a>
</th>