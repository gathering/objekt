<?php


class List_Task {
	
	function run(){

		echo "\n\n*** Help ***\n";

		$commands = [
			'Convertion Commands' => [
				'convert:mediabank',
				'convert:maps'
			],

			'Discount Commands' => [
				'discount:value_types',
				'discount:types',
				'discount:add <profile> <type> <object id> <value type> <value>',
				'discount:show',
				'discount:delete'
			],

			'Event Commands' => [
				'event:show',
				'event:profiles',
				'event:products <event-id>',
				'event:categories <event-id>'
			],

			'Export Commands' => [
				'export:csv <event-id> <semicolon-seperator?> <profiles-to-exclude>'
			],

			'Import Commands' => [
				'import:products <csv import file>'
			],

			'Index Commands (Elasticsearch)' => [
				'index:rebuild:profiles <event-id>'
			]
		];
	
		foreach($commands as $name => $category){
			echo "\n=== {$name} ===\n";
			foreach($category as $command)
				echo "{$command}\n";
		}
	}
}