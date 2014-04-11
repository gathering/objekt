<?php 

return array(

	/*
	|--------------------------------------------------------------------------
	| Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| The following language lines contain the default error messages used
	| by the validator class. Some of the rules contain multiple versions,
	| such as the size (max, min, between) rules. These versions are used
	| for different input types such as strings and files.
	|
	| These language lines may be easily changed to provide custom error
	| messages in your application. Error messages for custom validation
	| rules may also be added to this file.
	|
	*/

	"accepted"       => ":attribute må aksepteres.",
	"active_url"     => ":attribute er ikke en ekte URL.",
	"after"          => ":attribute må være en dato etter :date.",
	"alpha"          => ":attribute kan bare inneholde bokstaver.",
	"alpha_dash"     => ":attribute kan bare inneholde bokstaver, tall og understrek.",
	"alpha_num"      => ":attribute kan bare inneholde bokstaver og tall.",
	"array"          => ":attribute må ha valgte elementer.",
	"before"         => ":attribute må være en dato før :date.",
	"between"        => array(
		"numeric" => ":attribute må være mellom :min - :max.",
		"file"    => ":attribute må være mellom :min - :max kilobytes.",
		"string"  => ":attribute må være mellom :min - :max characters.",
	),
	"confirmed"      => ":attribute bekreftelse er ikke like.",
	"count"          => ":attribute må ha nøyaktig :count valgte elementer.",
	"countbetween"   => ":attribute må være mellom :min og :max valgte elementer.",
	"countmax"       => ":attribute må ha mindre enn :max valgte elementer.",
	"countmin"       => ":attribute må ha minst :min valgte elementer.",
	"date_format"	 => ":attribute må ha et gyldig datoformat.",
	"different"      => ":attribute og :other må være forskjellig.",
	"email"          => ":attribute format er ugyldig.",
	"exists"         => "Det valgte :attribute er ugyldig.",
	"image"          => ":attribute må være et bilde.",
	"in"             => "Det valgte :attribute er ugyldig.",
	"integer"        => ":attribute må være et nummer.",
	"ip"             => ":attribute må være en gyldig ip-adresse.",
	"match"          => ":attribute formatet er ugyldig.",
	"max"            => array(
		"numeric" => ":attribute må være mindre enn :max.",
		"file"    => ":attribute må være mindre enn :max kilobytes.",
		"string"  => ":attribute må være mindre enn :max tegn.",
	),
	"mimes"          => ":attribute må være en fil av typen: :values.",
	"min"            => array(
		"numeric" => ":attribute må være minst :min.",
		"file"    => ":attribute må være minst :min kilobytes.",
		"string"  => ":attribute må være minst :min tegn.",
	),
	"not_in"         => "Det valgte :attribute er ugyldig.",
	"numeric"        => ":attribute må være et nummer.",
	"required"       => ":attribute felt er påkrevet.",
    "required_with"  => ":attribute felt er påkrevet med :field",
	"same"           => ":attribute og@ :other må stemme.",
	"size"           => array(
		"numeric" => ":attribute må være :size.",
		"file"    => ":attribute må være :size kilobyte.",
		"string"  => ":attribute må være :size tegn.",
	),
	"unique"         => ":attribute er alt tatt.",
	"url"            => ":attribute format er ugyldig.",
	"owner"			=> "Eieren ble ikke funnet.",

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| Here you may specify custom validation messages for attributes using the
	| convention "attribute_rule" to name the lines. This helps keep your
	| custom validation clean and tidy.
	|
	| So, say you want to use a custom validation message when validating that
	| the "email" attribute is unique. Just add "email_unique" to this array
	| with your custom message. The Validator will handle the rest!
	|
	*/

	'custom' => array(),

	/*
	|--------------------------------------------------------------------------
	| Validation Attributes
	|--------------------------------------------------------------------------
	|
	| The following language lines are used to swap attribute place-holders
	| with something more reader friendly such as "E-Mail Address" instead
	| of "email". Your users will thank you.
	|
	| The Validator class will automatically search this array of lines it
	| is attempting to replace the :attribute place-holder in messages.
	| It's pretty slick. We think you'll like it.
	|
	*/

	'attributes' => array(),

);
