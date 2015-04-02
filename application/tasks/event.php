<?php


class Event_Task {
	
	function show(){
		$events = Events::all();
		foreach ($events as $event) {
			echo "#{$event->id} - {$event->name} / {$event->slug}\n";
		}
	}

	function profiles(){
		echo "\n";
		$events = Events::all();
		foreach ($events as $event) {
			echo "=== {$event->name} ===\n\n";
			$i = 0;
			foreach($event->profiles()->get() as $profile){
				$i++;
				echo "#{$profile->id} - {$profile->name}\n";
			}
			echo "\nTotal number of profiles: {$i}";
			echo "\n\n";
		}
	}

	function products($attributes){
		$event = Events::find($attributes[0]);
		$i = 0;
		echo "\n";
		foreach ($event->products()->get() as $product){
			$i++;
			echo "#{$product->id} - {$product->name}\n";
		}
		echo "\nTotal number of products in {$event->name}: {$i}";
		echo "\n\n";
	}

	function categories($attributes){
		$event = Events::find($attributes[0]);
		$i = 0;
		echo "\n";
		foreach ($event->categories()->get() as $category){
			$i++;
			echo "#{$category->id} - {$category->name}\n";
		}
		echo "\nTotal number of categories in {$event->name}: {$i}";
		echo "\n\n";
	}
}