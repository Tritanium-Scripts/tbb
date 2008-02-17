<template:quote>
 <div align="center"><table style="border:1px #000000 solid;" border="0" cellpadding="2" cellspacing="0" width="90%"><tr><td style="background-color:#000000"><span style="font:10px verdana; color:#FFFFFF;"><b>{QUOTETITLE}:</b></span></td></tr><tr><td style="background-color:#FFFFFF"><span class="fontsmall">{QUOTETEXT}</span></td></tr></table></div>
</template>

<template:code>
 <div style="overflow:auto; width:800px; height:{HEIGHT}px;"><table style="background-color:#000000; height:100%;" border="0" cellpadding="2" cellspacing="1" width="100%"><tr><td class="cellcat" colspan="2"><span class="fontcat">{LNG_CODE}</span></td></tr><tr><td style="background-color:#FFFFFF;" valign="top"><pre><span style="font-size:12px;">{LINES}</span></pre></td><td style="background-color:#FFFFFF;" valign="top"><pre><span style="font-size:12px;">{CODETEXT}</span></pre></td></tr></table></div>
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