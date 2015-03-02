<?php

function serverconfig(){
	return json_decode(file_get_contents(path('base').'.server.config'));
}