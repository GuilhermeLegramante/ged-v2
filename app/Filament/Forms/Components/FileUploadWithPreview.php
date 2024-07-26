<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Str;

class FileUploadWithPreview extends FileUpload
{
    protected $previewUrl;

    public function mount($state = null): void
    {
        parent::mount($state);
        $this->reactive();
    }

    public function preview()
    {
        return $this->afterStateUpdated(fn ($state) => $this->setPreviewUrl($state));
    }

    protected function setPreviewUrl($state)
    {
        $this->previewUrl = $state ? url('storage/tmp/' . $state->getFilename()) : null;
    }

    public function getPreviewUrl()
    {
        return $this->previewUrl;
    }
}
