
  <br />
  <!-- Footer -->
  {if $modules.Auth->isAdmin()}<p id="adminBox"><a class="fontNorm" href="{$smarty.const.INDEXFILE}?faction=adminpanel{$smarty.const.SID_AMPER}">{$modules.Language->getString('administration')}</a></p><br />{/if}
  <p class="fontNorm" style="text-align:center;">{mailto address=$modules.Config->getCfgVal('site_contact') text=$modules.Language->getString('contact') encode="javascript"} | <a href="{$modules.Config->getCfgVal('site_address')}">{$modules.Config->getCfgVal('site_name')}</a> | <a href="{$smarty.const.INDEXFILE}?faction=regeln{$smarty.const.SID_AMPER}">{$modules.Language->getString('board_rules')}</a></p>
  <br />
  <p id="copyrightBox">
   Tritanium Bulletin Board {$smarty.const.VERSION_PUBLIC}<br />
   &copy; 2010 <a href="http://www.tritanium-scripts.com/" target="_blank">Tritanium Scripts</a>
  </p>
  <br />{if $modules.Config->getCfgVal('show_site_creation_time')}
  <p id="techStats">
   {$creationTime+(microtime(true)-$smartyTime)|string_format:$modules.Language->getString('site_created_in_x_sec')}<br />
   {$processedFiles|string_format:$modules.Language->getString('processed_x_files')}<br />
   {if $modules.Config->getCfgVal('use_gzip_compression') == 1}{$modules.Language->getString('gzip_compression_enabled')}{else}{$modules.Language->getString('gzip_compression_disabled')}{/if}<br />
   {$memoryUsage|string_format:$modules.Language->getString('x_kib_memory_usage')}
  </p>{/if}
  </div>
 </body>
</html>