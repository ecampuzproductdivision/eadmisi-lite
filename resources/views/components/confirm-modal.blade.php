<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" id="confirmModalLabel">Konfirmasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-3">
                <div class="d-flex align-items-start gap-3">
                    <div class="confirm-modal-icon-wrapper flex-shrink-0" id="confirmModalIcon">
                        <i class="ti ti-alert-triangle fs-1 text-warning"></i>
                    </div>
                    <div>
                        <p class="mb-0" id="confirmModalMessage">Apakah Anda yakin?</p>
                        <small class="text-muted" id="confirmModalSubmessage"></small>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal" id="confirmModalCancel">Batal</button>
                <button type="button" class="btn" id="confirmModalConfirm">Ya, Hapus!</button>
            </div>
        </div>
    </div>
</div>

<style>
.confirm-modal-icon-wrapper {
    width: 48px;
    height: 48px;
    background-color: var(--ds-warning-subtle, #fff3cd);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

[data-bs-theme="dark"] .confirm-modal-icon-wrapper {
    background-color: rgba(255, 193, 7, 0.15);
}

#confirmModalConfirm.btn-danger {
    background-color: var(--ds-danger, #dc3545);
    border-color: var(--ds-danger, #dc3545);
    color: #fff;
}
#confirmModalConfirm.btn-danger:hover {
    background-color: var(--ds-danger-hover, #bb2d3b);
    border-color: var(--ds-danger-hover, #b02a37);
}
#confirmModalConfirm.btn-primary {
    background-color: var(--ds-primary, #f63a4c);
    border-color: var(--ds-primary, #f63a4c);
    color: #fff;
}
#confirmModalConfirm.btn-primary:hover {
    background-color: var(--ds-primary-hover, #d82939);
    border-color: var(--ds-primary-hover, #d82939);
}
#confirmModalConfirm.btn-success {
    background-color: var(--ds-success, #198754);
    border-color: var(--ds-success, #198754);
    color: #fff;
}
#confirmModalConfirm.btn-success:hover {
    background-color: var(--ds-success-hover, #157347);
    border-color: var(--ds-success-hover, #146c43);
}
#confirmModalConfirm.btn-warning {
    background-color: var(--ds-warning, #ffc107);
    border-color: var(--ds-warning, #ffc107);
    color: #000;
}
</style>

<script>
/**
 * Global confirm dialog using Bootstrap modal
 * Usage:
 *   // For onclick (synchronous)
 *   confirmAction(event, 'Pesan konfirmasi', { confirmText: 'Ya!', buttonClass: 'btn-danger' })
 *
 *   // For onsubmit (return boolean)
 *   confirmSubmit(event, 'Pesan konfirmasi')
 *
 *   // For async JS functions
 *   const result = await showConfirmDialog('Pesan konfirmasi', { ... })
 *   if (!result) return;
 */
(function() {
    let confirmResolve = null;
    const modalEl = document.getElementById('confirmModal');
    if (!modalEl) return;

    const modal = new bootstrap.Modal(modalEl, {
        backdrop: 'static',
        keyboard: false
    });

    const messageEl = document.getElementById('confirmModalMessage');
    const submessageEl = document.getElementById('confirmModalSubmessage');
    const confirmBtn = document.getElementById('confirmModalConfirm');
    const cancelBtn = document.getElementById('confirmModalCancel');
    const closeBtn = modalEl.querySelector('.btn-close');
    const iconWrapper = document.getElementById('confirmModalIcon');

    function resetModal() {
        confirmBtn.className = 'btn';
        confirmBtn.textContent = 'Ya, Hapus!';
        confirmBtn.className = 'btn btn-danger';
        iconWrapper.innerHTML = '<i class="ti ti-alert-triangle fs-1 text-warning"></i>';
        submessageEl.textContent = '';
    }

    function showConfirmDialog(message, options = {}) {
        return new Promise((resolve) => {
            resetModal();

            const opts = {
                confirmText: options.confirmText || 'Ya, Hapus!',
                buttonClass: options.buttonClass || 'btn-danger',
                icon: options.icon || 'alert-triangle',
                iconColor: options.iconColor || 'text-warning',
                submessage: options.submessage || '',
                title: options.title || 'Konfirmasi',
                ...options
            };

            messageEl.textContent = message;
            submessageEl.textContent = opts.submessage;
            document.getElementById('confirmModalLabel').textContent = opts.title;
            confirmBtn.textContent = opts.confirmText;
            confirmBtn.className = 'btn ' + opts.buttonClass;
            iconWrapper.innerHTML = '<i class="ti ti-' + opts.icon + ' fs-1 ' + opts.iconColor + '"></i>';

            confirmResolve = resolve;
            modal.show();
        });
    }

    // Clean up event listeners to avoid duplicates
    function cleanupListeners() {
        const newConfirmBtn = confirmBtn.cloneNode(true);
        confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
        return newConfirmBtn;
    }

    modalEl.addEventListener('shown.bs.modal', function() {
        const btn = cleanupListeners();
        btn.addEventListener('click', function() {
            modal.hide();
            if (confirmResolve) {
                confirmResolve(true);
                confirmResolve = null;
            }
        });

        // Handle Enter key
        modalEl.addEventListener('keydown', function handler(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                modal.hide();
                if (confirmResolve) {
                    confirmResolve(true);
                    confirmResolve = null;
                }
                modalEl.removeEventListener('keydown', handler);
            }
        });
    });

    modalEl.addEventListener('hidden.bs.modal', function() {
        // If resolve wasn't called (user clicked X or Batal), resolve false
        setTimeout(() => {
            if (confirmResolve) {
                confirmResolve(false);
                confirmResolve = null;
            }
        }, 100);
    });

    // Expose to window
    window.showConfirmDialog = showConfirmDialog;

    /**
     * For onclick attribute: returns false to prevent default, shows modal
     * Usage: onclick="return confirmAction(event, 'Pesan')"
     */
    window.confirmAction = function(event, message, options = {}) {
        event.preventDefault();
        event.stopPropagation();
        const target = event.currentTarget;
        showConfirmDialog(message, options).then(result => {
            if (result && target) {
                // Support data-form-id to submit a specific form
                if (target.dataset.formId) {
                    const form = document.getElementById(target.dataset.formId);
                    if (form) form.submit();
                } else if (target.tagName === 'A') {
                    // Only navigate if href is not "#" or "#!"
                    if (target.href && target.href !== '#' && target.href !== window.location.href + '#!' && target.href !== window.location.href + '#') {
                        window.location.href = target.href;
                    }
                } else if (target.tagName === 'BUTTON' && target.form) {
                    target.form.submit();
                } else if (target.dataset.href) {
                    window.location.href = target.dataset.href;
                } else if (target.onclickConfirmed) {
                    target.onclickConfirmed();
                }
            }
        });
        return false;
    };

    /**
     * For onsubmit attribute: returns false to prevent submit, shows modal
     * Usage: onsubmit="return confirmSubmit(event, 'Pesan')"
     */
    window.confirmSubmit = function(event, message, options = {}) {
        event.preventDefault();
        const form = event.target;
        showConfirmDialog(message, options).then(result => {
            if (result && form) {
                if (typeof form.submit === 'function') {
                    form.submit();
                } else {
                    HTMLFormElement.prototype.submit.call(form);
                }
            }
        });
        return false;
    };

    /**
     * For async usage in JavaScript functions
     */
    window.confirmAsync = async function(message, options = {}) {
        return await showConfirmDialog(message, options);
    };
})();
</script>