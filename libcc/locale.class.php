<?php
class LocaleFormatter {
	private $dateFormatter;
	private $direction;
	private $name;
	private $numberFormatter;

	public function __construct( $locale ) {
		$direction = null;
		switch( $locale ) {
			case 'fa': $direction = 'rtl'; break;
			case 'en': $direction = 'ltr'; break;
			default: Throw new Exception("Invalid locale $locale");
		}

		$this->name = $locale;
		$this->setDirection( $direction );
		$this->setDateFormatter( $locale );
		$this->setNumberFormatter( $locale );

	}

	public function direction() {
		return $this->direction;
	}

	public function date( $timestamp, $format = null ) {
		if( is_null( $format ) )
			return $this->dateFormatter->format( $timestamp );
			
		$df = clone( $this->dateFormatter );
		$df->setPattern( $format );
		return $df->format( $timestamp );
	}

	public function name() {
		return $this->name;
	}

	public function number( $number ) {
		return $this->numberFormatter->format( $number );
	}

	public function setDateFormatter( $locale ) {
		switch( $locale ) {
			case 'fa': $dfcal = 'fa_IR@calendar=persian'; $dfptr = "M/d/yy،‏ H:mm"; break;
			case 'en': $dfcal = 'en_US@calendar=gregorian'; $dfptr = "M/d/yy H:mm"; break; // Suppress default 12h time format.
			default: Throw new Exception ("Unknown locale $locale");
		}

		$this->dateFormatter = new IntlDateFormatter( $dfcal, IntlDateFormatter::SHORT, IntlDateFormatter::SHORT, 'GMT', IntlDateFormatter::TRADITIONAL );
		$this->dateFormatter->setPattern($dfptr);
		return true;
	}

	public function setDirection( $direction ) {
		switch( $direction ) {
			case 'ltr': $this->direction = 'ltr'; break;
			case 'rtl': $this->direction = 'rtl'; break;
			default: Throw new Exception("Invalid direction $direction");
		}
		return true;
	}

	private function setNumberFormatter( $locale ) {
		$this->numberFormatter = new NumberFormatter( $locale, NumberFormatter::DECIMAL);
		return true;
	}
}

?>
