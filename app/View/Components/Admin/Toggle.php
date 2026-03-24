<?php

namespace App\View\Components\Admin;

use Illuminate\View\Component;

class Toggle extends Component
{
    public bool $checked;
    public ?int $id;
    public ?string $field;
    public string $variant;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $checked = false,
        $id = null,
        $field = null,
        $variant = 'default'
    ) {
        $this->checked = (bool) $checked;
        $this->id = $id ? (int) $id : null;
        $this->field = $field;
        $this->variant = $variant;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.admin.toggle');
    }
}
