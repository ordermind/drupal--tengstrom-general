/**
 * Modifies the addEventListener() and removeEventListener() methods on HTML elements to keep a record of the current event listeners.
 * Also adds the prependEventListener() and getEventListeners() methods to HTML elements.
 */
(function () {
    Element.prototype._addEventListener = Element.prototype.addEventListener;
    Element.prototype._removeEventListener = Element.prototype.removeEventListener;

    Element.prototype.addEventListener = function (type, listener, options) {
        this._addEventListener(type, listener, options);

        if (!this.eventListeners) {
            this.eventListeners = {};
        }
        if (!this.eventListeners[type]) {
            this.eventListeners[type] = [];
        }

        this.eventListeners[type].push(listener);
    };

    Element.prototype.removeEventListener = function (type, listener, options) {
        this._removeEventListener(type, listener, options);

        if (!this.eventListeners) {
            this.eventListeners = {};
        }
        if (!this.eventListeners[type]) {
            this.eventListeners[type] = [];
        }

        const index = this.eventListeners[type].indexOf(listener);

        if (index === -1) {
            return;
        }

        this.eventListeners[type].splice(index, 1);
    }

    Element.prototype.prependEventListener = function (type, listener, options) {
        if (!this.eventListeners) {
            this.eventListeners = {};
        }
        if (!this.eventListeners[type]) {
            this.eventListeners[type] = [];
        }

        const originalEventListeners = [...this.eventListeners[type]];

        originalEventListeners.forEach(callback => this.removeEventListener(type, callback, options));
        this.addEventListener(type, listener, options);
        originalEventListeners.forEach(callback => this.addEventListener(type, callback, options));
    }

    /**
     * Gets the current event listeners for an element. Please note that manipulating this list will not have an effect on the actual
     * event listeners, it is only available as a record.
     */
    Element.prototype.getEventListeners = function () {
        if (!this.eventListeners) {
            this.eventListeners = {};
        }

        return this.eventListeners;
    }
})();