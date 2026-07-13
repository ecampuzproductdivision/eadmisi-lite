{{-- 
  Reusable Actions Dropdown Component
  Usage: 
  @include('components.actions-dropdown', ['items' => [
      ['url' => route('...'), 'icon' => 'ti ti-edit', 'label' => 'Edit'],
      ['divider' => true],
      ['url' => route('...'), 'icon' => 'ti ti-trash', 'label' => 'Delete', 'class' => 'text-danger', 'method' => 'DELETE', 'confirm' => 'Are you sure?'],
      ['modal' => '#modalId', 'icon' => 'ti ti-calendar', 'label' => 'Schedule', 'data' => ['id' => 1, 'name' => 'John']],
      ['onclick' => "functionName(id)", 'icon' => 'ti ti-trash', 'label' => 'Delete', 'class' => 'text-danger'],
      ['html' => '<span>Custom HTML</span>'],
  ]])
--}}
<div class="dropdown">
    <button class="btn btn-sm btn-light border dropdown-actions-btn" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" title="Actions">
        <i class="ti ti-dots-vertical fs-5"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
        @foreach($items as $item)
            @if(isset($item['divider']) && $item['divider'])
                <li><hr class="dropdown-divider"></li>
            @elseif(isset($item['modal']))
                <li>
                    <button type="button" class="dropdown-item {{ $item['class'] ?? '' }}"
                            data-bs-toggle="modal" data-bs-target="{{ $item['modal'] }}"
                            @foreach(($item['data'] ?? []) as $key => $val)
                                data-{{ $key }}="{{ $val }}"
                            @endforeach
                            @if(isset($item['title']))
                                title="{{ $item['title'] }}"
                            @endif>
                        <i class="{{ $item['icon'] }} me-2"></i> {{ $item['label'] }}
                    </button>
                </li>
            @elseif(isset($item['onclick']))
                <li>
                    <button type="button" class="dropdown-item {{ $item['class'] ?? '' }}"
                            onclick="{{ $item['onclick'] }}"
                            @if(isset($item['title']))
                                title="{{ $item['title'] }}"
                            @endif>
                        <i class="{{ $item['icon'] }} me-2"></i> {{ $item['label'] }}
                    </button>
                </li>
            @elseif(isset($item['url']))
                <li>
                    @if(isset($item['method']) && in_array($item['method'], ['DELETE', 'POST', 'PUT']))
                        <form action="{{ $item['url'] }}" method="POST" class="d-inline">
                            @csrf
                            @method($item['method'])
                            @if(isset($item['confirm']))
                                <button type="submit" class="dropdown-item {{ $item['class'] ?? '' }}"
                                        onclick="return confirmAction(event, '{{ addslashes($item['confirm']) }}');">
                                    <i class="{{ $item['icon'] }} me-2"></i> {{ $item['label'] }}
                                </button>
                            @else
                                <button type="submit" class="dropdown-item {{ $item['class'] ?? '' }}">
                                    <i class="{{ $item['icon'] }} me-2"></i> {{ $item['label'] }}
                                </button>
                            @endif
                        </form>
                    @else
                        <a class="dropdown-item {{ $item['class'] ?? '' }}" href="{{ $item['url'] }}"
                           @if(isset($item['title'])) title="{{ $item['title'] }}" @endif>
                            <i class="{{ $item['icon'] }} me-2"></i> {{ $item['label'] }}
                        </a>
                    @endif
                </li>
            @elseif(isset($item['html']))
                <li>{!! $item['html'] !!}</li>
            @endif
        @endforeach
    </ul>
</div>