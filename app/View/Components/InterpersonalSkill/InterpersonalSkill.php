<?php

namespace App\View\Components\InterpersonalSkill;

use Illuminate\View\Component;

class InterpersonalSkill extends Component
{
    public $users;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($users)
    {
        return $this->users = $users;
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.interpersonal-skill.interpersonal-skill');
    }
}
