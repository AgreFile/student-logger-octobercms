# REVIEW

## General
Tu ti spomeniem niektoré pripomienky čo mám, ale vyhľadaj si v projekte "REVIEW", tak nájdeš všetky moje komenty
Ak by ti niečo nedávalo zmysel alebo by si si to chcel bližšie prebrať, píš na slack :DD
Ak opravíš / zmeníš niečo čo spomínam v "REVIEW" komente, tak rovno odstráň ten "REVIEW" koment aby sa ti netvoril neporiadok

Pls pozri si tieto moje body, potom mi pushni zmeny, a keď to bude v pohode sa presunieme na Level 2

## composer.lock
composer.lock je lepšie mať v gite, a teda ho treba dať preč z .gitignore
Tento file určuje verzie pre packages, a to chceš aby mali všetci ľudia čo pracujú na projekte rovnaké

## API
Momentálne sa väčšina tvojej aplikácie odohráva v routes.php, okrem toho máš len urobený Model a migráciu
Máš to urobené tak, že to funguje samostatne bez frontendu, čiže endpointy dávaju echo() a vracajú redirect... máš to teda celkovo poriešené aj zo strany frotnend-u

1. vec je, že logika endpointov by sa nemala písať do routes.php, malo by to byť v controller-i, o čom som ti viac napísal nižšie
Je to takto pretože routes.php slúži skôr na zadefinovanie toho aké endpointy existujú a aké sú ich middlewares a pod., samotná logika už sa nachádza inde

2. vec je, že Levely OctoberCMS akadémie, ktoré budeš riešiť očakávajú skôr prístup k backend aplikácii ako k API (Application Programming Interface, ak je to pre teba nový pojem, určite si naštuduj nejaké všeobecné info)
Toto v podstate znamená, že tvoríš API (backend samostatne) pre nejaký (zatiaľ fiktívny) frontend. To v praxi znamená, že frontend posiela nejaké dáta (napr. meno, ID, ...) a tvoja API vráti response v podobe čistých dát
Čiže nevrátiš cez echo() nejaké <a>bla bla</a> alebo redirect() na presmerovanie usera (lebo to už je robota frontendu), namiesto toho vrátiš len to čo frotend potrebuje, čiže napr. nejaký object

[
    [
        'id' => 1,
        'student_name' => 'bla bla'
    ],
    [
        'id' => 2,
        'student_name' => 'bla bla 2'
    ],
    ...
]

Aby som to uviedol do kontextu

logcheckout - by vrátilo len nejaký message že zaznamenia príchodu prebehlo úspešne
showcheckouts - vráti nejaký array checkout-ov

Keďže tento Level si už tak celý urobil tak to nechajme tak, nemusíš to meniť :DD, V Leveli 2 to skúsme dať z tohto pohľadu, určite daj vedieť či ti to dáva zmysel

## Controller-y
V rámci OctoberCMS existujú 2 typy controller-ov a obidve budeš používať

1. HTTP Controller
Tento "Controller" je jednoducho file / class, ktorá obsahuje funkcie, ktoré sú zodpovedné za logiku HTTP endpointov
Čiže napr. v tvojom prípade by si mal mať nejaký "LoggerController.php", ktorý by obsahoval logiku tvojich "/logcheckout", "/showcheckouts", ... endpointov
Toto je koncept, ktorý existuje mimo OCMS / Laravel, a teda aj iné frameworky ho používajú

2. OCMS Controller
Tento "Controller" je niečo čo zahŕňa viacero súborov a funkciou je kontrolovanie formu a listu v admin page, ale o tomto sa viac dozvieš v Leveli 2

Problém je že sú to úplne iné veci ale volajú sa rovnako :D
Takže ja ti tu len dávam dopredu upozornenie aby si vedel o tom že existujú a sú to 2 rôzne veci
Pre tento Level potrebuješ len HTTP Controller, do ktorého dáš logiku tvojich endpointov, čiže tvoj routes.php bude zjednodušený a budeš tam mať nejaké takéto endpointy
...
Route::any('/route', [CheckoutController::class, 'menoFunkcieVControlleri']);
...
U nás sa to robí tak, že HTTP Controller-y umiestnime do plugins/parentPlugin/plugin/http/controllers
