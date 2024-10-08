<?php

namespace App\View\Components\ProfessionalSkillModal;

use Illuminate\View\Component;

class ProfessionalSkillModal extends Component
{
    public $users;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($users)
    {
        $this->users = $users;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.professional-skill-modal.professional-skill-modal');
    }
}
