<?php

namespace App\View\Components;

use App\Helpers\SystemSettingHelper;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CompanyInfo extends Component
{
    public $companyInfo;
    public $showLogo;
    public $showAddress;
    public $showContact;
    public $size;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $showLogo = true,
        $showAddress = true,
        $showContact = true,
        $size = 'medium'
    ) {
        $this->companyInfo = SystemSettingHelper::getCompanyInfo();
        $this->showLogo = $showLogo;
        $this->showAddress = $showAddress;
        $this->showContact = $showContact;
        $this->size = $size;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.company-info');
    }
}
