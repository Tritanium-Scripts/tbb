<!-- TPLBLOCK quote -->
 <p align="center"><table class="tbl" border="0" cellpadding="3" cellspacing="0" width="85%"><tr><td class="td2"><span class="small"><b>{quote.QUOTETITLE}:</b><br />{quote.QUOTETEXT}</span></td></tr></table></p>
<!-- /TPLBLOCK quote -->

<!-- TPLBLOCK code -->
 <br /><span class="small"><b>{code.CODE}:</b></span><br /><textarea style="font-size:8pt; border:1px #000000 solid;" cols="70" rows="8"readonly="readonly">{code.CODETEXT}</textarea><br />
<!-- /TPLBLOCK code -->

<!-- TPLBLOCK bold -->
 <span style="font-weight:bold;">{bold.BOLDTEXT}</span>
<!-- /TPLBLOCK bold -->

<!-- TPLBLOCK italic -->
 <span style="font-style:italic;">{italic.ITALICTEXT}</span>
<!-- /TPLBLOCK italic -->

<!-- TPLBLOCK underline -->
 <span style="text-decoration:underline;">{underline.UNDERLINETEXT}</span>
<!-- /TPLBLOCK underline -->

<!-- TPLBLOCK strike -->
 <span style="text-decoration:line-through;">{strike.STRIKETEXT}</span>
<!-- /TPLBLOCK strike -->

<!-- TPLBLOCK email -->
 <a href="mailto:{email.EMAILADDRESS}">{email.EMAILADDRESS}</a>
<!-- /TPLBLOCK email -->

<!-- TPLBLOCK center -->
 <p style="text-align:center;">{center.CENTERTEXT}</p>
<!-- /TPLBLOCK center -->

<!-- TPLBLOCK image -->
 <img src="{image.IMAGEADDRESS}" alt="" border="0" />
<!-- /TPLBLOCK image -->

<!-- TPLBLOCK link -->
 <a href="{link.LINKADDRESS}" target="_blank">{link.LINKTEXT}</a>
<!-- /TPLBLOCK link -->