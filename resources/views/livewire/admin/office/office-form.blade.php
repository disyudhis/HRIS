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
                    <label for="check_in_radius" class="block text-sm font-medium text-gray-700">Check-in Radius (meters)</label>
                    <input type="number" wire:model="check_in_radius" id="check_in_radius" min="10" max="1000" step="10" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                    @error('check_in_radius')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center mt-4">
                    <input type="checkbox" wire:model="is_active" id="is_active" value="1"
                        class="rounded border-gray-300 text-[#3085FE] shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">Active</label>
                    @error('is_active')
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
                <div
                    x-data="officeMap()"
                    x-init="initMap({{ $latitude ?? 'null' }}, {{ $longitude ?? 'null' }}, {{ $check_in_radius }})"
                    class="w-full h-96 rounded-lg border border-gray-300 overflow-hidden relative"
                    id="office-map" wire:ignore>
                    <div x-show="isLocating" class="absolute inset-0 bg-white bg-opacity-70 flex items-center justify-center z-10">
                        <div class="flex flex-col items-center">
                            <svg class="animate-spin h-8 w-8 text-[#3085FE] mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-sm text-gray-600">{{ $editMode ? 'Loading map...' : 'Getting your location...' }}</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-2">
                    <div>
                        <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                        <input type="text" wire:model="latitude" id="latitude" required readonly
                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                        @error('latitude')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                        <input type="text" wire:model="longitude" id="longitude" required readonly
                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                        @error('longitude')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <p class="text-sm text-gray-500 mt-2">
                    Click on the map to {{ $editMode ? 'update' : 'set' }} the office location. The circle represents the check-in radius.
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
    function officeMap() {
        return {
            map: null,
            marker: null,
            circle: null,
            userMarker: null,
            isLocating: false,

            initMap(lat, lng, radius) {
                this.isLocating = true;

                // Initialize map with a default view
                this.map = L.map('office-map').setView([0, 0], 2);

                // Add tile layer (OpenStreetMap)
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(this.map);

                // Force a resize to ensure map renders correctly
                this.map.invalidateSize();

                // If we already have coordinates, add a marker
                if (lat && lng) {
                    this.addMarker(lat, lng, radius);
                    this.map.setView([lat, lng], 15);
                    this.isLocating = false;
                } else {
                    // Otherwise, get user's location
                    window.LocationService.getUserLocation(
                        // Success callback
                        (position) => {
                            const userLat = position.coords.latitude;
                            const userLng = position.coords.longitude;

                            // Add user location marker
                            this.addUserLocationMarker(userLat, userLng);

                            // Center on user's location and zoom in
                            this.map.setView([userLat, userLng], 16);

                            // Pre-fill the latitude and longitude with user's location
                            @this.call('mapLocationSelected', userLat, userLng);

                            // Add a marker at user's location as the initial office location
                            this.addMarker(userLat, userLng, radius);

                            this.isLocating = false;
                        },
                        // Error callback
                        (error) => {
                            console.error("Geolocation error:", error);
                            this.isLocating = false;
                            alert("Failed to get your location. Please click on the map to set the office location.");
                        }
                    );
                }

                // Add click event to map
                this.map.on('click', (e) => {
                    const { lat, lng } = e.latlng;
                    this.addMarker(lat, lng, radius);

                    // Update Livewire component with location data
                    @this.call('mapLocationSelected', lat, lng);
                });

                // Watch for changes to check-in radius
                this.$watch('$wire.check_in_radius', (newRadius) => {
                    if (this.marker) {
                        const position = this.marker.getLatLng();
                        this.addMarker(position.lat, position.lng, newRadius);
                    }
                });

                // Handle window resize events
                window.addEventListener('resize', () => {
                    if (this.map) {
                        this.map.invalidateSize();
                    }
                });
            },

            addUserLocationMarker(lat, lng) {
                // Remove existing user marker if it exists
                if (this.userMarker) {
                    this.map.removeLayer(this.userMarker);
                }

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

                // Add user location marker
                this.userMarker = L.marker([lat, lng], { icon: userIcon }).addTo(this.map);
                this.userMarker.bindPopup("Your current location").openPopup();
            },

            addMarker(lat, lng, radius) {
                // Remove existing marker and circle
                if (this.marker) {
                    this.map.removeLayer(this.marker);
                }

                if (this.circle) {
                    this.map.removeLayer(this.circle);
                }

                // Create a custom icon for office location
                const officeIcon = L.icon({
                    iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                    shadowSize: [41, 41]
                });

                // Add new marker
                this.marker = L.marker([lat, lng], { icon: officeIcon }).addTo(this.map);
                this.marker.bindPopup("Office location").openPopup();

                // Add circle to represent check-in radius
                this.circle = L.circle([lat, lng], {
                    color: '#3085FE',
                    fillColor: '#3085FE',
                    fillOpacity: 0.2,
                    radius: radius
                }).addTo(this.map);
            }
        }
    }
</script>
@endpush

@push('styles')
<style>
    #office-map { height: 400px; }

    .user-location-marker {
        background: transparent;
        border: none;
    }
</style>
@endpush

