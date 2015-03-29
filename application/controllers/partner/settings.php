<?php
/**
 * @Author: Simen A.W. Olsen
 * @Date:   2015-03-29 13:38:32
 * @Last Modified by:   Simen A.W. Olsen
 * @Last Modified time: 2015-03-29 14:35:12
 */

class Partner_Settings_Controller extends Base_Controller
{
    public $restful = true;
	
	function get_index(){
		return View::make('partner.settings')->with('user', partnerAuth::user());
	}

	function post_index(){

		$person = partnerAuth::user();
		$profile = $person->profile();
        if(!$person) return Redirect::to('partner/')->with('error', 'En feil oppsto, vennligst forsøk igjen.');

        $input = Input::all();

        $input['phone'] = str_replace('+', '00', $input['phone']);

		if(substr($input['phone'], 0, 2) != '00')
		    return Redirect::to(Request::referrer())->with('error', 'Telefonnummeret var feil lagt inn. Dette må begynne med 00XX.')->with('post', $input);

        $rules = array(
            'firstname'  => 'required|max:255',
            'surname'  => 'required|max:255',
            'phone' => 'numeric',
            'email' => 'email',
            'password' => 'same:password2'
        );

        $validation = Validator::make($input, $rules);

        if ($validation->fails())
        {
            return Redirect::to(Request::referrer())->with('error', $validation->errors)->with('post', $input);
        }

        $person->firstname = $input['firstname'];
        $person->surname = $input['surname'];
        $person->phone = $input['phone'];
        $person->email = $input['email'];

        if(!empty($input['password']))
			$person->password = Hash::make($input['password']); // This is automatically salted and encrypted

        $person->slug = $this->slugname_person($person, $profile, 0);

        $person->sendNotification(__('profile.notifications.person_edited'));

        $person->save();
        return Redirect::to('partner/settings')->with("success", "Personellet ble endret.");

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

}