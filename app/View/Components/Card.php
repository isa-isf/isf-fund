<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Card extends Component
{
    public string $title;
    public string $titleColor;
    public string $color;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $color = 'white', string $titleColor = 'gray', string $title)
    {
        $this->title = $title;
        $this->titleColor = $titleColor;
        $this->color = $color;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.card');
    }
}
