<?php
// wcf imports
require_once (WCF_DIR . 'lib/system/event/EventListener.class.php');

/**
 * Displays the options for the posthighlight plugin by the message form
 * 
 * @author	Andreas Diendorfer
 * @copyright	2011 Andoca Haustier-WG UG
 *
 */
class PosthighlightMessageFormListener implements EventListener {

	/**
	 * css classes available for the current user
	 * 
	 * @var array
	 */
	public $availableClasses = array ();

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		// read the available classes for this user
		$this->getData();
		
		if (!count($this->availableClasses))
			return;
		
		if ($eventName == 'readData') {
			if (isset($eventObj->post) && $eventObj->post->posthighlightClass && !isset($eventObj->posthighlightClass)) {
				$eventObj->posthighlightClass = $eventObj->post->posthighlightClass;
			}
		} 

		else if ($eventName == 'assignVariables') {
			WCF::getTPL()->assign(array (
					'posthighlightSelect' => $this->availableClasses 
			));
			
			if (isset($eventObj->posthighlightClass)) {
				WCF::getTPL()->assign(array (
						'posthighlightClass' => $eventObj->posthighlightClass 
				));
			}
			
			WCF::getTPL()->append('additionalTabs', WCF::getTPL()->fetch('posthighlightMessageFormTab'));
			WCF::getTPL()->append('additionalSubTabs', WCF::getTPL()->fetch('posthighlightMessageFormTabContent'));
		}
	}

	public function getData() {
		// check if the user can use this plugin
		if (!WCF::getUser()->getPermission('user.board.canHighlightPost')) {
			return;
		}
		
		// get the classes available for the user
		$classes = WCF::getUser()->getPermission('user.board.posthighlightClasses');
		$classes = ArrayUtil::trim(explode("\n", trim($classes)));
		
		// store the available classes in an array		
		foreach ($classes as $class) {
			if (!preg_match("/^.*\;.*$/i", $class))
				continue; // wrong format in this line
			list ( $className, $classTitle ) = explode(';', $class, 2); // only explode at first matching character
			

			if (!isset($this->availableClasses [$className])) {
				$this->availableClasses [$className] = array (
						'className' => $className, 
						'classTitle' => $classTitle 
				);
			}
		}
	}
}
?>