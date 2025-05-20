<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AddressItem extends Component
{
    public $icon;
    public $label;
    public $value;
    public $color;


    public function __construct($icon, $label, $value, $color = 'primary')
    {
        $this->icon = $icon;
        $this->label = $label;
        $this->value = $value;
        $this->color = $color;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.address-item');
    }
}
