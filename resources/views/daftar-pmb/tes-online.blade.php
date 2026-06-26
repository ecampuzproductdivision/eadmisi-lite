@extends('layouts.app')

@section('content')
<main class="p-6">
  @if(!isset($currentQuestion) || !$currentQuestion || !isset($examResult) || !$examResult || (isset($examResult) && $examResult && $examResult->status === 'completed'))
    <!-- Main Tes Online Page -->
    <div class="row justify-content-center">
      <div class="col-lg-8">
        
        <!-- Header -->
        <div class="text-center mb-5">
          <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
            <i class="ti ti-pencil text-white" style="font-size: 2rem;"></i>
          </div>
          <h2 class="fw-bold mb-2">Tes Online</h2>
          <p class="text-muted mb-0">Pilih jalur pendaftaran yang sudah lunas untuk memulai tes online.</p>
        </div>

        @if($registrations->isEmpty())
          <!-- No Access -->
          <div class="card border-1 shadow-sm">
            <div class="card-body p-5 text-center">
              <div class="mb-4">
                <div class="rounded-circle bg-warning-subtle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                  <i class="ti ti-lock text-warning" style="font-size: 2rem;"></i>
                </div>
              </div>
              <h4 class="fw-bold mb-2">Belum Ada Akses Tes Online</h4>
              <p class="text-muted mb-4">Anda belum memiliki akses ke Tes Online. Pastikan Anda sudah menyelesaikan pendaftaran dan pembayaran sudah lunas.</p>
              <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('daftar-pmb') }}" class="btn btn-primary px-4">
                  Daftar PMB
                </a>
                <a href="{{ route('tagihan.index') }}" class="btn btn-warning px-4">
                  Cek Tagihan
                </a>
              </div>
            </div>
          </div>
        @else
          <!-- Daftar Jalur yang sudah Lunas -->
          <div class="card border-1 shadow-sm mb-4">
            <div class="card-body p-4">
              <h5 class="fw-bold mb-3"><i class="ti ti-road text-primary me-2"></i>Pilih Jalur Pendaftaran</h5>
              <p class="text-muted mb-0 small">Pilih salah satu jalur pendaftaran yang sudah lunas untuk memulai tes online.</p>
            </div>
          </div>

          @foreach($registrations as $reg)
            @php
              $examResult = $examResults[$reg->id] ?? null;
              $canStart = $reg->status === 'payment_verified' && !$examResult;
              $statusBadge = $examResult ? 'Selesai' : ($reg->status === 'payment_verified' ? 'Siap Tes' : 'Lunas');
              $badgeClass = $examResult ? 'bg-success' : 'bg-info';
            @endphp

            <div class="card border-1 shadow-sm mb-3">
              <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                  <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                      <i class="ti ti-road text-primary fs-4"></i>
                    </div>
                    <div>
                      <h5 class="fw-bold mb-1">{{ $reg->registrationPath?->name ?? '-' }}</h5>
                      <p class="text-muted mb-0 small">{{ $reg->programStudi1?->nama ?? '-' }}</p>
                    </div>
                  </div>
                  <div class="d-flex align-items-center gap-2">
                    <span class="badge {{ $badgeClass }} text-white px-3 py-2">{{ $statusBadge }}</span>
                    @if($canStart)
                      <a href="{{ route('tes-online.start', $reg->id) }}" class="btn btn-primary px-4" onclick="return confirm('Apakah Anda yakin ingin memulai tes untuk {{ $reg->registrationPath?->name }}? Timer akan berjalan setelah ini.');">
                        <i class="ti ti-play-circle me-1"></i> Mulai Tes
                      </a>
                    @elseif($examResult)
                      <span class="badge bg-success-subtle text-success px-3 py-2 d-flex align-items-center gap-1">
                        <i class="ti ti-check me-1"></i> Skor: {{ number_format($examResult->score, 1) }}%
                      </span>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          @endforeach

          <!-- Back -->
          <div class="d-flex justify-content-start mb-4">
            <a href="{{ route('home') }}" class="btn btn-outline-secondary px-4">
              <i class="ti ti-arrow-left me-1"></i> Kembali
            </a>
          </div>
        @endif
      </div>
    </div>

  @else
    <!-- Active Exam -->
    <div class="row justify-content-center">
      <div class="col-lg-8">
        
        <!-- Timer Bar -->
        <div class="card border-1 shadow-sm mb-4 sticky-top" style="top: 80px; z-index: 100;">
          <div class="card-body py-3 px-4">
            <div class="d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                  <i class="ti ti-pencil text-white"></i>
                </div>
                <div>
                  <h6 class="mb-0 fw-bold">Tes Online</h6>
                  <small class="text-muted">Soal {{ $currentQuestionIndex + 1 }} dari {{ $questions->count() }}</small>
                </div>
              </div>
              <div class="d-flex align-items-center gap-3">
                <div id="timer" class="d-flex align-items-center gap-2 px-3 py-2 rounded-3 fw-bold" style="font-size: 1.2rem; background: #fff3e0; color: #e65100;">
                  <i class="ti ti-clock"></i>
                  <span id="timerDisplay">15:00</span>
                </div>
              </div>
            </div>
            <!-- Progress Bar -->
            <div class="progress mt-3" style="height: 6px;">
              <div class="progress-bar bg-primary" style="width: {{ (($currentQuestionIndex + 1) / $questions->count()) * 100 }}%"></div>
            </div>
          </div>
        </div>

        <!-- Question Card -->
        @if($currentQuestion)
        <div class="card border-1 shadow-sm mb-4">
          <div class="card-body p-5">
            <div class="d-flex align-items-center gap-2 mb-3">
              <span class="badge bg-{{ $currentQuestion->category == 'Numerik' ? 'primary' : ($currentQuestion->category == 'Verbal' ? 'success' : 'warning') }}-subtle text-{{ $currentQuestion->category == 'Numerik' ? 'primary' : ($currentQuestion->category == 'Verbal' ? 'success' : 'warning') }} px-3 py-2">
                {{ $currentQuestion->category }}
              </span>
              <span class="badge bg-secondary-subtle text-secondary px-3 py-2">Soal {{ $currentQuestionIndex + 1 }}</span>
            </div>
            
            <h5 class="fw-bold mb-4">{{ $currentQuestion->question }}</h5>

            <form action="{{ route('tes-online.answer') }}" method="POST" id="answerForm">
              @csrf
              <input type="hidden" name="question_id" value="{{ $currentQuestion->id }}">
              <input type="hidden" name="answer" id="selectedAnswer" value="{{ $answers[$currentQuestion->id] ?? '' }}">
              <input type="hidden" name="elapsed_seconds" id="elapsedSeconds" value="{{ $elapsedSeconds ?? 0 }}">

              <div class="d-flex flex-column gap-3 mb-4">
                @foreach($currentQuestion->options as $option)
                  @php
                    $optionLetter = substr($option, 0, 1);
                    $isSelected = isset($answers[$currentQuestion->id]) && $answers[$currentQuestion->id] === $optionLetter;
                  @endphp
                  <label class="option-card {{ $isSelected ? 'selected' : '' }}" data-option="{{ $optionLetter }}">
                    <input type="radio" name="option" value="{{ $optionLetter }}" class="d-none" {{ $isSelected ? 'checked' : '' }}>
                    <div class="d-flex align-items-center gap-3">
                      <div class="option-circle {{ $isSelected ? 'bg-primary text-white' : 'bg-light text-muted' }}">{{ $optionLetter }}</div>
                      <span>{{ substr($option, 3) }}</span>
                    </div>
                  </label>
                @endforeach
              </div>

              <div class="d-flex justify-content-between">
                @if($currentQuestionIndex > 0)
                  <a href="{{ route('tes-online.question', [$registration->id, $currentQuestionIndex - 1]) }}" class="btn btn-outline-secondary px-4">
                    <i class="ti ti-arrow-left me-1"></i> Sebelumnya
                  </a>
                @else
                  <div></div>
                @endif

                @if($currentQuestionIndex < $questions->count() - 1)
                  <button type="submit" class="btn btn-primary px-4" id="nextBtn" disabled>
                    Selanjutnya <i class="ti ti-arrow-right ms-1"></i>
                  </button>
                @else
                  <button type="button" class="btn btn-danger px-4 fw-semibold" onclick="submitExam()">
                    Selesai Tes <i class="ti ti-check ms-1"></i>
                  </button>
                @endif
              </div>
            </form>
          </div>
        </div>

        <!-- Question Navigator -->
        <div class="card border-1 shadow-sm mb-4">
          <div class="card-body p-4">
            <h6 class="fw-bold mb-3">Navigasi Soal</h6>
            <div class="d-flex flex-wrap gap-2">
              @foreach($questions as $index => $q)
                @php
                  $isAnswered = isset($answers[$q->id]);
                  $isCurrent = $index == $currentQuestionIndex;
                @endphp
                <a href="{{ route('tes-online.question', [$registration->id, $index]) }}" 
                   class="nav-question {{ $isCurrent ? 'current' : ($isAnswered ? 'answered' : 'unanswered') }}">
                  {{ $index + 1 }}
                </a>
              @endforeach
            </div>
            <div class="d-flex gap-4 mt-3" style="font-size: 0.8rem;">
              <span><span class="nav-dot answered"></span> Terjawab</span>
              <span><span class="nav-dot current"></span> Sedang Dikerjakan</span>
              <span><span class="nav-dot unanswered"></span> Belum Dijawab</span>
            </div>
          </div>
        </div>
        @endif
      </div>
    </div>
  @endif
</main>

<style>
  .option-card {
    display: block;
    padding: 16px 20px;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
    background: #fff;
  }
  .option-card:hover {
    border-color: #0d6efd;
    background: #f8faff;
  }
  .option-card.selected {
    border-color: #0d6efd;
    background: #e7f1ff;
  }
  .option-circle {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    flex-shrink: 0;
  }

  .nav-question {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.85rem;
    text-decoration: none;
    transition: all 0.2s ease;
  }
  .nav-question.answered { background: #d4edda; color: #155724; border: 2px solid #28a745; }
  .nav-question.current { background: #0d6efd; color: #fff; border: 2px solid #0d6efd; }
  .nav-question.unanswered { background: #f8f9fa; color: #6c757d; border: 2px solid #dee2e6; }

  .nav-dot {
    display: inline-block;
    width: 12px;
    height: 12px;
    border-radius: 3px;
    margin-right: 4px;
  }
  .nav-dot.answered { background: #28a745; }
  .nav-dot.current { background: #0d6efd; }
  .nav-dot.unanswered { background: #dee2e6; }
</style>

<script>
  // Timer
  const totalSeconds = 15 * 60; // 15 minutes
  let elapsedSeconds = parseInt(document.getElementById('elapsedSeconds')?.value || 0);
  let remainingSeconds = totalSeconds - elapsedSeconds;

  const timerDisplay = document.getElementById('timerDisplay');

  function updateTimer() {
    if (remainingSeconds <= 0) {
      document.getElementById('answerForm').action = '{{ route("tes-online.submit") }}';
      document.getElementById('answerForm').submit();
      return;
    }

    const minutes = Math.floor(remainingSeconds / 60);
    const seconds = remainingSeconds % 60;
    timerDisplay.textContent = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');

    if (remainingSeconds <= 60) {
      document.getElementById('timer').style.background = '#ffebee';
      document.getElementById('timer').style.color = '#c62828';
    }

    elapsedSeconds++;
    remainingSeconds--;
    document.getElementById('elapsedSeconds').value = elapsedSeconds;
  }

  setInterval(updateTimer, 1000);
  updateTimer();

  // Option selection
  document.querySelectorAll('.option-card').forEach(card => {
    card.addEventListener('click', function() {
      document.querySelectorAll('.option-card').forEach(c => c.classList.remove('selected'));
      this.classList.add('selected');
      
      const option = this.dataset.option;
      document.getElementById('selectedAnswer').value = option;
      document.getElementById('nextBtn').disabled = false;
      
      document.querySelectorAll('.option-circle').forEach(c => {
        c.classList.remove('bg-primary', 'text-white');
        c.classList.add('bg-light', 'text-muted');
      });
      this.querySelector('.option-circle').classList.remove('bg-light', 'text-muted');
      this.querySelector('.option-circle').classList.add('bg-primary', 'text-white');
    });
  });

  function submitExam() {
    if (confirm('Apakah Anda yakin ingin menyelesaikan tes? Jawaban yang belum diisi akan dianggap salah.')) {
      document.getElementById('answerForm').action = '{{ route("tes-online.submit") }}';
      document.getElementById('answerForm').submit();
    }
  }
</script>
@endsection
