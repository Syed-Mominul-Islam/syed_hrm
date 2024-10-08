<?php

namespace App\View\Components\NotableProject;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class    NotableProjectTable extends Component
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
     * @return View|Closure|string
     */
    public function render()
    {
        return view('components.notable-project.notable-project-table');
    }
}
