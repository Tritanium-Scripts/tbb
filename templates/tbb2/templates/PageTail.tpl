
  <br />
  <!-- Footer -->
  {if Auth::getInstance()->isAdmin()}<p id="adminBox"><a class="fontNorm" href="{$smarty.const.INDEXFILE}?faction=adminpanel{$smarty.const.SID_AMPER}">{if $smarty.config.munky}<img src="{Template::getInstance()->getTplDir()}images/buttons/munky_panel.png" alt="" />{else}{Language::getInstance()->getString('administration')}{/if}</a></p><br />{/if}
  <p class="fontNorm" style="text-align:center;">{mailto address=Config::getInstance()->getCfgVal('site_contact') text=Language::getInstance()->getString('contact') encode="javascript"} | <a href="{Config::getInstance()->getCfgVal('site_address')}">{Config::getInstance()->getCfgVal('site_name')}</a> | <a href="{$smarty.const.INDEXFILE}?faction=regeln{$smarty.const.SID_AMPER}">{Language::getInstance()->getString('board_rules')}</a>{if !empty($privacyPolicyLink)} | <a href="{$privacyPolicyLink}">{Language::getInstance()->getString('privacy_policy')}</a>{/if}</p>
  <br />
  <p id="copyrightBox">
   Tritanium Bulletin Board {$smarty.const.VERSION_PUBLIC}<br />
   &copy; 2010&ndash;{$smarty.const.COPYRIGHT_YEAR} <a href="http://www.tritanium-scripts.com/" target="_blank">Tritanium Scripts</a>
  </p>
  <br />{if Config::getInstance()->getCfgVal('show_site_creation_time')}
  <p id="techStats">
   {($creationTime+(microtime(true)-$smartyTime))|string_format:Language::getInstance()->getString('site_created_in_x_sec')}<br />
   {$processedFiles|string_format:Language::getInstance()->getString('processed_x_files')}<br />
   {if Config::getInstance()->getCfgVal('use_gzip_compression') == 1}{Language::getInstance()->getString('gzip_compression_enabled')}{else}{Language::getInstance()->getString('gzip_compression_disabled')}{/if}<br />
   {$memoryUsage|string_format:Language::getInstance()->getString('x_kib_memory_usage')}
  </p>{/if}
  </div>
 </body>
</html>