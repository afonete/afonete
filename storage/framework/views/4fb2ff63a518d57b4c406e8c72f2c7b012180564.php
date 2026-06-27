<div id="transactionPasswordModal" style="display:none; position:fixed; inset:0; z-index:99999; background:rgba(0,0,0,.65); align-items:center; justify-content:center; padding:16px;">
    <div style="background:#fff; color:#222; width:100%; max-width:420px; border-radius:10px; box-shadow:0 12px 35px rgba(0,0,0,.35); overflow:hidden;">
        <div style="padding:16px 18px; background:#111827; color:#fff; display:flex; justify-content:space-between; align-items:center;">
            <strong>Second Transaction Password</strong>
            <button type="button" id="transactionPasswordClose" style="background:transparent; color:#fff; border:0; font-size:24px; line-height:1; cursor:pointer;">&times;</button>
        </div>
        <div style="padding:18px;">
            <p style="margin:0 0 12px; font-size:14px; color:#555;">
                Enter your second transaction password to confirm this action.
            </p>
            <input type="password" id="transactionPasswordModalInput" class="form-control" placeholder="Second transaction password" autocomplete="current-password" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
            <div id="transactionPasswordModalError" style="display:none; margin-top:8px; color:#dc2626; font-size:13px; font-weight:600;">Transaction password is required.</div>
            <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:18px;">
                <button type="button" id="transactionPasswordCancel" class="btn btn-secondary" style="padding:8px 14px; border-radius:6px; border:1px solid #ccc; background:#f3f4f6; color:#111; cursor:pointer;">Cancel</button>
                <button type="button" id="transactionPasswordConfirm" class="btn btn-primary" style="padding:8px 14px; border-radius:6px; border:0; background:#2563eb; color:#fff; cursor:pointer;">Verify & Continue</button>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    if (window.__transactionPasswordModalLoaded) return;
    window.__transactionPasswordModalLoaded = true;

    let pendingForm = null;
    let verifying = false;

    function modal() { return document.getElementById('transactionPasswordModal'); }
    function input() { return document.getElementById('transactionPasswordModalInput'); }
    function errorBox() { return document.getElementById('transactionPasswordModalError'); }
    function confirmBtn() { return document.getElementById('transactionPasswordConfirm'); }

    function showError(message) {
        errorBox().textContent = message || 'Could not verify transaction password.';
        errorBox().style.display = 'block';
    }

    function openModal(form) {
        pendingForm = form;
        errorBox().style.display = 'none';
        input().value = '';
        confirmBtn().disabled = false;
        confirmBtn().textContent = 'Verify & Continue';
        modal().style.display = 'flex';
        setTimeout(function () { input().focus(); }, 50);
    }

    function closeModal() {
        modal().style.display = 'none';
        pendingForm = null;
        verifying = false;
    }

    document.addEventListener('submit', function (event) {
        const form = event.target;
        if (!form.classList || !form.classList.contains('js-transaction-password-form')) return;

        const hidden = form.querySelector('input[name="transaction_password"]');
        if (!hidden) return;

        // If the hidden password is empty, stop the actual action and show popup.
        // If it is already filled after AJAX verification, allow the submit.
        if (!hidden.value) {
            event.preventDefault();
            openModal(form);
        }
    }, true);

    document.addEventListener('click', function (event) {
        if (event.target && (event.target.id === 'transactionPasswordClose' || event.target.id === 'transactionPasswordCancel')) {
            closeModal();
            return;
        }

        if (event.target && event.target.id === 'transactionPasswordConfirm') {
            if (!pendingForm || verifying) return;

            const value = input().value.trim();
            if (!value) {
                showError('Second transaction password is required.');
                input().focus();
                return;
            }

            verifying = true;
            confirmBtn().disabled = true;
            confirmBtn().textContent = 'Verifying...';
            errorBox().style.display = 'none';

            fetch('<?php echo e(route('transaction-password.verify')); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ transaction_password: value })
            })
            .then(function (response) {
                return response.json().catch(function () {
                    return { ok: false, message: 'Could not verify transaction password. Please try again.' };
                });
            })
            .then(function (data) {
                if (!data.ok) {
                    verifying = false;
                    confirmBtn().disabled = false;
                    confirmBtn().textContent = 'Verify & Continue';
                    showError(data.message || 'Second transaction password is wrong.');
                    input().focus();
                    return;
                }

                const formToSubmit = pendingForm;
                const hidden = formToSubmit.querySelector('input[name="transaction_password"]');
                hidden.value = value;

                // Close popup and submit the original action automatically.
                modal().style.display = 'none';
                pendingForm = null;
                verifying = false;

                HTMLFormElement.prototype.submit.call(formToSubmit);
            })
            .catch(function () {
                verifying = false;
                confirmBtn().disabled = false;
                confirmBtn().textContent = 'Verify & Continue';
                showError('Network/server error while verifying password. Please try again.');
            });
        }
    });

    document.addEventListener('keydown', function (event) {
        if (modal().style.display !== 'flex') return;

        if (event.key === 'Escape') {
            closeModal();
        }

        if (event.key === 'Enter' && document.activeElement === input()) {
            event.preventDefault();
            confirmBtn().click();
        }
    });
})();
</script>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/components/transaction-password-modal.blade.php ENDPATH**/ ?>