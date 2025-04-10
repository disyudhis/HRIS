<div>
    <!-- Tambahkan pesan error jika tidak ada office yang ditemukan -->
    @if (!$officeLatitude || !$officeLongitude)
        <div class="bg-red-100 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-4">
            {{ $errorMessage ?? 'Office location not configured. Please contact your administrator.' }}
        </div>
    @else
        <div class="w-full space-y-6" x-data="checkInMap()" x-init="initMap({{ $officeLatitude }}, {{ $officeLongitude }}, {{ $allowedRadius }})">
            <!-- Office Info -->
            <div class="bg-white rounded-xl border border-[#DADADA] p-4 mb-4">
                <h2 class="text-lg font-medium text-[#101317]">{{ $officeName }}</h2>
                <p class="text-[#ACAFB5] text-sm">Check-in radius: {{ $allowedRadius }} meters</p>
            </div>

            <!-- Today's Schedule -->
            @if ($todaySchedule)
                <div class="bg-white rounded-xl border border-[#DADADA] p-4 mb-4">
                    <h2 class="text-lg font-medium text-[#101317]">Today's Schedule</h2>
                    <div class="flex justify-between items-center mt-2">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#3085FE]" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-[#101317] font-medium">
                                    {{ Carbon\Carbon::parse($todaySchedule->start_time)->format('h:i A') }} -
                                    {{ Carbon\Carbon::parse($todaySchedule->end_time)->format('h:i A') }}</p>
                                <p class="text-[#ACAFB5] text-sm">{{ $todaySchedule->location }}</p>
                            </div>
                        </div>
                        <div
                            class="px-3 py-1 rounded-lg text-xs font-medium
          {{ $todaySchedule->shift_type === 'morning'
              ? 'bg-green-100 text-green-800'
              : ($todaySchedule->shift_type === 'afternoon'
                  ? 'bg-yellow-100 text-yellow-800'
                  : ($todaySchedule->shift_type === 'night'
                      ? 'bg-indigo-100 text-indigo-800'
                      : 'bg-gray-100 text-gray-800')) }}">
                            {{ ucfirst($todaySchedule->shift_type) }} Shift
                        </div>
                    </div>
                    @if (!$isWithinSchedule)
                        <div class="mt-3 text-sm text-yellow-600">
                            {{ $scheduleStatus }}
                        </div>
                    @endif
                </div>
            @else
                <div class="bg-yellow-50 rounded-xl border border-yellow-200 p-4 mb-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">No Schedule Found</h3>
                            <div class="mt-1 text-sm text-yellow-700">
                                You don't have a schedule for today. Please contact your manager.
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Attendance Status -->
            @if ($todayAttendance)
                <div class="bg-white rounded-xl border border-[#DADADA] p-4 mb-4">
                    <h2 class="text-lg font-medium text-[#101317]">Today's Attendance</h2>
                    <div class="mt-2 space-y-3">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[#101317] font-medium">Check In</p>
                                    <p class="text-[#ACAFB5] text-sm">
                                        {{ Carbon\Carbon::parse($todayAttendance->check_in_time)->format('h:i A') }}
                                    </p>
                                </div>
                            </div>
                            <div
                                class="px-3 py-1 rounded-lg text-xs font-medium
              {{ $todayAttendance->status === 'present'
                  ? 'bg-green-100 text-green-800'
                  : ($todayAttendance->status === 'late'
                      ? 'bg-yellow-100 text-yellow-800'
                      : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($todayAttendance->status) }}
                            </div>
                        </div>

                        @if ($todayAttendance->check_out_time)
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-[#101317] font-medium">Check Out</p>
                                        <p class="text-[#ACAFB5] text-sm">
                                            {{ Carbon\Carbon::parse($todayAttendance->check_out_time)->format('h:i A') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="px-3 py-1 rounded-lg text-xs font-medium bg-blue-100 text-blue-800">
                                    Completed
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Map Container -->
            <div wire:ignore class="w-full h-64 z-0 rounded-xl overflow-hidden border border-[#DADADA] shadow-sm" id="map"></div>

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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2">
                            </path>
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

            <!-- Check-in/Check-out Buttons -->
            @if (!$todayAttendance)
                <button x-on:click="checkIn"
                    x-bind:disabled="!isInRange || {{ $todaySchedule && !$isWithinSchedule ? 'true' : 'false' }}"
                    x-bind:class="{ 'bg-[#3085FE] hover:bg-[#2a75e6]': isInRange &&
                            {{ $todaySchedule && $isWithinSchedule ? 'true' : 'false' }}, 'bg-[#ACAFB5] cursor-not-allowed':
                            !isInRange || {{ $todaySchedule && !$isWithinSchedule ? 'true' : 'false' }} }"
                    class="w-full h-[60px] text-white rounded-xl text-xl font-medium transition-colors">
                    Check In Now
                </button>
            @elseif(!$todayAttendance->check_out_time)
                <button x-on:click="checkOut" x-bind:disabled="!isInRange"
                    x-bind:class="{ 'bg-[#3085FE] hover:bg-[#2a75e6]': isInRange, 'bg-[#ACAFB5] cursor-not-allowed': !isInRange }"
                    class="w-full h-[60px] text-white rounded-xl text-xl font-medium transition-colors">
                    Check Out Now
                </button>
            @else
                <button disabled
                    class="w-full h-[60px] bg-green-500 text-white rounded-xl text-xl font-medium cursor-not-allowed">
                    Attendance Completed
                </button>
            @endif

            <p class="text-center text-[#ACAFB5] text-sm">
                You must be within {{ $allowedRadius }} meters of the office location to check in/out
            </p>
        </div>
    @endif

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
                    allowedRadius: {{ $allowedRadius ?? 100 }},
                    watchId: null,
                    mapInitialized: false,

                    initMap(officeLat, officeLng, radius) {
                        // Prevent multiple initializations
                        if (this.mapInitialized) return;

                        this.isLocating = true;
                        this.officeLocation = {
                            latitude: officeLat,
                            longitude: officeLng
                        };
                        this.allowedRadius = radius;

                        // Initialize map centered on office location
                        this.map = L.map('map').setView([officeLat, officeLng], 18);

                        // Add tile layer (OpenStreetMap)
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                        }).addTo(this.map);

                        // Add office marker
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
                        this.officeMarker.bindPopup("{{ $officeName ?? 'Office Location' }}").openPopup();

                        // Add radius circle around office
                        L.circle([officeLat, officeLng], {
                            color: '#3085FE',
                            fillColor: '#3085FE',
                            fillOpacity: 0.1,
                            radius: this.allowedRadius
                        }).addTo(this.map);

                        // Get user location using the shared location service
                        this.getUserLocation();

                        // Mark as initialized
                        this.mapInitialized = true;

                        // Handle window resize events
                        window.addEventListener('resize', () => {
                            if (this.map) {
                                this.map.invalidateSize();
                            }
                        });
                    },

                    getUserLocation() {
                        this.isLocating = true;

                        // Use the shared location service
                        window.LocationService.getUserLocation(
                            // Success callback
                            (position) => this.handlePositionSuccess(position),
                            // Error callback
                            (error) => this.handlePositionError(error),
                            // Options
                            {
                                enableHighAccuracy: true,
                                maximumAge: 10000,
                                timeout: 10000
                            }
                        );

                        // Also set up a watch position for real-time updates
                        if (navigator.geolocation) {
                            this.watchId = navigator.geolocation.watchPosition(
                                (position) => this.handlePositionSuccess(position),
                                (error) => console.error("Watch position error:", error), {
                                    enableHighAccuracy: true,
                                    maximumAge: 5000,
                                    timeout: 10000
                                }
                            );
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
                            // Create a custom icon for user location
                            const userIcon = L.divIcon({
                                html: `
                      <div class="relative">
                          <div class="absolute inset-0 bg-blue-500 opacity-25 rounded-full animate-ping"></div>
                          <div class="relative bg-blue-500 w-4 h-4 rounded-full border-2 border-white"></div>
                      </div>
                  `,
                                className: 'user-location-marker',
                                iconSize: [20, 20],
                                iconAnchor: [10, 10]
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

                        // Calculate distance to office using the shared service
                        this.distance = window.LocationService.calculateDistance(
                            latitude,
                            longitude,
                            this.officeLocation.latitude,
                            this.officeLocation.longitude
                        );

                        this.isInRange = this.distance <= this.allowedRadius;

                        // Update Livewire component with location data
                        @this.call('locationUpdated',
                            this.userLocation.latitude,
                            this.userLocation.longitude,
                            this.distance,
                            this.isInRange
                        );

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

                    checkIn() {
                        if (this.isInRange) {
                            @this.call('performCheckIn');
                        }
                    },

                    checkOut() {
                        if (this.isInRange) {
                            @this.call('performCheckOut');
                        }
                    },

                    // Clean up on component destroy
                    disconnected() {
                        if (this.watchId !== null) {
                            navigator.geolocation.clearWatch(this.watchId);
                        }

                        if (this.map) {
                            this.map.remove();
                            this.map = null;
                        }
                    }
                }
            }
        </script>
    @endpush

</div>
