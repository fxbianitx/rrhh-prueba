document.addEventListener('DOMContentLoaded', function () {
    const form = $('#employee-search-form');
    const resetButton = $('#employee-search-reset');
    const feedback = $('#employee-search-feedback');
    const tableWrapper = $('#employees-table-wrapper');
    const searchInput = $('#search');
    const sortByInput = $('#sort_by');
    const sortDirectionInput = $('#sort_direction');

    if (!form.length || !feedback.length || !tableWrapper.length || !sortByInput.length || !sortDirectionInput.length) {
        return;
    }

    //! Obtener URL de busqueda
    function getSearchUrl(page) {
        const url = new URL(form.data('url'), window.location.origin);
        const formData = form.serializeArray();

        formData.forEach(function (field) {
            if (field.value) {
                url.searchParams.set(field.name, field.value);
            }
        });

        url.searchParams.set('page', page);

        return url.toString();
    }

    //! Actualizar tabla de empleados
    function renderTable(html) {
        tableWrapper.html(html);
    }

    //! Consultar empleados
    function fetchEmployees(page) {
        window.getJson(getSearchUrl(page))
            .done(function (result) {
                feedback.addClass('d-none');
                renderTable(result.html);
                bindSortEvents();
                bindPaginationEvents();
            })
            .fail(function () {
                feedback.text('No se pudo completar la busqueda.');
                feedback.removeClass('d-none');
            });
    }

    //! Reiniciar filtros
    function resetSearch() {
        form.trigger('reset');
        sortByInput.val('full_name');
        sortDirectionInput.val('asc');
        fetchEmployees(1);
    }

    //! Alternar orden de tabla
    function toggleSort(button) {
        const selectedSort = $(button).data('sort-by');
        const sameField = sortByInput.val() === selectedSort;

        sortDirectionInput.val(sameField && sortDirectionInput.val() === 'asc' ? 'desc' : 'asc');
        sortByInput.val(selectedSort);

        fetchEmployees(1);
    }

    //! Vincular eventos de orden
    function bindSortEvents() {
        tableWrapper.find('.employee-sort-trigger').each(function () {
            $(this).off('click').on('click', function () {
                toggleSort(this);
            });
        });
    }

    //! Vincular eventos de paginacion
    function bindPaginationEvents() {
        const pagination = tableWrapper.find('#employees-pagination');

        if (!pagination.length) {
            return;
        }

        pagination.find('.page-link').each(function () {
            $(this).off('click').on('click', function (event) {
                event.preventDefault();

                const pageUrl = $(this).attr('href');

                if (!pageUrl) {
                    return;
                }

                const page = new URL(pageUrl, window.location.origin).searchParams.get('page') || 1;

                fetchEmployees(page);
            });
        });
    }

    //! Vincular eventos de filtros
    function bindFilterEvents() {
        form.on('submit', function (event) {
            event.preventDefault();
            fetchEmployees(1);
        });

        if (searchInput.length) {
            searchInput.on('input', window.debounce(function () {
                fetchEmployees(1);
            }, 300));
        }

        form.find('select, input[type="date"]').each(function () {
            $(this).on('change', function () {
                fetchEmployees(1);
            });
        });

        if (resetButton.length) {
            resetButton.on('click', function () {
                resetSearch();
            });
        }
    }

    bindFilterEvents();
    bindSortEvents();
    bindPaginationEvents();
});
