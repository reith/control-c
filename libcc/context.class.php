<?php
class Context
{
	private $context;
	public function __construct( $context )
	{
		if (! is_null($context) )
			$this->setContext( $context );
	}
	
	public function setContext( $context )
	{
		switch ($context) {
			case 'json': $this->context = 'json'; break;
			case 'http': $this->context = 'http'; break;
		}
	}
	
	public function getContextName()
	{
		return $this->context;
	}
	
	public function setHeaders()
	{
		switch ($this->context) {
			case 'json':
				header ("Cache-Control: no-chache, must-revalidate");
				header ("Content-Type: application/json");
				break;
		}
	}
}