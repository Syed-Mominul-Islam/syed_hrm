<?php

namespace App\View\Components\LearningInterestModal;

use Illuminate\View\Component;

class LearningInterestModal extends Component
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
        return view('components.learning-interest-modal.learning-interest-modal');
    }
}
