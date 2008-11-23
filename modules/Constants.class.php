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

		define('PROFILE_FIELD_TYPE_TEXT',0);
		define('PROFILE_FIELD_TYPE_TEXTAREA',1);
		define('PROFILE_FIELD_TYPE_SELECTSINGLE',2);
		define('PROFILE_FIELD_TYPE_SELECTMULTI',3);

		define('LOCK_TYPE_NO_LOCK',0);
		define('LOCK_TYPE_NO_LOGIN',1);
		define('LOCK_TYPE_NO_POSTING',2);

		define('BBCODE_QUOTE',0);
		define('BBCODE_CODE',1);
		define('BBCODE_LIST', 2);
		define('BBCODE_BOLD',3);
		define('BBCODE_ITALIC',4);
		define('BBCODE_UNDERLINE',5);
		define('BBCODE_STRIKE',6);
		define('BBCODE_SUPERSCRIPT', 7);
		define('BBCODE_SUBSCRIPT', 8);
		define('BBCODE_CENTER',9);
		define('BBCODE_EMAIL',10);
		define('BBCODE_IMAGE',11);
		define('BBCODE_LINK',12);
		define('BBCODE_COLOR',13);
		define('BBCODE_SIZE', 14);
		define('BBCODE_GLOW', 15);
		define('BBCODE_SHADOW', 16);
		define('BBCODE_FLASH', 17);
	}
}

?>