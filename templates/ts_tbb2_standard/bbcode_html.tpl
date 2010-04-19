<template:quote>
 <div align="center"><table style="border:1px #000000 solid;" border="0" cellpadding="2" cellspacing="0" width="90%"><tr><td style="background-color:#000000"><span style="font:10px verdana; color:#FFFFFF;"><b>{QUOTETITLE}:</b></span></td></tr><tr><td style="background-color:#FFFFFF"><span class="small">{QUOTETEXT}</span></td></tr></table></div>
</template>

<template:code>
 <br /><span class="small"><b>{CODE}:</b></span><br /><textarea style="font-size:8pt; border:1px #000000 solid;" cols="70" rows="8"readonly="readonly">{CODETEXT}</textarea><br />
</template>

<template:bold><span style="font-weight:bold;">{BOLDTEXT}</span></template>

<template:italic><span style="font-style:italic;">{ITALICTEXT}</span></template>

<template:underline><span style="text-decoration:underline;">{UNDERLINETEXT}</span></template>

<template:strike><span style="text-decoration:line-through;">{STRIKETEXT}</span></template>

<template:email><a href="mailto:{EMAILADDRESS}">{EMAILADDRESS}</a></template>

<template:center><p style="text-align:center;">{CENTERTEXT}</p></template>

<template:image><img src="{IMAGEADDRESS}" alt="" border="0" /></template>

<template:link><a href="{LINKADDRESS}" target="_blank">{LINKTEXT}</a></template>

<template:color><span style="color:{COLORCODE}">{COLORTEXT}</span></template>