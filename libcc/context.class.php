<?php
class Context
{
	private $context;
	private $data;
	private $action;
	private $layout;
	private $content; // output buffer
	private $localeFormatter;
	private $legacy_rendering = false;
	private $_GET = array();

	public function __construct( $context ) {
		if (! is_null($context) )
			$this->setContext( $context );
	}

	public function put( $data ) {
		$this->content .= $data;
	}

	public function terminate() {
		$this->setHeaders();
		if( $this->content )
			die($this->content);
	}

	public function setLocaleFormatter( LocaleFormatter $lf ) {
		$this->localeFormatter = $lf;
	}

	public function locale() {
		return $this->localeFormatter;
	}

	public function setContext( $context ) {
		switch ($context) {
			case 'json': $this->context = 'json'; break;
			case 'http': $this->context = 'http'; break;
		}
	}

	public function isHTML() {
		return 'http' === $this->context;
	}

	public function isJSON() {
		return 'json' === $this->context;
	}
	
	public function getContextName() {
		return $this->context;
	}

	public function getData( $name ) {
		if( isset( $this->data[$name] ) ) {
			return $this->data[$name];
		}
		return null;
	}

	public function setData( $name, $value ) {
		$this->data[$name] = $value;
	}

	public function setRequestData( array $data ) {
		$this->_GET = $data;
	}

	public function getRequestData( ) {
		return $this->_GET;
	}

	public function rd() {
		return $this->getRequestData();
	}
	
	public function setHeaders() {
		switch ($this->context) {
			case 'json':
				header ("Cache-Control: no-chache, must-revalidate");
				header ("Content-Type: application/json");
				break;
		}
	}

	public function setLayout( $path ) {
		$this->layout = $path;
	}

	public function setAction( $path ) {
		$this->action = $path;
	}

	public function getLayout() {
		return $this->layout;
	}

	public function getAction() {
		return $this->action;
	}

	public function useLegacyRenderer( $layout ) {
		$this->legacy_rendering = true;
		$this->layout = $layout;
	}

	public function isLegacy() {
		return $this->legacy_rendering;
	}
}
