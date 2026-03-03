<?php

namespace App\View\Components\Admin;

use Illuminate\View\Component;

class Input extends Component
{
    public string $name;
    public string $label;
    public string $type;

    public function __construct($name, $label, $type = 'text')
    {
        $this->name  = $name;
        $this->label = $label;
        $this->type  = $type;
    }

    public function render()
    {
        return view('components.admin.input');
    }
}
