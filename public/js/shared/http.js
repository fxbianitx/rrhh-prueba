window.getJson = function (url) {
    return $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        headers: {
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
    });
};
