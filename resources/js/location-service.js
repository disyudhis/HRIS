/**
 * Location Service - Reusable service for getting user location
 * Can be used across the application for check-in and office management
 */
window.LocationService = {
    /**
     * Get the user's current location
     *
     * @param {Function} onSuccess - Callback function when location is successfully retrieved
     * @param {Function} onError - Callback function when location retrieval fails
     * @param {Object} options - Additional options for geolocation
     * @returns {Promise} - Promise that resolves with the position or rejects with error
     */
    getUserLocation: function(onSuccess, onError, options = {}) {
        const defaultOptions = {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        };

        const geolocationOptions = { ...defaultOptions, ...options };

        return new Promise((resolve, reject) => {
            if (!navigator.geolocation) {
                const error = new Error('Geolocation is not supported by this browser.');
                if (onError) onError(error);
                reject(error);
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    if (onSuccess) onSuccess(position);
                    resolve(position);
                },
                (error) => {
                    console.error('Geolocation error:', error);
                    if (onError) onError(error);
                    reject(error);
                },
                geolocationOptions
            );
        });
    },

    /**
     * Calculate distance between two points using Haversine formula
     *
     * @param {Number} lat1 - Latitude of first point
     * @param {Number} lon1 - Longitude of first point
     * @param {Number} lat2 - Latitude of second point
     * @param {Number} lon2 - Longitude of second point
     * @returns {Number} - Distance in meters
     */
    calculateDistance: function(lat1, lon1, lat2, lon2) {
        const R = 6371e3; // Earth radius in meters
        const φ1 = this.toRadians(lat1);
        const φ2 = this.toRadians(lat2);
        const Δφ = this.toRadians(lat2 - lat1);
        const Δλ = this.toRadians(lon2 - lon1);

        const a = Math.sin(Δφ/2) * Math.sin(Δφ/2) +
                Math.cos(φ1) * Math.cos(φ2) *
                Math.sin(Δλ/2) * Math.sin(Δλ/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

        return R * c; // Distance in meters
    },

    /**
     * Convert degrees to radians
     *
     * @param {Number} degrees - Angle in degrees
     * @returns {Number} - Angle in radians
     */
    toRadians: function(degrees) {
        return degrees * Math.PI / 180;
    }
};
