OC.L10N.register(
    "files_accesscontrol",
    {
    "No rule given" : "Keine Regel ausgewählt",
    "File access control" : "Dateizugriffskontrolle",
    "Restrict access to files based on factors such as filetype, user group memberships, time and more." : "Schränke den Zugriff auf Dateien ein. Dies kann auf Basis des Dateityps, Mitgliedschaft von Gruppen, Zeit oder weiterem passieren.",
    "Each rule group consists of one or more rules. A request matches a group if all rules evaluate to true. If a request matches at least one of the defined groups, the request is blocked and the file content can not be read or written." : "Jede Regelgruppe besteht aus einer oder mehreren Regeln. Eine Anfrage passt zu einer Gruppe, wenn alle Bedingungen erfüllt sind. Wenn eine Anfrage wenigstens auf eine Gruppe zutrifft, wird die Anfrage blockiert und der Dateiinhalt kann weder gelesen noch geschrieben werden.",
    "Control access to files based on conditions" : "Kontrolliert den Zugriff auf Dateien basierend auf Bedingungen",
    "Nextcloud's File Access Control app enables administrators to protect data from unauthorized access or modifications.\n\n## How it works\nThe administrator can create and manage a set of rule groups. Each of the rule groups consists of one or more rules. If all rules of a group hold true, the group matches the request and access is being denied or the upload is blocked. The rules criteria range from IP address, mimetype and request time to group membership, tags, user agent and more.\n\t\t\nAn example would be to deny access to MS Excel/XLSX files owned by the \"Human Resources\" group accessed from an IP not on the internal company network or to block uploads of files bigger than 512 mb by students in the \"1st year\" group.\n\t\nLearn more about File Access Control on [https://nextcloud.com/workflow](https://nextcloud.com/workflow)" : "Die Nextcloud Datei-Zugriffs-App (File Access Control) ermöglicht es Administratoren, Daten vor unberechtigtem Zugriff oder vor Veränderungen zu schützen.\n\n##So funktioniert es:\nDer Administrator kann Regel-Gruppen erstellen und bearbeiten. Jede dieser Regel-Gruppen besteht aus einer oder mehreren Regeln. Sobald alle Regeln einer Gruppe zutreffen wird der Zugriff verweigert bzw. das Hochladen verhindert. Die Gruppen-Regel-Kriterien beinhalten IP-Adressen, mime-Typ, Zugriffszeit, Gruppenmitgliedschaft, Tags, Nutzeragenten usw.\n\nZum Beispiel kann der Zugriff auf MS Excel/XLSK Dateien, die der Gruppe \"Personalabteilung\" zugeordnet sind für Zugriffe von IPs, die sich nciht innerhalb des Firmennetzes befinden, blockiert werden, oder es kann das Hochladen von Dateien die Größer sind als 512 MB für Studenten, die Mitglied in der \"1. Semester\" Gruppe sind, verhindert werden.\n\nWeitere Informationen zur APP unter [https://nextcloud.com/workflow](https://nextcloud.com/workflow)"
},
"nplurals=2; plural=(n != 1);");
