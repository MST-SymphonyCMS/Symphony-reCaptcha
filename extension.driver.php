<?php

// Not sure if these are needed?

// 	require_once(TOOLKIT . '/class.sectionmanager.php');
// require_once(TOOLKIT . '/class.entrymanager.php');
// require_once(TOOLKIT . '/class.fieldmanager.php');


Class extension_recaptcha extends Extension {
	/*-------------------------------------------------------------------------
		Extension definition
	-------------------------------------------------------------------------*/
	public function about() {
		return array( 'name' => 'reCaptcha',
			'version' => '0.1',
			'release-date' => '2017-04-27',
			'author' => array( 'name' => 'Sagara Dayananda',
				'website' => 'http://www.eyes-down.net/',
				'email' => 'sagara@eyes-down.net' ),
			'description' => 'Insert and process reCaptcha field for form submission.'
		);
	}

	public function uninstall() {
		# Remove preferences
		Symphony::Configuration()->remove( 'recaptcha' );
		Administration::instance()->saveConfig();
	}

	public function install() {
		return true;
	}

	public function getSubscribedDelegates() {
		return array(

			array(
				'page' => '/blueprints/events/new/',
				'delegate' => 'AppendEventFilter',
				'callback' => 'addFilterToEventEditor'
			),
			array(
				'page' => '/blueprints/events/edit/',
				'delegate' => 'AppendEventFilter',
				'callback' => 'addFilterToEventEditor'
			),

			array(
				'page' => '/system/preferences/',
				'delegate' => 'Save',
				'callback' => 'save_preferences'
			),
			array(
				'page' => '/system/preferences/success/',
				'delegate' => 'Save',
				'callback' => 'save_preferences'
			),
			array(
				'page' => '/system/preferences/',
				'delegate' => 'AddCustomPreferenceFieldsets',
				'callback' => 'append_preferences'
			),
			array(
				'page' => '/frontend/',
				'delegate' => 'FrontendParamsResolve',
				'callback' => 'addReCaptchaParams'
			),

			array(
				'page' => '/frontend/',
				'delegate' => 'EventPreSaveFilter',
				'callback' => 'processEventData'
			),

		);
	}

	public function addFilterToEventEditor( $context ) {
		$context[ 'options' ][] = array( 'recaptcha', @in_array( 'recaptcha', $context[ 'selected' ] ), 'reCAPTCHA Verification' );
	}

	/*-------------------------------------------------------------------------
		Append reCaptcha Params 
		-------------------------------------------------------------------------*/

	public function addReCaptchaParams( array $context = null ) {
		$context[ 'params' ][ 'recaptcha-secret-id' ] = $this->get_secret();
		$context[ 'params' ][ 'recaptcha-sitekey' ] = $this->_get_sitekey();
	}

	/*-------------------------------------------------------------------------
		Preferences
		-------------------------------------------------------------------------*/

	public function append_preferences( $context ) {
		# Add new fieldset
		$group = new XMLElement( 'fieldset' );
		$group->setAttribute( 'class', 'settings' );
		$group->appendChild( new XMLElement( 'legend', 'reCaptcha' ) );

		# Add reCaptcha secret ID field
		$label = Widget::Label( 'reCaptcha secret ID' );
		$label->appendChild( Widget::Input( 'settings[recaptcha][recaptcha-secret-id]', General::Sanitize( $this->get_secret() ) ) );


		$group->appendChild( $label );
		$group->appendChild( new XMLElement( 'p', 'The secret ID from your reCaptcha settings.', array( 'class' => 'help' ) ) );

		# Add reCaptcha site key field
		$label = Widget::Label( 'reCaptcha site key' );
		$label->appendChild( Widget::Input( 'settings[recaptcha][recaptcha-sitekey]', General::Sanitize( $this->_get_sitekey() ) ) );
		$group->appendChild( $label );
		$group->appendChild( new XMLElement( 'p', 'The site key from your reCaptcha settings.', array( 'class' => 'help' ) ) );

		$context[ 'wrapper' ]->appendChild( $group );
	}


	/*-------------------------------------------------------------------------
		Helpers
	-------------------------------------------------------------------------*/

	public function get_secret() {
		return Symphony::Configuration()->get( 'recaptcha-secret-id', 'recaptcha' );
	}

	private function _get_sitekey() {
		return Symphony::Configuration()->get( 'recaptcha-sitekey', 'recaptcha' );
	}

	/**
	 * perform event filter
	 */

	public function processEventData( $context ) {

		recaptcha::eventFilter($context);
	}




}