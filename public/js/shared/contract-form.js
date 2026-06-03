document.addEventListener('DOMContentLoaded', function () {

    // Buscar todos los bloques con con data-contract-form 
    document.querySelectorAll('[data-contract-form]').forEach(function (container) {
        const contractTypeField = container.querySelector('[data-contract-type]');
        const contractStartDateField = container.querySelector('[data-contract-start-date]');
        const contractEndDateField = container.querySelector('[data-contract-end-date]');
        const contractEndRequiredMark = container.querySelector('[data-contract-end-required]');
        const indefiniteContractType = container.dataset.indefiniteContract;

        if (!contractTypeField || 
            !contractStartDateField || 
            !contractEndDateField || 
            !contractEndRequiredMark) 
        {
            return;
        }

        // Aplicar reglas del tipo de contrato
        const updateContractRules = function () {
            const isIndefinite = contractTypeField.value === indefiniteContractType;

            if (isIndefinite) {
                contractEndDateField.removeAttribute('required');
                contractEndRequiredMark.classList.add('d-none');
                contractEndDateField.value = '';
                contractEndDateField.setCustomValidity('');

                return;
            }

            contractEndDateField.setAttribute('required', 'required');
            contractEndRequiredMark.classList.remove('d-none');
        };

        // Aplicar rango de fechas
        const updateDateRange = function () {
            const startDate = contractStartDateField.value;

            if (!startDate) {
                contractEndDateField.removeAttribute('min');
                contractEndDateField.setCustomValidity('');

                return;
            }

            contractEndDateField.setAttribute('min', startDate);

            if (contractEndDateField.value && contractEndDateField.value < startDate) {
                contractEndDateField.value = '';
            }

            contractEndDateField.setCustomValidity('');
        };

        // Validar fecha de fin
        const validateEndDate = function () {
            if (!contractStartDateField.value || !contractEndDateField.value) {
                contractEndDateField.setCustomValidity('');

                return;
            }

            if (contractEndDateField.value < contractStartDateField.value) {
                contractEndDateField.setCustomValidity('La fecha de fin no puede ser anterior a la fecha de inicio.');

                return;
            }

            contractEndDateField.setCustomValidity('');
        };

        // Sincronizar y ejecutar todas las funcionoees cuando algo cambia
        const refreshContractForm = function () {
            updateContractRules();
            updateDateRange();
            validateEndDate();
        };

        contractTypeField.addEventListener('change', refreshContractForm);
        contractStartDateField.addEventListener('change', refreshContractForm);
        contractEndDateField.addEventListener('change', validateEndDate);
        contractEndDateField.addEventListener('input', validateEndDate);

        refreshContractForm();
    });
});
