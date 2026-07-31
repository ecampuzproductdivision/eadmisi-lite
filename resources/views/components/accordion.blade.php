{{-- 
  Reusable Accordion Component
  Usage:
  @include('components.accordion', [
      'id' => 'faqAccordion',
      'flush' => false, // optional, default false
      'items' => [
          ['id' => 'faq1', 'title' => 'Question?', 'content' => 'Answer...', 'show' => false],
          ['id' => 'faq2', 'title' => 'Another question?', 'content' => 'Another answer...', 'show' => true],
      ],
  ])

  For custom content (not just text), use slot-based approach:
  @component('components.accordion', ['id' => 'myAccordion', 'items' => [...]])
  @endcomponent
--}}
@php
    $accordionId = $id ?? 'accordion-' . uniqid();
    $flush = $flush ?? false;
    $alwaysOpen = $alwaysOpen ?? false;
@endphp

<div class="accordion {{ $flush ? 'accordion-flush' : '' }}" id="{{ $accordionId }}">
    @foreach($items as $index => $item)
        @php
            $itemId = $item['id'] ?? $accordionId . '-item-' . $index;
            $headingId = 'heading-' . $itemId;
            $collapseId = 'collapse-' . $itemId;
            $show = $item['show'] ?? false;
            $icon = $item['icon'] ?? null;
            $class = $item['class'] ?? '';
        @endphp
        <div class="accordion-item {{ $item['item_class'] ?? 'border-0 mb-2' }} {{ $class }}">
            <h2 class="accordion-header" id="{{ $headingId }}">
                <button class="accordion-button {{ $show ? '' : 'collapsed' }} fw-semibold py-3 px-4 d-flex align-items-center gap-2"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#{{ $collapseId }}"
                        aria-expanded="{{ $show ? 'true' : 'false' }}"
                        aria-controls="{{ $collapseId }}">
                    @if($icon)
                        <i class="{{ $icon }}"></i>
                    @endif
                    <span class="flex-grow-1">{{ $item['title'] }}</span>
                    <i class="ti ti-chevron-down accordion-chevron fs-5 text-muted"></i>
                </button>
            </h2>
            <div id="{{ $collapseId }}"
                 class="accordion-collapse collapse {{ $show ? 'show' : '' }}"
                 aria-labelledby="{{ $headingId }}"
                 @if(!$alwaysOpen) data-bs-parent="#{{ $accordionId }}" @endif>
                <div class="accordion-body {{ $item['body_class'] ?? 'px-4 py-3' }}">
                    @if(isset($item['content']))
                        {!! $item['content'] !!}
                    @elseif(isset($item['slot']))
                        {{ $item['slot'] }}
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>

@push('styles')
<style>
    .accordion-chevron {
        transition: transform 0.3s ease;
    }
    /* Chevron down when collapsed (inactive), chevron up when expanded (active) via 180deg rotation */
    .accordion-button:not(.collapsed) .accordion-chevron {
        transform: rotate(180deg);
    }
    .accordion-button:focus {
        box-shadow: none;
    }
    .accordion-button:not(.collapsed) {
        background: transparent;
        box-shadow: none;
    }
    .accordion-button::after {
        display: none !important;
    }
</style>
@endpush