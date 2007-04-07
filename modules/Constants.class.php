<?php

class Constants extends ModuleTemplate {
	public function initializeMe() {
		define('SMILEY_TYPE_SMILEY',0);
		define('SMILEY_TYPE_TPIC',1);
		define('SMILEY_TYPE_ADMINSMILEY',2);

		define('SUBSCRIPTION_TYPE_FORUM',0);
		define('SUBSCRIPTION_TYPE_TOPIC',1);

		define('AUTH_TYPE_USER',0);
		define('AUTH_TYPE_GROUP',1);

		define('TOPIC_STATUS_OPEN',0);
		define('TOPIC_STATUS_CLOSED',1);

		define('PROFILE_FIELD_TYPE_TEXT',0);
		define('PROFILE_FIELD_TYPE_TEXTAREA',1);
		define('PROFILE_FIELD_TYPE_SELECTSINGLE',2);
		define('PROFILE_FIELD_TYPE_SELECTMULTI',3);
	}
}

?>