# HoneyPot_gymnarb

1. Börja med att utveckla en Low-Interaction Honeypot, och ifall jag får tid över så kan jag utvidga dens funktionalitet och komplexhet - till en High-Interaction HoneyPot.
   
2. Tänkte göra en fil överförings sida, som man kan koppla upp sig på och överföra dokument och bilder. Behöver inte vara så avancerad. 
Men användaren skall kunna logga in och skaap ett konto samt spara sina bilder därpå.

3. Förslag på svagheter jag kan implementera i web-sidan:
- Inga hashed passwords - brute forca användarnman och lösenord.
- SQL Injections eller Python/Javascript injection i skadlig kod som manipulerar string messages.
- Directory Traversal till en login page för admin - där dem kan brute-forca admin logins.
- SSH-åtkomst, genom att hackaren får tag på servern ip-address.

4. Logga allting hackern gör när han kommit in på honeypoten - såsom kommandon han kör.

5. Leda hackaren in på falska mappar och filer i webserverns honeypot, såsom falska lösenord och användarnamn som är svagt hashed - vilket kommer få hackaren
att slösa sin tid på att försöka komma på lösenorden genom hash collision eller annat.

6. När hackaren kommit in på honeypoten, så skickas en varning till admin och it-avdelningen.


## Typ av honeypot jag skall ha:
Jag skall göra en hidden directory som heter /admin - vilket är en login page för admin - vilket kommer kunna logas in via med brute force. 
Sedan skall honeypoten ta en till en falsk damin sida - där admin har kontroll över webserverns olika filer och directories. 
Där skall det även finnas en fil med falska hashed user passwords, som är hashade på ett svagt sätt - vilket kan göra att hackaren förökser göra en 
hash collision eller rainbow table, lägger inte till något salt. 
Honeypoten skall även skicka en varning till en riktigt admin - den riktiga webservern om ett eventuellt hot och föröska samla information om hackaren under
tiden hackaren är på honeypoten. 

## Vad jag måste göra:

1. Planera vilka element och hur websidan skall fungera och spara lösenord och användarnamn etc. (Förhoppningsvis inte för avancerat). 
2. Designa en websidas frontend i adobe - måste designa users-loginpage, blog/main-page, admin-loginpage and adminpage.
3. Börja programmera ihop alla element jag kommer behöva till alla olika pages på websidan, såsom knappar, menyer, textbars etc.
4. Jag implementerar en adminpage, som man kommer till om man ändrar dir till /admin.
5. Börjar med backend, innan jag börjar styla min websida - eftersom att backend är viktigare. Med python eller php.
     - Jag skall lära mig php - fast endast till den nivå att jag kan kommunicera med frontend och ta emot och skicka inputs.
     - Jag skall lära mig grundläggande SQL, så att jag kan hämta och skicka data till en databas.
6. Jag gör klart backend, som skall se till att hämta data från frontend, för inloggning och annat.
7. Jag sätter upp en separat honeypot databas.
8. Ser till så att SQL eller SQLite och php kommunicerar med honey-pot databasen för att logga hackerns aktivitet och vad han gör i adminpage.
9. Jag ser till att anpassa min kod i inloggnings-fasen på /admin pagen, så att den går att bruteforca.
10. Jag implementerar kod, som varanar när någon försöker brute-forca inloggnigen till honeypoten (adminpage), utan att stoppa hackern.
11. Jag testar min hemsida och ser ifall jag kan komma åt /admin directory och bruteforca inloggningen. 
12. Jag skapar falska directories och filer på honeypot databasen, som hackaren direkt kan interagera med när den är inloggad på adminpagen.
    Såsom filer med falska lösenord och användarnamn, kanske filer som hackaren kan ladda ned som är injicerade med malware (om jag får tid över). 
