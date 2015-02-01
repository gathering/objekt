<?php


class Partner_Accreditation_Controller extends Base_Controller
{
    public function action_index()
    {
        tplConstructor::set(true);
        return View::make('partner.accreditation');
    }

}