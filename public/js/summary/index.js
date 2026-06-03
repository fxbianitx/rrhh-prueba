document.addEventListener('DOMContentLoaded', function () {
    const form = $('#employee-summary-form');
    const feedback = $('#employee-summary-feedback');
    const tableBody = $('#summary-table-body');
    const total = $('#summary-total');

    if (!form.length || !feedback.length || !tableBody.length || !total.length) {
        return;
    }

    function getSummaryUrl() {
        const url = new URL(form.data('url'), window.location.origin);
        const formData = form.serializeArray();

        formData.forEach(function (field) {
            if (field.value) {
                url.searchParams.set(field.name, field.value);
            }
        });

        return url.toString();
    }

    function renderRows(areas) {
        if (!areas.length) {
            tableBody.html(`
                <tr>
                    <td colspan="2" class="text-center text-muted">No hay empleados activos para la fecha consultada.</td>
                </tr>
            `);

            return;
        }

        tableBody.html(areas.map(function (area) {
            return `
                <tr>
                    <td>${area.area_name}</td>
                    <td>${area.total}</td>
                </tr>
            `;
        }).join(''));
    }

    function fetchSummary() {
        window.getJson(getSummaryUrl())
            .done(function (summary) {
                feedback.addClass('d-none');
                total.text(`Total: ${summary.total_employees}`);
                total.removeClass('d-none');
                renderRows(summary.areas);
            })
            .fail(function () {
                feedback.text('No se pudo obtener el resumen.');
                feedback.removeClass('d-none');
                total.addClass('d-none');
            });
    }

    form.on('submit', function (event) {
        event.preventDefault();
        fetchSummary();
    });
});
