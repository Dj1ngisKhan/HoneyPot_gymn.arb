# HoneyPot_gymnarb

1. Börja med att utveckla en Low-Interaction Honeypot, och ifall jag får tid över så kan jag utvidga dens funktionalitet och komplexhet - till en High-Interaction HoneyPot.
   
2. Tänkte göra blogg hemsida, som man kan koppla upp sig på och posta blogg-posts och bilder. Behöver inte vara så avancerad. 
Men användaren skall kunna logga in och skaap ett konto samt lägga ut blogg-posts.

3. Förslag på svagheter jag kan implementera i web-sidan:
- Inga hashed passwords - brute forca användarnman och lösenord.
- SQL Injections eller Python/Javascript injection i skadlig kod som manipulerar string messages.
- Directory Traversal till en login page för admin - där dem kan brute-forca admin logins.
- SSH-åtkomst, genom att hackaren får tag på servern ip-address.

4. Logga allting hackern gör när han kommit in på honeypoten - såsom kommandon han kör.

5. Leda hackaren in på falska mappar och filer i webserverns honeypot, såsom falska lösenord och användarnamn som är svagt hashed - vilket kommer få hackaren
att slösa sin tid på att försöka komma på lösenorden genom hash collision eller annat.

6. När hackaren kommit in på honeypoten, så skickas en varning till admin och it-avdelningen. 
