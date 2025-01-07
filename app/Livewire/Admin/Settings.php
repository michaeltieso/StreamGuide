<?php

namespace App\Livewire\Admin;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Livewire\WithFileUploads;

class Settings extends AdminComponent
{
    use WithFileUploads;

    public $appName;
    public $serverTitle;
    public $serverDescription;
    public $maintenanceEnabled = false;
    public $maintenanceStart;
    public $maintenanceEnd;
    public $maintenanceMessage;
    public $logoUrl;
    public $logoFile;
    public $currentLogo;
    public $customCss;
    public $customJs;

    public function mount(...$args)
    {
        parent::mount(...$args);
        $this->loadGeneralSettings();
        $this->loadMaintenanceSettings();
        $this->loadCustomStyling();
    }

    protected function loadGeneralSettings()
    {
        $this->appName = SiteSetting::get('app_name');
        $this->serverTitle = SiteSetting::get('server_title');
        $this->serverDescription = SiteSetting::get('server_description');
        $this->currentLogo = SiteSetting::get('logo_url');
        $this->logoUrl = SiteSetting::get('logo_url');
    }

    protected function loadMaintenanceSettings()
    {
        $this->maintenanceEnabled = SiteSetting::get('maintenance_enabled', false);
        $this->maintenanceStart = SiteSetting::get('maintenance_start');
        $this->maintenanceEnd = SiteSetting::get('maintenance_end');
        $this->maintenanceMessage = SiteSetting::get('maintenance_message');
    }

    protected function loadCustomStyling()
    {
        $this->customCss = SiteSetting::get('custom_css');
        $this->customJs = SiteSetting::get('custom_js');
    }

    public function saveGeneralSettings()
    {
        try {
            SiteSetting::set('app_name', $this->appName);
            SiteSetting::set('server_title', $this->serverTitle);
            SiteSetting::set('server_description', $this->serverDescription);

            $this->dispatch('settings-saved');
            Log::info('General settings saved successfully');
        } catch (\Exception $e) {
            Log::error('Error saving general settings: ' . $e->getMessage());
            $this->addError('settings', 'Failed to save general settings.');
        }
    }

    public function saveMaintenanceSettings()
    {
        try {
            SiteSetting::set('maintenance_enabled', $this->maintenanceEnabled);
            SiteSetting::set('maintenance_start', $this->maintenanceStart);
            SiteSetting::set('maintenance_end', $this->maintenanceEnd);
            SiteSetting::set('maintenance_message', $this->maintenanceMessage);

            $this->dispatch('maintenance-settings-saved');
            Log::info('Maintenance settings saved successfully');
        } catch (\Exception $e) {
            Log::error('Error saving maintenance settings: ' . $e->getMessage());
            $this->addError('settings', 'Failed to save maintenance settings.');
        }
    }

    public function saveCustomStyling()
    {
        try {
            SiteSetting::set('custom_css', $this->customCss);
            SiteSetting::set('custom_js', $this->customJs);

            $this->dispatch('settings-saved');
            Log::info('Custom styling saved successfully');
        } catch (\Exception $e) {
            Log::error('Error saving custom styling: ' . $e->getMessage());
            $this->addError('settings', 'Failed to save custom styling.');
        }
    }

    public function updateLogoUrl()
    {
        try {
            SiteSetting::set('logo_url', $this->logoUrl);
            $this->currentLogo = $this->logoUrl;

            $this->dispatch('settings-saved');
            Log::info('Logo URL updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating logo URL: ' . $e->getMessage());
            $this->addError('settings', 'Failed to update logo URL.');
        }
    }

    public function uploadLogo()
    {
        try {
            $this->validate([
                'logoFile' => 'required|image|max:1024',
            ]);

            $path = $this->logoFile->store('logos', 'public');
            SiteSetting::set('logo_url', URL::asset('storage/' . $path));
            $this->currentLogo = URL::asset('storage/' . $path);
            $this->logoFile = null;

            $this->dispatch('settings-saved');
            Log::info('Logo uploaded successfully');
        } catch (\Exception $e) {
            Log::error('Error uploading logo: ' . $e->getMessage());
            $this->addError('settings', 'Failed to upload logo.');
        }
    }
}
