<?

/* settings.php - Legt alle Optionen und Einstellungen fest (c) 2001-2002 Tritanium Scripts */


/*** Angaben zum Forum (Sollten auf jeden Fall angepasst werden) ***/

// WWW-Adresse zum Forum, z.B. http://www.meinedomain.de/forum (http:// nicht vergessen!)
$config['address_to_forum'] = "http://www.meindedomain.de/forum";

// Name der Website, zu der das Forum gehört (wird immer unter dem Forum angezeigt)
$config['site_name'] = "Meineseite";

// Adresse der Website, zu der das Forum gehört ("http://" nicht vergessen!; wird immer unter dem Forum angezeigt)
$config['site_address'] = "http://www.meinedomain,de";

// Kontakt Emailadresse (wird immer unter dem Forum angezeigt)
$config['site_contact'] = "kontakt@meinedomain.de";

// Forumname
$config['forum_name'] = "Meinforum";

// Forumlogo (Pfad oder Adresse)
$config['forum_logo'] = "";

// Legt die Zeitzone fest (z.B. +0100 für Deutschland oder -0700 für USA)
$config['gmt_offset'] = "+0100";

// Log-Modi (Zur genauen Erläuterung bitte die Readme-Datei zu Rate ziehen) (Standard: nichts, also "")
$config['log_options'] = "";

// Legt fest, ab wieviel MB restlichem Speicherplatz dem Admin eine Warn-Email geschickt werden soll (Standard: 3; Kommazahlen mit Punkt angeben!, z.B. 2.5)
// Die Emailadresse des Admins können/müssen sie in "mailsettings.php" festlegen!
$config['warn_admin_fds'] = 3;

// Legt fest, ab wieviel MB restlichem Speicherplatz das Forum gesperrt werden soll (Standard: 1; Kommazahlen mit Punkt angeben!, z.B. 2.5)
$config['close_forum_fds'] = 1;

// Legt fest, ob man sich registrieren kann (1 = ja, 0 = nein; Standard: 1)
$config['activate_registration'] = 1;

// Legt die Maximalzahl an Mitgliedern fest (-1 = unendlich; Standard: -1)
$config['max_registrations'] = -1;

// Legt fest, ob bei Neuanmeldungen ein Passwort eingegeben werden kann oder eins erstellt und per Mail zugeschickt werden soll (1 = ja, 0 = nein; Standard: 0)
// Achtung: Um diese Funktion nützen zu können muss in "mailsettings.php" die Mailfunktion aktiviert sein!
$config['create_reg_pw'] = 0;

// Pfad zum Sprachordner
$config['lng_folder'] = "language/german";



/*** Weitere Einstellungen ***/

// Wartungsmodus? (1 = eingeschaltet, 0 = ausgeschaltet; Standard: 0)
$config['uc'] = 0;

// Meldung bei aktiviertem Wartungsmodus
$config['uc_message'] = "<font face=verdana size=3 color=red><b>Das Forum befindet sich im Moment im Umbau. Schauen sie in ein paar Minuten einfach nochmal vorbei!<br>Sorry, but this board is currently under construction. Try again in a few minutes!</b></font>";

// Anzahl der Themen, die pro Seite angezeigt werden sollen (Standard: 30)
$config['topics_per_page'] = 30;

// Anzahl der Beiträge, die pro Seite angezeigt werden sollen (Standard: 20)
$config['posts_per_page'] = 20;

// Wieviele Minuten ein User als Online gelten soll (Standard: 15)
$config['wio_timeout'] = 15;

// WhoIsOnline (Zeigt an, wer gerade wo im Forum aktiv ist) (1 = eingeschaltet, 0 = ausgeschaltet; Standard: 1)
$config['wio'] = 1;

// Legt fest, ob die Aufbauzeit der Seite und andere technische Statistiken angezeigt werden soll (1 = ja, 0 = nein; Standard: 1)
$config['show_site_creation_time'] = 1;

// Legt fest, ob die Boardstatistiken (Anzahl Themen/Posts...) angezeigt werden sollen (1 = ja, 0 = nein; Standard: 1)
$config['show_board_stats'] = 1;

// Legt fest, ob die letzten 5 Beiträge in der Forenübersicht angezeigt werden sollen (1 = ja, 0 = nein; Standard: 1)
$config['show_lposts'] = 1;

// Legt fest, ob die Kategorien angezeigt werden (1 = ja, 0 = nein; Standard: 1)
$config['show_kats'] = 1;

// Legt fest, ob Beiträge, Thementitel und Signaturen zensiert werden sollen (1 = ja, 0 = nein; Standard: 0)
$config['censored'] = 0;

// Legt fest, ob nur registrierte/eingeloggte Benutzer das Forum betreten dürfen (1 = ja, 0 = nein; Standard: 0)
$config['must_be_logged_in'] = 0;

// Status für Administratoren (Standard: Administrator)
$config['var_admin'] = "Administrator";

// Status für Moderatoren (Standard: Moderator)
$config['var_mod'] = "Moderator";

// Status für Verbannte (Standard: Verbannt)
$config['var_banned'] = "Verbannt";

// Status für Gelöschte (Standard: Gelöscht)
$config['var_killed'] = "Gelöscht";

// Anzahl der Sterne für Administratoren
$config['stars_admin'] = 6;

// Anzahl der Sterne für Moderatoren
$config['stars_mod'] = 5;

// Legt die Position der News fest (1 = Position 1, 2 = Postition 2; Standard: 1)
$config['news_position'] = 1;

// Legt fest, ab vielen Antworten ein Thema als "hot" bezeichnet wird (Standard: 15)
$config['topic_is_hot'] = 15;

// Legt fest, ob man eingeloggt sein muss, um einem User per Formular eine Mail zu schicken (1 = ja, 0 = nein; Standard: 1)
$config['formmail_mbli'] = 1;

// Legt fest, ob die Mitgliederliste aktiviert ist (kann bei vielen Mitgliedern sehr viele Ressourcen verbrauchen!) (1 = ja, 0 = nein; Standard: 1)
$config['activate_mlist'] = 1;

// Legt fest, ob nichteingeloggte User beim Themenerstellen... einen Namen eingeben müssen (1 = ja, 0 = nein; Standard: 1)
$config['nli_must_enter_name'] = 1;

// Legt fest, ob Foren ohne Leserechte in der Forenübersicht angezeigt werden sollen (1 = ja, 0 = nein; Standard: 1)
$config['show_private_forums'] = 1;




/*** CSS-Datei ***/

// Der Pfad oder die Adresse der CSS-Datei, die verwendet werden soll
$config['css_file'] = "styles/standard.css";



/*** Tabelleneinstellungen ***/

// Tabellenweite (Standard: 95%);
$twidth = "95%";

// Zellabstand (Standard: 0)
$tspacing = 0;

// Rahmenabstand (Standard: 4)
$tpadding = 4;



/*** Emaileinstellungen ***/

// Legt fest, ob allgemein Mails überhaupt verschickt werden (1 = ja, 0 = nein; Standard: 1)
$config['activate_mail'] = 1;

// Emailadresse des Admins (muss gültig sein!)
$config['admin_email'] = "admin@meinedomain.de";

// Emailadresse, die bei Mails vom Forum angegeben wird
$config['forum_email'] = "admin@meinedomain.de";

// Legt fest, ob der Admin über eine neue Registrierung per Mail informiert werden soll (1 = ja, 0 = nein; Standard: 0)
$config['mail_admin_new_registration'] = 0;

// Legt fest, ob man automatisch über Antworten auf sein Thema benachrichtigt werden kann (1 = eingeschaltet, Standard: 1)
$config['notify_new_replies'] = 1;




/*** weitere Einstellungen (sollten nicht verändert werden!) ***/

// Legt fest, ob der URL auf jeden Fall die SID angehängt werden soll (1 = ja, 0 = nein; Standard: 0)
$config['append_sid_url'] = 0;

// Legt fest, ob die gzip Komprimierung falls vorhanden verwendet werden soll, was weniger Traffic verursacht (1 = ja, 0 = nein; Standard: 1)
$config['use_gzip_compression'] = 0;

// Legt fest, ob das Filechaching aktiviert ist (1 = ja, 0 = nein; Standard: 1)
$config['use_file_caching'] = 1;

// Legt fest, ob der "Output" auf jeden Fall "gecacht" werden soll (1 = ja, 0 = nein; Standard: 0)
$config['activate_ob'] = 1;

// Legt fest, ob der Befehl getimagesize() verwendet werden soll (1 = ja, 0 = nein; Standard: 1)
$config['use_getimagesize'] = 1;

// Legt die maximale Avatarhöhe fest (Standard: 64)
$config['avatar_height'] = 64;

// Legt die maximale Avatarbreite fest (Standard: 64)
$config['avatar_width'] = 64;

// Legt fest, ob der Befehl discfreespace() verwendet werden soll (1 = ja, 0 = nein; Standard: 1)
$config['use_diskfreespace'] = 1;


?>