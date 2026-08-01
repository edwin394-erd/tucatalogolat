<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class DebugTempUpload extends Component
{
    use WithFileUploads;

    public $image;
    public $tempUrl;
    public $fileInfo = [];
    public $debugInfo = [];

    protected $rules = [
        'image' => 'nullable|image|max:4096',
    ];

    public function updatedImage()
    {
        $this->validateOnly('image');

        $this->fileInfo = [];
        $this->debugInfo = [];
        $this->tempUrl = null;

        if (! $this->image) {
            return;
        }

        try {
            $this->tempUrl = $this->image->temporaryUrl();
            $realPath = $this->image->getRealPath();

            $this->fileInfo = [
                'original_name' => $this->image->getClientOriginalName(),
                'mime_type' => $this->image->getClientMimeType(),
                'size' => $this->image->getSize(),
                'extension' => $this->image->getClientOriginalExtension(),
                'real_path' => $realPath,
                'real_path_exists' => $realPath ? (file_exists($realPath) ? 'sí' : 'no') : 'n/a',
                'temporary_url' => $this->tempUrl,
            ];
        } catch (\Throwable $e) {
            $this->debugInfo = [
                'error' => $e->getMessage(),
            ];
            return;
        }

        $this->debugInfo = $this->getTempUploadDebugInfo();
    }

    protected function getTempUploadDebugInfo(): array
    {
        $diskName = config('livewire.temporary_file_upload.disk') ?? config('filesystems.default');
        $directory = config('livewire.temporary_file_upload.directory') ?? 'livewire-tmp';
        $disk = Storage::disk($diskName);

        $tempPath = null;
        $tempExists = false;
        $tempWritable = false;

        try {
            $tempPath = $disk->path($directory);
            $tempExists = is_dir($tempPath);
            $tempWritable = $tempExists ? is_writable($tempPath) : false;
        } catch (\Throwable $e) {
            $tempPath = 'n/a';
        }

        return [
            'livewire_temp_disk' => $diskName,
            'livewire_temp_directory' => $directory,
            'temp_path' => $tempPath,
            'temp_dir_exists' => $tempExists ? 'sí' : 'no',
            'temp_dir_writable' => $tempExists ? ($tempWritable ? 'sí' : 'no') : 'n/a',
            'public_storage_exists' => file_exists(public_path('storage')) ? 'sí' : 'no',
            'public_storage_is_link' => is_link(public_path('storage')) ? 'sí' : 'no',
            'storage_public_exists' => is_dir(storage_path('app/public')) ? 'sí' : 'no',
            'storage_public_writable' => is_dir(storage_path('app/public')) ? (is_writable(storage_path('app/public')) ? 'sí' : 'no') : 'n/a',
        ];
    }

    public function render()
    {
        return view('livewire.debug-temp-upload');
    }
}
