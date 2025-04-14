export class ConnectionTimeTracker {
    /**
     * Constructor to initialize the connection timestamp.
     * @param {number} connectionTimestamp - The timestamp when the connection started (in milliseconds).
     */
    constructor(connectionTimestamp) {
        this.connectionTimestamp = connectionTimestamp;
    }

    /**
     * Calculates the time elapsed since the connection started.
     * @returns {Object} An object containing days, hours, minutes, and seconds.
     */
    calculateTimeElapsed() {
        const now = Math.floor(Date.now() / 1000);
        const timeDiff = now - this.connectionTimestamp;

        const days = Math.floor(timeDiff / (60 * 60 * 24));
        const hours = Math.floor((timeDiff % (60 * 60 * 24)) / (60 * 60));
        const minutes = Math.floor((timeDiff % (60 * 60)) / 60);
        const seconds = timeDiff % 60;
        return {days, hours, minutes, seconds}
    };


    /**
     * Formats the elapsed time into a human-readable string.
     * @param {Object} timeObj - An object containing days, hours, minutes, and seconds.
     * @returns {string} A formatted string representing the elapsed time.
     */
    formatElapsedTime(timeObj) {
        const { days, hours, minutes, seconds } = timeObj;
        const parts = [];

        if (days > 0) parts.push(`${days} d`);
        if (hours > 0) parts.push(`${hours} h`);
        if (minutes > 0) parts.push(`${minutes} m`);

        return parts.join(', ');
    }

    /**
     * Formats the elapsed time into a human-readable string.
     * @param {Object} timeObj - An object containing days, hours, minutes, and seconds.
     * @returns {string} A formatted string representing the elapsed time.
     */
    formatElapsedTime(timeObj, showSeconds=false) {
        const { days, hours, minutes, seconds } = timeObj;
        const parts = [];

        if (days > 0) parts.push(`${days} day${days > 1 ? 's' : ''}`);
        if (hours > 0) parts.push(`${hours} hour${hours > 1 ? 's' : ''}`);
        if (minutes > 0) parts.push(`${minutes} minute${minutes > 1 ? 's' : ''}`);
        if (showSeconds && seconds >= 0) parts.push(`${seconds} second${seconds > 1 ? 's' : ''}`);

        return parts.join(', ');
    }

    /**
     * Formats the elapsed time into a human-readable string.
     * @param {Object} timeObj - An object containing days, hours, minutes, and seconds.
     * @returns {string} A formatted string representing the elapsed time.
     */
    formatCompactedElapsedTime(timeObj) {
        const { days, hours, minutes, seconds } = timeObj;
        const parts = [];

        parts.push(`${days}`);
        parts.push(`${hours}`.padStart(2, '0'));
        parts.push(`${minutes}`.padStart(2, '0'));
        parts.push(`${seconds}`.padStart(2, '0'));

        return parts.join(':');
    }

    /**
     * Starts updating the elapsed connection time every second.
     * @param {string} elementId - The ID of the HTML element where the time will be displayed.
     */
    startUpdating(elementId) {
        setInterval(() => {
            const timeElapsed = this.calculateTimeElapsed();
            const formattedTime = this.formatCompactedElapsedTime(timeElapsed);

            // Update the HTML element with the formatted time
            $(elementId).text(`Connection Time: ${formattedTime}`);
        }, 1000);
    }
}
