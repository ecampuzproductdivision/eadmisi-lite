@extends('layouts.app')

@section('content')
<main class="p-6">
  @if(!$examResult || $examResult->status === 'completed')
    <!-- CTA / Exam Introduction -->
    <div class="row justify-content-center">
      <div class="col-lg-8">
        
        <!-- Header -->
        <div class="text-center mb-5">
          <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
            <i class="ti ti-pencil text-white" style="font-size: 2rem;"></i>
          </div>
          <h2 class="fw-bold mb-2">Ujian Seleksi Online</h2>
          <p class="text-muted mb-0">Uji kemampuan dasar akademik Anda melalui ujian seleksi online PMB</p>
        </div>

        <!-- Stepper -->
        <div class="d-flex align-items-center justify-content-center gap-2 mb-5">
          <div class="stepper-item-sm completed">
            <div class="stepper-circle-sm bg-success text-white"><i class="ti ti-check"></i></div>
            <span class="stepper-label-sm text-success fw-semibold">Data Pribadi</span>
          </div>
          <div class="stepper-line-sm bg-success"></div>
          <div class="stepper-item-sm completed">
            <div class="stepper-circle-sm bg-success text-white"><i class="ti ti-check"></i></div>
            <span class="stepper-label-sm text-success fw-semibold">Prodi</span>
          </div>
          <div class="stepper-line-sm bg-success"></div>
          <div class="stepper-item-sm completed">
            <div class="stepper-circle-sm bg-success text-white"><i class="ti ti-check"></i></div>
            <span class="stepper-label-sm text-success fw-semibold">Berkas</span>
          </div>
          <div class="stepper-line-sm bg-success"></div>
          <div class="stepper-item-sm current">
            <div class="stepper-circle-sm bg-primary text-white fw-bold">4</div>
            <span class="stepper-label-sm text-primary fw-semibold">Ujian</span>
          </div>
          <div class="stepper-line-sm"></div>
          <div class="stepper-item-sm">
            <div class="stepper-circle-sm bg-light text-muted fw-bold">5</div>
            <span class="stepper-label-sm text-muted">Selesai</span>
          </div>
        </div>

        <!-- Exam Info Card -->
        <div class="card border-1 shadow-sm mb-4">
          <div class="card-body p-5">
            <h4 class="fw-bold mb-4"><i class="ti ti-info-circle text-primary me-2"></i>Informasi Ujian</h4>
            
            <div class="row g-4 mb-4">
              <div class="col-md-4">
                <div class="d-flex align-items-center gap-3">
                  <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="ti ti-clock text-primary fs-4"></i>
                  </div>
                  <div>
                    <small class="text-muted d-block">Durasi</small>
                    <strong>15 Menit</strong>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="d-flex align-items-center gap-3">
                  <div class="rounded-circle bg-success-subtle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="ti ti-list-check text-success fs-4"></i>
                  </div>
                  <div>
                    <small class="text-muted d-block">Jumlah Soal</small>
                    <strong>15 Soal</strong>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="d-flex align-items-center gap-3">
                  <div class="rounded-circle bg-warning-subtle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="ti ti-category text-warning fs-4"></i>
                  </div>
                  <div>
                    <small class="text-muted d-block">Kategori</small>
                    <strong>3 Kategori</strong>
                  </div>
                </div>
              </div>
            </div>

            <hr>

            <h6 class="fw-bold mb-3">Kategori Soal:</h6>
            <div class="row g-3 mb-4">
              <div class="col-md-4">
                <div class="card bg-light border-0">
                  <div class="card-body text-center py-3">
                    <i class="ti ti-calculator text-primary fs-4 mb-1"></i>
                    <h6 class="mb-0 fw-semibold">Numerik</h6>
                    <small class="text-muted">5 Soal</small>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="card bg-light border-0">
                  <div class="card-body text-center py-3">
                    <i class="ti ti-article text-success fs-4 mb-1"></i>
                    <h6 class="mb-0 fw-semibold">Verbal</h6>
                    <small class="text-muted">5 Soal</small>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="card bg-light border-0">
                  <div class="card-body text-center py-3">
                    <i class="ti ti-brain text-warning fs-4 mb-1"></i>
                    <h6 class="mb-0 fw-semibold">Logika</h6>
                    <small class="text-muted">5 Soal</small>
                  </div>
                </div>
              </div>
            </div>

            <!-- Rules -->
            <div class="alert alert-warning border-0 mb-4" style="background: #fff8e1;">
              <h6 class="fw-bold mb-2"><i class="ti ti-alert-triangle me-1"></i> Peraturan Ujian:</h6>
              <ul class="mb-0" style="font-size: 0.9rem;">
                <li>Ujian berlangsung selama <strong>15 menit</strong></li>
                <li>Setiap soal hanya memiliki <strong>1 jawaban benar</strong></li>
                <li>Setelah menekan "Selesai Ujian", jawaban <strong>tidak dapat diubah</strong></li>
                <li>Pastikan koneksi internet stabil sebelum memulai</li>
              </ul>
            </div>

            @if($examResult && $examResult->status === 'completed')
              <div class="alert alert-info border-0 mb-0" style="background: #e3f2fd;">
                <h6 class="fw-bold mb-1"><i class="ti ti-check-circle me-1"></i> Ujian Sudah Diselesaikan</h6>
                <p class="mb-0">Skor Anda: <strong>{{ number_format($examResult->score, 1) }}%</strong> ({{ $examResult->correct_answers }}/{{ $examResult->total_questions }} benar)</p>
                <a href="{{ route('daftar-pmb.steps', $path?->code) }}" class="btn btn-primary btn-sm mt-2">
                  <i class="ti ti-arrow-right"></i> Lanjut ke Step Berikutnya
                </a>
              </div>
            @else
              <div class="d-grid">
                <a href="{{ route('daftar-pmb.exam.start', $path?->code) }}" class="btn btn-primary btn-lg fw-semibold py-3" onclick="return confirmAction(event, 'Apakah Anda yakin ingin memulai ujian? Timer akan dimulai segera setelah Anda menekan tombol ini.')">
                  <i class="ti ti-play-circle me-2"></i> Mulai Ujian Sekarang
                </a>
              </div>
            @endif
          </div>
        </div>

        <!-- Back Button -->
        <div class="d-flex justify-content-start mb-4">
          <a href="{{ route('daftar-pmb.steps', $path?->code) }}" class="btn btn-outline-secondary px-4">
            <i class="ti ti-arrow-left me-1"></i> Kembali ke Alur
          </a>
        </div>
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
                  <h6 class="mb-0 fw-bold">Ujian Seleksi Online</h6>
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

            <form action="{{ route('daftar-pmb.exam.answer', $path?->code) }}" method="POST" id="answerForm">
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
                  <a href="{{ route('daftar-pmb.exam.question', [$path?->code, $currentQuestionIndex - 1]) }}" class="btn btn-outline-secondary px-4">
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
                    Selesai Ujian <i class="ti ti-check ms-1"></i>
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
                <a href="{{ route('daftar-pmb.exam.question', [$path?->code, $index]) }}" 
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
  .stepper-item-sm { display: flex; flex-direction: column; align-items: center; gap: 4px; }
  .stepper-circle-sm { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; }
  .stepper-label-sm { font-size: 0.7rem; white-space: nowrap; }
  .stepper-line-sm { width: 50px; height: 3px; background: #dee2e6; margin-bottom: 20px; }
  .stepper-item-sm.current .stepper-circle-sm { box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25); }

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
      // Auto submit
      document.getElementById('answerForm').action = '{{ route("daftar-pmb.exam.submit", $path?->code) }}';
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
      
      // Update circle styles
      document.querySelectorAll('.option-circle').forEach(c => {
        c.classList.remove('bg-primary', 'text-white');
        c.classList.add('bg-light', 'text-muted');
      });
      this.querySelector('.option-circle').classList.remove('bg-light', 'text-muted');
      this.querySelector('.option-circle').classList.add('bg-primary', 'text-white');
    });
  });

  // Submit exam
  async function submitExam() {
    const confirmed = await confirmAsync('Apakah Anda yakin ingin menyelesaikan ujian? Jawaban yang belum diisi akan dianggap salah.', {
      confirmText: 'Ya, Selesaikan Ujian',
      buttonClass: 'btn-primary',
      icon: 'check-circle',
      iconColor: 'text-primary',
      title: 'Konfirmasi Selesai Ujian'
    });
    if (confirmed) {
      document.getElementById('answerForm').action = '{{ route("daftar-pmb.exam.submit", $path?->code) }}';
      document.getElementById('answerForm').submit();
    }
  }
</script>
@endsection
