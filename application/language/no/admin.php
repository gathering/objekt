<?

return array(
	
	'events' => 'Arrangementer',
	'filename' => 'Filnavn',
	'delete_file' => 'Slett fil',
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
		'from_date' => 'Fra dato',
		'to_date' => 'Til dato',
		'status' => 'Status',
		'deactivate' => 'Deaktiver arrangement',
		'deactivate_message' => 'Er du sikker på at du vil deaktivere arrangementet? For å aktivere det igjen må en systemadministrator aktivere dette.',
		'cancel' => 'Avbryt',
		'save_changes' => 'Lagre endringer',
		'slug' => 'Snegle (SLUG)',
		'badgeprinter' => 'Badgeprinter',
		'deskprinter' => 'Resepsjonsprinter',
		'no_printer' => '-- Har ikke printer --',
		'sms' => 'SMS-funksjonalitet',
		'logistics' => 'Logistikk',
		'helpdesk' => 'Kundestøttesystem',
		'chat' => 'Direktemeldinger',
		'welcomeletter' => 'Last opp velkomstbrev',
		'map' => 'Last opp arrangementskart'
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
		'printing' => 'Badgeprinting gjøres ved hjelp av Google Cloud Print. For å få dette til å fungere må man påse at en datamaskin med Google Chrome installert har lagt inn skriveren sin. <a href="https://support.google.com/cloudprint/answer/1686197?rd=1">Se lenken for mer informasjon</a>.<br />Skal man bruke Raspberry PI eller skjermløs datamaskin til printing, anbefales <a href="https://github.com/armooo/cloudprint">dette skriptet</a> med UNIX-basert system. For installering på Raspberry PI kan <a href="http://vzone.no/1fCDZvt">denne malen</a> benyttes.<br /><br /><b>Logg inn med følgende påloggingsinformasjon:</b><br />Brukernavn: %s<br />Passord: %s',
		'date' => 'Husk alltid å velge ytterpunktene på et arrangement. Hvis systemet skal brukes av arbeidere på arrangementet - må man velge første dato man antar at noen skal benytte seg av systemet og til dato må være datoen da man regner at arrangementet er nedrigget.',
		'deskprinter' => 'Resepsjonsprinteren brukes til utskrift av velkomstbrev, arrangementspesifike avtalebrev o.l. Dette er normalt en A4-printer.',
		'welcomeletter' => 'Velkomstbrev kan lastes opp i PDF-format, som deretter kan printes ut sammen med profil-spesifik informasjon. Det er mulig å laste opp flere velkomstbrev, om ønskelig.',
		'map' => 'Dersom arrangementskart er lastet opp, vil det være mulig å pinpunkte hvor en gitt profil har tilhørlighet på kartet. Dette vil være synlig via profilen - samt at dette kan skrives ut sammen med skreddersydd velkomstbrev med arrangementspesifike opplysninger.',
		'aid' => 'Velg de brukere som skal motta varsel om øyeblikkelig hjelp. Brukerene er selv ansvarlige for å sette opp Pushover.'
		),
	'settings_for' => 'Innstillinger for <b>%s</b>',
	'general_settings' => 'Generelt',
	'technical_settings' => 'Teknisk',
	'files' => 'Filer',
	'saved_success' => 'Endringene ble lagret.',
	'deactivated' => 'Arrangementet ble deaktivert.'
);

?>