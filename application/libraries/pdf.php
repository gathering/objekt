<?php

class PDF {

	static function view($document, $stylesheet=""){
		$pdf = new PDF;
	    $pdf->document = $document;
	    $pdf->stylesheet = $stylesheet;
	    return $pdf;
	}

	var $content;
	var $variables = array();
	var $layout = false;

	function prepare(){
		$document = $this->preparePath($this->document);
		$stylesheet = $this->preparePath($this->stylesheet, 'stylesheet');

		
		if($this->layout){
			$documentView = View::make('path: '.$this->layout.'/theme.blade.php')->nest('content', 'pdf.'.$this->document, $this->variables);
			$stylesheet = $this->layout.'/theme.stylesheet.blade.php';
		}
		else {
			$documentView = View::make('path: '.$document);
			foreach($this->variables as $name => $value) $documentView->with($name, $value);
		}
		
		$documentXml = $documentView->__toString();

		if(!empty($stylesheet)){
			$stylesheetView = View::make('path: '.$stylesheet);
			$stylesheetXml = $stylesheetView->__toString();
		} else {
			$path = $this->preparePath('layouts.default', 'stylesheet', true);
			$stylesheetView = View::make('path: '.$path);
			$stylesheetXml = $stylesheetView->__toString();
		}

		$loader = new PHPPdf\Core\Configuration\LoaderImpl();
		$loader->setFontFile(path('app').'views/pdf/config/fonts.xml');

		$facade = PHPPdf\Core\FacadeBuilder::create($loader)->build();
	    $this->content = $facade->render($documentXml, $stylesheetXml);
	}

	function get(){
		$this->prepare();
		header('Content-Type: application/pdf');
	    echo $this->content;
	    exit;
	}

	function with($name, $value){
		$this->variables[$name] = $value;
		return $this;
	}

	function layout($name){
		$this->layout = $name;
	}

	function string(){
		$this->prepare();
		return $this->content;
	}

	function preparePath($path, $type="", $debug=false){
		if(empty($path)) return null;
		$path = str_replace(".", "/", $path);

		if(!empty($type)){
			if(is_file(path('app').'views/pdf/'.$path.'.'.$type.'.blade.php'))
				return path('app').'views/pdf/'.$path.'.'.$type.'.blade.php';

			if(is_file(path('app').'views/pdf/'.$path.'.'.$type.'.php'))
				return path('app').'views/pdf/'.$path.'.'.$type.'.php';
		} else {
			if(is_file(path('app').'views/pdf/'.$path.'.blade.php'))
				return path('app').'views/pdf/'.$path.'.blade.php';

			if(is_file(path('app').'views/pdf/'.$path.'.php'))
				return path('app').'views/pdf/'.$path.'.php';
		}

		return null;
	}
}

class PDF_Renderer {
	
}

function pdf_asset($name){
	return path('app').'views/pdf/'.$name;
}