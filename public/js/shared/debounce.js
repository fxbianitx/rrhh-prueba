window.debounce = function (callback, delay) {
    let timeoutId = null;

    return function () {
        const context = this;
        const args = arguments;

        clearTimeout(timeoutId);

        timeoutId = setTimeout(function () {
            callback.apply(context, args);
        }, delay);
    };
};
