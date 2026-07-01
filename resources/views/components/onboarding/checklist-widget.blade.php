{{-- 
  Onboarding Checklist Widget
  Floating widget at bottom-right showing tutorial step progress.
  Tutorials are grouped — each tutorial contains its own steps.
--}}
<div id="onboardingChecklistWidget" class="position-fixed" style="bottom: 20px; right: 20px; z-index: 1050; display: none;">
  {{-- Floating toggle button --}}
  <button id="onboardingToggleBtn" class="btn btn-primary rounded-circle shadow d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;" title="Onboarding Progress">
    <i class="ti ti-notebook fs-5"></i>
  </button>

  {{-- Checklist panel --}}
  <div id="onboardingChecklistPanel" class="card shadow border-0 mt-2" style="width: 320px; display: none; position: absolute; bottom: 56px; right: 0; border-radius: 12px;">
    <div class="card-header bg-white border-bottom-0 d-flex align-items-center justify-content-between pt-3 px-3 pb-0">
      <h4 class="fw-bold mb-0"><i class="ti ti-notebook me-2 text-primary"></i>Panduan Aplikasi</h4>
      <button type="button" class="btn-close" id="closeOnboardingChecklist" aria-label="Close"></button>
    </div>
    <div class="card-body px-3 py-2">
      <p class="text-muted mb-2 small">Selesaikan tutorial berikut untuk mengenali aplikasi:</p>
      <div id="onboardingTutorialList">
        {{-- Tutorials will be rendered dynamically by JavaScript --}}
      </div>
    </div>
    <div class="card-footer bg-white border-top-0 px-3 pb-3 pt-0">
      <button type="button" class="btn btn-primary w-100" id="restartOnboardingTour">
        <i class="ti ti-refresh me-1"></i> Mulai Ulang Tur
      </button>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const widget = document.getElementById('onboardingChecklistWidget');
  const toggleBtn = document.getElementById('onboardingToggleBtn');
  const panel = document.getElementById('onboardingChecklistPanel');
  const closeBtn = document.getElementById('closeOnboardingChecklist');
  const restartBtn = document.getElementById('restartOnboardingTour');
  const tutorialList = document.getElementById('onboardingTutorialList');

  if (!widget) return;

  // Toggle panel
  toggleBtn?.addEventListener('click', function(e) {
    e.stopPropagation();
    const isVisible = panel.style.display !== 'none';
    panel.style.display = isVisible ? 'none' : 'block';
  });

  // Close panel
  closeBtn?.addEventListener('click', function() {
    panel.style.display = 'none';
  });

  // Close panel when clicking outside
  document.addEventListener('click', function(e) {
    if (panel.style.display === 'block' && !widget.contains(e.target)) {
      panel.style.display = 'none';
    }
  });

  // Restart tour
  restartBtn?.addEventListener('click', function() {
    fetch('{{ route("onboarding.reset") }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content, 'Content-Type': 'application/json' } })
      .then(() => {
        panel.style.display = 'none';
        if (typeof startDashboardTour === 'function') {
          setTimeout(() => startDashboardTour(), 300);
        }
      });
  });

  /**
   * Render all tutorials into the checklist.
   * @param {object} tutorialsProgress - e.g. { onboarding: { completed_steps: [...], is_completed: bool } }
   * @param {object} tutorialsDef - tutorial definitions from server: { onboarding: { id, title, steps: [...] } }
   */
  function renderTutorials(tutorialsProgress, tutorialsDef) {
    if (!tutorialList) return;
    if (!tutorialsDef) return;

    tutorialList.innerHTML = '';

    Object.values(tutorialsDef).forEach(function(tutorial) {
      const tId = tutorial.id;
      const tProgress = tutorialsProgress?.[tId] || { completed_steps: [], is_completed: false };
      const completedSteps = tProgress.completed_steps || [];
      const totalSteps = tutorial.steps.length;
      const doneCount = completedSteps.length;
      const isCompleted = tProgress.is_completed || doneCount >= totalSteps;

      // Tutorial wrapper
      const tutorialDiv = document.createElement('div');
      tutorialDiv.className = 'tutorial-group mb-2';
      tutorialDiv.dataset.tutorialId = tId;

      // Tutorial header (clickable to toggle steps)
      const header = document.createElement('div');
      header.className = 'tutorial-header d-flex align-items-center gap-2 py-2 px-2 rounded cursor-pointer';
      header.style.cursor = 'pointer';
      header.style.border = '1px solid #e2e8f0';
      header.style.borderRadius = '8px';
      header.style.transition = 'background 0.15s';
      header.onmouseover = function() { this.style.backgroundColor = '#f8fafc'; };
      header.onmouseout = function() { this.style.backgroundColor = ''; };

      // Expand/collapse icon
      const expandIcon = document.createElement('i');
      expandIcon.className = 'ti ti-chevron-down fs-6 text-muted';
      expandIcon.style.transition = 'transform 0.2s';

      // Checkbox / status icon
      const statusIcon = document.createElement('i');
      if (isCompleted) {
        statusIcon.className = 'ti ti-circle-check fs-5 text-success';
      } else {
        statusIcon.className = 'ti ti-circle fs-5 text-muted';
      }

      // Tutorial title
      const title = document.createElement('span');
      title.className = 'fw-semibold small flex-grow-1';
      title.textContent = tutorial.title;

      // Progress badge
      const badge = document.createElement('span');
      badge.className = 'badge ' + (isCompleted ? 'bg-success-subtle text-success' : 'bg-light text-muted');
      badge.style.fontSize = '0.7rem';
      badge.textContent = doneCount + '/' + totalSteps;

      header.appendChild(expandIcon);
      header.appendChild(statusIcon);
      header.appendChild(title);
      header.appendChild(badge);

      // Steps container (collapsible)
      const stepsContainer = document.createElement('div');
      stepsContainer.className = 'tutorial-steps';
      stepsContainer.style.display = isCompleted ? 'none' : 'block';
      stepsContainer.style.paddingLeft = '28px';

      // Render steps
      tutorial.steps.forEach(function(step) {
        const stepItem = document.createElement('div');
        stepItem.className = 'py-1 d-flex align-items-center gap-2';
        stepItem.dataset.step = step.id;

        const check = document.createElement('span');
        check.className = 'step-check d-flex align-items-center justify-content-center';
        check.style.width = '18px';
        check.style.height = '18px';
        check.style.borderRadius = '4px';
        check.style.border = '2px solid #dee2e6';
        check.style.flexShrink = '0';

        const checkIcon = document.createElement('i');
        checkIcon.className = 'ti ti-check';
        checkIcon.style.fontSize = '10px';
        checkIcon.style.opacity = '0';
        check.appendChild(checkIcon);

        const stepLabel = document.createElement('span');
        stepLabel.className = 'small';
        stepLabel.textContent = step.label;

        stepItem.appendChild(check);
        stepItem.appendChild(stepLabel);

        // Mark as completed if done
        if (completedSteps.includes(step.id)) {
          check.style.borderColor = '#198754';
          check.style.backgroundColor = '#198754';
          checkIcon.style.opacity = '1';
          checkIcon.style.color = '#fff';
          stepItem.style.opacity = '0.6';
        }

        stepsContainer.appendChild(stepItem);
      });

      // Toggle expand/collapse on header click
      header.addEventListener('click', function() {
        const isVisible = stepsContainer.style.display !== 'none';
        stepsContainer.style.display = isVisible ? 'none' : 'block';
        expandIcon.style.transform = isVisible ? 'rotate(-90deg)' : 'rotate(0deg)';
      });

      // Default collapsed state for completed tutorials
      if (isCompleted) {
        expandIcon.style.transform = 'rotate(-90deg)';
      }

      tutorialDiv.appendChild(header);
      tutorialDiv.appendChild(stepsContainer);
      tutorialList.appendChild(tutorialDiv);
    });
  }

  /**
   * Update checklist from server progress.
   * @param {object} tutorialsProgress - the tutorials_progress object from server
   */
  function updateChecklist(tutorialsProgress) {
    if (!tutorialsProgress) return;

    // We need tutorials definition — fetch from server if not available
    if (!window._tutorialsDef) {
      fetch('{{ route("onboarding.progress") }}')
        .then(r => r.json())
        .then(data => {
          if (data.tutorials) {
            window._tutorialsDef = data.tutorials;
            renderTutorials(tutorialsProgress, data.tutorials);
          }
        })
        .catch(() => {});
    } else {
      renderTutorials(tutorialsProgress, window._tutorialsDef);
    }
  }

  // Load initial progress
  fetch('{{ route("onboarding.progress") }}')
    .then(r => r.json())
    .then(data => {
      if (data.tutorials) {
        window._tutorialsDef = data.tutorials;
      }
      if (data.tutorials_progress) {
        renderTutorials(data.tutorials_progress, data.tutorials);
      }
    });

  // Expose update function globally
  window.updateOnboardingChecklist = updateChecklist;
});
</script>
@endpush