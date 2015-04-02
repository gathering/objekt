<?php
use League\Csv\Reader;

class Import_Task {

	protected function price($price)
	{
		// Remove all non-numeric values, except comma.
		$price = preg_replace('/[^0-9,.]+/i', '', $price);
		// Commas are replaced with dots.
		$price = str_replace(',', '.', $price);

		return $price;
	}
	
	function run($attributes){
		var_dump($attributes);
	}

	function products($attributes){
		$event = $attributes[0];
		$file = realpath($attributes[1]);
		$fileinfo = pathinfo($file);

		if($fileinfo['extension'] != 'csv')
			die("File extension not correct. Only accept csv. Extension was {$fileinfo['extension']}\n");

		$reader = Reader::createFromPath($file);
		$reader->setFlags(SplFileObject::READ_AHEAD|SplFileObject::SKIP_EMPTY);
		$header = ['Kategori','Navn','Beskrivelse','Pris','Enhet'];
		$data = $reader->fetchAssoc($header);

		/*if($data[0] != $header)
			die("The header is not as it supposed to be. Order and name are case-sensitive and needs to be in supplied order. It should be; ".implode(', ', $header)."\n");*/

		unset($data[0]);
		$i=0;

		foreach ($data as $row) {

			// Find or create a category.
			$category = Category::where('name', '=', $row['Kategori'])
								->where('event_id', '=', $event)
								->first();

			if(!$category){
				$category = new Category;
				$category->name = $row['Kategori'];
				$category->event_id = $event;
				$category->save();
			}

			#var_dump($category->products()->get()); exit;

			// Check if the product already exists.
			$product = Product::where('name', '=', $row['Navn'])
							  ->where('description', '=', $row['Beskrivelse'])
							  ->where('unit', '=', $row['Enhet'])
							  ->first();
			if(!$product)
				$product = new Product;

			var_dump($product);
			$product->name = $row['Navn'];
			$product->price = $this->price($row['Pris']);
			$product->description = $row['Beskrivelse'];
			$product->unit = $row['Enhet'];
			$product->event_id = $event;
			var_dump($product);

			$category->products()->insert($product);

			$i++;

		}

		print('Imported / Updated '.$i.' products.');
	}

}