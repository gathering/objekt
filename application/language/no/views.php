<?php

return array(

	'home' => 
		array(
			'index' => __('nav.dashboard')
		),
	'search' =>
		array(
			'results' => 'Søk'
		),
	'profiles' =>
		array(
			'index' => __('nav.profiles'),
			'profile' => 'Profil',
			'person_profile' => 'Profil',
			'add' => __('user.add_new_profile'),
			'add-person' => 'Legger til person'
		),
	'event' =>
		array(
			'select' => 'Velg arrangement'
		),
	'admin' =>
		array(
			'event' => 'Arrangementsinnstillinger'
		),
	'user' =>
		array(
			'index' => __('nav.users'),
			'add' => __('user.add_new_user'),
			'delete_user' => __('user.delete_user')
		),
	'accreditation' =>
		array(
			'index' => __('nav.accreditation'),
			'person_profile' => __('nav.accreditation'),
			'badge' => 'Akkreditering med badge'
		),
	'sms/send_person' => __('sms.send_to_person')

);

?>