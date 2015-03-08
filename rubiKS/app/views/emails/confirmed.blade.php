<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Vaš račun je potrjen!</h2>

        <div>
            Postopek registracije je uspešno zaključen, sedaj se lahko prvič <a href='{{{ URL::to("login") }}}'>prijavite</a> v sistem RubiKS. <br>
            <br>
            RubiKS ID: {{ $user->club_id }}<br>
            <br>
            Svoj profil si lahko ogledate na <a href='{{{ URL::to("competitors/{$user->club_id}") }}}'>tej</a> povezavi. <br>
            <br>
        </div>
    </body>
</html>
