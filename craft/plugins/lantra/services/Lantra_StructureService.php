<?php
namespace Craft;

class Lantra_StructureService extends BaseApplicationComponent
{
    /**
     * @return string
    */
    public function getCompanyLabel($company)
    {
        return $this->appendCompanyParent($company, $company->title);
    }

    /**
     * @param $company
     * @param $label
     * @return string
     */
    private function appendCompanyParent($company, $label) {

        if ($company->companyParent->total())
        {
            $parent = $company->companyParent->first();
            $label = $parent->title . ' > ' . $label;
            $label = $this->appendCompanyParent($parent, $label);
        }

        return $label;
    }
}
