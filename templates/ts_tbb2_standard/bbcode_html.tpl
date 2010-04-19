<template:quote>
 <div align="center"><table style="border:1px #000000 solid;" border="0" cellpadding="2" cellspacing="0" width="90%"><tr><td style="background-color:#000000"><span style="font:10px verdana; color:#FFFFFF;"><b>{quote.QUOTETITLE}:</b></span></td></tr><tr><td style="background-color:#FFFFFF"><span class="small">{quote.QUOTETEXT}</span></td></tr></table></div>
</template:quote>

<template:code>
 <br /><span class="small"><b>{code.CODE}:</b></span><br /><textarea style="font-size:8pt; border:1px #000000 solid;" cols="70" rows="8"readonly="readonly">{code.CODETEXT}</textarea><br />
</template:code>

<template:bold>
 <span style="font-weight:bold;">{bold.BOLDTEXT}</span>
</template:bold>

<template:italic>
 <span style="font-style:italic;">{italic.ITALICTEXT}</span>
</template:italic>

<template:underline>
 <span style="text-decoration:underline;">{underline.UNDERLINETEXT}</span>
</template:underline>

<template:strike>
 <span style="text-decoration:line-through;">{strike.STRIKETEXT}</span>
</template:strike>

<template:email>
 <a href="mailto:{email.EMAILADDRESS}">{email.EMAILADDRESS}</a>
</template:email>

<template:center>
 <p style="text-align:center;">{center.CENTERTEXT}</p>
</template:center>

<template:image>
 <img src="{image.IMAGEADDRESS}" alt="" border="0" />
</template:image>

<template:link>
 <a href="{link.LINKADDRESS}" target="_blank">{link.LINKTEXT}</a>
</template:link>