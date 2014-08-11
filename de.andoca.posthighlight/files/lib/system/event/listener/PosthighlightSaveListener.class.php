<?php
// wcf imports
require_once (WBB_DIR . 'lib/system/event/listener/PosthighlightMessageFormListener.class.php');

/**
 * Saves the selected value into the database
 * 
 * @author	Andreas Diendorfer
 * @copyright	2011 Andoca Haustier-WG UG
 *
 */
class PosthighlightSaveListener extends PosthighlightMessageFormListener {

	/**
	 * Class selected for this thread
	 * 
	 * @var string
	 */
	public $posthighlightClass = '';

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		// read the available classes for this user
		$this->getData();
		
		if (!count($this->availableClasses))
			return;
		
		if ($eventName == 'readFormParameters') {
			// inject the post parameter into the thread/post object
			if (isset($_POST ['posthighlightClass'])) {
				$eventObj->posthighlightClass = StringUtil::trim($_POST ['posthighlightClass']);
			}
			
			// check if this user is allowed to set this class
			if (!isset($eventObj->posthighlightClass) || (!isset($this->availableClasses [$eventObj->posthighlightClass]) && $eventObj->posthighlightClass != 'null'))
				$eventObj->posthighlightClass = null;
		}
		
		if ($eventName == 'saved') {
			// this is the saved event, so we have to add our css class to the stored post
			if ($className == 'ThreadAddForm')
				$postID = $eventObj->newThread->firstPostID;
			else if ($className == 'PostEditForm')
				$postID = $eventObj->post->postID;
			else if ($className == 'PostAddForm' || $className == 'PostQuickAddForm')
				$postID = $eventObj->newPost->postID;
			else
				return;
			
		// we now have the postID from the just saved post/thread in the var $postID
			if ($eventObj->posthighlightClass != 'null') {
				$sql = "UPDATE wbb" . WBB_N . "_post 
						SET posthighlightClass = '" . escapeString($eventObj->posthighlightClass) . "' 
						WHERE postID = '" . $postID . "'";
			} else {
				$sql = "UPDATE wbb" . WBB_N . "_post 
						SET posthighlightClass = NULL 
						WHERE postID = '" . $postID . "'";
			}
			WCF::getDB()->sendQuery($sql);
		}
	}
}
?>