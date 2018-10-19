<?php
namespace Craft;

class Lantra_StructureService extends BaseApplicationComponent
{
    /**
     * @return string
    */
    public function getCompanyLabel($company)
    {
        return $this->prependCompanyParent($company, $company->title);
    }

    /**
     * @param $company
     * @param $label
     * @return string
     * @throws mixed
     */
    private function prependCompanyParent($company, $label) {

        if ($company->companyParent->total())
        {
            $parent = $company->companyParent->first();
            $label = $parent->title . ' > ' . $label;
            $label = $this->prependCompanyParent($parent, $label);
        }

        return $label;
    }

    /**
     * @return string
     */
    public function getTeamLabel($team)
    {
        $teamCompany = $team->teamCompany->first();
        $label = $team->title;
        if ( ! $teamCompany){
            return $label;
        }
        return $this->prependCompanyParent($teamCompany, $label);
    }

}
