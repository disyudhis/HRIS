<div class="w-full space-y-6" x-data="checkInMap()" x-init="initMap({{ $officeLatitude }}, {{ $officeLongitude }}, {{ $allowedRadius }})">
    <!-- Map Container -->
    <div wire:ignore class="w-full h-64 rounded-xl overflow-hidden border border-[#DADADA] shadow-sm" id="map"></div>

    <!-- Location Status -->
    <div class="bg-gray-50 rounded-xl border border-[#DADADA] p-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-medium text-[#101317]">Your Location</h3>
                <template x-if="userLocation">
                    <p class="text-[#ACAFB5] text-sm">
                        <span x-text="userLocation.latitude.toFixed(6)"></span>,
                        <span x-text="userLocation.longitude.toFixed(6)"></span>
                    </p>
                </template>
                <template x-if="!userLocation">
                    <p class="text-[#ACAFB5] text-sm">Waiting for location...</p>
                </template>
            </div>
            <div x-show="isLocating" class="animate-pulse">
                <svg class="w-5 h-5 text-[#3085FE]" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2"></path>
                </svg>
            </div>
        </div>

        <!-- Distance Information -->
        <div class="mt-4 flex items-center justify-between">
            <div>
                <h3 class="font-medium text-[#101317]">Distance to Office</h3>
                <template x-if="distance !== null">
                    <p class="text-[#ACAFB5] text-sm">
                        <span x-text="distance.toFixed(1)"></span> meters
                    </p>
                </template>
                <template x-if="distance === null">
                    <p class="text-[#ACAFB5] text-sm">Calculating...</p>
                </template>
            </div>
            <div>
                <template x-if="isInRange">
                    <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                        In Range
                    </div>
                </template>
                <template x-if="!isInRange && distance !== null">
                    <div class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">
                        Out of Range
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Status Messages -->
    @if ($checkInStatus)
        <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-xl">
            {{ $checkInStatus }}
        </div>
    @endif

    @if ($errorMessage)
        <div class="bg-red-100 border border-red-200 text-red-800 px-4 py-3 rounded-xl">
            {{ $errorMessage }}
        </div>
    @endif

    <!-- Check-in Button -->
    <button x-on:click="checkIn" x-bind:disabled="!isInRange"
        x-bind:class="{ 'bg-[#3085FE] hover:bg-[#2a75e6]': isInRange, 'bg-[#ACAFB5] cursor-not-allowed': !isInRange }"
        class="w-full h-[60px] text-white rounded-xl text-xl font-medium transition-colors">
        Check In Now
    </button>

    <p class="text-center text-[#ACAFB5] text-sm">
        You must be within {{ $allowedRadius }} meters of the office location to check in
    </p>
</div>

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        function checkInMap() {
            return {
                map: null,
                userMarker: null,
                officeMarker: null,
                accuracyCircle: null,
                userLocation: null,
                officeLocation: null,
                distance: null,
                isInRange: false,
                isLocating: true,
                allowedRadius: 5,
                watchId: null,

                initMap(officeLat, officeLng, radius) {
                    try {
                        const mapContainer = document.getElementById('map');
                        if (!mapContainer) {
                            console.error('Map container not found');
                            return;
                        }
                        if (this.map) {
                            this.map.remove();
                            this.map = null;
                        }
                        this.officeLocation = {
                            latitude: officeLat,
                            longitude: officeLng
                        };
                        this.allowedRadius = radius;
                        this.map = L.map('map', {
                            preferCanvas: true,
                            zoomControl: false
                        }).setView([officeLat, officeLng], 18);

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                            maxZoom: 19
                        }).addTo(this.map);

                        const officeIcon = L.icon({
                            iconUrl: 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/images/marker-icon.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                            popupAnchor: [1, -34],
                            shadowUrl: 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/images/marker-shadow.png',
                            shadowSize: [41, 41]
                        });

                        this.officeMarker = L.marker([officeLat, officeLng], {
                            icon: officeIcon
                        }).addTo(this.map);
                        this.officeMarker.bindPopup("Office Location").openPopup();

                        L.circle([officeLat, officeLng], {
                            color: '#3085FE',
                            fillColor: '#3085FE',
                            fillOpacity: 0.1,
                            radius: this.allowedRadius
                        }).addTo(this.map);


                        setTimeout(() => {
                            this.map.invalidateSize();
                            this.getUserLocation();
                        }, 300);
                    } catch (error) {
                        console.error('Map initialization failed:', error);
                    }
                },

                getUserLocation() {
                    const self = this;

                    if (navigator.geolocation) {
                        this.isLocating = true;

                        this.watchId = navigator.geolocation.watchPosition(
                            (position) => {
                                self.handlePositionSuccess(position);
                            },
                            (error) => {
                                self.handlePositionError(error);
                            }, {
                                enableHighAccuracy: true,
                                maximumAge: 10000,
                                timeout: 15000
                            }
                        );
                    } else {
                        alert("Geolocation is not supported by this browser.");
                        this.isLocating = false;
                    }
                },


                handlePositionSuccess(position) {
                    const {
                        latitude,
                        longitude,
                        accuracy
                    } = position.coords;
                    this.userLocation = {
                        latitude,
                        longitude,
                        accuracy
                    };

                    // Update user marker
                    if (this.userMarker) {
                        this.userMarker.setLatLng([latitude, longitude]);
                    } else {
                        const userIcon = L.icon({
                            iconUrl: 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/images/marker-icon-2x.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                            popupAnchor: [1, -34],
                            shadowUrl: 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/images/marker-shadow.png',
                            shadowSize: [41, 41]
                        });

                        this.userMarker = L.marker([latitude, longitude], {
                            icon: userIcon
                        }).addTo(this.map);
                        this.userMarker.bindPopup("Your Location").openPopup();

                        // Fit bounds to show both markers
                        const bounds = L.latLngBounds([
                            [this.officeLocation.latitude, this.officeLocation.longitude],
                            [latitude, longitude]
                        ]);
                        this.map.fitBounds(bounds, {
                            padding: [50, 50]
                        });
                    }

                    // Update accuracy circle
                    if (this.accuracyCircle) {
                        this.accuracyCircle.setLatLng([latitude, longitude]);
                        this.accuracyCircle.setRadius(accuracy);
                    } else {
                        this.accuracyCircle = L.circle([latitude, longitude], {
                            color: '#2196F3',
                            fillColor: '#2196F3',
                            fillOpacity: 0.15,
                            radius: accuracy
                        }).addTo(this.map);
                    }

                    // Calculate distance to office
                    this.calculateDistance();
                    this.isLocating = false;
                },

                handlePositionError(error) {
                    console.error("Error getting location:", error);
                    this.isLocating = false;

                    let errorMessage = "Failed to get your location.";
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage = "Location access denied. Please enable location services.";
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage = "Location information is unavailable.";
                            break;
                        case error.TIMEOUT:
                            errorMessage = "Location request timed out.";
                            break;
                    }

                    alert(errorMessage);
                },

                calculateDistance() {
                    if (!this.userLocation || !this.officeLocation) return;

                    // Haversine formula to calculate distance between two points
                    const R = 6371e3; // Earth radius in meters
                    const φ1 = this.toRadians(this.userLocation.latitude);
                    const φ2 = this.toRadians(this.officeLocation.latitude);
                    const Δφ = this.toRadians(this.officeLocation.latitude - this.userLocation.latitude);
                    const Δλ = this.toRadians(this.officeLocation.longitude - this.userLocation.longitude);

                    const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
                        Math.cos(φ1) * Math.cos(φ2) *
                        Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
                    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

                    this.distance = R * c;
                    this.isInRange = this.distance <= this.allowedRadius;

                    // Update Livewire component with location data
                    @this.call('locationUpdated',
                        this.userLocation.latitude,
                        this.userLocation.longitude,
                        this.distance,
                        this.isInRange
                    );
                },

                toRadians(degrees) {
                    return degrees * Math.PI / 180;
                },

                checkIn() {
                    if (this.isInRange) {
                        @this.call('performCheckIn');
                    }
                },

                // Clean up on component destroy
                disconnected() {
                    if (this.watchId !== null) {
                        navigator.geolocation.clearWatch(this.watchId);
                    }
                }
            }
        }
    </script>
@endpush
