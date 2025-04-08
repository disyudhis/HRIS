<?php

namespace App\Livewire\Employee\Dashboard;

use Livewire\Component;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckIn extends Component
{
    public $userLatitude = null;
    public $userLongitude = null;
    public $distance = null;
    public $isInRange = false;
    public $checkInStatus = null;
    public $errorMessage = null;

    public $officeLatitude; // Example: Jakarta coordinates
    public $officeLongitude;
    public $allowedRadius = 10; // in meters

    protected $listeners = ['locationUpdated', 'performCheckIn'];


    public function mount()
    {
        $user = Auth::user();

        if (!$user->office_id) {
            $this->errorMessage = "You are not assigned to any office. Please contact your administrator.";
            return;
        }

        $office = \App\Models\Offices::find($user->office_id);

        if (!$office) {
            $this->errorMessage = "Office not found. Please contact your administrator.";
            return;
        }

        if (!$office->is_active) {
            $this->errorMessage = "Your office is currently inactive. Please contact your administrator.";
            return;
        }

        $this->officeLatitude = $office->latitude;
        $this->officeLongitude = $office->longitude;
        $this->allowedRadius = $office->check_in_radius;
        $this->officeName = $office->name;
    }

    public function locationUpdated($latitude, $longitude, $distance, $isInRange)
    {
        $this->userLatitude = $latitude;
        $this->userLongitude = $longitude;
        $this->distance = round($distance);
        $this->isInRange = $isInRange;

        // Reset status messages when location changes
        $this->checkInStatus = null;
        $this->errorMessage = null;
    }

    public function performCheckIn()
    {
        if (!$this->isInRange) {
            $this->errorMessage = "You must be within {$this->allowedRadius} meters of the office to check in.";
            return;
        }

        try {
            DB::transaction(function () {
                $attendance = Attendance::create([
                    'user_id' => Auth::id(),
                    'check_in_time' => now()->toDateTimeString(), // Pastikan string
                    'latitude' => $this->userLatitude,
                    'longitude' => $this->userLongitude,
                    'distance' => $this->distance,
                    'status' => 'PRESENT',
                ]);

                $this->checkInStatus = 'Success! Checked in at ' . now()->format('h:i A');
                $this->errorMessage = null;

                // Gunakan dispatch() bukan emit()
                $this->dispatch('checkInSuccess', attendanceId: $attendance->id);
            });
        } catch (\Exception $e) {
            $this->errorMessage = $this->formatError($e);
            logger()->error('Check-in failed', [
                'error' => $e->getMessage(),
                'data' => $this->getDataForLogging(),
            ]);
        }
    }

    private function formatError(\Exception $e): string
    {
        return match (true) {
            str_contains($e->getMessage(), 'attendances_status_check') => 'Invalid status value',
            default => 'Check-in failed. Please try again later.',
        };
    }

    private function getDataForLogging(): array
    {
        return [
            'user_id' => Auth::id(),
            'coordinates' => [$this->userLatitude, $this->userLongitude],
            'distance' => $this->distance,
            'time' => now()->toDateTimeString(),
        ];
    }

    public function render()
    {
        return view('livewire.employee.dashboard.check-in');
    }
}