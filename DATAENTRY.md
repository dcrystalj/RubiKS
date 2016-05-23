rubik.si lokalno: Portable WAMP Stack z že vnešeno bazo.

Dodajanje novega tekmovalca:
    - Odpri /public/assets/dataentry/data.json v Notepad++
    - Poišči "users": (vključno z narekovajem in dvopičjem)
    - Dodaj novega tekmovalca (ločeni so z vejico):
        { "id":1, "club_id":"SIM80DUHDA10", "name":"DAMIJAN", "last_name":"DUH" }
        Pri tem si izmisliš id, Rubik

Morebitni problemi:
    - Skype port 80 -> izklopi Skype.
    - Če se stran čudno prikaže, pobriši app/storage/views/* in obišči najprej / stran, potem pa spet /entry

Flow:
    1. Iz rubik.si/simpleresults/export shraniš podatke (izvorno kodo) v .JSON file!
    2. Ta .JSON file prekopiraš (lokalno) v /public/assets/dataentry/data.json
    2. Vneseš rezultate na /entry (lokalno)
    3. Shraniš /simpleresults (lokalno) v results.json
    4. Uploadaš results.json v /public/results-json/ (NA SERVERJU)
    5. rubik.si/results/import

    Če se podatki vnašajo na strežniku, lahko namesto 3., 4. in 5. narediš naslednje:
        Greš na www.rubik.si/results/import
        v URL dopišeš id tekme, npr www.rubik.si/results/import/SILJUBLJANA100101.
        Klikneš "Ustvari datoteko".
        Se vrneš nazaj (in po potrebi osvežiš stran).

    6. V administraciji tekmo označiš kot končano (-1).

Podatki, ki jih dobiš že od prej v eni .json datoteki:
    SimpleEvent         ~   Event
    SimpleRound         ~   Round
    SimpleCompetition   ~   Competition
    SimpleUser          -   id, club_id, name, last_name

Podatki, ki jih vnašaš:
    SimpleResult        -   id, competition_short_name, event_readable_id, round_short_name, user_club_id, single, average, results
