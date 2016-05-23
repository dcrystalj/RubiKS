(function() {
    app = angular.module("dataEntry", []);

    app.controller('MainController', function($scope, $http)
    {
        //$scope.jsonDB = '/simpleresults/export';
        $scope.jsonDB = '/assets/dataentry/data.json';
        $scope.simpleResultsDB = '/simpleresults';

        $scope.competition = "";
        $scope.event = "*";
        $scope.round = "*";
        //$scope.competition = "";
        $scope.user = "";

        $scope.addError = false;
        $scope.addFormShow = !false;
        $scope.addCompetitorShow = false;
        $scope.editing = false; // true => editing results, false => creating results
        $scope.resultBeingEdited = null;

        $scope.dnf = '77777777';
        $scope.dns = '88888888';
        $scope.dsq = '99999999';

        $scope.events = [];
        $scope.rounds = [];
        //$scope.competitions = [];
        $scope.users = [];
        $scope.results = [];

        $scope.getData = function() {
            $http.get($scope.jsonDB).success(function(data) {
                $scope.events = data.events;
                $scope.rounds = data.rounds;
                //$scope.competitions = data.competitions;

                $scope.users = [];
                var properName, properLastName;
                for (var i = 0; i < data.users.length; i++) {
                    $scope.users.push(data.users[i]);
                    $scope.users[i].name = $scope.users[i].name.toUpperCase();
                    $scope.users[i].last_name = $scope.users[i].last_name.toUpperCase();
                }
                $scope.users.sort(compareUsers);

                $scope.getResults();
            });
        }

        $scope.getResults = function() {
            $http.get($scope.simpleResultsDB).success(function(data) {
                var club_id, user;
                for (var i = 0; i < data.length; i++) data[i] = injectResultInfo(data[i]);

                $scope.results = data;
            });
        }

        $scope.showTime = function(time, event) {

            // Temporary fix
            if (time.toString().toUpperCase() === 'DNF') return 'DNF';
            if (time.toString().toUpperCase() === 'DNS') return 'DNS';
            if (time.toString().toUpperCase() === 'DSQ') return 'DSQ';

            if (time == $scope.dnf) return 'DNF';
            if (time == $scope.dns) return 'DNS';
            if (time == $scope.dsq) return 'DSQ';

            if (event === "333FM") return time;
            if (event === "33310MIN") {
                if (time == 0) return ""; // Average does not exist!
                // console.log(time, event);
                time = time.toString();
                var nrCubes = 400 - Number(time.substring(0, 3));
                var time = Number(time.substring(3));
                console.log(nrCubes, formatTime(time));
                return nrCubes.toString() + ' kock, ' + formatTime(time);
            }

            return formatTime(time);
            /*
            var csec = time % 100;
            var sec = Math.floor(time / 100);
            var min = Math.floor(sec / 60);
            sec = sec % 60;

            csec = (csec == 0 ? '00' : (csec < 10 ? '0' + csec : csec));
            if (min > 0) {
                sec = (sec == 0 ? '00' : (sec < 10 ? '0' + sec : sec));
                return "" + min + ":" + sec + "." + csec;
            } else {
                return "" + sec + "." + csec;
            }
            // */
        }

        var formatTime = function(time) {
            var csec = time % 100;
            var sec = Math.floor(time / 100);
            var min = Math.floor(sec / 60);
            sec = sec % 60;

            csec = (csec == 0 ? '00' : (csec < 10 ? '0' + csec : csec));
            if (min > 0) {
                sec = (sec == 0 ? '00' : (sec < 10 ? '0' + sec : sec));
                return "" + min + ":" + sec + "." + csec;
            } else {
                return "" + sec + "." + csec;
            }
        }

        $scope.showResults = function(results, event) {
            if (results === "") return "";
            var resultsArray = results.split('@');
            var arr = [];
            for (var i = 0; i < resultsArray.length; i++) arr.push($scope.showTime(resultsArray[i], event));
            return arr.join(', ');
        }

        $scope.getData();

        var injectResultInfo = function(result) {
            result['competitor_name'] = "NULL";
            for (var j = 0; j < $scope.users.length; j++) {
                if ($scope.users[j]['club_id'] == result['user_club_id']) {
                    result['competitor_name'] = $scope.users[j]['name'] + ' ' + $scope.users[j]['last_name'];
                }
            }
            return result;
        }

        var compareUsers = function(a, b) {
            if (a.name < b.name) return -1;
            if (a.name > b.name) return 1;
            if (a.last_name < b.last_name) return -1;
            if (a.last_name > b.last_name) return 1;
            return 0;
        }

        $scope.storeResult = function() {
            if ($scope.add.user == null || $scope.add.user == '') {
                displayError('Vnesite ime tekmovalca.');
                return;
            }

            var data = {
                competition_short_name: $scope.competition,
                event_readable_id: $scope.add.event,
                round_short_name: $scope.add.round,
                user_club_id: $scope.add.user,
                results: results2Text($scope.add.results)
            };
            $http({
                method: 'post',
                url: $scope.simpleResultsDB,
                data: $.param(data),
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            })
            .success(function(data) {
                // Preveri, ali je bil model zares ustvarjen
                console.log('data: ' + data);
                if (data === "" || !("created_at" in data)) {
                    console.log('false');
                    displayError('Prišlo je do napake pri shranjevanju rezultata.');
                    return;
                }

                data = injectResultInfo(data);
                $scope.results.push(data);
                $scope.addError = false;

                // Clear
                $scope.add.results = [];
                $scope.add.user = '';
            })
            .error(function(data) {
                displayError('Prišlo je do napake pri shranjevanju rezultata.');
            });
        }

        $scope.deleteResult = function(id) {
            var text = "Ali ste prepričani, da želite izbrisati rezultat?";
            if (confirm(text) != true) return;

            $http({
                method: 'delete',
                url: $scope.simpleResultsDB + '/' + id,
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            })
            .success(function(data) {
                for (var i = 0; i < $scope.results.length; i++) {
                    if ($scope.results[i].id == id) {
                        $scope.results.splice(i, 1);
                        return;
                    }
                }
            })
            .error(function(data) {
                displayError('Prišlo je do napake pri izbrisu rezultata.')
            });
        }

        $scope.updateResult = function() {
            var data = {
                id: $scope.resultBeingEdited.id,
                event_readable_id: $scope.add.event,
                round_short_name: $scope.add.round,
                user_club_id: $scope.add.user,
                results: results2Text($scope.add.results)
            };
            $http({
                method: 'put',
                url: $scope.simpleResultsDB + '/' + data.id,
                data: $.param(data),
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            })
            .success(function(data) {
                // Preveri, ali je bil model zares ustvarjen
                console.log('data: ' + data);
                if (data === "" || !("created_at" in data)) {
                    console.log('false');
                    displayError('Prišlo je do napake pri urejanju rezultata.');
                    return;
                }

                data = injectResultInfo(data);
                for (var i = 0; i < $scope.results.length; i++) {
                    if ($scope.results[i].id == data.id) {
                        $scope.results[i] = data;
                    }
                }
                $scope.addError = false;

                // Clear
                $scope.add.results = [];
                $scope.add.user = '';
                $scope.editingId = null;
                $scope.editing = false;
            })
            .error(function(data) {
                displayError('Prišlo je do napake pri urejanju rezultata.');
            });
        }

        $scope.creatingResult = function() {
            return !$scope.editing;
        }

        $scope.editingResult = function() {
            return $scope.editing;
        }

        $scope.editResult = function(result) {
            //console.log("editing result id: " + result.id);
            $scope.resultBeingEdited = result;
            $scope.add.event = result.event_readable_id;
            $scope.add.round = result.round_short_name;
            $scope.add.user = result.user_club_id;
            if (result.event_readable_id === "333FM" || result.eventReadableId === "33310MIN") $scope.add.results = [ result.single ];
            else $scope.add.results = result.results.split("@");
            $scope.editing = true;
        }

        $scope.cancelEditing = function() {
            //console.log("cancel editing");
            $scope.editing = false;
            $scope.add.event = "333"; $scope.add.round = "default"; $scope.add.user = ""; $scope.add.results = [];
            $scope.resultBeingEdited = null;
        }

        $scope.showResultFilter = function(result) {
            return (result.event_readable_id === $scope.event || $scope.event === "*") && (result.round_short_name === $scope.round || $scope.round === "*");
        }

        $scope.extendedOptions = function(array1, option) {
            var options = array1.slice();
            options.unshift(option);
            return options;
        }

        $scope.range = function(num) {
            var arr = [];
            for (var i = 0; i < num; i++) arr.push(i);
            return arr;
        }

        $scope.nrAttempts = function(eventReadableId) {
            for (var i = 0; i < $scope.events.length; i++) {
                if ($scope.events[i].readable_id === eventReadableId) {
                    return $scope.events[i].attempts;
                }
            }
            return 5;
        }

        displayError = function(msg) {
            $scope.addError = true;
            $scope.addErrorMsg = msg;
        }

        results2Text = function(array) {
            return array.join("@");
        }

        $scope.orderByArray = function(event) {
            // Dopolni seznam!
            if (event === '*') return ['event_readable_id', 'round_short_name', 'average', 'single'];
            else if (event === '333BLD') return ['event_readable_id', 'round_short_name', 'single' ];
            else if (event === '666' || event === '777') return ['event_readable_id', 'round_short_name', 'average', 'single'];
            else if (event === '2345') return ['event_readable_id', 'round_short_name', 'single'];
            else if (event === '33310MIN') return ['event_readable_id', 'round_short_name', 'single'];
            else return ['event_readable_id', 'round_short_name', 'average', 'single'];
        }

    });

    /* Tab Controller */
    app.controller('TabController', function() {
        this.tab = 1;

        this.setTab = function(tabNumber) {
            this.tab = tabNumber;
        }

        this.isSet = function(tabNumber) {
            return this.tab === tabNumber;
        }
    });

})();
