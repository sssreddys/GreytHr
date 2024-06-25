<?php
// File Name                       : PageTitle.php
// Description                     : This file contains the implementation of to get pagetitle in header
// Creator                         : Bandari Divya
// Email                           : bandaridivya1@gmail.com
// Organization                    : PayG.
// Date                            : 2024-03-07
// Framework                       : Laravel (10.10 Version)
// Programming Language            : PHP (8.1 Version)
// Database                        : MySQL
// Models                          : -
namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Illuminate\Support\Facades\Route;

class PageTitle extends Component
{
    public $pageTitle;

    public function mount()
    {
        try {
            // Fetch the title dynamically based on the current route
            $this->pageTitle = $this->getTitleFromRoute();
        } catch (\Exception $e) {
            Log::error('Error occurred in mount method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while fetching page title.');
        }
    }

    private function getTitleFromRoute()
    {
        try {
            // Get the current route name
            $routeName = Route::currentRouteName();
            return $this->mapRouteToTitle($routeName);
        } catch (\Exception $e) {
            Log::error('Error occurred in getTitleFromRoute method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while fetching page title.');
        }
    }

    private function mapRouteToTitle($routeName)
    {
        $routeTitleMap = [
            'home' => 'Home',
            'feeds' => 'Feeds',
            'people' => 'People',
            'profile.info' => 'Employee Information',
            'itdeclaration' => 'It Declaration',
            'whoisin' => 'Who is in ?',
            'leave-history' => 'Leave - View Details',
            'leave-pending' =>'Leave - View Details',
            'approved-details' => 'Review - Leave',
            'leave-page' => 'Leave Apply',
            
        ];
        // Use the mapped title or fallback to the original route name
        return $routeTitleMap[$routeName] ?? ucwords(str_replace('-', ' ', $routeName));
    }

    public function render()
    {
        return view('livewire.page-title');
    }
}
