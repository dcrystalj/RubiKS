@extends('dataentry.main')
@section('content')

    <!-- AngularJS -->
    <!--<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.19/angular.min.js"></script>-->
    <script src="<% asset('assets/angular/angular.min.js') %>"></script>
    <script src="<% asset('assets/dataentry/app.js') %>"></script>

    <div ng-app="dataEntry">
        <div ng-controller="MainController as Ctrl">
            <div class="row">
                <h4 class="pull-right" ng-click="addFormShow=!addFormShow">
                    <span class="glyphicon glyphicon-plus hidden-print" aria-hidden="true"></span>
                </h4>

                <!--
                <h4 class="pull-right" ng-click="addCompetitorShow=!addCompetitorShow">
                    <span class="glyphicon glyphicon glyphicon-user hidden-print" aria-hidden="true"></span>
                    &nbsp;
                </h4>
                -->

                <h4>Rezultati</h4>

                <!-- Add competitor form -->
                <form class="form-horizontal hidden-print" novalidate ng-show="addCompetitorShow">
                    <div class="form-group">
                        <div class="col-md-3">
                            <input class="form-control input-sm" placeholder="Ime">
                        </div>
                        <div class="col-md-3">
                            <input class="form-control input-sm" placeholder="Priimek">
                        </div>
                        <div class="col-md-3">
                            <input class="form-control input-sm" placeholder="RubiKS ID">
                        </div>
                    </div>
                    <hr>
                </form>
                <!-- / Add competitor form -->

                <!-- Add result form -->
                <form class="form-horizontal hidden-print" novalidate ng-init="add={ event:'333', round:'default', user:null, results:[] };" ng-show="addFormShow">
                    <div class="form-group">
                        <div class="col-md-4">
                            <select class="form-control input-sm" ng-model="add.event" ng-options="event.readable_id as event.short_name for event in events"></select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-control input-sm" ng-model="add.round" ng-options="round.short_name as (round.name + ' (' + round.short_name + ')') for round in rounds"></select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-control input-sm" ng-model="add.user" ng-options="user.club_id as (user.name + ' ' + user.last_name + ' (' + user.club_id + ')') for user in users"></select>
                        </div>
                    </div>
                    <div class="form-group" ng-show="add.event != null && add.event != '' && add.event != '*'">
                        <div class="col-md-2" ng-show="add.event != '33310MIN' && add.event != '333FM' && add.event != '2345'" ng-repeat="i in range(nrAttempts(add.event))">
                            <input class="form-control input-sm" ng-model="add.results[i]" placeholder="{{(i + 1).toString() + '. čas'}}">
                        </div>
                        <div class="" ng-show="add.event == '33310MIN'">
                            <div class="col-md-3">
                                <input class="form-control input-sm" ng-model="add.results[0]" placeholder="Št. rešenih kock">
                            </div>
                            <div class="col-md-3">
                                <input class="form-control input-sm" ng-model="add.results[1]" placeholder="Čas">
                            </div>
                        </div>
                        <div class="" ng-show="add.event == '2345'">
                            <div class="col-md-3">
                                <input class="form-control input-sm" ng-model="add.results[0]" placeholder="1. poskus">
                            </div>
                            <div class="col-md-3">
                                <input class="form-control input-sm" ng-model="add.results[1]" placeholder="2. poskus">
                            </div>
                        </div>
                        <div class="col-md-6" ng-show="add.event == '333FM'">
                            <input class="form-control input-sm" ng-model="add.results[0]" placeholder="Št. potez">
                        </div>
                    </div>
                    <input class="btn btn-primary pull-right" type="submit" ng-click="creatingResult() && storeResult()" value="Dodaj" ng-show="creatingResult()">
                    <input class="btn btn-primary pull-right" type="submit" ng-click="updateResult()" value="Uredi" ng-show="editingResult()">
                    <input class="btn btn-link pull-right" type="submit" ng-click="cancelEditing()" value="Prekliči" ng-show="editingResult()">
                    {{add.results}}<br><br>
                    <div class="alert alert-danger" ng-show="addError">
                        <button type="button" class="close" aria-label="Close" ng-click="addError=false"><span aria-hidden="true">&times;</span></button>
                        {{addErrorMsg}}
                    </div>
                    <hr>
                </form>
                <!-- / Add results form -->

                <div style="text-align: center;">
                    Disciplina:
                    <select ng-model="event" ng-options="event.readable_id as event.short_name for event in extendedOptions(events, { readable_id: '*', short_name: 'Vse' }) | orderBy:['readable_id']" ng-change="add.event=(event != null && event != '' && event != '*')?event:add.event"></select>

                    &nbsp; &nbsp;

                    Krog:
                    <select ng-model="round" ng-options="round.short_name as round.name for round in extendedOptions(rounds, { short_name: '*', name: 'Vse' })"></select>
                    <br><br>
                </div>

                <table class="table table-striped table-condensed table-results">
                    <thead>
                        <th>#</th>
                        <th>Tekmovalec</th>
                        <th>Posamezno</th>
                        <th>Povprečje</th>
                        <th>Vsi časi</th>
                        <th>&nbsp;</th>
                    </thead>
                    <tbody>
                        <tr ng-repeat="result in results | filter: showResultFilter | orderBy:orderByArray(event)">
                        <!--
                        <tr ng-repeat="result in results | orderBy:orderByArray(event)" ng-show="showResult(result.event_readable_id, result.round_short_name)">
                        -->
                            <td ng-show="event === '*' || round === '*'">{{result.event_readable_id}}{{result.round_short_name == "default" ? "" : ("/" + result.round_short_name)}}</td>
                            <td ng-hide="event === '*' || round === '*'">{{$index + 1}}.</td>
                            <td>{{result.competitor_name}}</td>
                            <!--<td>{{result.user_club_id}}</td>-->
                            <td>{{showTime(result.single, result.event_readable_id)}}</td>
                            <td>{{showTime(result.average, result.event_readable_id)||""}}</td>
                            <!--<td>{{result.single}}</td>
                            <td>{{result.average}}</td>-->
                            <td>{{showResults(result.results, event)}}</td>
                            <td>
                                <button type="button" class="btn btn-warning btn-xs hidden-print" aria-label="Edit" ng-click="editResult(result)">
                                    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                </button>
                                <button type="button" class="btn btn-danger btn-xs hidden-print" aria-label="Delete" ng-click="deleteResult(result.id)">
                                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row pull-right  hidden-print" ng-show="addFormShow">
                <a href="/results/help">Navodila za izvoz podatkov</a>
                 | 
                <a href="/simpleresults" target="_blank">Izvozi rezultate v datoteko</a>
            </div>

        </div>
    </div>
@stop
