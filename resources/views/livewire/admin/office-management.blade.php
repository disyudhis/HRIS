<div>
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-[#101317]">Office Management</h1>
            <button wire:click="openModal" class="bg-[#3085FE] text-white px-4 py-2 rounded-lg text-sm font-medium">
                Add New Office
            </button>
        </div>

        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6">
                {{ session('message') }}
            </div>
        @endif

        <div class="mb-4">
            <div class="relative">
                <input type="text" wire:model.debounce.300ms="search" placeholder="Search offices..."
                    class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#3085FE] focus:border-transparent">
                <div class="absolute left-3 top-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Office Name
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Location
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Manager
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($offices as $office)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $office->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $office->city }}, {{ $office->country }}</div>
                                <div class="text-xs text-gray-500">{{ $office->address }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $manager = \App\Models\User::where('office_id', $office->id)
                                        ->where('user_type', 'manager')
                                        ->first();
                                @endphp

                                @if ($manager)
                                    <div class="text-sm text-gray-900">{{ $manager->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $manager->email }}</div>
                                @else
                                    <span class="text-sm text-gray-500">No manager assigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $office->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $office->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="edit({{ $office->id }})"
                                    class="text-[#3085FE] hover:text-blue-900 mr-3">
                                    Edit
                                </button>

                                <button wire:click="confirmDelete({{ $office->id }})"
                                    class="text-red-600 hover:text-red-900">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No offices found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $offices->links() }}
        </div>
    </div>

    <!-- Office Modal -->
    <x-modal wire:model="showModal">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
                <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ $editMode ? 'Edit Office' : 'Create New Office' }}
                    </h3>

                    <div class="mt-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Office
                                    Name</label>
                                <input type="text" wire:model.defer="name" id="name"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                                @error('name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                <input type="text" wire:model.defer="address" id="address"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                                @error('address')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                                <input type="text" wire:model.defer="city" id="city"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                                @error('city')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="state"
                                    class="block text-sm font-medium text-gray-700">State/Province</label>
                                <input type="text" wire:model.defer="state" id="state"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                                @error('state')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="postal_code" class="block text-sm font-medium text-gray-700">Postal
                                    Code</label>
                                <input type="text" wire:model.defer="postal_code" id="postal_code"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                                @error('postal_code')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                                <input type="text" wire:model.defer="country" id="country"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                                @error('country')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="check_in_radius" class="block text-sm font-medium text-gray-700">Check-in
                                    Radius (meters)</label>
                                <input type="number" wire:model.defer="check_in_radius" id="check_in_radius"
                                    min="10" max="1000" step="10"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                                @error('check_in_radius')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="flex items-center mt-4">
                                <input type="checkbox" wire:model.defer="is_active" id="is_active"
                                    class="rounded border-gray-300 text-[#3085FE] shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]">
                                <label for="is_active" class="ml-2 block text-sm text-gray-700">Active</label>
                                @error('is_active')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="description"
                                class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea wire:model.defer="description" id="description" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#3085FE] focus:ring-[#3085FE]"></textarea>
                            @error('description')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Office Location</label>
                            <div x-data="officeMap()" x-init="initMapWhenVisible({{ $latitude ?? 'null' }}, {{ $longitude ?? 'null' }}, {{ $check_in_radius ?? 100 }})"
                                class="w-full h-64 rounded-lg border border-gray-300 overflow-hidden relative"
                                id="office-map" wire:ignore>
                                <div x-show="isLocating"
                                    class="absolute inset-0 bg-white bg-opacity-70 flex items-center justify-center z-10">
                                    <div class="flex flex-col items-center">
                                        <svg class="animate-spin h-8 w-8 text-[#3085FE] mb-2"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        <span class="text-sm text-gray-600">Getting your location...</span>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mt-2">
                                <div>
                                    <label for="latitude"
                                        class="block text-sm font-medium text-gray-700">Latitude</label>
                                    <input type="text" wire:model.live="latitude" id="latitude" readonly
                                        class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                                    @error('latitude')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="longitude"
                                        class="block text-sm font-medium text-gray-700">Longitude</label>
                                    <input type="text" wire:model.live="longitude" id="longitude" readonly
                                        class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                                    @error('longitude')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <p class="text-sm text-gray-500 mt-2">
                                Click on the map to set the office location. The circle represents the check-in radius.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            @if ($editMode)
                <button wire:click="update" type="button"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#3085FE] text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Update
                </button>
            @else
                <button wire:click="store" type="button"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#3085FE] text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Create
                </button>
            @endif

            <button wire:click="closeModal" type="button"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                Cancel
            </button>
        </div>
    </x-modal>

    <!-- Delete Confirmation Modal -->
    <x-modal wire:model="confirmingDeletion" maxWidth="md">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
                <div
                    class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Delete Office
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            Are you sure you want to delete this office? This action cannot be undone.
                            All employees and managers associated with this office will be unassigned.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button wire:click="delete" type="button"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                Delete
            </button>
            <button wire:click="cancelDelete" type="button"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                Cancel
            </button>
        </div>
    </x-modal>

    @push('scripts')
        <script>
            function officeMap() {
                return {
                    map: null,
                    marker: null,
                    circle: null,
                    userMarker: null,
                    isLocating: false,
                    mapInitialized: false,

                    // New method to initialize map only when modal is visible
                    initMapWhenVisible(lat, lng, radius) {
                        // Wait for the element to be available in the DOM
                        this.$nextTick(() => {
                            // Create a MutationObserver to watch for changes in the modal's visibility
                            const observer = new MutationObserver((mutations) => {
                                mutations.forEach((mutation) => {
                                    if (mutation.target.style.display !== 'none' && !this
                                        .mapInitialized) {
                                        // Modal is visible, initialize the map
                                        this.initMap(lat, lng, radius);
                                        this.mapInitialized = true;
                                        observer.disconnect(); // Stop observing once initialized
                                    }
                                });
                            });

                            // Start observing the modal element
                            const modalElement = document.getElementById('office-map').closest(
                                '[x-data="{ show: true }"]');
                            if (modalElement) {
                                observer.observe(modalElement, {
                                    attributes: true,
                                    attributeFilter: ['style']
                                });

                                // If modal is already visible, initialize map immediately
                                if (modalElement.style.display !== 'none') {
                                    this.initMap(lat, lng, radius);
                                    this.mapInitialized = true;
                                    observer.disconnect();
                                }
                            }

                            // Handle window resize events to make the map responsive
                            window.addEventListener('resize', () => {
                                if (this.map) {
                                    this.map.invalidateSize();
                                }
                            });
                        });
                    },

                    initMap(lat, lng, radius) {
                        console.log('init map');

                        this.isLocating = true;

                        // Delay map initialization slightly to ensure container is fully rendered
                        setTimeout(() => {
                            // Check if map container exists and has dimensions
                            const mapContainer = document.getElementById('office-map');
                            if (!mapContainer || mapContainer.clientHeight === 0) {
                                console.error('Map container not ready or has zero height');
                                this.isLocating = false;
                                return;
                            }

                            // Initialize map with a default view first
                            this.map = L.map('office-map').setView([0, 0], 2);

                            // Add tile layer (OpenStreetMap)
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                            }).addTo(this.map);

                            // Force a resize to ensure map renders correctly
                            this.map.invalidateSize();

                            // Prioritize getting user location first
                            window.LocationService.getUserLocation(
                                // Success callback
                                (position) => {
                                    const userLat = position.coords.latitude;
                                    const userLng = position.coords.longitude;

                                    // Add user location marker
                                    this.addUserLocationMarker(userLat, userLng);

                                    // If we're in edit mode and have office coordinates
                                    if (lat && lng) {
                                        // Add office marker
                                        this.addMarker(lat, lng, radius);

                                        // Fit bounds to show both user and office
                                        const bounds = L.latLngBounds(
                                            [userLat, userLng],
                                            [lat, lng]
                                        );
                                        this.map.fitBounds(bounds, {
                                            padding: [50, 50]
                                        });
                                    } else {
                                        // In create mode, center on user's location and zoom in
                                        this.map.setView([userLat, userLng], 16);

                                        // Pre-fill the latitude and longitude with user's location
                                        @this.call('mapLocationSelected', userLat, userLng);

                                        // Add a marker at user's location as the initial office location
                                        this.addMarker(userLat, userLng, radius);
                                    }

                                    this.isLocating = false;

                                    // Force another resize after everything is loaded
                                    setTimeout(() => {
                                        this.map.invalidateSize();
                                    }, 100);
                                },
                                // Error callback
                                (error) => {
                                    console.error("Geolocation error:", error);
                                    this.isLocating = false;

                                    // If geolocation fails but we have office coordinates (edit mode)
                                    if (lat && lng) {
                                        this.addMarker(lat, lng, radius);
                                        this.map.setView([lat, lng], 15);
                                    }

                                    // Force another resize
                                    setTimeout(() => {
                                        this.map.invalidateSize();
                                    }, 100);
                                }
                            );

                            // Add click event to map
                            this.map.on('click', (e) => {
                                const {
                                    lat,
                                    lng
                                } = e.latlng;
                                this.addMarker(lat, lng, radius);

                                // Emit event to Livewire component
                                @this.call('mapLocationSelected', lat, lng);
                            });
                        }, 300); // Small delay to ensure modal is fully rendered
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
                        this.userMarker = L.marker([lat, lng], {
                            icon: userIcon
                        }).addTo(this.map);
                        this.userMarker.bindPopup("Your current location").openPopup();

                        // If we have both user location and office marker, fit bounds to show both
                        if (this.marker) {
                            const bounds = L.latLngBounds(
                                [lat, lng],
                                [this.marker.getLatLng().lat, this.marker.getLatLng().lng]
                            );
                            this.map.fitBounds(bounds, {
                                padding: [50, 50]
                            });
                        }
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
                        this.marker = L.marker([lat, lng], {
                            icon: officeIcon
                        }).addTo(this.map);
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
            .user-location-marker {
                background: transparent;
                border: none;
            }
        </style>
    @endpush
</div>
