<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Button extends Component
{
    public string $type;
    public string $theme;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $type = 'button', string $theme = 'default')
    {
        $this->type = $type;
        $this->theme = $theme;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.button');
    }
}
