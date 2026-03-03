<?php

namespace App\View\Components\Admin;

use Illuminate\View\Component;

class ImageUpload extends Component
{
    public string $name;
    public string $label;
    public string $previewId;

    public function __construct($name, $label)
    {
        $this->name = $name;
        $this->label = $label;
        $this->previewId = $name.'_preview';
    }

    public function render()
    {
        return view('components.admin.image-upload');
    }
}
