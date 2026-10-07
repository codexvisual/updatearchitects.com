<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ConsultationForm extends Component
{
    public function __construct(
        public ?string $action = null,
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.forms.consultation-form');
    }

    public function projectTypes(): array
    {
        return [
            'residential' => 'Residential',
            'commercial' => 'Commercial',
            'industrial' => 'Industrial',
            'institutional' => 'Institutional',
            'interior' => 'Interior Design',
            'renovation' => 'Renovation',
            'other' => 'Other',
        ];
    }

    public function serviceOptions(): array
    {
        return [
            'architecture' => 'Architecture',
            'structural' => 'Structural Engineering',
            'geotechnical' => 'Geotechnical Engineering',
            'construction' => 'Construction Consultancy',
            'interior' => 'Interior Design',
            'mep' => 'MEP Design',
            'approval' => 'Approvals (RAJUK, City Corp, etc.)',
            'documentation' => 'Bank Loan Documentation',
            'other' => 'Other',
        ];
    }

    public function budgetRanges(): array
    {
        return [
            'under-10lac' => 'Under 10 Lakh BDT',
            '10-25lac' => '10 - 25 Lakh BDT',
            '25-50lac' => '25 - 50 Lakh BDT',
            '50lac-1cr' => '50 Lakh - 1 Crore BDT',
            '1-3cr' => '1 - 3 Crore BDT',
            '3-5cr' => '3 - 5 Crore BDT',
            '5cr+' => '5 Crore+ BDT',
            'not-sure' => 'Not Sure Yet',
        ];
    }
}
