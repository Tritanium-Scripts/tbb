<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<ajaxresult>
	<mode>{$mode|escape}</mode>
	<status>{$status|escape}</status>
	<error>{$error|escape}</error>
	<values>
		{foreach from=$values item=curValue}
		<value name="{$curValue.key}">{$curValue.value|escape}</value>
		{/foreach}
	</values>
</ajaxresult>