<?php


class Partner_Accreditation_Controller extends Base_Controller
{
    public $restful = true;

    public function get_index()
    {
        tplConstructor::set(true);
        return View::make('partner.accreditation');
    }

    public function get_add()
    {
        return ModalView::make('partner.accreditation.add');
    }

    public function slugname_person($person, $profile, $i){
        if($i < 1){
            $person->slug = Str::slug($person->firstname." ".$person->surname);
        }
        $slug_exists = Person::where("slug", "=", $person->slug)->where("profile_id", "=", $profile->id)->first();
        if($slug_exists){
            if(@$person->id == $slug_exists->id) return $person->slug;

            $i++;
            $person->slug = Str::slug($person->firstname." ".$person->surname." ".$i);
            $slugy = Person::where("slug", "=", $person->slug)->where("profile_id", "=", $profile->id)->count();
            if($slugy > 0) return $this->slugname_person($person, $profile, $i);
            else return $person->slug;
        } else return $person->slug;
    }

    public function post_add()
    {
        $input = Input::all();

        $input['phone'] = str_replace('+', '00', $input['phone']);

        if(substr($input['phone'], 0, 2) != '00')
            return Redirect::to(Request::referrer())->with('error', 'Telefonnummeret var feil lagt inn. Dette må begynne med 00XX.')->with('post', $input);

        $rules = array(
            'firstname'  => 'required|max:255',
            'surname'  => 'required|max:255',
            'phone' => 'numeric',
            'email' => 'email',
        );

        $validation = Validator::make($input, $rules);

        if ($validation->fails())
        {
            return Redirect::to(Request::referrer())->with('error', $validation->errors)->with('post', $input);
        }

        $person = new Person;
        foreach($rules as $field => $rule){
            $person->{$field} = $input[$field];
        }


        $profile = partnerAuth::user()->profile();

        $event = Config::get('application.event');
        $person->event_id = $event->id;
        $person->slug = $this->slugname_person($person, $profile, 0);
        $person->hash = Str::random(32);
        unset($person->i);
        $person = $profile->person()->insert($person);

        $profile->sendNotification(sprintf(__('profile.notification.new_person'), $person->firstname." ".$person->surname));
        return Redirect::to('partner/accreditation')->with("success", "Nytt personell ble lagt til!");
    }

    public function get_edit($id)
    {
        $person = partnerAuth::user()->profile()->person()->find($id);
        if(!$person) return Redirect::to('partner/accreditation')->with('error', 'En feil oppsto, vennligst forsøk igjen.');

        if($person->status != "registred")
            return ModalView::make('partner.error_message')->with('error', 'Personen har endret status, og kan derfor ikke redigeres.');

        return ModalView::make('partner.accreditation.edit')->with('person', $person);
    }

    public function post_edit($id)
    {
        $profile = partnerAuth::user()->profile();
        $person = $profile->person()->find($id);
        if(!$person) return Redirect::to('partner/accreditation')->with('error', 'En feil oppsto, vennligst forsøk igjen.');

        $input = Input::all();

        $input['phone'] = str_replace('+', '00', $input['phone']);

        if(substr($input['phone'], 0, 2) != '00')
            return Redirect::to(Request::referrer())->with('error', 'Telefonnummeret var feil lagt inn. Dette må begynne med 00XX.')->with('post', $input);


        $rules = array(
            'firstname'  => 'required|max:255',
            'surname'  => 'required|max:255',
            'phone' => 'numeric',
            'email' => 'email',
        );

        $validation = Validator::make($input, $rules);

        if ($validation->fails())
        {
            return Redirect::to(Request::referrer())->with('error', $validation->errors)->with('post', $input);
        }

        foreach($rules as $field => $rule){
            $person->{$field} = $input[$field];
        }

        $person->slug = $this->slugname_person($person, $profile, 0);
        unset($person->i);
        $person->save();

        $person->sendNotification(__('profile.notifications.person_edited'));
        return Redirect::to('partner/accreditation')->with("success", "Personellet ble endret.");
    }

    public function get_delete($id)
    {
        $person = partnerAuth::user()->profile()->person()->find($id);
        if(!$person) return Redirect::to('partner/accreditation')->with('error', 'En feil oppsto, vennligst forsøk igjen.');

        if($person->status != "registred")
            return ModalView::make('partner.error_message')->with('error', 'Personen har endret status, og kan derfor ikke fjernes.');

        if(partnerAuth::user()->id == $id)
            return ModalView::make('partner.error_message')->with('error', 'Du kan ikke fjerne deg selv fra systemet.');

        return ModalView::make('partner.accreditation.delete')->with('person', $person);
    }

    public function post_delete($id)
    {
        $person = partnerAuth::user()->profile()->person()->find($id);
        if(!$person) return Redirect::to('partner/accreditation')->with('error', 'En feil oppsto, vennligst forsøk igjen.');

        if($person->status != "registred")
            return ModalView::make('partner.error_message')->with('error', 'Personen har endret status, og kan derfor ikke fjernes.');

        if(partnerAuth::user()->id == $id)
            return ModalView::make('partner.error_message')->with('error', 'Du kan ikke fjerne deg selv fra systemet.');


        $person->delete();

        return Redirect::to('partner/accreditation')->with("success", "Personen ble fjernet.");
    }


    public function get_promote($id)
    {
        $person = partnerAuth::user()->profile()->person()->find($id);
        if(!$person) return Redirect::to('partner/accreditation')->with('error', 'En feil oppsto, vennligst forsøk igjen.');

        return ModalView::make('partner.accreditation.promote')->with('person', $person);
    }

    public function post_promote($id)
    {
        $person = partnerAuth::user()->profile()->person()->find($id);
        if(!$person) return Redirect::to('partner/accreditation')->with('error', 'En feil oppsto, vennligst forsøk igjen.');

        $person->contact_person = 1;
        $person->save();

        return Redirect::to('partner/accreditation')->with("success", "Personen oppnevnt til kontaktperson.");
    }

    public function get_demote($id)
    {
        $person = partnerAuth::user()->profile()->person()->find($id);
        if(!$person) return Redirect::to('partner/accreditation')->with('error', 'En feil oppsto, vennligst forsøk igjen.');

        $person->contact_person = 0;
        $person->save();

        return Redirect::to('partner/accreditation')->with("success", "Personen ble nedgradert til vanlig personell.");
    }


}