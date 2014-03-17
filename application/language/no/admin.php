<?

return array(
	
	'events' => 'Arrangementer',
	'events_description' => 'Klikk på et av arrangementene nedenfor for å sette innstillingene for arrangementet',
	'errors' => array(
		'not_found' => 'Kunne ikke finne arrangementet.'
		),
	'field' => array(
		'name' => 'Navn',
		'features' => 'Funksjoner',
		'mediabank' => 'Mediebank',
		'profiles' => 'Profiler',
		'accreditation' => 'Akkreditation (I/O)',
		'tags' => 'Merkelapper',
		'date' => 'Dato',
		'status' => 'Status',
		'deactivate' => 'Deaktiver arrangement',
		'deactivate_message' => 'Er du sikker på at du vil deaktivere arrangementet? For å aktivere det igjen må en systemadministrator aktivere dette.',
		'cancel' => 'Avbryt',
		'save_changes' => 'Lagre endringer',
		'slug' => 'Snegle (SLUG)',
		'printer' => 'Badgeprinter',
		'no_printer' => '-- Har ikke printer --'
		),
	'placeholder' => array(
		'name' => 'Arrangementnavn',
		'tags' => 'Legg til merkelapper',
		'date' => 'Dato for arrangementstart',
		'slug' => 'URL-snegle (SLUG)'
		),
	'description' => array(
		'tags' => 'Disse merkelappene vil legges til i alle filer i mediebanken. Gjelder kun filer som er lastet opp i etterkant av innlegging av merkelapper.',
		'slug' => 'Dette vil være nettadressen man bruker for å velge arrangement. Eksempel på nettadresse er: http://obj.no/(snegle)/. Det er smart å velge en kort snegle som mulig, slik at man kan huske den. Videre, så husk at dette er unikt for hvert arrangement, så en god snegle er «tg14» for eksempel. Er sneglen brukt allerede, vil du få opp en feilmelding.',
		'printing' => 'Badgeprinting gjøres ved hjelp av Google Cloud Print. For å få dette til å fungere må man påse at en datamaskin med Google Chrome installert har lagt inn skriveren sin. <a href="https://support.google.com/cloudprint/answer/1686197?rd=1">Se lenken for mer informasjon</a>.<br />Skal man bruke Raspberry PI eller skjermløs datamaskin til printing, anbefales <a href="https://github.com/armooo/cloudprint">dette skriptet</a> med UNIX-basert system. For installering på Raspberry PI kan <a href="http://vzone.no/1fCDZvt">denne malen</a> benyttes.<br /><br /><b>Logg inn med følgende påloggingsinformasjon:</b><br />Brukernavn: %s<br />Passord: %s'
		),
	'settings_for' => 'Innstillinger for <b>%s</b>',
	'general_settings' => 'Generelt',
	'technical_settings' => 'Teknisk'
);

?>