<br />
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr><td align="center"><span class="norm">{$admin_link}</span></td></tr>
</table>
<br />
<div align="center">
<table border="0" cellpadding="5" cellspacing="0" style="border:1px #ACACAC solid;">
<tr><td align="center" style="background-color:#F0F8FF;"><span class="copyright">Tritanium Bulletin Board {$tbb_version}<br />&copy; 2003 <a class="copyright" target="_blank" href="http://www.tritanium-scripts.com">Tritanium Scripts</a></span></td></tr>
</table>
</div>
<!-- TPLBLOCK techstats -->
 <br /><br />
 <table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr><td align="center"><span class="techstats">{techstats.$lng["DB_queries"]}: {techstats.$db->query_counter}; {techstats.$lng["Site_creation_time"]}: {techstats.$STATS["site_creation_time"]};<br />{techstats.$lng["GZIP_compression"]}: {techstats.$gzip_status};</span></td></tr>
 </table>
<!-- /TPLBLOCK techstats -->
</body>
</html>