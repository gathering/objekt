<?php


class Discount_Task {

	function types(){
		foreach(DiscountType::all() as $discount)
			print("#{$discount->id} - {$discount->name}\n");
	}

	function value_types(){
		foreach(DiscountValueType::all() as $discount)
			print("#{$discount->id} - {$discount->name}\n");
	}

	function add($attributes){
		if(count($attributes) != 5)
			die("Usage: artisan discount:add <profile> <type> <object id> <value type> <value>\n");

		$profile_id = $attributes[0];
		$type = $attributes[1];
		$object_id = $attributes[2];
		$value_type = $attributes[3];
		$value = $attributes[4];

		if(!is_numeric($attributes[0]))
			die("We're sorry to inform you that we cannot take anything other than numeric values on profile.");

		$type = Discount_Type::where('name', '=', $attributes[1])->first();
		if(!$type) die("Type not found\n");
		$type_id = $type->id;

		$value_type = Discount_Value_Type::where('name', '=', $attributes[3])->first();
		if(!$value_type) die("Value type not found\n");
		$value_type_id = $value_type->id;

		$discount = new Discount;
		$discount->active = true;
		$discount->profile_id = $attributes[0];
		$discount->type_id = $type_id;
		$discount->object_id = $attributes[2];
		$discount->value_type_id = $value_type_id;
		$discount->value = $attributes[4];
		$discount->save();

		die("\n\nSaved to database.\n\n");

	}

	function show($attributes){
		foreach(Profile::where('event_id', '=', $attributes[0])->get() as $profile){
			foreach($profile->discounts()->get() as $discount){
				$object = $discount->object()->first();
				$value_type = $discount->value_type()->first();
				$type = $discount->type()->first();
				if(!is_array($discountArr[$profile->id])) $discountArr[$profile->id] = [];
				array_push($discountArr[$profile->id], "#{$discount->id} - {$profile->name} = {$discount->value}{$value_type->symbol} --> {$object->name} [{$type->name}]");
			}
			if($discountArr[$profile->id]){
				echo "=== {$profile->name} ===\n\n";
				foreach($discountArr[$profile->id] as $message)
					echo $message."\n";
			}
		}
	}

	function delete($attributes){
		Discount::find($attributes[0])->delete();
		die("\nDeleted\n\n");
	}

}