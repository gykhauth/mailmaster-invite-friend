Hívd meg ismerőseid űrlap MailMaster segítségével

## Fájlok
ajanlas-oldal.html : a továbbajánló űrlap
ajanlas-feldolgozo.php : a továbbajánlás elmentését végző szkript.

## Leírás az üzembe helyezéshez
A továbbajánló rendszerhez két email listát kell létrehozni. 
1. Az elsõ listában kerülnek eltárolásra az ajánló adatai (név, email cím és az ajánlások száma). 
A mellékelt szkript alapján a következõ mezõket kell felvenni a listába (az email cím már alapértelmezetten benne van a listában):
keresztnév - mssys_firstname
ajánlások száma - nominee_num
A listához létre kell hozni egy feliratkozó ûrlapot is. A lista és az ûrlap azonosítóját a szkript beállítások részében be kell állítani (NOMINATOR_LISTID és NOMINATOR_FORMID konstansok).

2. A második listában kerülnek eltárolsára az ajánlottak adatai (név, email cím, ajánlás szövege, ajánló email címe, ajánló keresztneve).
A mellékelt szkript alapján a következõ mezõket kell felvenni a listába (az email cím már alapértelmezetten benne van a listában):
ajánlott keresztneve - mssys_firstname
ajánlás szövege - email_content
ajánló email címe - nominator_email
ajánló keresztneve - nominator_firstname
érvényes ajánlások száma - nominee_num
Ehhez a listához is létre kell hozni egy feliratkozó ûrlapot. A lista és az ûrlap azonosítóját a szkript beállítások részében be kell állítani (NOMINEE_LISTID és NOMINEE_FORMID konstansok).

Ha ez meg van, akkor létre kell hozni az ajánlásokat lehetõvé tevõ oldalt az ûrlappal. A szkript jelenleg maximum 4 ajánlást tud kezelni, ez tetszõleges számúra növelhetõ.
Az az ajánlottak keresztnevét és email címét lehet megadni az ûrlapon illetve a szöveget, amelyet email-ben megkapnak.
Az ajánlottak email címei az nominee_email_[i], keresztnevük az nominee_firstname_[i] konvenció alapján elnevezett mezõkben kell legyen megadva.
A továbbajánló szöveg az email_content nevû mezõben szerepeljen. A továbbajánló szövegnek a hatályos jogszabály szerint szerkeszthetõnek kell lennie, azaz az ajánló át kell tudni írja.
A szövegben szerepelnek olyan speciális mezõkódok amelyek kiküldéskor lesznek lecserélve, hogy az ajánló üzenet személyre legyen szabva. Az ajánlottak adatait tartalmazó lista mezõit lehet felhasználni a szövegben.
Például: 
Szia [mssys_firstname]! 
Mindenképp nézd meg ezt a weboldalt .... 
[nominator_firstname]

A fenti szöveg az alábbi szöveggel fog elküldésre kerülni ha az ajánló keresztneve Gyuri, az ajánlotté Béla.
Szia Béla!
Mindenképp nézd meg ezt a weboldalt...
Gyuri

Az ajánlottak adatait tartalmazó listához létre kell hozni egy levelet, amelynek tartalma az [email_content] legyen. A szöveg elhelyezhetõ egy HTML levélben is természetesen. 
Ez mindig az adott céltól függ. Elképzelhetõ olyan helyzet, hogy nincs használatban a továbbajánlás szövege (nem kötelezõ az ûrlapra sem kitenni), hanem egy sablon levél megy ki az ajánlottaknak, amely arra kéri õket, hogy erõsítsék meg a feliratkozásukat. A levélben ebben az esetben a kettõs opt-in megerõsítés kérõ kódot kell elhelyezni és az ajánlottakat gyûjtõ lista feliratkozó ûrlapjának feliratkozási módját kettõs opt-in-re kell állítani.

Létre kell hozni egy feliratkozáskori (vagy kettõs opt-in feliratkozáskori) idõzítést a levélhez. Az idõzítés beállításánál lehetõség van arra, hogy a levél feladója az ajánló legyen. A feladó email címénél a [nominator_email], a feladó nevénél a [nominator_firstname] értéket kell megadni. Az idõzítés kiküldésekor a mezõkódok lecserélésre kerülnek az ajánló adataira.
