
  <!-- Footer -->
  <br />{if $modules.Auth->isLoggedIn()}
  <table class="tbl" cellpadding="{$modules.Config->getCfgVal('tpadding')}" cellspacing="{$modules.Config->getCfgVal('tspacing')}" style="width:{$modules.Config->getCfgVal('twidth')}; margin:auto;">
   <tr><td class="td1"><span class="small"><a class="small" href="{$smarty.const.INDEXFILE}?faction=pm{$smarty.const.SID_AMPER}">{if $unreadPMs > 0}<b>{$modules.Language->getString('new_pms_received')}</b>{else}{$modules.Language->getString('no_new_pms')}{/if}</a></span></td></tr>
  </table>
  <br />{/if}
  {if $modules.Auth->isAdmin()}<p class="norm" style="text-align:center;"><a class="norm" href="{$smarty.const.INDEXFILE}?faction=adminpanel{$smarty.const.SID_AMPER}">{$modules.Language->getString('administration')}</a></p><br />{/if}
  <p class="norm" style="text-align:center;">{mailto address=$modules.Config->getCfgVal('site_contact') text=$modules.Language->getString('contact') extra='class="norm"' encode="javascript"} | <a class="norm" href="{$modules.Config->getCfgVal('site_address')}">{$modules.Config->getCfgVal('site_name')}</a> | <a class="norm" href="{$smarty.const.INDEXFILE}?faction=regeln{$smarty.const.SID_AMPER}">{$modules.Language->getString('board_rules')}</a>{if !empty($privacyPolicyLink)} | <a class="norm" href="{$privacyPolicyLink}">{$modules.Language->getString('privacy_policy')}</a>{/if}</p>
  <br />
  <p class="copyr" style="text-align:center;">
   Tritanium Bulletin Board {$smarty.const.VERSION_PUBLIC}<br />
   &copy; 2010&ndash;{$smarty.const.COPYRIGHT_YEAR} <a class="copyr" href="http://www.tritanium-scripts.com/" target="_blank">Tritanium Scripts</a>
  </p>
  <br />{if $modules.Config->getCfgVal('show_site_creation_time')}
  <p class="stat" style="text-align:center;">
   {($creationTime+(microtime(true)-$smartyTime))|string_format:$modules.Language->getString('site_created_in_x_sec')}<br />
   {$processedFiles|string_format:$modules.Language->getString('processed_x_files')}<br />
   {if $modules.Config->getCfgVal('use_gzip_compression') == 1}{$modules.Language->getString('gzip_compression_enabled')}{else}{$modules.Language->getString('gzip_compression_disabled')}{/if}<br />
   {$memoryUsage|string_format:$modules.Language->getString('x_kib_memory_usage')}
  </p>{/if}
  </div>
 </body>
</html>