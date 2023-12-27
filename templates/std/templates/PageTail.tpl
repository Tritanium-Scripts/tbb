
  <!-- Footer -->
  <br />{if Auth::getInstance()->isLoggedIn()}
  <table class="tbl" cellpadding="{Config::getInstance()->getCfgVal('tpadding')}" cellspacing="{Config::getInstance()->getCfgVal('tspacing')}" style="width:{Config::getInstance()->getCfgVal('twidth')}; margin:auto;">
   <tr><td class="td1"><span class="small"><a class="small" href="{$smarty.const.INDEXFILE}?faction=pm{$smarty.const.SID_AMPER}">{if $unreadPMs > 0}<b>{Language::getInstance()->getString('new_pms_received')}</b>{else}{Language::getInstance()->getString('no_new_pms')}{/if}</a></span></td></tr>
  </table>
  <br />{/if}
  {if Auth::getInstance()->isAdmin()}<p class="norm" style="text-align:center;"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=adminpanel{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('administration')}</a></p><br />{/if}
  <p class="norm" style="text-align:center;">{mailto address=Config::getInstance()->getCfgVal('site_contact') text=Language::getInstance()->getString('contact') extra='class="norm"' encode="javascript"} | <a class="norm" href="{Config::getInstance()->getCfgVal('site_address')}">{Config::getInstance()->getCfgVal('site_name')}</a> | <a class="norm" href="{$smarty.const.INDEXFILE}?faction=regeln{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('board_rules')}</a>{if !empty($privacyPolicyLink)} | <a class="norm" href="{$privacyPolicyLink}">{Language::getInstance()->getString('privacy_policy')}</a>{/if}</p>
  <br />
  <p class="copyr" style="text-align:center;">
   Tritanium Bulletin Board {$smarty.const.VERSION_PUBLIC}<br />
   &copy; 2010&ndash;{$smarty.const.COPYRIGHT_YEAR} <a class="copyr" href="http://www.tritanium-scripts.com/" target="_blank">Tritanium Scripts</a>
  </p>
  <br />{if Config::getInstance()->getCfgVal('show_site_creation_time')}
  <p class="stat" style="text-align:center;">
   {($creationTime+(microtime(true)-$smartyTime))|string_format:Language::getInstance()->getString('site_created_in_x_sec')}<br />
   {$processedFiles|string_format:Language::getInstance()->getString('processed_x_files')}<br />
   {if Config::getInstance()->getCfgVal('use_gzip_compression') == 1}{Language::getInstance()->getString('gzip_compression_enabled')}{else}{Language::getInstance()->getString('gzip_compression_disabled')}{/if}<br />
   {$memoryUsage|string_format:Language::getInstance()->getString('x_kib_memory_usage')}
  </p>{/if}
  </div>
 </body>
</html>