<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<!DOCTYPE ajaxresult [
	<!ELEMENT ajaxresult (mode, status, values)>
	<!ELEMENT mode (#PCDATA)>
	<!ELEMENT status (#PCDATA)>
	<!ELEMENT values (value)>
	<!ELEMENT value (#PCDATA)>
]>
<ajaxresult>
	<mode>{$mode}</mode>
	<status>{$status}</status>
	<error>{$error}</error>
	<values>
		{foreach from=$values item=curValue}
		<value name="{$curValue.key}">{$curValue.value}</value>
		{/foreach}
	</values>
</ajaxresult>