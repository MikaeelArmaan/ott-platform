<?php

namespace App\View\Components\Admin;

use Illuminate\View\Component;

class VideoUpload extends Component
{
    public string $name;
    public string $label;

    public function __construct($name, $label)
    {
        $this->name  = $name;
        $this->label = $label;
    }

    public function render()
    {
        return view('components.admin.video-upload');
    }
}
