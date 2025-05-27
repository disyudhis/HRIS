<div>
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-[#101317]">
                {{ $editMode ? 'Edit Office: ' . $name : 'Create New Office' }}
            </h1>
            <a href="{{ route('admin.offices.index') }}" class="text-[#3085FE] hover:underline">
                Back to Offices
            </a>
        </div>

        <form wire:submit.prevent="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Office Name</label>
                    <input type="text" wire:model="name" id="name" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                    <input type="text" wire:model="address" id="address" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                    <input type="text" wire:model="city" id="city" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                    @error('city')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="state" class="block text-sm font-medium text-gray-700">State/Province</label>
                    <input type="text" wire:model="state" id="state"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                    @error('state')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="postal_code" class="block text-sm font-medium text-gray-700">Postal Code</label>
                    <input type="text" wire:model="postal_code" id="postal_code"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                    @error('postal_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                    <input type="text" wire:model="country" id="country" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                    @error('country')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="check_in_radius" class="block text-sm font-medium text-gray-700">Check-in Radius
                        (meters)</label>
                    <input type="number" wire:model="check_in_radius" id="check_in_radius" min="10"
                        max="1000" step="10" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                    @error('check_in_radius')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea wire:model="description" id="description" rows="3"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]"></textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Office Location</label>

                <!-- Latitude and Longitude Inputs -->
                <div class="grid sm:grid-cols-3 grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                        <input type="number" wire:model.live="latitude" id="latitude" step="any" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]"
                            placeholder="e.g. -6.2088">
                        @error('latitude')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                        <input type="number" wire:model.live="longitude" id="longitude" step="any" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]"
                            placeholder="e.g. 106.8456">
                        @error('longitude')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-end">
                        <button type="button" onclick="getMyLocation()"
                            class="bg-gray-500 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-600 transition-colors">
                            📍 Use My Location
                        </button>
                    </div>
                </div>



                <div id="office-map" wire:ignore
                    class="w-full h-96 rounded-lg border border-gray-300 overflow-hidden relative mb-2"></div>

                <p class="text-sm text-gray-500">
                    • Enter latitude and longitude coordinates manually, or<br>
                    • Click on the map to {{ $editMode ? 'update' : 'set' }} the office location<br>
                    • Use "Use My Location" button to get your current position<br>
                    The blue circle represents the check-in radius.
                </p>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-[#3085FE] text-white px-4 py-2 rounded-lg text-sm font-medium">
                    {{ $editMode ? 'Update Office' : 'Create Office' }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        let map;
        let marker;
        let circle;
        let isMapInitialized = false;

        // Initialize map when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initializeMap();
        });

        function initializeMap() {
            if (isMapInitialized) return;

            // Get initial coordinates from Livewire
            const initialLat = @this.latitude;
            const initialLng = @this.longitude;
            const initialRadius = @this.check_in_radius || 100;

            // Initialize map
            map = L.map('office-map').setView([0, 0], 2);

            // Add tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // If coordinates already exist (edit mode), use them
            if (initialLat && initialLng) {
                addMarker(initialLat, initialLng, initialRadius);
            } else {
                // For new office, get user's current location
                getUserLocationForInitialSetup();
            }

            // Handle map clicks
            map.on('click', function(e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;

                // Update Livewire properties
                @this.set('latitude', lat);
                @this.set('longitude', lng);

                // Update map marker
                addMarker(lat, lng, @this.check_in_radius || 100);
            });

            isMapInitialized = true;
        }

        function getUserLocationForInitialSetup() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    // Update Livewire properties
                    @this.set('latitude', lat);
                    @this.set('longitude', lng);

                    // Update map
                    addMarker(lat, lng, @this.check_in_radius || 100);

                }, function(error) {
                    console.log('Geolocation error:', error);
                    // If geolocation fails, just show world map without marker
                    // User can still click on map or enter coordinates manually
                });
            }
        }

        function addMarker(lat, lng, radius) {
            // Remove existing marker and circle
            if (marker) {
                map.removeLayer(marker);
            }
            if (circle) {
                map.removeLayer(circle);
            }

            // Add new marker
            marker = L.marker([lat, lng]).addTo(map);
            marker.bindPopup(`Office Location<br>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}`);

            // Add circle for check-in radius
            circle = L.circle([lat, lng], {
                color: '#3085FE',
                fillColor: '#3085FE',
                fillOpacity: 0.2,
                radius: radius
            }).addTo(map);

            // Center map on marker
            map.setView([lat, lng], 15);
        }

        function getMyLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    // Update Livewire properties
                    @this.set('latitude', lat);
                    @this.set('longitude', lng);

                    // Update map
                    addMarker(lat, lng, @this.check_in_radius || 100);

                }, function(error) {
                    alert('Unable to get your location. Please enter coordinates manually.');
                });
            } else {
                alert('Geolocation is not supported by this browser.');
            }
        }

        // Listen for Livewire updates to coordinates
        document.addEventListener('livewire:initialized', () => {
            @this.on('refreshMap', (data) => {
                if (data.latitude && data.longitude) {
                    addMarker(data.latitude, data.longitude, data.radius);
                }
            });
        });

        // Watch for changes in latitude/longitude inputs
        document.addEventListener('input', function(e) {
            if (e.target.id === 'latitude' || e.target.id === 'longitude') {
                setTimeout(() => {
                    const lat = parseFloat(document.getElementById('latitude').value);
                    const lng = parseFloat(document.getElementById('longitude').value);

                    if (!isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <=
                        180) {
                        addMarker(lat, lng, @this.check_in_radius || 100);
                    }
                }, 500); // Debounce untuk menghindari terlalu banyak update
            }
        });

        // Update circle when radius changes
        document.addEventListener('input', function(e) {
            if (e.target.id === 'check_in_radius') {
                setTimeout(() => {
                    const lat = parseFloat(document.getElementById('latitude').value);
                    const lng = parseFloat(document.getElementById('longitude').value);
                    const radius = parseInt(e.target.value);

                    if (!isNaN(lat) && !isNaN(lng) && radius) {
                        addMarker(lat, lng, radius);
                    }
                }, 300);
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        #office-map {
            height: 400px;
            z-index: 1;
        }
    </style>
@endpush
